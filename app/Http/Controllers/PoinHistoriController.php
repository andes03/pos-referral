<?php

namespace App\Http\Controllers;

use App\Models\PoinHistori;
use App\Models\Pelanggan;
use Illuminate\Http\Request;

class PoinHistoriController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $poinHistori = PoinHistori::with('pelanggan')
            ->when($search, function ($query) use ($search) {
                $query->where('keterangan', 'like', '%' . $search . '%')
                      ->orWhereHas('pelanggan', function ($q) use ($search) {
                          $q->where('nama', 'like', '%' . $search . '%');
                      });
            })
            ->orderBy('tanggal', 'desc')
            ->paginate(10);

        if ($request->ajax()) {
            return response()->json([
                'data' => $poinHistori->items(),
                'pagination' => [
                    'current_page' => $poinHistori->currentPage(),
                    'last_page' => $poinHistori->lastPage(),
                    'per_page' => $poinHistori->perPage(),
                    'total' => $poinHistori->total(),
                ]
            ]);
        }

        return view('pegawai.poin_histori.index', compact('poinHistori'));
    }

    public function create()
    {
        $pelanggan = Pelanggan::all();
        return view('pegawai.poin_histori.create', compact('pelanggan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_pelanggan' => 'required|exists:pelanggan,id_pelanggan',
            'jenis' => 'required|in:tambah,kurang',
            'jumlah_poin' => 'required|integer|min:1',
            'keterangan' => 'required|string|max:255',
        ]);

        $data = $request->all();
        $data['tanggal'] = now();

        // Update customer points
        $pelanggan = Pelanggan::find($request->id_pelanggan);
        if ($request->jenis === 'tambah') {
            $pelanggan->increment('poin', $request->jumlah_poin);
        } else {
            $pelanggan->decrement('poin', $request->jumlah_poin);
        }

        PoinHistori::create($data);

        return response()->json(['success' => true, 'message' => 'Poin histori created successfully']);
    }

    public function show(PoinHistori $poinHistori)
    {
        return response()->json($poinHistori->load('pelanggan'));
    }

    public function edit(PoinHistori $poinHistori)
    {
        $pelanggan = Pelanggan::all();
        return view('pegawai.poin_histori.edit', compact('poinHistori', 'pelanggan'));
    }

    public function update(Request $request, PoinHistori $poinHistori)
    {
        $request->validate([
            'id_pelanggan' => 'required|exists:pelanggan,id_pelanggan',
            'jenis' => 'required|in:tambah,kurang',
            'jumlah_poin' => 'required|integer|min:1',
            'keterangan' => 'required|string|max:255',
        ]);

        // Reverse previous point change
        $pelanggan = Pelanggan::find($poinHistori->id_pelanggan);
        if ($poinHistori->jenis === 'tambah') {
            $pelanggan->decrement('poin', $poinHistori->jumlah_poin);
        } else {
            $pelanggan->increment('poin', $poinHistori->jumlah_poin);
        }

        // Apply new point change
        if ($request->jenis === 'tambah') {
            $pelanggan->increment('poin', $request->jumlah_poin);
        } else {
            $pelanggan->decrement('poin', $request->jumlah_poin);
        }

        $poinHistori->update($request->all());

        return response()->json(['success' => true, 'message' => 'Poin histori updated successfully']);
    }

    public function destroy(PoinHistori $poinHistori)
    {
        // Reverse point change
        $pelanggan = Pelanggan::find($poinHistori->id_pelanggan);
        if ($poinHistori->jenis === 'tambah') {
            $pelanggan->decrement('poin', $poinHistori->jumlah_poin);
        } else {
            $pelanggan->increment('poin', $poinHistori->jumlah_poin);
        }

        $poinHistori->delete();

        return response()->json(['success' => true, 'message' => 'Poin histori deleted successfully']);
    }
}
