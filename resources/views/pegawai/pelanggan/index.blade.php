@extends('layouts.pegawai')

@section('title', 'Kelola Pelanggan')

@section('content')
<div class="container mx-auto px-4 py-8 space-y-6">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Kelola Pelanggan</h1>
        <p class="text-gray-500 mt-1">Manajemen data pelanggan perusahaan</p>
    </div>

    <div class="flex flex-col sm:flex-row gap-3 items-stretch sm:items-center">
        <div class="flex-1 relative">
            <svg class="w-5 h-5 absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
            <input type="text" id="searchInput" placeholder="Cari nama, email atau kode referal..."
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
                <thead>
                    <tr class="bg-gradient-to-r from-green-50 to-emerald-50 border-b border-gray-100">
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Nama</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">No. Telp</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Kode Referal</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody id="pelangganTableBody">
                </tbody>
            </table>
        </div>
        <div id="paginationContainer" class="px-6 py-4 bg-gray-50 border-t border-gray-100">
        </div>
    </div>
</div>

<!-- Create/Edit Modal -->
<div id="pelangganModal" class="fixed inset-0 overflow-y-auto h-full w-full hidden z-50" style="background-color: rgba(0, 0, 0, 0.5);">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="relative w-full max-w-lg bg-white rounded-lg shadow-xl">
            <div class="flex justify-between items-center px-5 py-3 border-b border-gray-200">
                <h3 id="modalTitle" class="text-base font-semibold text-gray-900">Tambah Pelanggan</h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <form id="pelangganForm" enctype="multipart/form-data" class="p-5">
                @csrf
                <input type="hidden" id="pelangganId" name="id_pelanggan">

                <div class="space-y-3">
                    <div>
                        <label for="nama" class="block text-xs font-medium text-gray-700 mb-1 flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            Nama Lengkap <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="nama" name="nama" required
                               class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-green-600 focus:border-green-600">
                    </div>

                    <div>
                        <label for="email" class="block text-xs font-medium text-gray-700 mb-1 flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            Email <span class="text-red-500">*</span>
                        </label>
                        <input type="email" id="email" name="email" required
                               class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-green-600 focus:border-green-600">
                    </div>

                    <div>
                        <label for="password" class="block text-xs font-medium text-gray-700 mb-1 flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                            Password
                        </label>
                        <input type="password" id="password" name="password"
                               class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-green-600 focus:border-green-600">
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label for="no_telp" class="block text-xs font-medium text-gray-700 mb-1 flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                </svg>
                                No. Telepon
                            </label>
                            <input type="text" id="no_telp" name="no_telp"
                                   class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-green-600 focus:border-green-600">
                        </div>
                        <div>
                            <label for="kode_referal" class="block text-xs font-medium text-gray-700 mb-1 flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                </svg>
                                Kode Referal <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="kode_referal" name="kode_referal" required
                                   class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-green-600 focus:border-green-600">
                        </div>
                    </div>

                    <div>
                        <label for="image" class="block text-xs font-medium text-gray-700 mb-1 flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            Foto Profil
                        </label>
                        <div class="flex items-center gap-3">
                            <div id="imagePreview" class="flex-shrink-0 hidden">
                                <img id="previewImg" src="" alt="Preview" class="h-16 w-16 rounded-lg object-cover border-2 border-gray-200">
                            </div>
                            <div class="flex-1">
                                <input type="file" id="image" name="image" accept="image/*" onchange="previewImage(event)"
                                       class="w-full px-3 py-1.5 text-xs border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-green-600 focus:border-green-600 file:mr-2 file:py-0.5 file:px-2 file:rounded file:border-0 file:text-xs file:bg-green-50 file:text-green-700 hover:file:bg-green-100">
                            </div>
                        </div>
                    </div>

                    <div>
                        <label for="alamat" class="block text-xs font-medium text-gray-700 mb-1 flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            Alamat
                        </label>
                        <textarea id="alamat" name="alamat" rows="2"
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

<!-- Delete Modal -->
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
                <p class="text-xs text-gray-600 mb-4">Apakah Anda yakin ingin menghapus pelanggan ini?</p>

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
        <div class="relative w-full max-w-lg bg-white rounded-lg shadow-xl">
            <div class="flex justify-between items-center px-5 py-3 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Detail Pelanggan</h3>
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
                        <p id="viewEmail" class="text-gray-600"></p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">No. Telepon</label>
                        <p id="viewNoTelp" class="text-sm text-gray-900">-</p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Kode Referal</label>
                        <p id="viewKodeReferal" class="text-sm text-gray-900">-</p>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-xs font-medium text-gray-700 mb-1">Alamat</label>
                        <p id="viewAlamat" class="text-sm text-gray-900">-</p>
                    </div>
                </div>
            </div>

            <div class="flex justify-end px-5 py-3 border-t border-gray-200">
                <button type="button" onclick="closeViewModal()"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded hover:bg-gray-50">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<script>
