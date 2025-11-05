@extends('layouts.pegawai')

@section('title', 'Kelola Produk')

@section('content')
<div class="container mx-auto px-4 py-8 space-y-6">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Kelola Produk</h1>
        <p class="text-gray-500 mt-1">Manajemen data produk</p>
    </div>

    <div class="flex flex-col sm:flex-row gap-3 items-stretch sm:items-center">
        <div class="flex-1 relative">
            <svg class="w-5 h-5 absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
            <input type="text" id="searchInput" placeholder="Cari nama produk, deskripsi atau kategori..."
                   class="w-full pl-10 pr-4 py-2.5 bg-white border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-green-600 focus:border-green-600 transition-all">
        </div>
        <button onclick="openCreateModal()" class="bg-green-600 hover:bg-green-700 text-white px-5 py-2.5 rounded-lg flex items-center justify-center gap-2 transition-all shadow-sm hover:shadow-md">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            <span class="font-medium">Tambah</span>
        </button>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="sticky top-0 bg-gradient-to-r from-green-50 to-emerald-50 border-b border-gray-100 z-10">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Produk</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Kategori</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Harga</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Stok</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody id="produkTableBody">
                </tbody>
            </table>
        </div>
        <div id="paginationContainer" class="px-6 py-4 bg-gray-50 border-t border-gray-100">
        </div>
    </div>
</div>

<!-- Create/Edit Modal -->
<div id="produkModal" class="fixed inset-0 overflow-y-auto h-full w-full hidden z-50" style="background-color: rgba(0, 0, 0, 0.5);">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="relative w-full max-w-lg bg-white rounded-lg shadow-xl">
            <div class="flex justify-between items-center px-5 py-3 border-b border-gray-200">
                <h3 id="modalTitle" class="text-base font-semibold text-gray-900">Tambah Produk</h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <form id="produkForm" enctype="multipart/form-data" class="p-5">
                @csrf
                <input type="hidden" id="produkId" name="id_produk">

                <div class="space-y-3">
                    <div>
                        <label for="nama" class="block text-xs font-medium text-gray-700 mb-1 flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                            Nama Produk <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="nama" name="nama" required
                               class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-green-600 focus:border-green-600">
                    </div>

                    <div>
                        <label for="id_kategori" class="block text-xs font-medium text-gray-700 mb-1 flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                            Kategori <span class="text-red-500">*</span>
                        </label>
                        <select id="id_kategori" name="id_kategori" required
                                class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-green-600 focus:border-green-600">
                            <option value="">Pilih Kategori</option>
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label for="harga" class="block text-xs font-medium text-gray-700 mb-1 flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                </svg>
                                Harga <span class="text-red-500">*</span>
                            </label>
                            <input type="number" id="harga" name="harga" min="0" step="0.01" required
                                   class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-green-600 focus:border-green-600">
                        </div>
                        <div>
                            <label for="stok" class="block text-xs font-medium text-gray-700 mb-1 flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                                Stok <span class="text-red-500">*</span>
                            </label>
                            <input type="number" id="stok" name="stok" min="0" required
                                   class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-green-600 focus:border-green-600">
                        </div>
                    </div>

                    <div>
                        <label for="image" class="block text-xs font-medium text-gray-700 mb-1 flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            Foto Produk
                        </label>
                        <input type="file" id="image" name="image" accept="image/*"
                               class="w-full px-3 py-1.5 text-xs border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-green-600 focus:border-green-600 file:mr-2 file:py-0.5 file:px-2 file:rounded file:border-0 file:text-xs file:bg-green-50 file:text-green-700 hover:file:bg-green-100">
                    </div>

                    <div>
                        <label for="deskripsi" class="block text-xs font-medium text-gray-700 mb-1 flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Deskripsi
                        </label>
                        <textarea id="deskripsi" name="deskripsi" rows="2"
                                  class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-green-600 focus:border-green-600 resize-none"></textarea>
                    </div>
                </div>

                <div class="flex justify-end gap-2 mt-5 pt-4 border-t border-gray-200">
                    <button type="button" onclick="closeModal()"
                            class="px-4 py-1.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded hover:bg-gray-50">
                        Batal
                    </button>
                    <button type="submit"
                            class="px-4 py-1.5 text-sm font-medium text-white bg-green-600 rounded hover:bg-green-700">
                        Simpan
                    </button>
                </div>
            </form>
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
                <p class="text-xs text-gray-600 mb-4">Apakah Anda yakin ingin menghapus produk ini?</p>

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

