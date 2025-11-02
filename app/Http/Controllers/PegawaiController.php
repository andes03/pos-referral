<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule; // Pastikan ini ada

class PegawaiController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $pegawai = Pegawai::when($search, function ($query) use ($search) {
            $query->where('nama', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%');
        })->paginate(10); // Anda mungkin ingin mengubah paginate(1) ini ke nilai lebih tinggi, misal 10

        if ($request->ajax()) {
            return response()->json([
                'data' => $pegawai->items(),
                'pagination' => [
                    'current_page' => $pegawai->currentPage(),
                    'last_page' => $pegawai->lastPage(),
                    'per_page' => $pegawai->perPage(),
                    'total' => $pegawai->total(),
                ]
            ]);
        }

        return view('pegawai.pegawai.index', compact('pegawai'));
    }

    public function create()
    {
        return view('pegawai.pegawai.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
            'email' => [
                'required',
                'email',
                'unique:pegawai,email', // Cek di tabel pegawai
                'unique:pelanggan,email' // Cek di tabel pelanggan
            ],
            'password' => 'required|string|min:8',
            'role' => 'required|in:admin,user',
            'no_telp' => [
                'nullable',
                'string',
                'regex:/^\d{10,15}$/', // Regex untuk 10-15 digit angka
                'unique:pegawai,no_telp' // Unik di tabel pegawai
            ],
            'alamat' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            // Pesan custom
            'email.unique' => 'Email sudah di gunakan di sistem.',
            'no_telp.regex' => 'No. Telp harus terdiri dari 10-15 digit angka.',
            'no_telp.unique' => 'No. Telp sudah terdaftar.',
            'password.min' => 'Password minimal harus 8 karakter.' // INI TAMBAHANNYA
        ]);

        $data = $request->all();
        $data['password'] = Hash::make($request->password);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('pegawai', 'public');
        }

        Pegawai::create($data);

        return response()->json(['success' => true, 'message' => 'Pegawai created successfully']);
    }

    public function show(Pegawai $pegawai)
    {
        return response()->json($pegawai);
    }

    public function edit(Pegawai $pegawai)
    {
        return view('pegawai.pegawai.edit', compact('pegawai'));
    }

    public function update(Request $request, Pegawai $pegawai)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
            'email' => [
                'required',
                'email',
                Rule::unique('pegawai', 'email')->ignore($pegawai->id_pegawai, 'id_pegawai'), // Cek di pegawai (abaikan diri sendiri)
                'unique:pelanggan,email' // Cek di tabel pelanggan
            ],
            'password' => 'nullable|string|min:8', // INI TAMBAHAN ATURAN
            'role' => 'required|in:admin,user',
            'no_telp' => [
                'nullable',
                'string',
                'regex:/^\d{10,15}$/', // Regex untuk 10-15 digit angka
                Rule::unique('pegawai', 'no_telp')->ignore($pegawai->id_pegawai, 'id_pegawai') // Unik di pegawai (abaikan diri sendiri)
            ],
            'alamat' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            // Pesan custom
            'email.unique' => 'Email sudah di gunakan di sistem.',
            'no_telp.regex' => 'No. Telp harus terdiri dari 10-15 digit angka.',
            'no_telp.unique' => 'No. Telp sudah terdaftar.',
            'password.min' => 'Password minimal harus 8 karakter.' // INI TAMBAHAN PESAN
        ]);

        $data = $request->except('password');

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        if ($request->hasFile('image')) {
            if ($pegawai->image) {
                Storage::disk('public')->delete($pegawai->image);
            }
            $data['image'] = $request->file('image')->store('pegawai', 'public');
        }

        $pegawai->update($data);

        return response()->json(['success' => true, 'message' => 'Pegawai updated successfully']);
    }

    public function destroy(Pegawai $pegawai)
    {
        if ($pegawai->image) {
            Storage::disk('public')->delete($pegawai->image);
        }

        $pegawai->delete();

        return response()->json(['success' => true, 'message' => 'Pegawai deleted successfully']);
    }
}