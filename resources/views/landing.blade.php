@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-green-50 to-emerald-100">
    <!-- Navigation -->
    <nav class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <h1 class="text-xl font-bold text-gray-900">Sistem Manajemen Pelanggan dan Referral Sebelas Coffee</h1>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('login') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm font-medium transition duration-150 ease-in-out">
                        Login
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="h-screen relative overflow-hidden flex items-center justify-center">
        <div id="hero-canvas" class="absolute inset-0 w-full h-full"></div>
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl md:text-6xl font-bold text-white mb-6 drop-shadow-lg">
                Sistem Manajemen Pelanggan <span class="text-green-300">dan Referral Sebelas Coffee</span>
            </h1>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('register') }}" class="bg-green-600 hover:bg-green-700 text-white px-8 py-3 rounded-lg text-lg font-medium transition duration-150 ease-in-out shadow-lg hover:shadow-xl z-10 relative">
                    Daftar Sekarang
                </a>
                <a href="{{ route('login') }}" class="bg-emerald-600 hover:bg-emerald-700 text-white px-8 py-3 rounded-lg text-lg font-medium transition duration-150 ease-in-out shadow-lg hover:shadow-xl z-10 relative">
                    Login
                </a>
                <a href="#features" class="bg-white/90 hover:bg-white text-gray-900 px-8 py-3 rounded-lg text-lg font-medium border border-white/20 transition duration-150 ease-in-out shadow-lg hover:shadow-xl z-10 relative">
                   Lihat Menu
                </a>
            </div>
        </div>
    </div>

    <!-- Products Grid Section -->
    <div id="features" class="bg-white py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Menu Kami</h2>
                <p class="text-lg text-gray-600">Temukan berbagai menu kopi berkualitas dari Sebelas Coffee</p>
            </div>

            @if($products->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($products as $product)
                <div class="bg-white/50 backdrop-blur-sm rounded-xl shadow-sm border border-green-100/50 overflow-hidden hover:shadow-lg transition-shadow duration-300">
                    <div class="aspect-square bg-gradient-to-br from-green-50 to-emerald-50 flex items-center justify-center">
                        @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->nama }}" class="h-full w-full object-cover">
                        @else
                        <div class="w-full h-full bg-green-100 flex items-center justify-center">
                            <x-heroicon-o-cube class="w-16 h-16 text-green-600" />
                        </div>
                        @endif
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ $product->nama }}</h3>
                        <p class="text-sm text-gray-600 mb-2">{{ $product->kategori->nama_kategori ?? 'Uncategorized' }}</p>
                        <div class="flex justify-between items-center mb-4">
                            <span class="text-2xl font-bold text-green-600">Rp {{ number_format($product->harga, 0, ',', '.') }}</span>
                            <span class="px-3 py-1 bg-green-100 text-green-800 text-sm font-medium rounded-full">
                                Stok: {{ $product->stok }}
                            </span>
                        </div>
                        <p class="text-gray-600 text-sm mb-4">{{ Str::limit($product->deskripsi, 100) }}</p>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-12">
                <x-heroicon-o-shopping-bag class="w-16 h-16 text-gray-400 mx-auto mb-4" />
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Belum Ada Produk</h3>
                <p class="text-gray-600">Produk akan segera tersedia.</p>
            </div>
            @endif

        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gradient-to-r from-green-900 to-emerald-900 text-white py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <p>&copy; 2024 Sistem Manajemen Pelanggan dan Referral Sebelas Coffee. All rights reserved.</p>
        </div>
    </footer>
</div>
@endsection
