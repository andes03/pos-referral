<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use App\Models\Pelanggan;
use App\Models\Produk;
use App\Models\Transaksi;
use App\Models\Kategori;
use App\Models\DetailTransaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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

        // === DATA UNTUK GRAFIK ===

        // 1. Grafik Pendapatan 7 Hari Terakhir
        $pendapatanHarian = Transaksi::where('status_pembayaran', 'paid')
            ->where('tanggal_transaksi', '>=', Carbon::now()->subDays(6)->startOfDay())
            ->select(
                DB::raw('DATE(tanggal_transaksi) as tanggal'),
                DB::raw('SUM(total) as total_pendapatan'),
                DB::raw('COUNT(*) as jumlah_transaksi')
            )
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->get();

        // Format data untuk Chart.js
        $chartPendapatan = [
            'labels' => [],
            'pendapatan' => [],
            'transaksi' => []
        ];

        // Fill dengan data 7 hari terakhir (termasuk hari tanpa transaksi)
        for ($i = 6; $i >= 0; $i--) {
            $tanggal = Carbon::now()->subDays($i)->format('Y-m-d');
            $data = $pendapatanHarian->firstWhere('tanggal', $tanggal);
            
            $chartPendapatan['labels'][] = Carbon::parse($tanggal)->format('d M');
            $chartPendapatan['pendapatan'][] = $data ? (float)$data->total_pendapatan : 0;
            $chartPendapatan['transaksi'][] = $data ? $data->jumlah_transaksi : 0;
        }

        // 2. Grafik Metode Pembayaran (Doughnut Chart)
        $metodePembayaran = Transaksi::where('status_pembayaran', 'paid')
            ->select('metode_pembayaran', DB::raw('SUM(total) as total_pendapatan'))
            ->groupBy('metode_pembayaran')
            ->get();

        $chartMetodePembayaran = [
            'labels' => [],
            'data' => []
        ];

        foreach ($metodePembayaran as $metode) {
            $chartMetodePembayaran['labels'][] = strtoupper($metode->metode_pembayaran);
            $chartMetodePembayaran['data'][] = (float)$metode->total_pendapatan;
        }

        // 3. Grafik Top 5 Produk Terlaris
        $produkTerlaris = DetailTransaksi::join('produk', 'detail_transaksi.id_produk', '=', 'produk.id_produk')
            ->join('transaksi', 'detail_transaksi.id_transaksi', '=', 'transaksi.id_transaksi')
            ->where('transaksi.status_pembayaran', 'paid')
            ->select(
                'produk.nama',
                DB::raw('SUM(detail_transaksi.jumlah) as total_terjual'),
                DB::raw('SUM(detail_transaksi.subtotal) as total_pendapatan')
            )
            ->groupBy('produk.id_produk', 'produk.nama')
            ->orderBy('total_terjual', 'desc')
            ->limit(5)
            ->get();

        $chartProdukTerlaris = [
            'labels' => [],
            'terjual' => [],
            'pendapatan' => []
        ];

        foreach ($produkTerlaris as $produk) {
            $chartProdukTerlaris['labels'][] = $produk->nama;
            $chartProdukTerlaris['terjual'][] = $produk->total_terjual;
            $chartProdukTerlaris['pendapatan'][] = (float)$produk->total_pendapatan;
        }

        // 4. Grafik Penjualan per Kategori
        $penjualanKategori = DetailTransaksi::join('produk', 'detail_transaksi.id_produk', '=', 'produk.id_produk')
            ->join('kategori', 'produk.id_kategori', '=', 'kategori.id_kategori')
            ->join('transaksi', 'detail_transaksi.id_transaksi', '=', 'transaksi.id_transaksi')
            ->where('transaksi.status_pembayaran', 'paid')
            ->select(
                'kategori.nama_kategori',
                DB::raw('SUM(detail_transaksi.jumlah) as total_terjual'),
                DB::raw('SUM(detail_transaksi.subtotal) as total_pendapatan')
            )
            ->groupBy('kategori.id_kategori', 'kategori.nama_kategori')
            ->orderBy('total_pendapatan', 'desc')
            ->get();

        $chartKategori = [
            'labels' => [],
            'data' => []
        ];

        foreach ($penjualanKategori as $kategori) {
            $chartKategori['labels'][] = $kategori->nama_kategori;
            $chartKategori['data'][] = (float)$kategori->total_pendapatan;
        }

        // 5. Grafik Pertumbuhan Pelanggan (30 Hari Terakhir)
        $pertumbuhanPelanggan = Pelanggan::where('created_at', '>=', Carbon::now()->subDays(29)->startOfDay())
            ->select(
                DB::raw('DATE(created_at) as tanggal'),
                DB::raw('COUNT(*) as jumlah')
            )
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->get();

        $chartPelanggan = [
            'labels' => [],
            'data' => []
        ];

        $totalKumulatif = Pelanggan::where('created_at', '<', Carbon::now()->subDays(29)->startOfDay())->count();

        for ($i = 29; $i >= 0; $i--) {
            $tanggal = Carbon::now()->subDays($i)->format('Y-m-d');
            $data = $pertumbuhanPelanggan->firstWhere('tanggal', $tanggal);
            
            if ($data) {
                $totalKumulatif += $data->jumlah;
            }
            
            $chartPelanggan['labels'][] = Carbon::parse($tanggal)->format('d M');
            $chartPelanggan['data'][] = $totalKumulatif;
        }

        // 6. Grafik Transaksi per Jam (Hari Ini)
        $transaksiPerJam = Transaksi::whereDate('tanggal_transaksi', Carbon::today())
            ->select(
                DB::raw('HOUR(tanggal_transaksi) as jam'),
                DB::raw('COUNT(*) as jumlah')
            )
            ->groupBy('jam')
            ->orderBy('jam')
            ->get();

        $chartJam = [
            'labels' => [],
            'data' => []
        ];

        for ($jam = 0; $jam < 24; $jam++) {
            $data = $transaksiPerJam->firstWhere('jam', $jam);
            $chartJam['labels'][] = sprintf('%02d:00', $jam);
            $chartJam['data'][] = $data ? $data->jumlah : 0;
        }

        return view('pegawai.dashboard', compact(
            'pegawai',
            'stats',
            'recentTransactions',
            'lowStockProducts',
            'chartPendapatan',
            'chartMetodePembayaran',
            'chartProdukTerlaris',
            'chartKategori',
            'chartPelanggan',
            'chartJam'
        ));
    }
}