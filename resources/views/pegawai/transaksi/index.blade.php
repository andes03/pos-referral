@extends('layouts.pegawai')

@section('title', 'Kelola Transaksi')

@section('content')
<div class="container mx-auto px-4 py-8 space-y-6">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Kelola Transaksi</h1>
        <p class="text-gray-500 mt-1">Manajemen data transaksi perusahaan</p>
    </div>

    <div class="flex flex-col sm:flex-row gap-3 items-stretch sm:items-center">
        <div class="flex-1 relative">
            <svg class="w-5 h-5 absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
            <input type="text" id="searchInput" placeholder="Cari kode transaksi atau nama pelanggan..."
                   class="w-full pl-10 pr-4 py-2.5 bg-white border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-green-600 focus:border-green-600 transition-all">
        </div>
        <a href="{{ route('pegawai.transaksi.create') }}" class="bg-green-600 hover:bg-green-700 text-white px-5 py-2.5 rounded-lg flex items-center justify-center gap-2 transition-all shadow-sm hover:shadow-md">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            <span class="font-medium">Tambah</span>
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="sticky top-0 bg-gradient-to-r from-green-50 to-emerald-50 border-b border-gray-100 z-10">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Kode Transaksi</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Pelanggan</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Pegawai</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Metode</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Total</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody id="transaksiTableBody">
                </tbody>
            </table>
        </div>
        <div id="paginationContainer" class="px-6 py-4 bg-gray-50 border-t border-gray-100">
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 overflow-y-auto h-full w-full hidden z-50" style="background-color: rgba(0, 0, 0, 0.5);">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="relative w-full max-w-sm bg-white rounded-lg shadow-xl p-5">
            <div class="text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-3">
                    <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
                <h3 class="text-base font-semibold text-gray-900 mb-1">Konfirmasi Hapus</h3>
                <p class="text-xs text-gray-600 mb-4">Apakah Anda yakin ingin menghapus transaksi ini? Stok produk akan dikembalikan.</p>

                <div class="flex gap-2">
                    <button type="button" onclick="closeDeleteModal()"
                            class="flex-1 px-4 py-1.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded hover:bg-gray-50">
                        Batal
                    </button>
                    <button type="button" id="confirmDeleteBtn" onclick="confirmDelete()"
                            class="flex-1 px-4 py-1.5 text-sm font-medium text-white bg-red-600 rounded hover:bg-red-700">
                        Hapus
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- View Modal -->
<div id="viewModal" class="fixed inset-0 overflow-y-auto h-full w-full hidden z-50" style="background-color: rgba(0, 0, 0, 0.5);">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="relative w-full max-w-4xl bg-white rounded-lg shadow-xl">
            <div class="flex justify-between items-center px-5 py-3 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Detail Transaksi</h3>
                <button onclick="closeViewModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <div class="p-5">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <h4 class="text-sm font-medium text-gray-700 mb-2">Informasi Transaksi</h4>
                        <div class="space-y-2 text-sm">
                            <p><span class="font-medium text-gray-600">Kode:</span> <span class="text-gray-900" id="viewKode"></span></p>
                            <p><span class="font-medium text-gray-600">Tanggal:</span> <span class="text-gray-900" id="viewTanggal"></span></p>
                            <p><span class="font-medium text-gray-600">Metode:</span> <span class="text-gray-900" id="viewMetode"></span></p>
                            <p><span class="font-medium text-gray-600">Pegawai:</span> <span class="text-gray-900" id="viewPegawai"></span></p>
                        </div>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-700 mb-2">Informasi Pelanggan</h4>
                        <div class="space-y-2 text-sm">
                            <p><span class="font-medium text-gray-600">Nama:</span> <span class="text-gray-900" id="viewPelanggan"></span></p>
                            <p><span class="font-medium text-gray-600">Email:</span> <span class="text-gray-900" id="viewEmail"></span></p>
                            <p><span class="font-medium text-gray-600">No. Telp:</span> <span class="text-gray-900" id="viewNoTelp"></span></p>
                        </div>
                    </div>
                </div>

                <div>
                    <h4 class="text-sm font-medium text-gray-700 mb-3">Detail Produk</h4>
                    <div id="produkList" class="space-y-3 max-h-96 overflow-y-auto">
                        <!-- Produk akan dimuat di sini -->
                    </div>
                    <div class="border-t border-gray-200 pt-3 mt-3">
                        <div class="space-y-1 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Subtotal:</span>
                                <span class="text-gray-900" id="viewSubtotal"></span>
                            </div>
                            <div id="viewDiscountRow" class="hidden">
                                <div class="flex justify-between items-center py-2 px-3 bg-green-50 rounded-lg">
                                    <span class="text-green-700 font-medium flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        Diskon Referral (10%)
                                    </span>
                                    <span class="text-red-600 font-bold" id="viewDiskon"></span>
                                </div>
                            </div>
                            <div class="flex justify-between items-center pt-2 border-t border-gray-200">
                                <span class="text-lg font-semibold text-gray-900">Total:</span>
                                <span class="text-lg font-bold text-green-600">Rp <span id="viewTotal"></span></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-end px-5 py-3 border-t border-gray-200">
                <div class="flex gap-2">
                    <button type="button" onclick="printNotaFromModal()"
                            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded hover:bg-blue-700 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                        </svg>
                        Print
                    </button>
                    <button type="button" onclick="closeViewModal()"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded hover:bg-gray-50">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let currentPage = 1;
