<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use App\Models\Pegawai;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class PelangganDashboardController extends Controller
{
    public function index()
    {
        $pelanggan = Auth::guard('pelanggan')->user();

        // Get customer statistics
        $stats = [
            'total_transaksi' => Transaksi::where('id_pelanggan', $pelanggan->id_pelanggan)->count(),
            'total_referral' => Pelanggan::where('kode_referal', $pelanggan->kode_referal)->count(),
        ];

        // Get all transactions with pagination
        $transactions = Transaksi::where('id_pelanggan', $pelanggan->id_pelanggan)
            ->with('detailTransaksi.produk')
            ->orderBy('tanggal_transaksi', 'desc')
            ->paginate(10);

        // Get referrals with pagination
        $referrals = Pelanggan::where('kode_referal', $pelanggan->kode_referal)
            ->select('nama', 'email', 'created_at')
            ->paginate(10);

        return view('pelanggan.dashboard', compact('pelanggan', 'stats', 'transactions', 'referrals'));
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
            'email' => [
                'required',
                'email',
                Rule::unique('pelanggan', 'email')->ignore($pelanggan->id_pelanggan, 'id_pelanggan'),
                Rule::unique('pegawai', 'email'),
            ],
            'no_telp' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $data = $request->only(['nama', 'email', 'no_telp', 'alamat']);

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($pelanggan->image && Storage::disk('public')->exists($pelanggan->image)) {
                Storage::disk('public')->delete($pelanggan->image);
            }

            // Store new image
            $imagePath = $request->file('image')->store('pelanggan', 'public');
            $data['image'] = $imagePath;
        }

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $pelanggan->update($data);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true, 
                'message' => 'Profil berhasil diperbarui!',
                'data' => [
                    'nama' => $pelanggan->nama,
                    'email' => $pelanggan->email,
                    'no_telp' => $pelanggan->no_telp,
                    'alamat' => $pelanggan->alamat,
                    'image' => $pelanggan->image ? asset('storage/' . $pelanggan->image) : null
                ]
            ]);
        }

        return redirect()->route('pelanggan.dashboard')->with('success', 'Profil berhasil diperbarui!');
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

    public function showTransaction($id)
    {
        $pelanggan = Auth::guard('pelanggan')->user();

        $transaction = Transaksi::where('id_pelanggan', $pelanggan->id_pelanggan)
            ->where('id_transaksi', $id)
            ->with('detailTransaksi.produk.kategori')
            ->firstOrFail();

        return view('pelanggan.transaction_show', compact('pelanggan', 'transaction'));
    }

    public function referrals()
    {
        $pelanggan = Auth::guard('pelanggan')->user();

        $referrals = Pelanggan::where('kode_referal', $pelanggan->kode_referal)
            ->select('nama', 'email', 'created_at')
            ->paginate(10);

        return view('pelanggan.referrals', compact('pelanggan', 'referrals'));
    }

    public function updatePassword(Request $request)
    {
        $pelanggan = Auth::guard('pelanggan')->user();

        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ], [
            'password.required' => 'Password baru harus diisi',
            'password.min' => 'Password minimal 8 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
        ]);

        $pelanggan->update([
            'password' => Hash::make($request->password)
        ]);

        return redirect()->back()->with('success', 'Password berhasil diubah!');
    }
}
