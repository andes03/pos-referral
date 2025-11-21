@extends('layouts.pegawai')

@section('title', 'Import Transaksi CSV')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-4xl">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Import Transaksi</h1>
        <p class="text-gray-600 mt-1">Upload file CSV untuk import data transaksi</p>
    </div>

    <!-- Main Card -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <!-- Template Section -->
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <h3 class="text-sm font-semibold text-gray-900 mb-1">Template CSV</h3>
                    <p class="text-sm text-gray-600">Download template untuk format yang benar</p>
                </div>
                <a href="{{ route('pegawai.import.downloadTemplate') }}" class="inline-flex items-center px-4 py-2 bg-gray-900 hover:bg-gray-800 text-white text-sm font-medium rounded-lg transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                    </svg>
                    Download
                </a>
            </div>
        </div>

        <!-- Upload Section -->
        <div class="p-6">
            <form id="uploadForm" enctype="multipart/form-data">
                @csrf
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-900 mb-3">Upload File CSV</label>
                    <div class="relative border-2 border-dashed border-gray-300 rounded-lg p-12 text-center hover:border-gray-400 transition-colors cursor-pointer" id="dropZone">
                        <input type="file" name="csv_file" id="csvFile" accept=".csv" class="hidden" required>
                        
                        <!-- File Info (Hidden by default) -->
                        <div id="fileInfo" class="hidden">
                            <svg class="mx-auto h-10 w-10 text-green-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <p class="text-sm font-medium text-gray-900 mb-1" id="fileName"></p>
                            <p class="text-xs text-gray-500" id="fileSize"></p>
                            <button type="button" onclick="resetForm()" class="mt-3 text-sm text-gray-600 hover:text-gray-900 underline">
                                Ganti file
                            </button>
                        </div>
                        
                        <!-- Drop Zone Content -->
                        <div id="dropZoneContent">
                            <svg class="mx-auto h-10 w-10 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                            </svg>
                            <p class="text-sm text-gray-900 mb-1">
                                <button type="button" onclick="document.getElementById('csvFile').click()" class="text-green-600 hover:text-green-700 font-medium">Pilih file</button>
                                <span class="text-gray-600">atau drag & drop</span>
                            </p>
                            <p class="text-xs text-gray-500">CSV maksimal 10MB</p>
                        </div>
                    </div>
                </div>

                <!-- Progress Bar -->
                <div id="progressContainer" class="hidden mb-6">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-medium text-gray-700">Memproses file...</span>
                        <span class="text-sm text-gray-500" id="progressText">0%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div id="progressBar" class="bg-green-600 h-2 rounded-full transition-all duration-300" style="width: 0%"></div>
                    </div>
                </div>

                <!-- Result Section -->
                <div id="resultContainer" class="hidden mb-6"></div>

                <!-- Action Button -->
                <div class="flex justify-end">
                    <button type="submit" id="uploadBtn" class="inline-flex items-center px-6 py-2.5 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors shadow-sm">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                        </svg>
                        Import Data
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Info Panel -->
    <div class="mt-6 bg-gray-50 rounded-lg p-5 border border-gray-200">
        <h4 class="text-sm font-semibold text-gray-900 mb-3">Panduan Import</h4>
        <ul class="space-y-2 text-sm text-gray-700">
            <li class="flex items-start">
                <svg class="w-4 h-4 text-green-600 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                <span>Email pelanggan dan nama produk harus sudah terdaftar</span>
            </li>
            <li class="flex items-start">
                <svg class="w-4 h-4 text-green-600 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                <span>Format tanggal: YYYY-MM-DD HH:MM:SS (contoh: 2025-11-21 10:30:00)</span>
            </li>
            <li class="flex items-start">
                <svg class="w-4 h-4 text-green-600 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                <span>Metode pembayaran: cash, qris, atau transfer</span>
            </li>
            <li class="flex items-start">
                <svg class="w-4 h-4 text-green-600 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                <span>Pastikan stok produk mencukupi sebelum import</span>
            </li>
        </ul>
    </div>
</div>

<script>
const dropZone = document.getElementById('dropZone');
const fileInput = document.getElementById('csvFile');
const fileInfo = document.getElementById('fileInfo');
const dropZoneContent = document.getElementById('dropZoneContent');
const fileName = document.getElementById('fileName');
const fileSize = document.getElementById('fileSize');
const uploadForm = document.getElementById('uploadForm');
const progressContainer = document.getElementById('progressContainer');
const progressBar = document.getElementById('progressBar');
const progressText = document.getElementById('progressText');
const resultContainer = document.getElementById('resultContainer');

// Drag & Drop handlers
['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
    dropZone.addEventListener(eventName, preventDefaults, false);
});

function preventDefaults(e) {
    e.preventDefault();
    e.stopPropagation();
}

['dragenter', 'dragover'].forEach(eventName => {
    dropZone.addEventListener(eventName, highlight, false);
});

['dragleave', 'drop'].forEach(eventName => {
    dropZone.addEventListener(eventName, unhighlight, false);
});

function highlight(e) {
    dropZone.classList.add('border-green-500', 'bg-green-50');
}

function unhighlight(e) {
    dropZone.classList.remove('border-green-500', 'bg-green-50');
}

dropZone.addEventListener('drop', handleDrop, false);

function handleDrop(e) {
    const dt = e.dataTransfer;
    const files = dt.files;
    
    if (files.length > 0) {
        fileInput.files = files;
        displayFileInfo(files[0]);
    }
}

fileInput.addEventListener('change', function() {
    if (this.files.length > 0) {
        displayFileInfo(this.files[0]);
    }
});

function displayFileInfo(file) {
    fileName.textContent = file.name;
    fileSize.textContent = formatFileSize(file.size);
    dropZoneContent.classList.add('hidden');
    fileInfo.classList.remove('hidden');
}

