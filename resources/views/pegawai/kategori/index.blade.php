@extends('layouts.pegawai')

@section('title', 'Kelola Kategori')

@section('content')
<div class="container mx-auto px-4 py-8 space-y-6">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Kelola Kategori</h1>
        <p class="text-gray-500 mt-1">Manajemen data kategori produk perusahaan</p>
    </div>

    <div class="flex flex-col sm:flex-row gap-3 items-stretch sm:items-center">
        <div class="flex-1 relative">
            <svg class="w-5 h-5 absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
            <input type="text" id="searchInput" placeholder="Cari nama kategori atau deskripsi..."
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
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Nama Kategori</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Deskripsi</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody id="kategoriTableBody">
                </tbody>
            </table>
        </div>
        <div id="paginationContainer" class="px-6 py-4 bg-gray-50 border-t border-gray-100">
            </div>
    </div>
</div>

<!-- Create/Edit Modal -->
<div id="kategoriModal" class="fixed inset-0 overflow-y-auto h-full w-full hidden z-50" style="background-color: rgba(0, 0, 0, 0.5);">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="relative w-full max-w-lg bg-white rounded-lg shadow-xl">
            <div class="flex justify-between items-center px-5 py-3 border-b border-gray-200">
                <h3 id="modalTitle" class="text-base font-semibold text-gray-900">Tambah Kategori</h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <form id="kategoriForm" class="p-5">
                @csrf
                <input type="hidden" id="kategoriId" name="id_kategori">

                <div class="space-y-3">
                    <div>
                        <label for="nama_kategori" class="block text-xs font-medium text-gray-700 mb-1 flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                            Nama Kategori <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="nama_kategori" name="nama_kategori" required
                               class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-green-600 focus:border-green-600">
                    </div>

                    <div>
                        <label for="deskripsi" class="block text-xs font-medium text-gray-700 mb-1 flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Deskripsi
                        </label>
                        <textarea id="deskripsi" name="deskripsi" rows="3"
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
                <p class="text-xs text-gray-600 mb-4">Apakah Anda yakin ingin menghapus kategori ini?</p>

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



<script>
let currentPage = 1;
let deleteId = null;

document.addEventListener('DOMContentLoaded', function() {
    loadKategori();

    // Search on input change
    document.getElementById('searchInput').addEventListener('input', function() {
        searchData();
    });

    // Form submission
    document.getElementById('kategoriForm').addEventListener('submit', function(e) {
        e.preventDefault();
        saveKategori();
    });
});

