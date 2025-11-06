<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\Kategori;

class LandingController extends Controller
{
    public function index()
    {
        $products = Produk::with('kategori')->get();
        return view('landing', compact('products'));
    }
}
