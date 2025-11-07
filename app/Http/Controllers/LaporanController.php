<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Transaksi::with(['pelanggan', 'pegawai', 'detailTransaksi.produk']);

        // Filter by date range
        if ($request->filled('tanggal_mulai')) {
            $query->whereDate('tanggal_transaksi', '>=', $request->tanggal_mulai);
        }

        if ($request->filled('tanggal_selesai')) {
            $query->whereDate('tanggal_transaksi', '<=', $request->tanggal_selesai);
        }

        // Filter by payment method
        if ($request->filled('metode_pembayaran') && $request->metode_pembayaran !== 'all') {
            $query->where('metode_pembayaran', $request->metode_pembayaran);
        }

        $transaksi = $query->orderBy('tanggal_transaksi', 'desc')->paginate(20);

        // Calculate summary statistics
        $summary = [
            'total_transaksi' => $transaksi->count(),
            'total_pendapatan' => $transaksi->sum('total'),
            'total_cash' => $transaksi->where('metode_pembayaran', 'cash')->sum('total'),
            'total_qris' => $transaksi->where('metode_pembayaran', 'qris')->sum('total'),
            'total_transfer' => $transaksi->where('metode_pembayaran', 'transfer')->sum('total'),
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'metode_pembayaran' => $request->metode_pembayaran,
        ];

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'data' => $transaksi,
                'summary' => $summary
            ]);
        }

        return view('pegawai.laporan.index', compact('transaksi', 'summary'));
    }

    /**
     * Export laporan to PDF
     */
    public function exportPDF(Request $request)
    {
        $query = Transaksi::with(['pelanggan', 'pegawai', 'detailTransaksi.produk']);

        // Filter by date range
        if ($request->filled('tanggal_mulai')) {
            $query->whereDate('tanggal_transaksi', '>=', $request->tanggal_mulai);
        }

        if ($request->filled('tanggal_selesai')) {
            $query->whereDate('tanggal_transaksi', '<=', $request->tanggal_selesai);
        }

        // Filter by payment method
        if ($request->filled('metode_pembayaran') && $request->metode_pembayaran !== 'all') {
            $query->where('metode_pembayaran', $request->metode_pembayaran);
        }

        $transaksi = $query->orderBy('tanggal_transaksi', 'desc')->get();

        // Calculate summary statistics
        $summary = [
            'total_transaksi' => $transaksi->count(),
            'total_pendapatan' => $transaksi->sum('total'),
            'total_cash' => $transaksi->where('metode_pembayaran', 'cash')->sum('total'),
            'total_qris' => $transaksi->where('metode_pembayaran', 'qris')->sum('total'),
            'total_transfer' => $transaksi->where('metode_pembayaran', 'transfer')->sum('total'),
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'metode_pembayaran' => $request->metode_pembayaran,
        ];

        $pdf = Pdf::loadView('pegawai.laporan.pdf', compact('transaksi', 'summary'));

        $filename = 'laporan-transaksi-' . now()->format('Y-m-d-H-i-s') . '.pdf';

        return $pdf->download($filename);
    }
}