function loadKategori(page = 1) {
    currentPage = page;
    const search = document.getElementById('searchInput').value;

    fetch(`{{ route('pegawai.kategori.index') }}?page=${page}&search=${encodeURIComponent(search)}`, {
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
    .catch(error => console.error('Error:', error));
}

function renderTable(kategori) {
    const tbody = document.getElementById('kategoriTableBody');
    tbody.innerHTML = '';

    if (kategori.length === 0) {
        if (document.getElementById('searchInput').value.trim() !== '') {
            // Show search no results message
            tbody.innerHTML = `
                <tr>
                    <td colspan="3" class="px-6 py-12 text-center">
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
            // Show no data message
            tbody.innerHTML = `
                <tr>
                    <td colspan="3" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center">
                            <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                            <p class="text-gray-500 font-medium">Tidak ada data kategori</p>
                            <p class="text-gray-400 text-sm mt-1">Mulai tambahkan kategori baru</p>
                        </div>
                    </td>
                </tr>
            `;
        }
        return;
    }

    kategori.forEach(item => {
        const row = `
            <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors">
                <td class="px-6 py-3">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-10 w-10 rounded-full overflow-hidden bg-gradient-to-br from-green-400 to-emerald-500 flex items-center justify-center text-white font-semibold">
                            ${item.nama_kategori.charAt(0).toUpperCase()}
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900">${item.nama_kategori}</p>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-3 text-sm text-gray-600">${item.deskripsi || '-'}</td>
                <td class="px-6 py-3">
                    <div class="flex items-center justify-end gap-2">
                        <button onclick="editKategori(${item.id_kategori})" class="p-1.5 bg-green-50 text-green-600 hover:bg-green-100 rounded-md transition-all" title="Edit">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                        </button>
                        <button onclick="deleteKategori(${item.id_kategori})" class="p-1.5 bg-red-50 text-red-600 hover:bg-red-100 rounded-md transition-all" title="Hapus">
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

    // Previous button - always visible
    if (pagination.current_page > 1) {
        html += `
            <button onclick="loadKategori(${pagination.current_page - 1})" class="p-1.5 text-gray-700 bg-white border border-gray-200 rounded hover:bg-gray-50 transition-all" title="Previous">
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
            html += `<button onclick="loadKategori(${i})" class="px-3 py-1.5 text-sm text-gray-700 bg-white border border-gray-200 rounded hover:bg-gray-50 transition-all">${i}</button>`;
        }
    }

    // Next button - always visible
    if (pagination.current_page < pagination.last_page) {
        html += `
            <button onclick="loadKategori(${pagination.current_page + 1})" class="p-1.5 text-gray-700 bg-white border border-gray-200 rounded hover:bg-gray-50 transition-all" title="Next">
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
    loadKategori(1);
}

function openCreateModal() {
    document.getElementById('modalTitle').textContent = 'Tambah Kategori';
    document.getElementById('kategoriForm').reset();
    document.getElementById('kategoriId').value = '';
    clearValidationErrors();
    document.getElementById('kategoriModal').classList.remove('hidden');
}

function editKategori(id) {
    fetch(`{{ route('pegawai.kategori.index') }}/${id}`)
    .then(response => response.json())
    .then(data => {
        document.getElementById('modalTitle').textContent = 'Edit Kategori';
        document.getElementById('kategoriId').value = data.id_kategori;
        document.getElementById('nama_kategori').value = data.nama_kategori;
        document.getElementById('deskripsi').value = data.deskripsi || '';
        clearValidationErrors();
        document.getElementById('kategoriModal').classList.remove('hidden');
    })
    .catch(error => console.error('Error:', error));
}

function saveKategori() {
    const form = document.getElementById('kategoriForm');
    const formData = new FormData(form);
    const id = document.getElementById('kategoriId').value;

    const url = id ? `{{ route('pegawai.kategori.index') }}/${id}` : '{{ route('pegawai.kategori.index') }}';
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
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            closeModal();
            loadKategori(currentPage);
            showAlert('Kategori berhasil ' + (id ? 'diupdate' : 'ditambahkan'), 'success');
        } else {
            // Handle validation errors
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

/**
 * Menampilkan pesan error validasi pada form.
 */
function displayValidationErrors(errors) {
    // Hapus error sebelumnya
    clearValidationErrors();

    // Tampilkan error baru
    for (const field in errors) {
        const input = document.getElementById(field);
        if (input) {
            // Tambahkan style error ke input
            input.classList.add('border-red-500', 'focus:border-red-500', 'focus:ring-red-500');

            // Buat dan tampilkan elemen pesan error
            const errorEl = document.createElement('p');
            errorEl.className = 'text-xs text-red-600 mt-1';
            errorEl.textContent = errors[field][0]; // Ambil pesan error pertama

            // Sisipkan setelah input
            input.parentNode.appendChild(errorEl);
        }
    }
}

/**
 * Menghapus semua pesan dan style error validasi dari form.
 */
function clearValidationErrors() {
    const form = document.getElementById('kategoriForm');

    // Hapus style error dari semua input
    form.querySelectorAll('.border-red-500').forEach(el => {
        el.classList.remove('border-red-500', 'focus:border-red-500', 'focus:ring-red-500');
    });

    // Hapus semua elemen pesan error
    form.querySelectorAll('p.text-red-600').forEach(el => {
        el.remove();
    });
}

function deleteKategori(id) {
    deleteId = id;
    document.getElementById('deleteModal').classList.remove('hidden');
}

function confirmDelete() {
    fetch(`{{ route('pegawai.kategori.index') }}/${deleteId}`, {
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
            loadKategori(currentPage);
            showAlert('Kategori berhasil dihapus', 'success');
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
    document.getElementById('kategoriModal').classList.add('hidden');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
    deleteId = null;
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
