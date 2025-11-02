<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use App\Models\Transaksi;
use App\Models\PoinHistori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PelangganDashboardController extends Controller
{
    public function index()
    {
        $pelanggan = Auth::guard('pelanggan')->user();

        // Get customer statistics
        $stats = [
            'total_poin' => $pelanggan->poin,
            'total_transaksi' => Transaksi::where('id_pelanggan', $pelanggan->id_pelanggan)->count(),
            'total_referral' => Pelanggan::where('kode_referal', $pelanggan->kode_referal)->count(),
            'total_poin_dari_referral' => PoinHistori::where('id_pelanggan', $pelanggan->id_pelanggan)
                ->where('keterangan', 'like', '%referral%')
                ->sum('jumlah_poin'),
        ];

        // Get recent transactions
        $recentTransactions = Transaksi::where('id_pelanggan', $pelanggan->id_pelanggan)
            ->with('detailTransaksi.produk')
            ->orderBy('tanggal_transaksi', 'desc')
            ->limit(5)
            ->get();

        // Get point history
        $pointHistory = PoinHistori::where('id_pelanggan', $pelanggan->id_pelanggan)
            ->orderBy('tanggal', 'desc')
            ->limit(10)
            ->get();

        // Get referrals
        $referrals = Pelanggan::where('kode_referal', $pelanggan->kode_referal)
            ->select('nama', 'email', 'created_at')
            ->get();

        return view('pelanggan.dashboard', compact('pelanggan', 'stats', 'recentTransactions', 'pointHistory', 'referrals'));
    }

    public function profile()
    {
        $pelanggan = Auth::guard('pelanggan')->user();
        return view('pelanggan.profile', compact('pelanggan'));
    }

    public function updateProfile(Request $request)
    {
        $pelanggan = Auth::guard('pelanggan')->user();

        $request->validate([
            'nama' => 'required|string|max:100',
            'email' => 'required|email|unique:pelanggan,email,' . $pelanggan->id_pelanggan . ',id_pelanggan',
            'no_telp' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $data = $request->only(['nama', 'email', 'no_telp', 'alamat']);

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $pelanggan->update($data);

        return redirect()->back()->with('success', 'Profile updated successfully');
    }

    public function transactions()
    {
        $pelanggan = Auth::guard('pelanggan')->user();

        $transactions = Transaksi::where('id_pelanggan', $pelanggan->id_pelanggan)
            ->with('detailTransaksi.produk')
            ->orderBy('tanggal_transaksi', 'desc')
            ->paginate(10);

        return view('pelanggan.transactions', compact('pelanggan', 'transactions'));
    }

    public function referrals()
    {
        $pelanggan = Auth::guard('pelanggan')->user();

        $referrals = Pelanggan::where('kode_referal', $pelanggan->kode_referal)
            ->select('nama', 'email', 'created_at')
            ->paginate(10);

        return view('pelanggan.referrals', compact('pelanggan', 'referrals'));
    }
}
