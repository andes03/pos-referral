@extends('layouts.pegawai')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-2 bg-blue-100 rounded-lg">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Pelanggan</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ number_format($stats['total_pelanggan']) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-2 bg-green-100 rounded-lg">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Produk</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ number_format($stats['total_produk']) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-2 bg-purple-100 rounded-lg">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Kategori</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ number_format($stats['total_kategori']) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-2 bg-yellow-100 rounded-lg">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Transaksi</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ number_format($stats['total_transaksi']) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Additional Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-2">Transaksi Hari Ini</h3>
            <p class="text-3xl font-bold text-blue-600">{{ number_format($stats['transaksi_hari_ini']) }}</p>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-2">Pendapatan Hari Ini</h3>
            <p class="text-3xl font-bold text-green-600">Rp {{ number_format($stats['pendapatan_hari_ini'], 0, ',', '.') }}</p>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-2">Transaksi Pending</h3>
            <p class="text-3xl font-bold text-red-600">{{ number_format($stats['transaksi_pending']) }}</p>
        </div>
    </div>

    <!-- Recent Transactions and Low Stock -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Transactions -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Transaksi Terbaru</h3>
            </div>
            <div class="p-6">
                @if($recentTransactions->count() > 0)
                    <div class="space-y-4">
                        @foreach($recentTransactions as $transaction)
                        <div class="flex items-center justify-between py-2">
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $transaction->pelanggan->nama }}</p>
                                <p class="text-sm text-gray-500">{{ $transaction->tanggal_transaksi->format('d M Y H:i') }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-medium text-gray-900">Rp {{ number_format($transaction->total, 0, ',', '.') }}</p>
                                <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full
                                    {{ $transaction->status_pembayaran === 'paid' ? 'bg-green-100 text-green-800' :
                                       ($transaction->status_pembayaran === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                    {{ ucfirst($transaction->status_pembayaran) }}
                                </span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-center py-4">Belum ada transaksi</p>
                @endif
            </div>
        </div>

        <!-- Low Stock Products -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Produk Stok Rendah</h3>
            </div>
            <div class="p-6">
                @if($lowStockProducts->count() > 0)
                    <div class="space-y-4">
                        @foreach($lowStockProducts as $product)
                        <div class="flex items-center justify-between py-2">
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $product->nama }}</p>
                                <p class="text-sm text-gray-500">{{ $product->kategori->nama_kategori }}</p>
                            </div>
                            <div class="text-right">
                                <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full
                                    {{ $product->stok === 0 ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    Stok: {{ $product->stok }}
                                </span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-center py-4">Semua produk memiliki stok cukup</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