let deleteId = null;
let searchTimeout;
let isSearching = false;
let currentTransaksiData = null; // Store current transaction data for printing

// Data awal dari server
const initialData = @json($initialData ?? []);
const initialPagination = @json($pagination ?? null);

document.addEventListener('DOMContentLoaded', function() {
    // Render data awal langsung tanpa AJAX call
    if (initialData.length > 0) {
        renderTable(initialData);
        if (initialPagination) {
            renderPagination(initialPagination);
            currentPage = initialPagination.current_page;
        }
    } else {
        renderTable([]);
    }

    // Search on input change with debounce (600ms for better UX)
    const searchInput = document.getElementById('searchInput');
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);

        // Show searching indicator
        showSearchingIndicator();

        searchTimeout = setTimeout(function() {
            searchData();
        }, 600);
    });
});

function loadTransaksi(page = 1) {
    if (isSearching) return; // Prevent multiple simultaneous requests

    isSearching = true;
    currentPage = page;
    const search = document.getElementById('searchInput').value;

    const tbody = document.getElementById('transaksiTableBody');
    tbody.innerHTML = `
        <tr class="border-b border-gray-100">
            <td class="px-6 py-3">
                <div class="h-4 bg-gray-200 rounded animate-pulse w-24"></div>
            </td>
            <td class="px-6 py-3">
                <div class="flex items-center">
                    <div class="flex-shrink-0 h-8 w-8 rounded-full bg-gray-200 animate-pulse"></div>
                    <div class="ml-3">
                        <div class="h-4 bg-gray-200 rounded animate-pulse w-20"></div>
                        <div class="h-3 bg-gray-200 rounded animate-pulse w-16 mt-1"></div>
                    </div>
                </div>
            </td>
            <td class="px-6 py-3">
                <div class="h-4 bg-gray-200 rounded animate-pulse w-16"></div>
            </td>
            <td class="px-6 py-3">
                <div class="h-4 bg-gray-200 rounded animate-pulse w-20"></div>
                <div class="h-3 bg-gray-200 rounded animate-pulse w-16 mt-1"></div>
            </td>
            <td class="px-6 py-3">
                <div class="h-5 bg-gray-200 rounded-full animate-pulse w-12"></div>
            </td>
            <td class="px-6 py-3">
                <div class="h-4 bg-gray-200 rounded animate-pulse w-16"></div>
            </td>
            <td class="px-6 py-3">
                <div class="flex items-center justify-end gap-2">
                    <div class="h-8 w-8 bg-gray-200 rounded animate-pulse"></div>
                    <div class="h-8 w-8 bg-gray-200 rounded animate-pulse"></div>
                </div>
            </td>
        </tr>
        <tr class="border-b border-gray-100">
            <td class="px-6 py-3">
                <div class="h-4 bg-gray-200 rounded animate-pulse w-28"></div>
            </td>
            <td class="px-6 py-3">
                <div class="flex items-center">
                    <div class="flex-shrink-0 h-8 w-8 rounded-full bg-gray-200 animate-pulse"></div>
                    <div class="ml-3">
                        <div class="h-4 bg-gray-200 rounded animate-pulse w-24"></div>
                        <div class="h-3 bg-gray-200 rounded animate-pulse w-20 mt-1"></div>
                    </div>
                </div>
            </td>
            <td class="px-6 py-3">
                <div class="h-4 bg-gray-200 rounded animate-pulse w-18"></div>
            </td>
            <td class="px-6 py-3">
                <div class="h-4 bg-gray-200 rounded animate-pulse w-22"></div>
                <div class="h-3 bg-gray-200 rounded animate-pulse w-18 mt-1"></div>
            </td>
            <td class="px-6 py-3">
                <div class="h-5 bg-gray-200 rounded-full animate-pulse w-14"></div>
            </td>
            <td class="px-6 py-3">
                <div class="h-4 bg-gray-200 rounded animate-pulse w-18"></div>
            </td>
            <td class="px-6 py-3">
                <div class="flex items-center justify-end gap-2">
                    <div class="h-8 w-8 bg-gray-200 rounded animate-pulse"></div>
                    <div class="h-8 w-8 bg-gray-200 rounded animate-pulse"></div>
                </div>
            </td>
        </tr>
        <tr class="border-b border-gray-100">
            <td class="px-6 py-3">
                <div class="h-4 bg-gray-200 rounded animate-pulse w-26"></div>
            </td>
            <td class="px-6 py-3">
                <div class="flex items-center">
                    <div class="flex-shrink-0 h-8 w-8 rounded-full bg-gray-200 animate-pulse"></div>
                    <div class="ml-3">
                        <div class="h-4 bg-gray-200 rounded animate-pulse w-22"></div>
                        <div class="h-3 bg-gray-200 rounded animate-pulse w-18 mt-1"></div>
                    </div>
                </div>
            </td>
            <td class="px-6 py-3">
                <div class="h-4 bg-gray-200 rounded animate-pulse w-20"></div>
            </td>
            <td class="px-6 py-3">
                <div class="h-4 bg-gray-200 rounded animate-pulse w-24"></div>
                <div class="h-3 bg-gray-200 rounded animate-pulse w-20 mt-1"></div>
            </td>
            <td class="px-6 py-3">
                <div class="h-5 bg-gray-200 rounded-full animate-pulse w-16"></div>
            </td>
            <td class="px-6 py-3">
                <div class="h-4 bg-gray-200 rounded animate-pulse w-20"></div>
            </td>
            <td class="px-6 py-3">
                <div class="flex items-center justify-end gap-2">
                    <div class="h-8 w-8 bg-gray-200 rounded animate-pulse"></div>
                    <div class="h-8 w-8 bg-gray-200 rounded animate-pulse"></div>
                </div>
            </td>
        </tr>
        <tr class="border-b border-gray-100">
            <td class="px-6 py-3">
                <div class="h-4 bg-gray-200 rounded animate-pulse w-30"></div>
            </td>
            <td class="px-6 py-3">
                <div class="flex items-center">
                    <div class="flex-shrink-0 h-8 w-8 rounded-full bg-gray-200 animate-pulse"></div>
                    <div class="ml-3">
                        <div class="h-4 bg-gray-200 rounded animate-pulse w-26"></div>
                        <div class="h-3 bg-gray-200 rounded animate-pulse w-22 mt-1"></div>
                    </div>
                </div>
            </td>
            <td class="px-6 py-3">
                <div class="h-4 bg-gray-200 rounded animate-pulse w-19"></div>
            </td>
            <td class="px-6 py-3">
                <div class="h-4 bg-gray-200 rounded animate-pulse w-23"></div>
                <div class="h-3 bg-gray-200 rounded animate-pulse w-19 mt-1"></div>
            </td>
            <td class="px-6 py-3">
                <div class="h-5 bg-gray-200 rounded-full animate-pulse w-13"></div>
            </td>
            <td class="px-6 py-3">
                <div class="h-4 bg-gray-200 rounded animate-pulse w-17"></div>
            </td>
            <td class="px-6 py-3">
                <div class="flex items-center justify-end gap-2">
                    <div class="h-8 w-8 bg-gray-200 rounded animate-pulse"></div>
                    <div class="h-8 w-8 bg-gray-200 rounded animate-pulse"></div>
                </div>
            </td>
        </tr>
        <tr class="border-b border-gray-100">
            <td class="px-6 py-3">
                <div class="h-4 bg-gray-200 rounded animate-pulse w-25"></div>
            </td>
            <td class="px-6 py-3">
                <div class="flex items-center">
                    <div class="flex-shrink-0 h-8 w-8 rounded-full bg-gray-200 animate-pulse"></div>
                    <div class="ml-3">
                        <div class="h-4 bg-gray-200 rounded animate-pulse w-21"></div>
                        <div class="h-3 bg-gray-200 rounded animate-pulse w-17 mt-1"></div>
                    </div>
                </div>
            </td>
            <td class="px-6 py-3">
                <div class="h-4 bg-gray-200 rounded animate-pulse w-15"></div>
            </td>
            <td class="px-6 py-3">
                <div class="h-4 bg-gray-200 rounded animate-pulse w-21"></div>
                <div class="h-3 bg-gray-200 rounded animate-pulse w-17 mt-1"></div>
            </td>
            <td class="px-6 py-3">
                <div class="h-5 bg-gray-200 rounded-full animate-pulse w-15"></div>
            </td>
            <td class="px-6 py-3">
                <div class="h-4 bg-gray-200 rounded animate-pulse w-19"></div>
            </td>
            <td class="px-6 py-3">
                <div class="flex items-center justify-end gap-2">
                    <div class="h-8 w-8 bg-gray-200 rounded animate-pulse"></div>
                    <div class="h-8 w-8 bg-gray-200 rounded animate-pulse"></div>
                </div>
            </td>
        </tr>
    `;

    fetch(`{{ route('pegawai.transaksi.index') }}?page=${page}&search=${encodeURIComponent(search)}`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        renderTable(data.data);
        renderPagination(data.pagination);
        isSearching = false;
        hideSearchingIndicator();
    })
    .catch(error => {
        console.error('Error:', error);
        tbody.innerHTML = `
            <tr>
                <td colspan="7" class="px-6 py-8 text-center">
                    <p class="text-red-500">Gagal memuat data. Silakan refresh halaman.</p>
                </td>
            </tr>
        `;
        isSearching = false;
        hideSearchingIndicator();
    });
}

