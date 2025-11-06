<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use App\Models\Pelanggan;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TransaksiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->get('search');
        
        $transaksi = Transaksi::with(['pelanggan', 'pegawai', 'detailTransaksi.produk'])
            ->when($search, function ($query) use ($search) {
                $query->where('id_transaksi', 'like', '%' . $search . '%')
                      ->orWhere('metode_pembayaran', 'like', '%' . $search . '%')
                      ->orWhereHas('pelanggan', function ($q) use ($search) {
                          $q->where('nama', 'like', '%' . $search . '%')
                            ->orWhere('email', 'like', '%' . $search . '%');
                      })
                      ->orWhereHas('pegawai', function ($q) use ($search) {
                          $q->where('nama', 'like', '%' . $search . '%');
                      });
            })
            ->orderBy('tanggal_transaksi', 'desc')
            ->paginate(10);

        if ($request->ajax()) {
            return response()->json([
                'data' => $transaksi->items(),
                'pagination' => [
                    'current_page' => $transaksi->currentPage(),
                    'last_page' => $transaksi->lastPage(),
                    'per_page' => $transaksi->perPage(),
                    'total' => $transaksi->total(),
                ]
            ]);
        }

        return view('pegawai.transaksi.index', [
            'initialData' => $transaksi->items(),
            'pagination' => [
                'current_page' => $transaksi->currentPage(),
                'last_page' => $transaksi->lastPage(),
                'per_page' => $transaksi->perPage(),
                'total' => $transaksi->total(),
            ]
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pelanggan = Pelanggan::orderBy('nama')->get();
        $produk = Produk::where('stok', '>', 0)->orderBy('nama')->get();
        
        return view('pegawai.transaksi.create', compact('pelanggan', 'produk'));
    }

    /**
     * Verify referral code
     */
    public function verifyReferral(Request $request)
    {
        $request->validate([
            'kode_referal' => 'required|string',
            'id_pelanggan' => 'required|exists:pelanggan,id_pelanggan'
        ]);

        $pelanggan = Pelanggan::where('id_pelanggan', $request->id_pelanggan)
                              ->where('kode_referal', $request->kode_referal)
                              ->first();

        if ($pelanggan) {
            return response()->json([
                'success' => true,
                'message' => 'Kode referral valid! Diskon 10% diterapkan',
                'diskon' => 10
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Kode referral tidak cocok dengan pelanggan'
        ], 422);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_pelanggan' => 'required|exists:pelanggan,id_pelanggan',
            'metode_pembayaran' => 'required|in:cash,qris,transfer',
            'kode_referal' => 'nullable|string',
            'produk' => 'required|array|min:1',
            'produk.*.id_produk' => 'required|exists:produk,id_produk',
            'produk.*.jumlah' => 'required|integer|min:1',
        ], [
            'id_pelanggan.required' => 'Pelanggan harus dipilih',
            'id_pelanggan.exists' => 'Pelanggan tidak ditemukan',
            'metode_pembayaran.required' => 'Metode pembayaran harus dipilih',
            'metode_pembayaran.in' => 'Metode pembayaran tidak valid',
            'produk.required' => 'Minimal 1 produk harus dipilih',
            'produk.*.id_produk.required' => 'Produk harus dipilih',
            'produk.*.id_produk.exists' => 'Produk tidak ditemukan',
            'produk.*.jumlah.required' => 'Jumlah harus diisi',
            'produk.*.jumlah.min' => 'Jumlah minimal 1',
        ]);

        try {
            DB::beginTransaction();

            $pegawai = Auth::guard('pegawai')->user();
            
            if (!$pegawai) {
                throw new \Exception('Pegawai tidak ditemukan. Silakan login kembali.');
            }

            // Verify referral code if provided
            $diskonPersen = 0;
            if ($request->filled('kode_referal')) {
                $pelanggan = Pelanggan::where('id_pelanggan', $request->id_pelanggan)
                                      ->where('kode_referal', $request->kode_referal)
                                      ->first();
                
                if ($pelanggan) {
                    $diskonPersen = 10; // 10% discount for valid referral
                }
            }

            $subtotal = 0;
            $produkDetails = [];

            // Validate stock and calculate subtotal
            foreach ($request->produk as $item) {
                $produk = Produk::lockForUpdate()->find($item['id_produk']);
                
                if (!$produk) {
                    throw new \Exception("Produk dengan ID {$item['id_produk']} tidak ditemukan");
                }
                
                if ($produk->stok < $item['jumlah']) {
                    throw new \Exception("Stok produk '{$produk->nama}' tidak mencukupi. Stok tersedia: {$produk->stok}, diminta: {$item['jumlah']}");
                }
                
                $itemSubtotal = $produk->harga * $item['jumlah'];
                $subtotal += $itemSubtotal;
                
                $produkDetails[] = [
                    'produk' => $produk,
                    'jumlah' => $item['jumlah'],
                    'subtotal' => $itemSubtotal
                ];
            }

            // Calculate total after discount
            $diskonAmount = ($subtotal * $diskonPersen) / 100;
            $total = $subtotal - $diskonAmount;

            // Calculate points earned (1 point per 1000 IDR from final total)
            $pointsEarned = floor($total / 1000);

            // Create transaction
            $transaksi = Transaksi::create([
                'id_pelanggan' => $request->id_pelanggan,
                'id_pegawai' => $pegawai->id_pegawai,
                'total' => $total,
                'metode_pembayaran' => $request->metode_pembayaran,
                'status_pembayaran' => 'paid',
                'tanggal_transaksi' => now(),
            ]);

            // Create detail transactions and update stock
            foreach ($produkDetails as $detail) {
                DetailTransaksi::create([
                    'id_transaksi' => $transaksi->id_transaksi,
                    'id_produk' => $detail['produk']->id_produk,
                    'jumlah' => $detail['jumlah'],
                    'subtotal' => $detail['subtotal'],
                ]);

                $detail['produk']->decrement('stok', $detail['jumlah']);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil dibuat',
                'redirect' => route('pegawai.transaksi.show', $transaksi->id_transaksi),
                'data' => [
                    'id_transaksi' => $transaksi->id_transaksi,
                    'subtotal' => $subtotal,
                    'diskon_persen' => $diskonPersen,
                    'diskon_amount' => $diskonAmount,
                    'total' => $total,
                    'poin_earned' => $pointsEarned
                ]
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating transaction: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Transaksi $transaksi)
    {
        if (request()->wantsJson() || request()->ajax()) {
            $transaksi->load(['pelanggan', 'pegawai', 'detailTransaksi.produk']);
            
            return response()->json([
                'success' => true,
                'data' => $transaksi
            ]);
        }
        
        return view('pegawai.transaksi.show', compact('transaksi'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transaksi $transaksi)
    {
        $pelanggan = Pelanggan::orderBy('nama')->get();
        $produk = Produk::orderBy('nama')->get();
        
        return view('pegawai.transaksi.edit', compact('transaksi', 'pelanggan', 'produk'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Transaksi $transaksi)
    {
        $validated = $request->validate([
            'id_pelanggan' => 'required|exists:pelanggan,id_pelanggan',
            'metode_pembayaran' => 'required|in:cash,qris,transfer',
            'status_pembayaran' => 'nullable|in:pending,paid,failed',
        ], [
            'id_pelanggan.required' => 'Pelanggan harus dipilih',
            'id_pelanggan.exists' => 'Pelanggan tidak ditemukan',
            'metode_pembayaran.required' => 'Metode pembayaran harus dipilih',
            'metode_pembayaran.in' => 'Metode pembayaran tidak valid',
            'status_pembayaran.in' => 'Status pembayaran tidak valid',
        ]);

        try {
            $updateData = [
                'id_pelanggan' => $request->id_pelanggan,
                'metode_pembayaran' => $request->metode_pembayaran,
            ];

            if ($request->has('status_pembayaran')) {
                $updateData['status_pembayaran'] = $request->status_pembayaran;
            }

            $transaksi->update($updateData);

            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil diupdate',
                'data' => $transaksi
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error updating transaction: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengupdate transaksi: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaksi $transaksi)
    {
        try {
            DB::beginTransaction();
            
            $idTransaksi = $transaksi->id_transaksi;
            
            foreach ($transaksi->detailTransaksi as $detail) {
                $produk = $detail->produk;
                if ($produk) {
                    $produk->increment('stok', $detail->jumlah);
                }
            }

            $transaksi->delete();
            
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil dihapus dan stok telah dikembalikan'
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting transaction: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus transaksi: ' . $e->getMessage()
            ], 500);
        }
    }
}