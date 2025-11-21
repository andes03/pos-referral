<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use App\Models\Pelanggan;
use App\Models\Pegawai;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ImportController extends Controller
{
    /**
     * Menampilkan halaman import CSV
     */
    public function index()
    {
        return view('pegawai.import.index');
    }

    /**
     * Download template CSV
     */
    public function downloadTemplate()
    {
        $filename = 'template_import_transaksi.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $columns = [
            'pelanggan_email',
            'tanggal_transaksi',
            'metode_pembayaran',
            'produk_nama',
            'jumlah',
            'harga'
        ];

        $callback = function() use ($columns) {
            $file = fopen('php://output', 'w');
            
            // Write header
            fputcsv($file, $columns);
            
            // Write example data
            fputcsv($file, [
                'jane@customer.test',
                '2025-12-21 10:30:00',
                'qris',
                'Cappuccino',
                '2',
                '25000'
            ]);
            
            fputcsv($file, [
                'john@customer.test',
                '2025-12-21 10:30:00',
                'cash',
                'Americano',
                '1',
                '20000'
            ]);
            
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Import CSV ke database
     */
    public function import(Request $request)
    {
        // Validasi file
        $validator = Validator::make($request->all(), [
            'csv_file' => 'required|file|mimes:csv,txt|max:10240',
        ], [
            'csv_file.required' => 'File CSV wajib diupload.',
            'csv_file.mimes' => 'File harus berformat CSV.',
            'csv_file.max' => 'Ukuran file maksimal 10MB.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        try {
            $file = $request->file('csv_file');
            $path = $file->getRealPath();
            
            // Baca CSV
            $csv = array_map(function($line) {
                return str_getcsv($line);
            }, file($path));

            // Ambil header
            $header = array_map('trim', $csv[0]);
            unset($csv[0]);

            // Validasi header (kode_referal optional)
            $requiredColumns = [
                'pelanggan_email',
                'tanggal_transaksi',
                'metode_pembayaran',
                'produk_nama',
                'jumlah',
                'harga'
            ];

            foreach ($requiredColumns as $col) {
                if (!in_array($col, $header)) {
                    return response()->json([
                        'success' => false,
                        'message' => "Kolom '$col' tidak ditemukan dalam CSV."
                    ], 422);
                }
            }
            
            // Check if kode_referal column exists
            $hasReferralColumn = in_array('kode_referal', $header);

            $errors = [];
            $successCount = 0;
            $groupedData = [];

            // Group data by transaction (same customer, date, payment method, and referral code)
            foreach ($csv as $rowNumber => $row) {
                if (count($row) !== count($header)) {
                    continue;
                }

                $data = array_combine($header, array_map('trim', $row));
                
                // Skip empty rows
                if (empty($data['pelanggan_email'])) {
                    continue;
                }

                // Group by email, date, payment method, and referral code
                $kodeReferal = $hasReferralColumn && isset($data['kode_referal']) ? $data['kode_referal'] : '';
                $key = $data['pelanggan_email'] . '|' . 
                       $data['tanggal_transaksi'] . '|' . 
                       $data['metode_pembayaran'] . '|' .
                       $kodeReferal;
                
                if (!isset($groupedData[$key])) {
                    $groupedData[$key] = [
                        'pelanggan_email' => $data['pelanggan_email'],
                        'tanggal_transaksi' => $data['tanggal_transaksi'],
                        'metode_pembayaran' => $data['metode_pembayaran'],
                        'kode_referal' => $kodeReferal,
                        'items' => []
                    ];
                }

                $groupedData[$key]['items'][] = [
                    'produk_nama' => $data['produk_nama'],
                    'jumlah' => $data['jumlah'],
                    'harga' => $data['harga'],
                    'row' => $rowNumber + 1
                ];
            }

            // Process grouped transactions
            DB::beginTransaction();
            
            // Get logged in pegawai
            $pegawai = Auth::guard('pegawai')->user();
            
            foreach ($groupedData as $transactionData) {
                try {
                    // Validasi pelanggan
                    $pelanggan = Pelanggan::where('email', $transactionData['pelanggan_email'])->first();
                    if (!$pelanggan) {
                        $errors[] = "Pelanggan dengan email '{$transactionData['pelanggan_email']}' tidak ditemukan.";
                        continue;
                    }

                    // Validasi tanggal
                    try {
                        $tanggalTransaksi = Carbon::parse($transactionData['tanggal_transaksi']);
                    } catch (\Exception $e) {
                        $errors[] = "Format tanggal '{$transactionData['tanggal_transaksi']}' tidak valid.";
                        continue;
                    }

                    // Validasi metode pembayaran
                    if (!in_array($transactionData['metode_pembayaran'], ['cash', 'qris', 'transfer'])) {
                        $errors[] = "Metode pembayaran '{$transactionData['metode_pembayaran']}' tidak valid. Gunakan: cash, qris, atau transfer.";
                        continue;
                    }

                    // Verify referral code if provided
                    $diskonPersen = 0;
                    if (!empty($transactionData['kode_referal'])) {
                        $referralCheck = Pelanggan::where('id_pelanggan', $pelanggan->id_pelanggan)
                                                  ->where('kode_referal', $transactionData['kode_referal'])
                                                  ->first();
                        
                        if ($referralCheck) {
                            $diskonPersen = 10; // 10% discount for valid referral
                        } else {
                            $errors[] = "Kode referral '{$transactionData['kode_referal']}' tidak cocok dengan pelanggan '{$pelanggan->email}'.";
                            continue;
                        }
                    }

                    // Validasi dan proses items
                    $validItems = [];
                    $subtotal = 0;
                    $itemError = false;

                    foreach ($transactionData['items'] as $item) {
                        $produk = Produk::where('nama', $item['produk_nama'])->first();
                        if (!$produk) {
                            $errors[] = "Baris {$item['row']}: Produk '{$item['produk_nama']}' tidak ditemukan.";
                            $itemError = true;
                            break;
                        }

                        if (!is_numeric($item['jumlah']) || $item['jumlah'] <= 0) {
                            $errors[] = "Baris {$item['row']}: Jumlah tidak valid.";
                            $itemError = true;
                            break;
                        }

                        if (!is_numeric($item['harga']) || $item['harga'] < 0) {
                            $errors[] = "Baris {$item['row']}: Harga tidak valid.";
                            $itemError = true;
                            break;
                        }

                        // Check stock
                        if ($produk->stok < $item['jumlah']) {
                            $errors[] = "Baris {$item['row']}: Stok produk '{$produk->nama}' tidak mencukupi. Stok tersedia: {$produk->stok}, diminta: {$item['jumlah']}.";
                            $itemError = true;
                            break;
                        }

                        $itemSubtotal = $item['jumlah'] * $item['harga'];
                        $subtotal += $itemSubtotal;

                        $validItems[] = [
                            'produk' => $produk,
                            'jumlah' => (int)$item['jumlah'],
                            'harga' => (float)$item['harga'],
                            'subtotal' => $itemSubtotal
                        ];
                    }

                    if ($itemError) {
                        continue;
                    }

                    // Calculate total after discount
                    $diskonAmount = ($subtotal * $diskonPersen) / 100;
                    $total = $subtotal - $diskonAmount;

                    // Buat transaksi
                    $transaksi = Transaksi::create([
                        'id_pelanggan' => $pelanggan->id_pelanggan,
                        'id_pegawai' => $pegawai->id_pegawai,
                        'tanggal_transaksi' => $tanggalTransaksi,
                        'total' => $total,
                        'metode_pembayaran' => $transactionData['metode_pembayaran'],
                        'status_pembayaran' => 'paid'
                    ]);

                    // Buat detail transaksi dan update stok
                    foreach ($validItems as $item) {
                        DetailTransaksi::create([
                            'id_transaksi' => $transaksi->id_transaksi,
                            'id_produk' => $item['produk']->id_produk,
                            'jumlah' => $item['jumlah'],
                            'subtotal' => $item['subtotal']
                        ]);

                        // Update stok
                        $item['produk']->decrement('stok', $item['jumlah']);
                    }

                    $successCount++;

                } catch (\Exception $e) {
                    $errors[] = "Error pada transaksi: " . $e->getMessage();
                    continue;
                }
            }

            DB::commit();

            $message = "$successCount transaksi berhasil diimport.";
            if (count($errors) > 0) {
                $message .= " " . count($errors) . " transaksi gagal.";
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'success_count' => $successCount,
                'error_count' => count($errors),
                'errors' => array_slice($errors, 0, 10) // Limit to 10 errors
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}