function renderTable(transaksi) {
    const tbody = document.getElementById('transaksiTableBody');
    tbody.innerHTML = '';

    if (transaksi.length === 0) {
        if (document.getElementById('searchInput').value.trim() !== '') {
            tbody.innerHTML = `
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center">
                            <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            <p class="text-gray-500 font-medium">Tidak ada hasil untuk "${document.getElementById('searchInput').value}"</p>
                            <p class="text-gray-400 text-sm mt-1">Coba gunakan kata kunci lain</p>
                        </div>
                    </td>
                </tr>
            `;
        } else {
            tbody.innerHTML = `
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center">
                            <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                            </svg>
                            <p class="text-gray-500 font-medium">Tidak ada data transaksi</p>
                            <p class="text-gray-400 text-sm mt-1">Mulai tambahkan transaksi baru</p>
                        </div>
                    </td>
                </tr>
            `;
        }
        return;
    }

    transaksi.forEach(item => {
        // Generate kode transaksi from date and ID
        const date = new Date(item.tanggal_transaksi);
        const kodeTransaksi = `${date.getDate().toString().padStart(2, '0')}${(date.getMonth() + 1).toString().padStart(2, '0')}${date.getFullYear().toString().slice(-2)}-TRA${item.id_transaksi.toString().padStart(3, '0')}`;

        // Format metode pembayaran
        const metodeBadge = item.metode_pembayaran === 'cash'
            ? '<span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-700 rounded">Cash</span>'
            : item.metode_pembayaran === 'qris'
            ? '<span class="px-2 py-1 text-xs font-medium bg-blue-100 text-blue-700 rounded">QRIS</span>'
            : '<span class="px-2 py-1 text-xs font-medium bg-purple-100 text-purple-700 rounded">Transfer</span>';

        const row = `
            <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors">
                <td class="px-6 py-3">
                    <div class="text-sm font-medium text-gray-900">${kodeTransaksi}</div>
                </td>
                <td class="px-6 py-3">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-8 w-8 rounded-full overflow-hidden ${item.pelanggan?.image ? '' : 'bg-gradient-to-br from-blue-400 to-indigo-500 flex items-center justify-center text-white font-semibold text-xs'}">
                            ${item.pelanggan?.image
                                ? `<img src="/storage/${item.pelanggan.image}" alt="${item.pelanggan.nama}" class="h-full w-full object-cover" onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\\'h-8 w-8 bg-gradient-to-br from-blue-400 to-indigo-500 rounded-full flex items-center justify-center text-white font-semibold text-xs\\'>${item.pelanggan.nama.charAt(0).toUpperCase()}</div>'">`
                                : (item.pelanggan?.nama ? item.pelanggan.nama.charAt(0).toUpperCase() : '-')
                            }
                        </div>
                        <div class="ml-3">
                            <div class="text-sm font-medium text-gray-900">${item.pelanggan?.nama || '-'}</div>
                            <div class="text-xs text-gray-500">${item.pelanggan?.email || ''}</div>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-3">
                    <div class="text-sm text-gray-900">${item.pegawai?.nama || '-'}</div>
                </td>
                <td class="px-6 py-3">
                    <div class="text-sm text-gray-600">${new Date(item.tanggal_transaksi).toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' })}</div>
                    <div class="text-xs text-gray-500">${new Date(item.tanggal_transaksi).toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' })}</div>
                </td>
                <td class="px-6 py-3">${metodeBadge}</td>
                <td class="px-6 py-3">
                    <div class="text-sm font-semibold text-gray-900">Rp ${parseFloat(item.total).toLocaleString('id-ID')}</div>
                </td>
                <td class="px-6 py-3">
                    <div class="flex items-center justify-end gap-2">
                        <button onclick="viewTransaksi(${item.id_transaksi})" class="p-1.5 bg-blue-50 text-blue-600 hover:bg-blue-100 rounded-md transition-all" title="Lihat Detail">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </button>
                        <button onclick="deleteTransaksi(${item.id_transaksi})" class="p-1.5 bg-red-50 text-red-600 hover:bg-red-100 rounded-md transition-all" title="Hapus">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </button>
                    </div>
                </td>
            </tr>
        `;
        tbody.innerHTML += row;
    });
}

function renderPagination(pagination) {
    const container = document.getElementById('paginationContainer');
    if (pagination.last_page <= 1) {
        container.innerHTML = '';
        return;
    }

    let html = '<div class="flex items-center justify-between">';
    html += `<p class="text-sm text-gray-600">Menampilkan ${((pagination.current_page - 1) * pagination.per_page) + 1} - ${Math.min(pagination.current_page * pagination.per_page, pagination.total)} dari ${pagination.total} data</p>`;
    html += '<div class="flex gap-1">';

    // Previous button
    if (pagination.current_page > 1) {
        html += `
            <button onclick="loadTransaksi(${pagination.current_page - 1})" class="p-1.5 text-gray-700 bg-white border border-gray-200 rounded hover:bg-gray-50 transition-all" title="Previous">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </button>
        `;
    } else {
        html += `
            <button disabled class="p-1.5 text-gray-300 bg-gray-100 border border-gray-200 rounded cursor-not-allowed" title="Previous">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </button>
        `;
    }

    // Page numbers
    for (let i = Math.max(1, pagination.current_page - 2); i <= Math.min(pagination.last_page, pagination.current_page + 2); i++) {
        if (i === pagination.current_page) {
            html += `<button class="px-3 py-1.5 text-sm text-white bg-green-600 border border-green-600 rounded font-medium">${i}</button>`;
        } else {
            html += `<button onclick="loadTransaksi(${i})" class="px-3 py-1.5 text-sm text-gray-700 bg-white border border-gray-200 rounded hover:bg-gray-50 transition-all">${i}</button>`;
        }
    }

    // Next button
    if (pagination.current_page < pagination.last_page) {
        html += `
            <button onclick="loadTransaksi(${pagination.current_page + 1})" class="p-1.5 text-gray-700 bg-white border border-gray-200 rounded hover:bg-gray-50 transition-all" title="Next">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>
        `;
    } else {
        html += `
            <button disabled class="p-1.5 text-gray-300 bg-gray-100 border border-gray-200 rounded cursor-not-allowed" title="Next">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>
        `;
    }

    html += '</div></div>';
    container.innerHTML = html;
}

function searchData() {
    loadTransaksi(1);
}

function deleteTransaksi(id) {
    deleteId = id;
    document.getElementById('deleteModal').classList.remove('hidden');
}

function confirmDelete() {
    const confirmBtn = document.getElementById('confirmDeleteBtn');
    confirmBtn.disabled = true;
    confirmBtn.innerHTML = 'Menghapus...';

    fetch(`{{ route('pegawai.transaksi.index') }}/${deleteId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            closeDeleteModal();
            loadTransaksi(currentPage);
            showAlert(data.message || 'Transaksi berhasil dihapus', 'success');
        } else {
            showAlert(data.message || 'Terjadi kesalahan', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('Terjadi kesalahan saat menghapus transaksi', 'error');
    })
    .finally(() => {
        confirmBtn.disabled = false;
        confirmBtn.innerHTML = 'Hapus';
    });
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
    deleteId = null;
}

function viewTransaksi(id) {
    fetch(`{{ route('pegawai.transaksi.index') }}/${id}`, {
        headers: {
            'Accept': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (!data.success) {
            throw new Error(data.message || 'Gagal memuat data');
        }

        const transaksi = data.data;
        currentTransaksiData = transaksi; // Store for printing
        
        // Generate kode transaksi
        const date = new Date(transaksi.tanggal_transaksi);
        const kodeTransaksi = `${date.getDate().toString().padStart(2, '0')}${(date.getMonth() + 1).toString().padStart(2, '0')}${date.getFullYear().toString().slice(-2)}-TRA${transaksi.id_transaksi.toString().padStart(3, '0')}`;
        
        document.getElementById('viewKode').textContent = kodeTransaksi;
        document.getElementById('viewTanggal').textContent = new Date(transaksi.tanggal_transaksi).toLocaleDateString('id-ID', { 
            day: '2-digit', 
            month: 'long', 
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
        
        // Metode pembayaran
        const metodeMap = {
            'cash': 'Cash',
            'qris': 'QRIS',
            'transfer': 'Transfer Bank'
        };
        document.getElementById('viewMetode').textContent = metodeMap[transaksi.metode_pembayaran] || transaksi.metode_pembayaran;
        
        // Pegawai
        document.getElementById('viewPegawai').textContent = transaksi.pegawai?.nama || '-';

        // Pelanggan
        if (transaksi.pelanggan) {
            document.getElementById('viewPelanggan').textContent = transaksi.pelanggan.nama;
            document.getElementById('viewEmail').textContent = transaksi.pelanggan.email;
            document.getElementById('viewNoTelp').textContent = transaksi.pelanggan.no_telp || '-';
        }

        // Load detail transaksi
        const produkList = document.getElementById('produkList');
        produkList.innerHTML = '';

        let subtotal = 0;
        if (transaksi.detail_transaksi && transaksi.detail_transaksi.length > 0) {
            transaksi.detail_transaksi.forEach(detail => {
                subtotal += parseFloat(detail.subtotal);
                const produkItem = `
                    <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                        <div class="flex-shrink-0 h-12 w-12 rounded-lg overflow-hidden ${detail.produk?.image ? '' : 'bg-gradient-to-br from-green-400 to-emerald-500 flex items-center justify-center text-white font-semibold text-lg'}">
                            ${detail.produk?.image
                                ? `<img src="/storage/${detail.produk.image}" alt="${detail.produk.nama}" class="h-full w-full object-cover">`
                                : (detail.produk?.nama ? detail.produk.nama.charAt(0).toUpperCase() : 'P')
                            }
                        </div>
                        <div class="ml-3 flex-1">
                            <p class="text-sm font-medium text-gray-900">${detail.produk?.nama || 'Produk tidak ditemukan'}</p>
                            <p class="text-xs text-gray-600">${detail.jumlah} x Rp ${parseFloat(detail.subtotal / detail.jumlah).toLocaleString('id-ID')} = <span class="font-semibold">Rp ${parseFloat(detail.subtotal).toLocaleString('id-ID')}</span></p>
                        </div>
                    </div>
                `;
                produkList.innerHTML += produkItem;
            });
        } else {
            produkList.innerHTML = '<p class="text-sm text-gray-500 text-center py-4">Tidak ada detail produk</p>';
        }

        // Calculate discount
        const total = parseFloat(transaksi.total);
        const diskon = subtotal - total;
        const hasDiskon = diskon > 0;

        // Display subtotal, discount, and total
        document.getElementById('viewSubtotal').textContent = 'Rp ' + subtotal.toLocaleString('id-ID');
        
        const discountRow = document.getElementById('viewDiscountRow');
        if (hasDiskon) {
            document.getElementById('viewDiskon').textContent = '-Rp ' + diskon.toLocaleString('id-ID');
            discountRow.classList.remove('hidden');
        } else {
            discountRow.classList.add('hidden');
        }

        document.getElementById('viewTotal').textContent = total.toLocaleString('id-ID');

        document.getElementById('viewModal').classList.remove('hidden');
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert(error.message || 'Gagal memuat detail transaksi', 'error');
    });
}

function closeViewModal() {
    document.getElementById('viewModal').classList.add('hidden');
    currentTransaksiData = null;
}

function printNotaFromModal() {
    if (!currentTransaksiData) {
        showAlert('Data transaksi tidak tersedia', 'error');
        return;
    }

    const transaksi = currentTransaksiData;
    
    // Generate kode transaksi
    const date = new Date(transaksi.tanggal_transaksi);
    const kode = `${date.getDate().toString().padStart(2, '0')}${(date.getMonth() + 1).toString().padStart(2, '0')}${date.getFullYear().toString().slice(-2)}-TRA${transaksi.id_transaksi.toString().padStart(3, '0')}`;
    
    const tanggal = new Date(transaksi.tanggal_transaksi).toLocaleDateString('id-ID', { 
        day: '2-digit', 
        month: '2-digit', 
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
    
    const metodeMap = {
        'cash': 'Cash',
        'qris': 'QRIS',
        'transfer': 'Transfer Bank'
    };
    const metode = metodeMap[transaksi.metode_pembayaran] || transaksi.metode_pembayaran;
    const pegawai = transaksi.pegawai?.nama || '-';
    const pelanggan = transaksi.pelanggan?.nama || '-';

    // Calculate subtotal and discount
    let subtotal = 0;
    let produkHTML = '';

    if (transaksi.detail_transaksi && transaksi.detail_transaksi.length > 0) {
        transaksi.detail_transaksi.forEach(detail => {
            subtotal += parseFloat(detail.subtotal);
            const nama = detail.produk?.nama || 'Produk tidak ditemukan';
            const qty = detail.jumlah;
            const harga = (detail.subtotal / detail.jumlah).toLocaleString('id-ID');
            const subtotalItem = parseFloat(detail.subtotal).toLocaleString('id-ID');

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
    }

    const total = parseFloat(transaksi.total);
    const diskon = subtotal - total;
    const hasDiskon = diskon > 0;

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

function showAlert(message, type) {
    const alertDiv = document.createElement('div');
    alertDiv.className = `fixed top-4 right-4 z-50 px-6 py-4 rounded-xl shadow-lg transform transition-all duration-300 flex items-center gap-3 ${
        type === 'success' ? 'bg-green-600' : 'bg-red-600'
    } text-white`;

    const icon = type === 'success'
        ? '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>'
        : '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>';
    
    alertDiv.innerHTML = icon + '<span class="font-medium">' + message + '</span>';
    document.body.appendChild(alertDiv);
    
    setTimeout(() => {
        alertDiv.style.opacity = '0';
        alertDiv.style.transform = 'translateX(100%)';
        setTimeout(() => {
            document.body.removeChild(alertDiv);
        }, 300);
    }, 3000);
}

function showSearchingIndicator() {
    const searchInput = document.getElementById('searchInput');
    searchInput.classList.add('pr-10');
    
    const existingSpinner = searchInput.parentElement.querySelector('.search-spinner');
    if (existingSpinner) {
        existingSpinner.remove();
    }
    
    const spinner = document.createElement('div');
    spinner.className = 'search-spinner absolute right-3 top-1/2 transform -translate-y-1/2';
    spinner.innerHTML = '<div class="animate-spin rounded-full h-4 w-4 border-b-2 border-green-600"></div>';
    searchInput.parentElement.appendChild(spinner);
}

function hideSearchingIndicator() {
    const searchInput = document.getElementById('searchInput');
    searchInput.classList.remove('pr-10');
    
    const spinner = searchInput.parentElement.querySelector('.search-spinner');
    if (spinner) {
        spinner.remove();
    }
}
</script>
@endsection