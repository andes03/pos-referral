<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $kategori = Kategori::when($search, function ($query) use ($search) {
            $query->where('nama_kategori', 'like', '%' . $search . '%')
                  ->orWhere('deskripsi', 'like', '%' . $search . '%');
        })->paginate(10);

        if ($request->ajax()) {
            return response()->json([
                'data' => $kategori->items(),
                'pagination' => [
                    'current_page' => $kategori->currentPage(),
                    'last_page' => $kategori->lastPage(),
                    'per_page' => $kategori->perPage(),
                    'total' => $kategori->total(),
                ]
            ]);
        }

        // Kirim data awal ke view
        return view('pegawai.kategori.index', [
            'initialData' => $kategori->items(),
            'pagination' => [
                'current_page' => $kategori->currentPage(),
                'last_page' => $kategori->lastPage(),
                'per_page' => $kategori->perPage(),
                'total' => $kategori->total(),
            ]
        ]);
    }

    public function create()
    {
        return view('pegawai.kategori.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:100|unique:kategori,nama_kategori',
            'deskripsi' => 'nullable|string',
        ], [
            'nama_kategori.unique' => 'Nama kategori sudah ada.',
        ]);

        Kategori::create($request->all());

        return response()->json(['success' => true, 'message' => 'Kategori created successfully']);
    }

    public function show(Kategori $kategori)
    {
        return response()->json($kategori);
    }

    public function edit(Kategori $kategori)
    {
        return view('pegawai.kategori.edit', compact('kategori'));
    }

    public function update(Request $request, Kategori $kategori)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:100|unique:kategori,nama_kategori,' . $kategori->id_kategori . ',id_kategori',
            'deskripsi' => 'nullable|string',
        ], [
            'nama_kategori.unique' => 'Nama kategori sudah ada.',
        ]);

        $kategori->update($request->all());

        return response()->json(['success' => true, 'message' => 'Kategori updated successfully']);
    }

    public function destroy(Kategori $kategori)
    {
        $kategori->delete();

        return response()->json(['success' => true, 'message' => 'Kategori deleted successfully']);
    }
}