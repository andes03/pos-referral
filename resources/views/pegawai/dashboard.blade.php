@extends('layouts.pegawai')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-5">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Dashboard</h1>
            <p class="text-sm text-gray-500 mt-1">Selamat datang, {{ Auth::guard('pegawai')->user()->nama }}</p>
        </div>
        <div class="text-sm text-gray-500">
            {{ now()->locale('id')->isoFormat('dddd, D MMMM YYYY') }}
        </div>
    </div>

    <!-- Statistics Cards - Compact Version -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <a href="{{ route('pegawai.pelanggan.index') }}" class="bg-white rounded-lg border border-gray-200 p-4 hover:shadow-md transition-shadow cursor-pointer">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Pelanggan</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($stats['total_pelanggan']) }}</p>
                </div>
                <div class="w-10 h-10 bg-blue-50 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
            </div>
        </a>

        <a href="{{ route('pegawai.produk.index') }}" class="bg-white rounded-lg border border-gray-200 p-4 hover:shadow-md transition-shadow cursor-pointer">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Produk</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($stats['total_produk']) }}</p>
                </div>
                <div class="w-10 h-10 bg-green-50 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                </div>
            </div>
        </a>

        <a href="{{ route('pegawai.kategori.index') }}" class="bg-white rounded-lg border border-gray-200 p-4 hover:shadow-md transition-shadow cursor-pointer">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($stats['total_kategori']) }}</p>
                </div>
                <div class="w-10 h-10 bg-purple-50 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                    </svg>
                </div>
            </div>
        </a>

        <a href="{{ route('pegawai.transaksi.index') }}" class="bg-white rounded-lg border border-gray-200 p-4 hover:shadow-md transition-shadow cursor-pointer">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Transaksi</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($stats['total_transaksi']) }}</p>
                </div>
                <div class="w-10 h-10 bg-amber-50 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
            </div>
        </a>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        <!-- Pendapatan 7 Hari - Takes 2 columns -->
        <div class="lg:col-span-2 bg-white rounded-lg border border-gray-200 p-5">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-semibold text-gray-900">Pendapatan 7 Hari Terakhir</h3>
                <span class="text-xs text-gray-500">Dalam Rupiah</span>
            </div>
            <div style="position: relative; height: 280px;">
                <canvas id="chartPendapatan"></canvas>
            </div>
        </div>

        <!-- Metode Pembayaran -->
        <div class="bg-white rounded-lg border border-gray-200 p-5">
            <h3 class="text-sm font-semibold text-gray-900 mb-4">Metode Pembayaran</h3>
            <div style="position: relative; height: 280px;">
                <canvas id="chartMetodePembayaran"></canvas>
            </div>
        </div>
    </div>

    <!-- Product & Category Charts -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        <!-- Top Products -->
        <div class="bg-white rounded-lg border border-gray-200 p-5">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-semibold text-gray-900">Produk Terlaris</h3>
                <span class="text-xs text-gray-500">Top 5</span>
            </div>
            <div style="position: relative; height: 280px;">
                <canvas id="chartProdukTerlaris"></canvas>
            </div>
        </div>

        <!-- Category Sales -->
        <div class="bg-white rounded-lg border border-gray-200 p-5">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-semibold text-gray-900">Penjualan per Kategori</h3>
                <span class="text-xs text-gray-500">Total Pendapatan</span>
            </div>
            <div style="position: relative; height: 280px;">
                <canvas id="chartKategori"></canvas>
            </div>
        </div>
    </div>

    <!-- Customer Growth and Transactions Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        <!-- Customer Growth (2/3 width) -->
        <div class="lg:col-span-2 bg-white rounded-lg border border-gray-200 p-5">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-semibold text-gray-900">Pertumbuhan Pelanggan</h3>
                <span class="text-xs text-gray-500">30 Hari Terakhir</span>
            </div>
            <div style="position: relative; height: 240px;">
                <canvas id="chartPelanggan"></canvas>
            </div>
        </div>

        <!-- Recent Transactions (1/3 width) -->
        <div class="bg-white rounded-lg border border-gray-200">
            <div class="px-4 py-3 border-b border-gray-200">
                <h3 class="text-sm font-semibold text-gray-900">Transaksi Terbaru</h3>
            </div>
            <div class="p-4">
                @if($recentTransactions->count() > 0)
                    <div class="space-y-2">
                        @foreach($recentTransactions as $transaction)
                        <div class="flex flex-col py-2 border-b border-gray-100 last:border-0">
                            <div class="flex items-center justify-between mb-1">
                                <p class="text-xs font-medium text-gray-900">{{ $transaction->pelanggan->nama }}</p>
                                <p class="text-xs text-gray-500">{{ $transaction->tanggal_transaksi->format('d/m/Y H:i') }}</p>
                            </div>
                            <div class="flex items-center justify-between">
                                <p class="text-xs font-semibold text-gray-900">Rp {{ number_format($transaction->total, 0, ',', '.') }}</p>
                                <a href="{{ route('pegawai.transaksi.show', $transaction->id_transaksi) }}"
                                   class="text-blue-500 hover:text-blue-600 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"></path>
                                    </svg>
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-6">
                        <svg class="w-10 h-10 text-gray-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <p class="text-xs text-gray-500">Belum ada transaksi</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    // Chart.js Global Configuration
    Chart.defaults.font.family = "'Figtree', sans-serif";
    Chart.defaults.font.size = 12;
    Chart.defaults.color = '#6B7280';

    // 1. Grafik Pendapatan 7 Hari Terakhir
    const ctxPendapatan = document.getElementById('chartPendapatan');
    new Chart(ctxPendapatan, {
        type: 'line',
        data: {
            labels: @json($chartPendapatan['labels']),
            datasets: [{
                label: 'Pendapatan',
                data: @json($chartPendapatan['pendapatan']),
                borderColor: '#10b981',
                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                tension: 0.4,
                fill: true,
                borderWidth: 2,
                pointRadius: 4,
                pointHoverRadius: 6,
                pointBackgroundColor: '#10b981',
                pointBorderColor: '#fff',
                pointBorderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 12,
                    titleFont: { size: 13, weight: 'bold' },
                    bodyFont: { size: 12 },
                    callbacks: {
                        label: function(context) {
                            return 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    border: { display: false },
                    grid: { color: '#f3f4f6' },
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + (value/1000) + 'k';
                        }
                    }
                },
                x: {
                    border: { display: false },
                    grid: { display: false }
                }
            }
        }
    });

    // 2. Grafik Metode Pembayaran
    const ctxMetode = document.getElementById('chartMetodePembayaran');
    new Chart(ctxMetode, {
        type: 'doughnut',
        data: {
            labels: @json($chartMetodePembayaran['labels']),
            datasets: [{
                data: @json($chartMetodePembayaran['data']),
                backgroundColor: ['#10b981', '#3b82f6', '#8b5cf6'],
                borderColor: '#fff',
                borderWidth: 2,
                hoverOffset: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 15,
                        usePointStyle: true,
                        font: { size: 11 }
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 12,
                    callbacks: {
                        label: function(context) {
                            return 'Rp ' + context.parsed.toLocaleString('id-ID');
                        }
                    }
                }
            }
        }
    });

    // 3. Grafik Produk Terlaris
    const ctxProduk = document.getElementById('chartProdukTerlaris');
    new Chart(ctxProduk, {
        type: 'bar',
        data: {
            labels: @json($chartProdukTerlaris['labels']),
            datasets: [{
                label: 'Jumlah Terjual',
                data: @json($chartProdukTerlaris['terjual']),
                backgroundColor: 'rgba(59, 130, 246, 0.8)',
                borderRadius: 6,
                borderSkipped: false
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 12
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    border: { display: false },
                    grid: { color: '#f3f4f6' }
                },
                x: {
                    border: { display: false },
                    grid: { display: false }
                }
            }
        }
    });

    // 4. Grafik Kategori
    const ctxKategori = document.getElementById('chartKategori');
    new Chart(ctxKategori, {
        type: 'bar',
        data: {
            labels: @json($chartKategori['labels']),
            datasets: [{
                label: 'Pendapatan',
                data: @json($chartKategori['data']),
                backgroundColor: 'rgba(16, 185, 129, 0.8)',
                borderRadius: 6,
                borderSkipped: false
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 12,
                    callbacks: {
                        label: function(context) {
                            return 'Rp ' + context.parsed.x.toLocaleString('id-ID');
                        }
                    }
                }
            },
            scales: {
                x: {
                    beginAtZero: true,
                    border: { display: false },
                    grid: { color: '#f3f4f6' },
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + (value/1000) + 'k';
                        }
                    }
                },
                y: {
                    border: { display: false },
                    grid: { display: false }
                }
            }
        }
    });

    // 5. Grafik Pertumbuhan Pelanggan
    const ctxPelanggan = document.getElementById('chartPelanggan');
    new Chart(ctxPelanggan, {
        type: 'line',
        data: {
            labels: @json($chartPelanggan['labels']),
            datasets: [{
                label: 'Total Pelanggan',
                data: @json($chartPelanggan['data']),
                borderColor: '#8b5cf6',
                backgroundColor: 'rgba(139, 92, 246, 0.1)',
                tension: 0.4,
                fill: true,
                borderWidth: 2,
                pointRadius: 3,
                pointHoverRadius: 5,
                pointBackgroundColor: '#8b5cf6',
                pointBorderColor: '#fff',
                pointBorderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 12
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    border: { display: false },
                    grid: { color: '#f3f4f6' }
                },
                x: {
                    border: { display: false },
                    grid: { display: false },
                    ticks: {
                        maxRotation: 0,
                        autoSkip: true,
                        maxTicksLimit: 10
                    }
                }
            }
        }
    });
</script>
@endpush
@endsection