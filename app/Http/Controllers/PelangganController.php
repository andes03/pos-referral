<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use App\Models\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class PelangganController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $pelanggan = Pelanggan::when($search, function ($query) use ($search) {
            $query->where('nama', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%')
                  ->orWhere('kode_referal', 'like', '%' . $search . '%');
        })
        ->orderBy('created_at', 'desc')
        ->paginate(10);

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

        // Kirim data awal ke view untuk rendering pertama
        return view('pegawai.pelanggan.index', [
            'initialData' => $pelanggan->items(),
            'pagination' => [
                'current_page' => $pelanggan->currentPage(),
                'last_page' => $pelanggan->lastPage(),
                'per_page' => $pelanggan->perPage(),
                'total' => $pelanggan->total(),
            ]
        ]);
    }

    public function create()
    {
        return view('pegawai.pelanggan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
            'email' => [
                'required',
                'email',
                'unique:pelanggan,email',
                Rule::unique('pegawai', 'email'),
            ],
            'password' => 'required|string|min:8',
            'no_telp' => ['nullable', 'regex:/^[0-9]{10,15}$/'],
            'alamat' => 'required|string',
            'kode_referal' => 'required|string|max:20|unique:pelanggan,kode_referal',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'nama.required' => 'Nama harus diisi',
            'nama.max' => 'Nama maksimal 100 karakter',
            'email.required' => 'Email harus diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah digunakan, silakan gunakan email lain',
            'password.required' => 'Password harus diisi',
            'password.min' => 'Password minimal 8 karakter',
            'no_telp.regex' => 'Nomor telepon harus berisi 10-15 digit angka',
            'alamat.required' => 'Alamat harus diisi',
            'kode_referal.required' => 'Kode referal harus diisi',
            'kode_referal.unique' => 'Kode referal sudah digunakan',
            'image.image' => 'File harus berupa gambar',
            'image.mimes' => 'Format gambar harus jpeg, png, jpg, atau gif',
            'image.max' => 'Ukuran gambar maksimal 2MB',
        ]);

        $data = $request->all();
        $data['password'] = Hash::make($request->password);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('pelanggan', 'public');
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
            'email' => [
                'required',
                'email',
                Rule::unique('pelanggan', 'email')->ignore($pelanggan->id_pelanggan, 'id_pelanggan'),
                Rule::unique('pegawai', 'email'),
            ],
            'password' => 'nullable|string|min:8',
            'no_telp' => ['nullable', 'regex:/^[0-9]{10,15}$/'],
            'alamat' => 'required|string',
            'kode_referal' => 'required|string|max:20|unique:pelanggan,kode_referal,' . $pelanggan->id_pelanggan . ',id_pelanggan',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'nama.required' => 'Nama harus diisi',
            'nama.max' => 'Nama maksimal 100 karakter',
            'email.required' => 'Email harus diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah digunakan, silakan gunakan email lain',
            'password.min' => 'Password minimal 8 karakter',
            'no_telp.regex' => 'Nomor telepon harus berisi 10-15 digit angka',
            'kode_referal.required' => 'Kode referal harus diisi',
            'kode_referal.unique' => 'Kode referal sudah digunakan',
            'image.image' => 'File harus berupa gambar',
            'image.mimes' => 'Format gambar harus jpeg, png, jpg, atau gif',
            'image.max' => 'Ukuran gambar maksimal 2MB',
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