let currentPage = 1;
let deleteId = null;

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
        // Jika memang tidak ada data, tampilkan pesan kosong
        renderTable([]);
    }

    // Search on input change with debounce
    let searchTimeout;
    document.getElementById('searchInput').addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(function() {
            searchData();
        }, 300);
    });

    // Form submission
    document.getElementById('pelangganForm').addEventListener('submit', function(e) {
        e.preventDefault();
        clearValidationErrors();
        savePelanggan();
    });
});

function loadPelanggan(page = 1) {
    currentPage = page;
    const search = document.getElementById('searchInput').value;

    // Tampilkan loading indicator
    const tbody = document.getElementById('pelangganTableBody');
    tbody.innerHTML = `
        <tr>
            <td colspan="5" class="px-6 py-8 text-center">
                <div class="flex flex-col items-center">
                    <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-green-600 mb-3"></div>
                    <p class="text-gray-500 text-sm">Memuat data...</p>
                </div>
            </td>
        </tr>
    `;

    fetch(`{{ route('pegawai.pelanggan.index') }}?page=${page}&search=${encodeURIComponent(search)}`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        renderTable(data.data);
        renderPagination(data.pagination);
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
    });
}

function renderTable(pelanggan) {
    const tbody = document.getElementById('pelangganTableBody');
    tbody.innerHTML = '';
    
    if (pelanggan.length === 0) {
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
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                            </svg>
                            <p class="text-gray-500 font-medium">Tidak ada data pelanggan</p>
                            <p class="text-gray-400 text-sm mt-1">Mulai tambahkan pelanggan baru</p>
                        </div>
                    </td>
                </tr>
            `;
        }
        return;
    }

    pelanggan.forEach(item => {
        const row = `
            <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors">
                <td class="px-6 py-3">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-10 w-10 rounded-full overflow-hidden ${item.image ? '' : 'bg-gradient-to-br from-green-400 to-emerald-500 flex items-center justify-center text-white font-semibold'}">
                            ${item.image 
                                ? `<img src="/storage/${item.image}" alt="${item.nama}" class="h-full w-full object-cover" onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\\'h-10 w-10 bg-gradient-to-br from-green-400 to-emerald-500 rounded-full flex items-center justify-center text-white font-semibold\\'>${item.nama.charAt(0).toUpperCase()}</div>'">` 
                                : item.nama.charAt(0).toUpperCase()
                            }
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900">${item.nama}</p>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-3 text-sm text-gray-600">${item.email}</td>
                <td class="px-6 py-3 text-sm text-gray-600">${item.no_telp || '-'}</td>
                <td class="px-6 py-3 text-sm text-gray-600">${item.kode_referal}</td>
                <td class="px-6 py-3">
                    <div class="flex items-center justify-end gap-2">
                        <button onclick="viewPelanggan(${item.id_pelanggan})" class="p-1.5 bg-blue-50 text-blue-600 hover:bg-blue-100 rounded-md transition-all" title="Lihat Detail">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </button>
                        <button onclick="editPelanggan(${item.id_pelanggan})" class="p-1.5 bg-green-50 text-green-600 hover:bg-green-100 rounded-md transition-all" title="Edit">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                        </button>
                        <button onclick="deletePelanggan(${item.id_pelanggan})" class="p-1.5 bg-red-50 text-red-600 hover:bg-red-100 rounded-md transition-all" title="Hapus">
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
            <button onclick="loadPelanggan(${pagination.current_page - 1})" class="p-1.5 text-gray-700 bg-white border border-gray-200 rounded hover:bg-gray-50 transition-all" title="Previous">
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
            html += `<button onclick="loadPelanggan(${i})" class="px-3 py-1.5 text-sm text-gray-700 bg-white border border-gray-200 rounded hover:bg-gray-50 transition-all">${i}</button>`;
        }
    }

    // Next button
    if (pagination.current_page < pagination.last_page) {
        html += `
            <button onclick="loadPelanggan(${pagination.current_page + 1})" class="p-1.5 text-gray-700 bg-white border border-gray-200 rounded hover:bg-gray-50 transition-all" title="Next">
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
    loadPelanggan(1);
}

function openCreateModal() {
    document.getElementById('modalTitle').textContent = 'Tambah Pelanggan';
    document.getElementById('pelangganForm').reset();
    document.getElementById('pelangganId').value = '';
    document.getElementById('password').required = true;
    document.getElementById('kode_referal').value = generateReferralCode();
    document.getElementById('imagePreview').classList.add('hidden');
    clearValidationErrors();
    document.getElementById('pelangganModal').classList.remove('hidden');
}

function generateReferralCode() {
    const letters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    let code = '';
    for (let i = 0; i < 5; i++) {
        code += letters.charAt(Math.floor(Math.random() * letters.length));
    }
    code += Math.floor(Math.random() * 1000).toString().padStart(3, '0');
    return code;
}

function editPelanggan(id) {
    fetch(`{{ route('pegawai.pelanggan.index') }}/${id}`)
    .then(response => response.json())
    .then(data => {
        document.getElementById('modalTitle').textContent = 'Edit Pelanggan';
        document.getElementById('pelangganId').value = data.id_pelanggan;
        document.getElementById('nama').value = data.nama;
        document.getElementById('email').value = data.email;
        document.getElementById('no_telp').value = data.no_telp || '';
        document.getElementById('alamat').value = data.alamat || '';
        document.getElementById('kode_referal').value = data.kode_referal;
        document.getElementById('password').required = false;
        
        // Show existing image preview
        if (data.image) {
            document.getElementById('previewImg').src = '/storage/' + data.image;
            document.getElementById('imagePreview').classList.remove('hidden');
        } else {
            document.getElementById('imagePreview').classList.add('hidden');
        }
        
        clearValidationErrors();
        document.getElementById('pelangganModal').classList.remove('hidden');
    })
    .catch(error => console.error('Error:', error));
}

function savePelanggan() {
    const form = document.getElementById('pelangganForm');
    const formData = new FormData(form);
    const id = document.getElementById('pelangganId').value;

    const url = id ? `{{ route('pegawai.pelanggan.index') }}/${id}` : '{{ route('pegawai.pelanggan.index') }}';
    const method = id ? 'POST' : 'POST';

    if (id) {
        formData.append('_method', 'PUT');
    }

    fetch(url, {
        method: method,
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
        }
    })
    .then(async response => {
        if (response.status === 422) {
            const data = await response.json();
            displayValidationErrors(data.errors);
            throw new Error('Validation failed');
        }
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            closeModal();
            loadPelanggan(currentPage);
            showAlert('Pelanggan berhasil ' + (id ? 'diupdate' : 'ditambahkan'), 'success');
        } else {
            showAlert(data.message || 'Terjadi kesalahan', 'error');
        }
    })
    .catch(error => {
        if (error.message !== 'Validation failed') {
            console.error('Error:', error);
            showAlert('Terjadi kesalahan', 'error');
        }
    });
}

function deletePelanggan(id) {
    deleteId = id;
    document.getElementById('deleteModal').classList.remove('hidden');
}

function confirmDelete() {
    fetch(`{{ route('pegawai.pelanggan.index') }}/${deleteId}`, {
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
            loadPelanggan(currentPage);
            showAlert('Pelanggan berhasil dihapus', 'success');
        } else {
            showAlert('Terjadi kesalahan', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('Terjadi kesalahan', 'error');
    });
}

function closeModal() {
    document.getElementById('pelangganModal').classList.add('hidden');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
    deleteId = null;
}

function closeViewModal() {
    document.getElementById('viewModal').classList.add('hidden');
}

function viewPelanggan(id) {
    fetch(`{{ route('pegawai.pelanggan.index') }}/${id}`)
    .then(response => response.json())
    .then(data => {
        document.getElementById('viewNama').textContent = data.nama;
        document.getElementById('viewEmail').textContent = data.email;
        document.getElementById('viewNoTelp').textContent = data.no_telp || '-';
        document.getElementById('viewKodeReferal').textContent = data.kode_referal;
        document.getElementById('viewAlamat').textContent = data.alamat || '-';

        const viewImageDiv = document.getElementById('viewImage');
        if (data.image) {
            viewImageDiv.innerHTML = `<img src="/storage/${data.image}" alt="${data.nama}" class="h-full w-full object-cover" onerror="this.onerror=null; this.parentElement.innerHTML='${data.nama.charAt(0).toUpperCase()}'">`;
        } else {
            viewImageDiv.innerHTML = data.nama.charAt(0).toUpperCase();
        }

        document.getElementById('viewModal').classList.remove('hidden');
    })
    .catch(error => console.error('Error:', error));
}

function previewImage(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('previewImg').src = e.target.result;
            document.getElementById('imagePreview').classList.remove('hidden');
        };
        reader.readAsDataURL(file);
    }
}

function clearValidationErrors() {
    const form = document.getElementById('pelangganForm');
    form.querySelectorAll('.border-red-500').forEach(el => {
        el.classList.remove('border-red-500', 'focus:border-red-500', 'focus:ring-red-500');
    });
    form.querySelectorAll('p.text-red-600').forEach(el => {
        el.remove();
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
            
            if(field === 'image') {
                input.closest('.flex-1').appendChild(errorEl);
            } else {
                input.parentNode.appendChild(errorEl);
            }
        }
    }
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
</script>
@endsection