<!-- View Detail Modal -->
<div id="viewModal" class="fixed inset-0 overflow-y-auto h-full w-full hidden z-50" style="background-color: rgba(0, 0, 0, 0.5);">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="relative w-full max-w-lg bg-white rounded-lg shadow-xl">
            <div class="flex justify-between items-center px-5 py-3 border-b border-gray-200">
                <h3 class="text-base font-semibold text-gray-900">Detail Produk</h3>
                <button onclick="closeViewModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <div class="p-5">
                <div class="flex items-center mb-6">
                    <div id="viewImage" class="flex-shrink-0 h-20 w-20 rounded-full overflow-hidden bg-gradient-to-br from-green-400 to-emerald-500 flex items-center justify-center text-white font-bold text-2xl">
                    </div>
                    <div class="ml-4">
                        <h4 id="viewNama" class="text-xl font-semibold text-gray-900"></h4>
                        <p id="viewKategori" class="text-gray-600 text-sm"></p>
                        <span id="viewHarga" class="px-3 py-1 inline-flex text-xs font-semibold rounded-full bg-green-100 text-green-700 mt-1"></span>
                    </div>
                </div>

                <div class="space-y-3">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Stok</label>
                        <p id="viewStok" class="text-sm text-gray-900">-</p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Deskripsi</label>
                        <p id="viewDeskripsi" class="text-sm text-gray-900">-</p>
                    </div>
                </div>
            </div>

            <div class="flex justify-end px-5 py-3 border-t border-gray-200">
                <button type="button" onclick="closeViewModal()"
                        class="px-4 py-1.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded hover:bg-gray-50">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<script>
let currentPage = 1;
let deleteId = null;
let searchTimeout;
let isSearching = false;

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

    // Form submission
    document.getElementById('produkForm').addEventListener('submit', function(e) {
        e.preventDefault();
        saveProduk();
    });
});

