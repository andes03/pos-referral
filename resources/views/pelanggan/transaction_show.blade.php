@extends('layouts.pelanggan')

@section('title', 'Detail Transaksi')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-900">Detail Transaksi</h1>
        <a href="{{ route('pelanggan.dashboard') }}#transactions" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
            ← Kembali
        </a>
    </div>

    <!-- Transaction Details -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Informasi Transaksi</h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <p class="text-sm font-medium text-gray-700">ID Transaksi</p>
                    <p class="text-sm text-gray-900">{{ $transaction->id_transaksi }}</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-700">Tanggal Transaksi</p>
                    <p class="text-sm text-gray-900">{{ $transaction->tanggal_transaksi->format('d M Y H:i') }}</p>
                </div>

                <div>
                    <p class="text-sm font-medium text-gray-700">Total Pembayaran</p>
                    <p class="text-lg font-semibold text-gray-900">Rp {{ number_format($transaction->total, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Transaction Items -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Detail Item</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produk</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Qty</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harga</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($transaction->detailTransaksi as $detail)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $detail->produk->nama }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-500">{{ $detail->produk->kategori->nama_kategori }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $detail->quantity }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">Rp {{ number_format($detail->harga, 0, ',', '.') }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-gray-50">
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-sm font-medium text-gray-900 text-right">Total</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">Rp {{ number_format($transaction->total, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection
