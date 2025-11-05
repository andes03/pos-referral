<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProdukController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $kategori = $request->get('kategori');
        
        $produk = Produk::with('kategori')
            ->when($kategori, function ($query) use ($kategori) {
                $query->where('id_kategori', $kategori);
            })
            ->when($search, function ($query) use ($search) {
                $query->where('nama', 'like', '%' . $search . '%')
                      ->orWhere('deskripsi', 'like', '%' . $search . '%')
                      ->orWhereHas('kategori', function ($q) use ($search) {
                          $q->where('nama_kategori', 'like', '%' . $search . '%');
                      });
            })
            ->orderBy('created_at', 'desc')->paginate(10);

        if ($request->ajax()) {
            return response()->json([
                'data' => $produk->items(),
                'pagination' => [
                    'current_page' => $produk->currentPage(),
                    'last_page' => $produk->lastPage(),
                    'per_page' => $produk->perPage(),
                    'total' => $produk->total(),
                ]
            ]);
        }

        // Kirim data awal ke view
        return view('pegawai.produk.index', [
            'initialData' => $produk->items(),
            'pagination' => [
                'current_page' => $produk->currentPage(),
                'last_page' => $produk->lastPage(),
                'per_page' => $produk->perPage(),
                'total' => $produk->total(),
            ]
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_kategori' => 'required|exists:kategori,id_kategori',
            'nama' => 'required|string|max:100|unique:produk,nama',
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'deskripsi' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'nama.unique' => 'Nama produk sudah ada.',
            'id_kategori.required' => 'Kategori harus dipilih.',
            'id_kategori.exists' => 'Kategori tidak valid.',
            'harga.required' => 'Harga harus diisi.',
            'harga.numeric' => 'Harga harus berupa angka.',
            'harga.min' => 'Harga tidak boleh kurang dari 0.',
            'stok.required' => 'Stok harus diisi.',
            'stok.integer' => 'Stok harus berupa angka.',
            'stok.min' => 'Stok tidak boleh kurang dari 0.',
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('produk', 'public');
        }

        Produk::create($data);

        return response()->json(['success' => true, 'message' => 'Produk berhasil ditambahkan']);
    }

    public function show(Produk $produk)
    {
        return response()->json($produk->load('kategori'));
    }

    public function update(Request $request, Produk $produk)
    {
        $request->validate([
            'id_kategori' => 'required|exists:kategori,id_kategori',
            'nama' => 'required|string|max:100|unique:produk,nama,' . $produk->id_produk . ',id_produk',
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'deskripsi' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'nama.unique' => 'Nama produk sudah ada.',
            'id_kategori.required' => 'Kategori harus dipilih.',
            'id_kategori.exists' => 'Kategori tidak valid.',
            'harga.required' => 'Harga harus diisi.',
            'harga.numeric' => 'Harga harus berupa angka.',
            'harga.min' => 'Harga tidak boleh kurang dari 0.',
            'stok.required' => 'Stok harus diisi.',
            'stok.integer' => 'Stok harus berupa angka.',
            'stok.min' => 'Stok tidak boleh kurang dari 0.',
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            if ($produk->image) {
                Storage::disk('public')->delete($produk->image);
            }
            $data['image'] = $request->file('image')->store('produk', 'public');
        }

        $produk->update($data);

        return response()->json(['success' => true, 'message' => 'Produk berhasil diupdate']);
    }

    public function destroy(Produk $produk)
    {
        if ($produk->image) {
            Storage::disk('public')->delete($produk->image);
        }

        $produk->delete();

        return response()->json(['success' => true, 'message' => 'Produk berhasil dihapus']);
    }
}