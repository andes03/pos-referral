@extends('layouts.pegawai')

@section('title', 'Detail Transaksi')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 py-6">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ $transaksi->kode_transaksi }}</h1>
                    <p class="text-sm text-gray-600 mt-1">{{ $transaksi->tanggal_transaksi->format('d M Y, H:i') }}</p>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('pegawai.transaksi.index') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Kembali
                    </a>
                    <a href="{{ route('pegawai.transaksi.create') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-green-600 border border-transparent rounded-lg hover:bg-green-700 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Tambah Transaksi
                    </a>
                    <button onclick="printNota()" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-lg hover:bg-blue-700 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                        </svg>
                        Cetak Nota
                    </button>
                </div>
            </div>
        </div>

        @php
            $subtotal = $transaksi->detailTransaksi->sum('subtotal');
            $diskon = $subtotal - $transaksi->total;
            $hasDiskon = $diskon > 0;
        @endphp

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Receipt Preview -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="p-6">
                        <!-- Receipt Header -->
                        <div class="text-center border-b border-dashed border-gray-400 pb-4 mb-4">
                            <h2 class="text-lg font-bold text-gray-900 mb-2">SEBELAS COFFEE</h2>
                            <div class="text-xs text-gray-600 leading-tight">
                                <div>Jl. Nologaten, Nologaten, Caturtunggal,</div>
                                <div>Kec. Depok, Kabupaten Sleman,</div>
                                <div>Daerah Istimewa Yogyakarta 55281</div>
                            </div>
                        </div>

                        <!-- Transaction Details -->
                        <div class="text-xs space-y-1 mb-4">
                            <div class="flex justify-between">
                                <span>No. Transaksi</span>
                                <span class="font-bold">{{ $transaksi->kode_transaksi }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Tanggal</span>
                                <span>{{ $transaksi->tanggal_transaksi->format('d/m/Y H:i') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Kasir</span>
                                <span>{{ $transaksi->pegawai->nama ?? '-' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Pelanggan</span>
                                <span>{{ $transaksi->pelanggan->nama ?? '-' }}</span>
                            </div>
                        </div>

                        <!-- Products -->
                        <div class="border-t border-dashed border-gray-400 pt-3 mb-3">
                            <div class="space-y-2">
                                @forelse($transaksi->detailTransaksi as $detail)
                                <div class="text-xs">
                                    <div class="font-bold">{{ $detail->produk->nama }}</div>
                                    <div class="flex justify-between text-gray-600" style="font-size: 10px;">
                                        <span>{{ $detail->jumlah }} x Rp {{ number_format($detail->subtotal / $detail->jumlah, 0, ',', '.') }}</span>
                                        <span class="font-bold">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                                @empty
                                <div class="text-xs text-center text-gray-500 py-2">Tidak ada detail produk</div>
                                @endforelse
                            </div>
                        </div>

                        <!-- Total -->
                        <div class="border-t border-dashed border-gray-400 pt-3 mb-4">
                            <div class="text-xs space-y-1">
                                <div class="flex justify-between">
                                    <span>Subtotal</span>
                                    <span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                                </div>
                                
                                @if($hasDiskon)
                                <div class="py-1 bg-green-50 rounded px-2 -mx-2">
                                    <div class="flex items-center justify-between mb-0.5">
                                        <span class="text-green-700 font-medium flex items-center gap-1">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            Diskon Referral 10%
                                        </span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span>Potongan</span>
                                        <span class="text-red-600 font-bold">-Rp {{ number_format($diskon, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                                @endif

                                <div class="flex justify-between font-bold text-sm pt-1">
                                    <span>TOTAL</span>
                                    <span>Rp {{ number_format($transaksi->total, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Pembayaran</span>
                                    <span class="font-bold">
                                        @if($transaksi->metode_pembayaran === 'cash')
                                            Cash
                                        @elseif($transaksi->metode_pembayaran === 'qris')
                                            QRIS
                                        @else
                                            Transfer Bank
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Footer -->
                        <div class="text-center text-xs border-t border-gray-300 pt-3">
                            <div class="font-bold mb-2">Terima kasih atas kunjungan Anda!</div>
                            <div>Barang yang sudah dibeli</div>
                            <div>tidak dapat ditukar/dikembalikan</div>
                            <div class="mt-3 pt-2 border-t border-gray-200">
                                <div>Simpan nota ini sebagai bukti</div>
                                <div>pembayaran yang sah</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Transaction Details -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Key Metrics -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="bg-white rounded-xl p-4 border border-gray-200">
                        <div class="flex items-center">
                            <div class="p-2 bg-blue-100 rounded-lg">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-xs font-medium text-gray-600">Kode Transaksi</p>
                                <p class="text-sm font-bold text-gray-900">{{ $transaksi->kode_transaksi }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl p-4 border border-gray-200">
                        <div class="flex items-center">
                            <div class="p-2 bg-green-100 rounded-lg">
                                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-xs font-medium text-gray-600">Total</p>
                                <p class="text-sm font-bold text-gray-900">Rp {{ number_format($transaksi->total, 0, ',', '.') }}</p>
                                @if($hasDiskon)
                                <p class="text-xs text-green-600 font-medium">Hemat Rp {{ number_format($diskon, 0, ',', '.') }}</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl p-4 border border-gray-200">
                        <div class="flex items-center">
                            <div class="p-2 bg-purple-100 rounded-lg">
                                <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-xs font-medium text-gray-600">Pembayaran</p>
                                <p class="text-sm font-bold text-gray-900">
                                    @if($transaksi->metode_pembayaran === 'cash')
                                        Cash
                                    @elseif($transaksi->metode_pembayaran === 'qris')
                                        QRIS
                                    @else
                                        Transfer
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Transaction Information -->
                <div class="bg-white rounded-xl p-4 border border-gray-200">
                    <h3 class="text-sm font-semibold text-gray-900 mb-3">Informasi Transaksi</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div class="space-y-2">
                            <div>
                                <label class="text-xs font-medium text-gray-600">Tanggal & Waktu</label>
                                <p class="text-xs text-gray-900">{{ $transaksi->tanggal_transaksi->format('d M Y, H:i') }}</p>
                            </div>
                            <div>
                                <label class="text-xs font-medium text-gray-600">Kasir</label>
                                <p class="text-xs text-gray-900">{{ $transaksi->pegawai->nama ?? '-' }}</p>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <div>
                                <label class="text-xs font-medium text-gray-600">Pelanggan</label>
                                <p class="text-xs text-gray-900">{{ $transaksi->pelanggan->nama ?? '-' }}</p>
                            </div>
                            @if($transaksi->pelanggan)
                            <div>
                                <label class="text-xs font-medium text-gray-600">Kontak</label>
                                <p class="text-xs text-gray-900">{{ $transaksi->pelanggan->email }}</p>
                                <p class="text-xs text-gray-900">{{ $transaksi->pelanggan->no_telp ?? '-' }}</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Products List -->
                <div class="bg-white rounded-xl p-4 border border-gray-200">
                    <h3 class="text-sm font-semibold text-gray-900 mb-3">Detail Produk</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produk</th>
                                    <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Qty</th>
                                    <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Harga</th>
                                    <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($transaksi->detailTransaksi as $detail)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="w-8 h-8 rounded-lg overflow-hidden {{ $detail->produk->image ? '' : 'bg-gradient-to-br from-green-400 to-emerald-500 flex items-center justify-center text-white font-semibold text-xs' }}">
                                                @if($detail->produk->image)
                                                    <img src="{{ asset('storage/' . $detail->produk->image) }}" alt="{{ $detail->produk->nama }}" class="w-full h-full object-cover">
                                                @else
                                                    {{ substr($detail->produk->nama, 0, 1) }}
                                                @endif
                                            </div>
                                            <div class="ml-3">
                                                <div class="text-xs font-medium text-gray-900">{{ $detail->produk->nama }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-center text-xs text-gray-900">
                                        {{ $detail->jumlah }}
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-right text-xs text-gray-900">
                                        Rp {{ number_format($detail->subtotal / $detail->jumlah, 0, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-right text-xs font-medium text-gray-900">
                                        Rp {{ number_format($detail->subtotal, 0, ',', '.') }}
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-3 text-center text-xs text-gray-500">
                                        Tidak ada detail produk
                                    </td>
                                </tr>
                                @endforelse
                                
                                <!-- Summary Footer -->
                                <tr class="bg-gray-50 font-medium">
                                    <td colspan="3" class="px-4 py-2 text-right text-xs text-gray-700">
                                        Subtotal
                                    </td>
                                    <td class="px-4 py-2 text-right text-xs text-gray-900">
                                        Rp {{ number_format($subtotal, 0, ',', '.') }}
                                    </td>
                                </tr>
                                
                                @if($hasDiskon)
                                <tr class="bg-green-50">
                                    <td colspan="3" class="px-4 py-2 text-right text-xs text-green-700 font-medium">
                                        <span class="flex items-center justify-end gap-1">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            Diskon Referral (10%)
                                        </span>
                                    </td>
                                    <td class="px-4 py-2 text-right text-xs text-red-600 font-bold">
                                        -Rp {{ number_format($diskon, 0, ',', '.') }}
                                    </td>
                                </tr>
                                @endif
                                
                                <tr class="bg-gray-100 font-bold">
                                    <td colspan="3" class="px-4 py-3 text-right text-sm text-gray-900">
                                        TOTAL BAYAR
                                    </td>
                                    <td class="px-4 py-3 text-right text-sm text-green-600">
                                        Rp {{ number_format($transaksi->total, 0, ',', '.') }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function printNota() {
    const kode = '{{ $transaksi->kode_transaksi }}';
    const tanggal = '{{ $transaksi->tanggal_transaksi->format('d/m/Y H:i') }}';
    const metode = '{{ $transaksi->metode_pembayaran === 'cash' ? 'Cash' : ($transaksi->metode_pembayaran === 'qris' ? 'QRIS' : 'Transfer Bank') }}';
    const pegawai = '{{ $transaksi->pegawai->nama ?? '-' }}';
    const pelanggan = '{{ $transaksi->pelanggan->nama ?? '-' }}';
    
    const subtotal = {{ $subtotal }};
    const diskon = {{ $diskon }};
    const total = {{ $transaksi->total }};
    const hasDiskon = {{ $hasDiskon ? 'true' : 'false' }};

    // Get product list
    const produkItems = @json($transaksi->detailTransaksi);
    let produkHTML = '';

    produkItems.forEach(item => {
        const nama = item.produk.nama;
        const qty = item.jumlah;
        const harga = (item.subtotal / item.jumlah).toLocaleString('id-ID');
        const subtotalItem = item.subtotal.toLocaleString('id-ID');

        produkHTML += `
            <tr>
                <td colspan="3" style="padding: 4px 0;">
                    <div style="font-weight: bold;">${nama}</div>
                    <div style="color: #666; display: flex; justify-content: space-between; font-size: 10px;">
                        <span>${qty} x Rp ${harga}</span>
                        <span style="font-weight: bold;">Rp ${subtotalItem}</span>
                    </div>
                </td>
            </tr>
        `;
    });

    const printWindow = window.open('', '', 'height=600,width=400');
    printWindow.document.write('<html><head><title>Nota Transaksi</title>');
    printWindow.document.write(`
        <style>
            @media print {
                @page {
                    size: 80mm auto;
                    margin: 0;
                }
            }
            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }
            body {
                font-family: 'Courier New', monospace;
                width: 80mm;
                margin: 0 auto;
                padding: 5mm;
                font-size: 11px;
                line-height: 1.4;
            }
            .text-center { text-align: center; }
            .font-bold { font-weight: bold; }
            .border-dashed {
                border-style: dashed !important;
                border-color: #999 !important;
            }
            .border-b { border-bottom: 1px dashed #999; padding-bottom: 8px; margin-bottom: 8px; }
            .border-t { border-top: 1px dashed #999; padding-top: 8px; margin-top: 8px; }
            table { width: 100%; border-collapse: collapse; }
            .flex { display: flex; justify-content: space-between; margin: 2px 0; }
            .text-xs { font-size: 10px; }
            .text-sm { font-size: 11px; }
            .text-xl { font-size: 16px; }
            h1 { font-size: 18px; margin-bottom: 4px; }
            .discount-box {
                background: #f0fdf4;
                padding: 6px;
                margin: 6px -6px;
                border-radius: 4px;
            }
            .discount-label {
                display: flex;
                align-items: center;
                justify-content: space-between;
                margin-bottom: 2px;
                color: #166534;
                font-weight: bold;
            }
        </style>
    `);
    printWindow.document.write('</head><body>');
    printWindow.document.write(`
        <div class="text-center border-b pb-3 mb-3" style="padding-bottom: 12px; margin-bottom: 12px;">
            <h1 class="font-bold">SEBELAS COFFEE</h1>
            <div class="text-xs">Jl. Nologaten, Nologaten, Caturtunggal,</div>
            <div class="text-xs">Kec. Depok, Kabupaten Sleman,</div>
            <div class="text-xs">Daerah Istimewa Yogyakarta 55281</div>
        </div>

        <div class="text-xs" style="margin-bottom: 12px;">
            <div class="flex">
                <span>No. Transaksi</span>
                <span class="font-bold">${kode}</span>
            </div>
            <div class="flex">
                <span>Tanggal</span>
                <span>${tanggal}</span>
            </div>
            <div class="flex">
                <span>Kasir</span>
                <span>${pegawai}</span>
            </div>
            <div class="flex">
                <span>Pelanggan</span>
                <span>${pelanggan}</span>
            </div>
        </div>

        <div class="border-t" style="padding-top: 8px; margin-bottom: 8px;">
            <table class="text-xs">
                ${produkHTML}
            </table>
        </div>

        <div class="border-t text-xs" style="padding-top: 8px; margin-bottom: 12px;">
            <div class="flex">
                <span>Subtotal</span>
                <span>Rp ${subtotal.toLocaleString('id-ID')}</span>
            </div>
            
            ${hasDiskon ? `
            <div class="discount-box">
                <div class="discount-label text-xs">
                    <span>✓ Diskon Referral 10%</span>
                </div>
                <div class="flex" style="color: #dc2626;">
                    <span>Potongan</span>
                    <span class="font-bold">-Rp ${diskon.toLocaleString('id-ID')}</span>
                </div>
            </div>
            ` : ''}
            
            <div class="flex font-bold text-sm" style="margin-top: 6px; font-size: 12px;">
                <span>TOTAL</span>
                <span>Rp ${total.toLocaleString('id-ID')}</span>
            </div>
            <div class="flex">
                <span>Pembayaran</span>
                <span class="font-bold">${metode}</span>
            </div>
        </div>

        <div class="text-center text-xs border-t" style="padding-top: 12px;">
            <div class="font-bold" style="margin-bottom: 8px;">Terima kasih atas kunjungan Anda!</div>
            ${hasDiskon ? '<div style="color: #059669; font-weight: bold; margin-bottom: 8px;">Anda hemat Rp ' + diskon.toLocaleString('id-ID') + '!</div>' : ''}
            <div>Barang yang sudah dibeli</div>
            <div>tidak dapat ditukar/dikembalikan</div>
            <div style="margin-top: 12px; padding-top: 8px; border-top: 1px solid #ccc;">
                <div>Simpan nota ini sebagai bukti</div>
                <div>pembayaran yang sah</div>
            </div>
        </div>
    `);
    printWindow.document.write('</body></html>');
    printWindow.document.close();

    setTimeout(() => {
        printWindow.print();
        printWindow.close();
    }, 250);
}
</script>
@endsection