function loadProduk(page = 1) {
    if (isSearching) return; // Prevent multiple simultaneous requests
    
    isSearching = true;
    currentPage = page;
    const search = document.getElementById('searchInput').value;

    const tbody = document.getElementById('produkTableBody');
    tbody.innerHTML = `
        <tr class="border-b border-gray-100">
            <td class="px-6 py-3">
                <div class="flex items-center">
                    <div class="flex-shrink-0 h-10 w-10 rounded-full bg-gray-200 animate-pulse"></div>
                    <div class="ml-3">
                        <div class="h-4 bg-gray-200 rounded animate-pulse w-32"></div>
                        <div class="h-3 bg-gray-200 rounded animate-pulse w-24 mt-1"></div>
                    </div>
                </div>
            </td>
            <td class="px-6 py-3">
                <div class="h-4 bg-gray-200 rounded animate-pulse w-full"></div>
            </td>
            <td class="px-6 py-3">
                <div class="h-4 bg-gray-200 rounded animate-pulse w-full"></div>
            </td>
            <td class="px-6 py-3">
                <div class="h-5 bg-gray-200 rounded-full animate-pulse w-12"></div>
            </td>
            <td class="px-6 py-3">
                <div class="flex items-center justify-end gap-2">
                    <div class="h-8 w-8 bg-gray-200 rounded animate-pulse"></div>
                    <div class="h-8 w-8 bg-gray-200 rounded animate-pulse"></div>
                    <div class="h-8 w-8 bg-gray-200 rounded animate-pulse"></div>
                </div>
            </td>
        </tr>
        <tr class="border-b border-gray-100">
            <td class="px-6 py-3">
                <div class="flex items-center">
                    <div class="flex-shrink-0 h-10 w-10 rounded-full bg-gray-200 animate-pulse"></div>
                    <div class="ml-3">
                        <div class="h-4 bg-gray-200 rounded animate-pulse w-28"></div>
                        <div class="h-3 bg-gray-200 rounded animate-pulse w-20 mt-1"></div>
                    </div>
                </div>
            </td>
            <td class="px-6 py-3">
                <div class="h-4 bg-gray-200 rounded animate-pulse w-full"></div>
            </td>
            <td class="px-6 py-3">
                <div class="h-4 bg-gray-200 rounded animate-pulse w-full"></div>
            </td>
            <td class="px-6 py-3">
                <div class="h-5 bg-gray-200 rounded-full animate-pulse w-12"></div>
            </td>
            <td class="px-6 py-3">
                <div class="flex items-center justify-end gap-2">
                    <div class="h-8 w-8 bg-gray-200 rounded animate-pulse"></div>
                    <div class="h-8 w-8 bg-gray-200 rounded animate-pulse"></div>
                    <div class="h-8 w-8 bg-gray-200 rounded animate-pulse"></div>
                </div>
            </td>
        </tr>
        <tr class="border-b border-gray-100">
            <td class="px-6 py-3">
                <div class="flex items-center">
                    <div class="flex-shrink-0 h-10 w-10 rounded-full bg-gray-200 animate-pulse"></div>
                    <div class="ml-3">
                        <div class="h-4 bg-gray-200 rounded animate-pulse w-36"></div>
                        <div class="h-3 bg-gray-200 rounded animate-pulse w-28 mt-1"></div>
                    </div>
                </div>
            </td>
            <td class="px-6 py-3">
                <div class="h-4 bg-gray-200 rounded animate-pulse w-full"></div>
            </td>
            <td class="px-6 py-3">
                <div class="h-4 bg-gray-200 rounded animate-pulse w-full"></div>
            </td>
            <td class="px-6 py-3">
                <div class="h-5 bg-gray-200 rounded-full animate-pulse w-12"></div>
            </td>
            <td class="px-6 py-3">
                <div class="flex items-center justify-end gap-2">
                    <div class="h-8 w-8 bg-gray-200 rounded animate-pulse"></div>
                    <div class="h-8 w-8 bg-gray-200 rounded animate-pulse"></div>
                    <div class="h-8 w-8 bg-gray-200 rounded animate-pulse"></div>
                </div>
            </td>
        </tr>
        <tr class="border-b border-gray-100">
            <td class="px-6 py-3">
                <div class="flex items-center">
                    <div class="flex-shrink-0 h-10 w-10 rounded-full bg-gray-200 animate-pulse"></div>
                    <div class="ml-3">
                        <div class="h-4 bg-gray-200 rounded animate-pulse w-24"></div>
                        <div class="h-3 bg-gray-200 rounded animate-pulse w-16 mt-1"></div>
                    </div>
                </div>
            </td>
            <td class="px-6 py-3">
                <div class="h-4 bg-gray-200 rounded animate-pulse w-full"></div>
            </td>
            <td class="px-6 py-3">
                <div class="h-4 bg-gray-200 rounded animate-pulse w-full"></div>
            </td>
            <td class="px-6 py-3">
                <div class="h-5 bg-gray-200 rounded-full animate-pulse w-12"></div>
            </td>
            <td class="px-6 py-3">
                <div class="flex items-center justify-end gap-2">
                    <div class="h-8 w-8 bg-gray-200 rounded animate-pulse"></div>
                    <div class="h-8 w-8 bg-gray-200 rounded animate-pulse"></div>
                    <div class="h-8 w-8 bg-gray-200 rounded animate-pulse"></div>
                </div>
            </td>
        </tr>
        <tr class="border-b border-gray-100">
            <td class="px-6 py-3">
                <div class="flex items-center">
                    <div class="flex-shrink-0 h-10 w-10 rounded-full bg-gray-200 animate-pulse"></div>
                    <div class="ml-3">
                        <div class="h-4 bg-gray-200 rounded animate-pulse w-32"></div>
                        <div class="h-3 bg-gray-200 rounded animate-pulse w-24 mt-1"></div>
                    </div>
                </div>
            </td>
            <td class="px-6 py-3">
                <div class="h-4 bg-gray-200 rounded animate-pulse w-full"></div>
            </td>
            <td class="px-6 py-3">
                <div class="h-4 bg-gray-200 rounded animate-pulse w-full"></div>
            </td>
            <td class="px-6 py-3">
                <div class="h-5 bg-gray-200 rounded-full animate-pulse w-12"></div>
            </td>
            <td class="px-6 py-3">
                <div class="flex items-center justify-end gap-2">
                    <div class="h-8 w-8 bg-gray-200 rounded animate-pulse"></div>
                    <div class="h-8 w-8 bg-gray-200 rounded animate-pulse"></div>
                    <div class="h-8 w-8 bg-gray-200 rounded animate-pulse"></div>
                </div>
            </td>
        </tr>
    `;

    fetch(`{{ route('pegawai.produk.index') }}?page=${page}&search=${encodeURIComponent(search)}`, {
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
                <td colspan="5" class="px-6 py-8 text-center">
                    <p class="text-red-500">Gagal memuat data. Silakan refresh halaman.</p>
                </td>
            </tr>
        `;
        isSearching = false;
        hideSearchingIndicator();
    });
}

function renderTable(produk) {
    const tbody = document.getElementById('produkTableBody');
    tbody.innerHTML = '';

    if (produk.length === 0) {
        if (document.getElementById('searchInput').value.trim() !== '') {
            tbody.innerHTML = `
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center">
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
                    <td colspan="5" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center">
                            <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                            <p class="text-gray-500 font-medium">Tidak ada data produk</p>
                            <p class="text-gray-400 text-sm mt-1">Mulai tambahkan produk baru</p>
                        </div>
                    </td>
                </tr>
            `;
        }
        return;
    }

    produk.forEach(item => {
        const row = `
            <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors">
                <td class="px-6 py-3">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-10 w-10 rounded-full overflow-hidden ${item.image ? '' : 'bg-gradient-to-br from-green-400 to-emerald-500 flex items-center justify-center text-white font-semibold'}">
                            ${item.image
                                ? `<img src="/storage/${item.image}" alt="${item.nama}" class="h-full w-full object-cover">`
                                : item.nama.charAt(0).toUpperCase()
                            }
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900">${item.nama}</p>
                            <p class="text-xs text-gray-500">${item.deskripsi ? (item.deskripsi.length > 30 ? item.deskripsi.substring(0, 30) + '...' : item.deskripsi) : '-'}</p>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-3 text-sm text-gray-600">${item.kategori ? item.kategori.nama_kategori : '-'}</td>
                <td class="px-6 py-3 text-sm text-gray-600">Rp ${parseInt(item.harga).toLocaleString('id-ID')}</td>
                <td class="px-6 py-3">
                    <span class="px-3 py-1 inline-flex text-xs font-semibold rounded-full ${item.stok > 10 ? 'bg-green-100 text-green-700' : item.stok > 0 ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700'}">
                        ${item.stok}
                    </span>
                </td>
                <td class="px-6 py-3">
                    <div class="flex items-center justify-end gap-2">
                        <button onclick="viewProduk(${item.id_produk})" class="p-1.5 bg-blue-50 text-blue-600 hover:bg-blue-100 rounded-md transition-all" title="Lihat Detail">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </button>
                        <button onclick="editProduk(${item.id_produk})" class="p-1.5 bg-green-50 text-green-600 hover:bg-green-100 rounded-md transition-all" title="Edit">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                        </button>
                        <button onclick="deleteProduk(${item.id_produk})" class="p-1.5 bg-red-50 text-red-600 hover:bg-red-100 rounded-md transition-all" title="Hapus">
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
            <button onclick="loadProduk(${pagination.current_page - 1})" class="p-1.5 text-gray-700 bg-white border border-gray-200 rounded hover:bg-gray-50 transition-all" title="Previous">
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
            html += `<button onclick="loadProduk(${i})" class="px-3 py-1.5 text-sm text-gray-700 bg-white border border-gray-200 rounded hover:bg-gray-50 transition-all">${i}</button>`;
        }
    }

    // Next button
    if (pagination.current_page < pagination.last_page) {
        html += `
            <button onclick="loadProduk(${pagination.current_page + 1})" class="p-1.5 text-gray-700 bg-white border border-gray-200 rounded hover:bg-gray-50 transition-all" title="Next">
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
    loadProduk(1);
}

function openCreateModal() {
    document.getElementById('modalTitle').textContent = 'Tambah Produk';
    document.getElementById('produkForm').reset();
    document.getElementById('produkId').value = '';
    clearValidationErrors();
    loadKategoriOptions();
    document.getElementById('produkModal').classList.remove('hidden');
}

function loadKategoriOptions() {
    return fetch('{{ route('pegawai.kategori.index') }}', {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        const select = document.getElementById('id_kategori');
        select.innerHTML = '<option value="">Pilih Kategori</option>';
        data.data.forEach(kategori => {
            select.innerHTML += `<option value="${kategori.id_kategori}">${kategori.nama_kategori}</option>`;
        });
    })
    .catch(error => {
        console.error('Error loading kategori:', error);
    });
}

function editProduk(id) {
    fetch(`{{ route('pegawai.produk.index') }}/${id}`)
    .then(response => response.json())
    .then(data => {
        document.getElementById('modalTitle').textContent = 'Edit Produk';
        document.getElementById('produkId').value = data.id_produk;
        document.getElementById('nama').value = data.nama;
        document.getElementById('harga').value = data.harga;
        document.getElementById('stok').value = data.stok;
        document.getElementById('deskripsi').value = data.deskripsi || '';
        
        loadKategoriOptions().then(() => {
            document.getElementById('id_kategori').value = data.id_kategori;
        });

        clearValidationErrors();
        document.getElementById('produkModal').classList.remove('hidden');
    })
    .catch(error => console.error('Error:', error));
}



function saveProduk() {
    const form = document.getElementById('produkForm');
    const formData = new FormData(form);
    const id = document.getElementById('produkId').value;
    const url = id ? `{{ route('pegawai.produk.index') }}/${id}` : '{{ route('pegawai.produk.index') }}';

    if (id) {
        formData.append('_method', 'PUT');
    }

    clearValidationErrors();

    fetch(url, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            closeModal();
            loadProduk(currentPage);
            showAlert('Produk berhasil ' + (id ? 'diupdate' : 'ditambahkan'), 'success');
        } else {
            if (data.errors) {
                displayValidationErrors(data.errors);
            } else {
                showAlert('Terjadi kesalahan', 'error');
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('Terjadi kesalahan', 'error');
    });
}

function displayValidationErrors(errors) {
    clearValidationErrors();
    for (const field in errors) {
        const input = document.getElementById(field);
        if (input) {
            input.classList.add('border-red-500', 'focus:border-red-500', 'focus:ring-red-500');
            const errorEl = document.createElement('p');
            errorEl.className = 'text-xs text-red-600 mt-1';
            errorEl.textContent = errors[field][0];
            input.parentNode.appendChild(errorEl);
        }
    }
}

function clearValidationErrors() {
    const form = document.getElementById('produkForm');
    form.querySelectorAll('.border-red-500').forEach(el => {
        el.classList.remove('border-red-500', 'focus:border-red-500', 'focus:ring-red-500');
    });
    form.querySelectorAll('p.text-red-600').forEach(el => {
        el.remove();
    });
}

function deleteProduk(id) {
    deleteId = id;
    document.getElementById('deleteModal').classList.remove('hidden');
}

function confirmDelete() {
    fetch(`{{ route('pegawai.produk.index') }}/${deleteId}`, {
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
            loadProduk(currentPage);
            showAlert('Produk berhasil dihapus', 'success');
        } else {
            showAlert('Terjadi kesalahan', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('Terjadi kesalahan', 'error');
    });
}

function viewProduk(id) {
    fetch(`{{ route('pegawai.produk.index') }}/${id}`)
    .then(response => response.json())
    .then(data => {
        document.getElementById('viewNama').textContent = data.nama;
        document.getElementById('viewKategori').textContent = data.kategori ? data.kategori.nama_kategori : '-';
        document.getElementById('viewHarga').textContent = 'Rp ' + parseInt(data.harga).toLocaleString('id-ID');
        document.getElementById('viewStok').textContent = data.stok;
        document.getElementById('viewDeskripsi').textContent = data.deskripsi || '-';

        const viewImageDiv = document.getElementById('viewImage');
        if (data.image) {
            viewImageDiv.innerHTML = `<img src="/storage/${data.image}" alt="${data.nama}" class="h-full w-full object-cover">`;
        } else {
            viewImageDiv.innerHTML = data.nama.charAt(0).toUpperCase();
            viewImageDiv.className = 'flex-shrink-0 h-20 w-20 rounded-full overflow-hidden bg-gradient-to-br from-green-400 to-emerald-500 flex items-center justify-center text-white font-bold text-2xl';
        }

        document.getElementById('viewModal').classList.remove('hidden');
    })
    .catch(error => console.error('Error:', error));
}

function closeModal() {
    document.getElementById('produkModal').classList.add('hidden');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
    deleteId = null;
}

function closeViewModal() {
    document.getElementById('viewModal').classList.add('hidden');
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