function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
}

function resetForm() {
    uploadForm.reset();
    dropZoneContent.classList.remove('hidden');
    fileInfo.classList.add('hidden');
    progressContainer.classList.add('hidden');
    resultContainer.classList.add('hidden');
    progressBar.style.width = '0%';
    progressText.textContent = '0%';
}

uploadForm.addEventListener('submit', async function(e) {
    e.preventDefault();
    
    if (!fileInput.files.length) {
        showAlert('Pilih file CSV terlebih dahulu', 'error');
        return;
    }

    const formData = new FormData(uploadForm);
    
    // Show progress
    progressContainer.classList.remove('hidden');
    resultContainer.classList.add('hidden');
    document.getElementById('uploadBtn').disabled = true;
    
    // Simulate progress
    let progress = 0;
    const progressInterval = setInterval(() => {
        progress += 10;
        if (progress <= 90) {
            progressBar.style.width = progress + '%';
            progressText.textContent = progress + '%';
        }
    }, 200);

    try {
        const response = await fetch('{{ route("pegawai.import.import") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: formData
        });

        clearInterval(progressInterval);
        progressBar.style.width = '100%';
        progressText.textContent = '100%';

        const data = await response.json();

        if (data.success) {
            showResult(data, 'success');
            showAlert(data.message, 'success');
            
            // Reset form after 5 seconds only if success
            setTimeout(() => {
                resetForm();
            }, 5000);
        } else {
            showResult(data, 'error');
            showAlert(data.message, 'error');
            // Don't auto-reset on error so user can see the error details
        }

    } catch (error) {
        clearInterval(progressInterval);
        console.error('Error:', error);
        showAlert('Terjadi kesalahan saat mengupload file', 'error');
    } finally {
        document.getElementById('uploadBtn').disabled = false;
    }
});

function showResult(data, type) {
    resultContainer.classList.remove('hidden');
    
    if (type === 'success') {
        let html = `
            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-3 flex-1">
                        <h4 class="text-sm font-semibold text-green-900">${data.message}</h4>
                        <div class="mt-2 text-sm text-green-800">
                            <p>Berhasil: <span class="font-semibold">${data.success_count}</span> transaksi</p>
                            ${data.error_count > 0 ? `<p class="text-red-700">Gagal: <span class="font-semibold">${data.error_count}</span> transaksi</p>` : ''}
                        </div>
        `;
        
        if (data.errors && data.errors.length > 0) {
            html += `
                        <details class="mt-3" open>
                            <summary class="text-sm font-medium text-red-800 cursor-pointer hover:text-red-900 select-none">Lihat detail error (${data.errors.length})</summary>
                            <div class="mt-2 max-h-40 overflow-y-auto bg-white rounded p-3 border border-red-200">
                                <ul class="text-xs text-red-700 space-y-1">
            `;
            data.errors.forEach(error => {
                html += `<li class="break-words">• ${error}</li>`;
            });
            html += `
                                </ul>
                            </div>
                        </details>
            `;
        }
        
        html += `
                        <button onclick="resetForm()" class="mt-3 text-sm text-green-700 hover:text-green-900 font-medium">
                            Upload file baru →
                        </button>
                    </div>
                </div>
            </div>
        `;
        
        resultContainer.innerHTML = html;
    } else {
        let errorDetails = '';
        if (data.errors && Array.isArray(data.errors)) {
            errorDetails = `
                <div class="mt-2 max-h-40 overflow-y-auto bg-white rounded p-3 border border-red-200">
                    <ul class="text-xs text-red-700 space-y-1">
            `;
            data.errors.forEach(error => {
                errorDetails += `<li class="break-words">• ${error}</li>`;
            });
            errorDetails += '</ul></div>';
        } else if (data.errors && typeof data.errors === 'object') {
            errorDetails = `
                <div class="mt-2 max-h-40 overflow-y-auto bg-white rounded p-3 border border-red-200">
                    <ul class="text-xs text-red-700 space-y-1">
            `;
            Object.values(data.errors).forEach(errorArray => {
                if (Array.isArray(errorArray)) {
                    errorArray.forEach(error => {
                        errorDetails += `<li class="break-words">• ${error}</li>`;
                    });
                } else {
                    errorDetails += `<li class="break-words">• ${errorArray}</li>`;
                }
            });
            errorDetails += '</ul></div>';
        }
        
        resultContainer.innerHTML = `
            <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-3 flex-1">
                        <h4 class="text-sm font-semibold text-red-900">Import Gagal</h4>
                        <p class="text-sm text-red-800 mt-1 break-words">${data.message}</p>
                        ${errorDetails}
                        <button onclick="resetForm()" class="mt-3 text-sm text-red-700 hover:text-red-900 font-medium">
                            Coba lagi →
                        </button>
                    </div>
                </div>
            </div>
        `;
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
    
    const closeBtn = '<button onclick="this.parentElement.remove()" class="ml-4 text-white hover:text-gray-200"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>';
    
    alertDiv.innerHTML = icon + '<span class="font-medium flex-1">' + message + '</span>' + closeBtn;
    document.body.appendChild(alertDiv);
    
    // Auto-hide after longer time (10 seconds for errors, 5 seconds for success)
    const hideTime = type === 'error' ? 10000 : 5000;
    
    setTimeout(() => {
        if (alertDiv.parentElement) {
            alertDiv.style.opacity = '0';
            alertDiv.style.transform = 'translateX(100%)';
            setTimeout(() => {
                if (alertDiv.parentElement) {
                    document.body.removeChild(alertDiv);
                }
            }, 300);
        }
    }, hideTime);
}
</script>
@endsection