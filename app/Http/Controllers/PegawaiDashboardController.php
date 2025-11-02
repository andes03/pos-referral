<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use App\Models\Pelanggan;
use App\Models\Produk;
use App\Models\Transaksi;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PegawaiDashboardController extends Controller
{
    public function index()
    {
        $pegawai = Auth::guard('pegawai')->user();
        
        // Get statistics for dashboard
        $stats = [
            'total_pelanggan' => Pelanggan::count(),
            'total_produk' => Produk::count(),
            'total_kategori' => Kategori::count(),
            'total_transaksi' => Transaksi::count(),
            'transaksi_hari_ini' => Transaksi::whereDate('tanggal_transaksi', Carbon::today())->count(),
            'pendapatan_hari_ini' => Transaksi::whereDate('tanggal_transaksi', Carbon::today())
                ->where('status_pembayaran', 'paid')
                ->sum('total'),
            'transaksi_pending' => Transaksi::where('status_pembayaran', 'pending')->count(),
            'produk_stok_habis' => Produk::where('stok', 0)->count(),
        ];

        // Get recent transactions
        $recentTransactions = Transaksi::with(['pelanggan', 'pegawai'])
            ->orderBy('tanggal_transaksi', 'desc')
            ->limit(5)
            ->get();

        // Get low stock products
        $lowStockProducts = Produk::where('stok', '<=', 10)
            ->where('stok', '>', 0)
            ->orderBy('stok', 'asc')
            ->limit(5)
            ->get();

        return view('pegawai.dashboard', compact('pegawai', 'stats', 'recentTransactions', 'lowStockProducts'));
    }
}
