@extends('layouts.pegawai')

@section('title', 'Laporan Transaksi')

@section('content')
<div class="container mx-auto px-4 py-8 space-y-6">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Laporan Transaksi</h1>
        <p class="text-gray-500 mt-1">Laporan dan analisis data transaksi</p>
    </div>

    <!-- Filter Form -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Filter Laporan</h2>
        <form id="filterForm" method="GET" action="{{ route('pegawai.laporan.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
            <div>
                <label for="tanggal_mulai" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Mulai</label>
                <input type="date" id="tanggal_mulai" name="tanggal_mulai"
                       value="{{ request('tanggal_mulai') }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-green-600 focus:border-green-600">
            </div>
            <div>
                <label for="tanggal_selesai" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Selesai</label>
                <input type="date" id="tanggal_selesai" name="tanggal_selesai"
                       value="{{ request('tanggal_selesai') }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-green-600 focus:border-green-600">
            </div>
            <div>
                <label for="metode_pembayaran" class="block text-sm font-medium text-gray-700 mb-2">Metode Pembayaran</label>
                <select id="metode_pembayaran" name="metode_pembayaran"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-green-600 focus:border-green-600">
                    <option value="all" {{ request('metode_pembayaran') === 'all' ? 'selected' : '' }}>Semua Metode</option>
                    <option value="cash" {{ request('metode_pembayaran') === 'cash' ? 'selected' : '' }}>Cash</option>
                    <option value="qris" {{ request('metode_pembayaran') === 'qris' ? 'selected' : '' }}>QRIS</option>
                    <option value="transfer" {{ request('metode_pembayaran') === 'transfer' ? 'selected' : '' }}>Transfer</option>
                </select>
            </div>
            <div class="flex items-end">
                <button type="button" onclick="exportPDF()"
                        class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition-all shadow-sm hover:shadow-md flex items-center justify-center gap-2">
                    <x-heroicon-o-document-arrow-down class="w-4 h-4" />
                    <span>Download PDF</span>
                </button>
            </div>
        </form>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
        <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-gray-600">Total Transaksi</p>
                    <p class="text-lg font-bold text-gray-900">{{ $summary['total_transaksi'] }}</p>
                </div>
                <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                    <x-heroicon-o-document-text class="w-4 h-4 text-blue-600" />
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-gray-600">Total Pendapatan</p>
                    <p class="text-lg font-bold text-green-600">Rp {{ number_format($summary['total_pendapatan'], 0, ',', '.') }}</p>
                </div>
                <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                    <x-heroicon-o-currency-dollar class="w-4 h-4 text-green-600" />
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-gray-600">Cash</p>
                    <p class="text-lg font-bold text-gray-900">Rp {{ number_format($summary['total_cash'], 0, ',', '.') }}</p>
                </div>
                <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center">
                    <x-heroicon-o-banknotes class="w-4 h-4 text-gray-600" />
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-gray-600">QRIS</p>
                    <p class="text-lg font-bold text-gray-900">Rp {{ number_format($summary['total_qris'], 0, ',', '.') }}</p>
                </div>
                <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                    <x-heroicon-o-qr-code class="w-4 h-4 text-purple-600" />
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-gray-600">Transfer</p>
                    <p class="text-lg font-bold text-gray-900">Rp {{ number_format($summary['total_transfer'], 0, ',', '.') }}</p>
                </div>
                <div class="w-8 h-8 bg-indigo-100 rounded-lg flex items-center justify-center">
                    <x-heroicon-o-credit-card class="w-4 h-4 text-indigo-600" />
                </div>
            </div>
        </div>
    </div>

    <!-- Transaction Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h3 class="text-lg font-semibold text-gray-800">Detail Transaksi</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Kode Transaksi</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Pelanggan</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Pegawai</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Metode</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">Total</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse($transaksi as $item)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $item->kode_transaksi }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            {{ $item->tanggal_transaksi->format('d/m/Y H:i') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $item->pelanggan->nama ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $item->pegawai->nama ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($item->metode_pembayaran === 'cash')
                                <span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-700 rounded">Cash</span>
                            @elseif($item->metode_pembayaran === 'qris')
                                <span class="px-2 py-1 text-xs font-medium bg-blue-100 text-blue-700 rounded">QRIS</span>
                            @elseif($item->metode_pembayaran === 'transfer')
                                <span class="px-2 py-1 text-xs font-medium bg-purple-100 text-purple-700 rounded">Transfer</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900 text-right">
                            Rp {{ number_format($item->total, 0, ',', '.') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center">
                                <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                </svg>
                                <p class="text-gray-500 font-medium">Tidak ada data transaksi</p>
                                <p class="text-gray-400 text-sm mt-1">Coba ubah filter untuk melihat data</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $transaksi->appends(request()->query())->links() }}
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('filterForm');
    const inputs = form.querySelectorAll('input, select');

    inputs.forEach(input => {
        input.addEventListener('change', function() {
            form.submit();
        });
    });
});

function exportPDF() {
    const formData = new FormData(document.getElementById('filterForm'));
    const params = new URLSearchParams();

    for (let [key, value] of formData.entries()) {
        if (value.trim() !== '') {
            params.append(key, value);
        }
    }

    const url = '{{ route("pegawai.laporan.exportPDF") }}?' + params.toString();
    window.open(url, '_blank');
}
</script>
@endsection
