<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use App\Models\PoinHistori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PelangganController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $pelanggan = Pelanggan::when($search, function ($query) use ($search) {
            $query->where('nama', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%')
                  ->orWhere('kode_referal', 'like', '%' . $search . '%');
        })->paginate(10);

        if ($request->ajax()) {
            return response()->json([
                'data' => $pelanggan->items(),
                'pagination' => [
                    'current_page' => $pelanggan->currentPage(),
                    'last_page' => $pelanggan->lastPage(),
                    'per_page' => $pelanggan->perPage(),
                    'total' => $pelanggan->total(),
                ]
            ]);
        }

        return view('pegawai.pelanggan.index', compact('pelanggan'));
    }

    public function create()
    {
        return view('pegawai.pelanggan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
            'email' => 'required|email|unique:pelanggan,email',
            'password' => 'required|string|min:8',
            'no_telp' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'kode_referal' => 'nullable|string|max:20|unique:pelanggan,kode_referal',
        ]);

        $data = $request->all();
        $data['password'] = Hash::make($request->password);

        // Generate referral code if not provided
        if (!$request->filled('kode_referal')) {
            $data['kode_referal'] = strtoupper(Str::random(8));
        }

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('pelanggan', 'public');
        }

        // Check for referral bonus
        if ($request->filled('referral_code')) {
            $referrer = Pelanggan::where('kode_referal', $request->referral_code)->first();
            if ($referrer) {
                // Add bonus points to referrer
                $referrer->increment('poin', 50);

                // Record in point history
                PoinHistori::create([
                    'id_pelanggan' => $referrer->id_pelanggan,
                    'jenis' => 'tambah',
                    'jumlah_poin' => 50,
                    'keterangan' => 'Bonus referral untuk pendaftaran pelanggan baru',
                    'tanggal' => now(),
                ]);
            }
        }

        Pelanggan::create($data);

        return response()->json(['success' => true, 'message' => 'Pelanggan created successfully']);
    }

    public function show(Pelanggan $pelanggan)
    {
        return response()->json($pelanggan);
    }

    public function edit(Pelanggan $pelanggan)
    {
        return view('pegawai.pelanggan.edit', compact('pelanggan'));
    }

    public function update(Request $request, Pelanggan $pelanggan)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
            'email' => 'required|email|unique:pelanggan,email,' . $pelanggan->id_pelanggan . ',id_pelanggan',
            'no_telp' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
            'poin' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'kode_referal' => 'required|string|max:20|unique:pelanggan,kode_referal,' . $pelanggan->id_pelanggan . ',id_pelanggan',
        ]);

        $data = $request->except('password');

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        if ($request->hasFile('image')) {
            if ($pelanggan->image) {
                Storage::disk('public')->delete($pelanggan->image);
            }
            $data['image'] = $request->file('image')->store('pelanggan', 'public');
        }

        $pelanggan->update($data);

        return response()->json(['success' => true, 'message' => 'Pelanggan updated successfully']);
    }

    public function destroy(Pelanggan $pelanggan)
    {
        if ($pelanggan->image) {
            Storage::disk('public')->delete($pelanggan->image);
        }

        $pelanggan->delete();

        return response()->json(['success' => true, 'message' => 'Pelanggan deleted successfully']);
    }
}
