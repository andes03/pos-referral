<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class PegawaiController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $pegawai = Pegawai::when($search, function ($query) use ($search) {
            $query->where('nama', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%');
        })->paginate(10);

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

        // Kirim data awal ke view
        return view('pegawai.pegawai.index', [
            'initialData' => $pegawai->items(),
            'pagination' => [
                'current_page' => $pegawai->currentPage(),
                'last_page' => $pegawai->lastPage(),
                'per_page' => $pegawai->perPage(),
                'total' => $pegawai->total(),
            ]
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
            'email' => [
                'required',
                'email',
                'unique:pegawai,email',
                'unique:pelanggan,email'
            ],
            'password' => 'required|string|min:8',
            'role' => 'required|in:admin,user',
            'no_telp' => [
                'nullable',
                'string',
                'regex:/^\d{10,15}$/',
                'unique:pegawai,no_telp'
            ],
            'alamat' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'email.unique' => 'Email sudah digunakan di sistem.',
            'no_telp.regex' => 'No. Telp harus terdiri dari 10-15 digit angka.',
            'no_telp.unique' => 'No. Telp sudah terdaftar.',
            'password.min' => 'Password minimal harus 8 karakter.'
        ]);

        $data = $request->all();
        $data['password'] = Hash::make($request->password);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('pegawai', 'public');
        }

        Pegawai::create($data);

        return response()->json(['success' => true, 'message' => 'Pegawai berhasil ditambahkan']);
    }

    public function show(Pegawai $pegawai)
    {
        return response()->json($pegawai);
    }

    public function update(Request $request, Pegawai $pegawai)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
            'email' => [
                'required',
                'email',
                Rule::unique('pegawai', 'email')->ignore($pegawai->id_pegawai, 'id_pegawai'),
                'unique:pelanggan,email'
            ],
            'password' => 'nullable|string|min:8',
            'role' => 'required|in:admin,user',
            'no_telp' => [
                'nullable',
                'string',
                'regex:/^\d{10,15}$/',
                Rule::unique('pegawai', 'no_telp')->ignore($pegawai->id_pegawai, 'id_pegawai')
            ],
            'alamat' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'email.unique' => 'Email sudah digunakan di sistem.',
            'no_telp.regex' => 'No. Telp harus terdiri dari 10-15 digit angka.',
            'no_telp.unique' => 'No. Telp sudah terdaftar.',
            'password.min' => 'Password minimal harus 8 karakter.'
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

        return response()->json(['success' => true, 'message' => 'Pegawai berhasil diupdate']);
    }

    public function destroy(Pegawai $pegawai)
    {
        if ($pegawai->image) {
            Storage::disk('public')->delete($pegawai->image);
        }

        $pegawai->delete();

        return response()->json(['success' => true, 'message' => 'Pegawai berhasil dihapus']);
    }
}