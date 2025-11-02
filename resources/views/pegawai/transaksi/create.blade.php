@extends('layouts.pegawai')

@section('title', 'Tambah Transaksi')

@section('content')
<div class="container mx-auto px-4 py-8 space-y-6">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Tambah Transaksi</h1>
        <p class="text-gray-500 mt-1">Buat transaksi baru untuk pelanggan</p>
    </div>

    <form id="transaksiForm" class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 space-y-6">
        @csrf

        <!-- Customer Selection -->
        <div>
            <label for="id_pelanggan" class="block text-xs font-medium text-gray-700 mb-1 flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                Pelanggan <span class="text-red-500">*</span>
            </label>
            <select id="id_pelanggan" name="id_pelanggan" required
                    class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-green-600 focus:border-green-600">
                <option value="">Pilih Pelanggan</option>
                @foreach($pelanggan as $p)
                    <option value="{{ $p->id_pelanggan }}" data-poin="{{ $p->poin }}">
                        {{ $p->nama }} ({{ $p->email }}) - Poin: {{ $p->poin }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Payment Method -->
        <div>
            <label for="metode_pembayaran" class="block text-xs font-medium text-gray-700 mb-1 flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                </svg>
                Metode Pembayaran <span class="text-red-500">*</span>
            </label>
            <select id="metode_pembayaran" name="metode_pembayaran" required
                    class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-green-600 focus:border-green-600">
                <option value="">Pilih Metode Pembayaran</option>
                <option value="cash">Cash</option>
                <option value="qris">QRIS</option>
                <option value="transfer">Transfer Bank</option>
            </select>
        </div>

        <!-- Product Selection -->
        <div>
            <label class="block text-xs font-medium text-gray-700 mb-3 flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                </svg>
                Produk <span class="text-red-500">*</span>
            </label>

            <div id="produkContainer" class="space-y-3">
                <!-- Product rows will be added here -->
            </div>

            <button type="button" onclick="addProductRow()" class="mt-3 px-4 py-2 text-sm bg-green-50 text-green-600 border border-green-200 rounded hover:bg-green-100 transition-all flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Tambah Produk
            </button>
        </div>

        <!-- Summary -->
        <div class="border-t border-gray-200 pt-4 space-y-3">
            <div class="flex justify-between items-center text-sm">
                <span class="text-gray-600">Subtotal:</span>
                <span id="subtotalHarga" class="font-medium text-gray-900">Rp 0</span>
            </div>
            <div class="flex justify-between items-center text-sm">
                <span class="text-gray-600">Total Item:</span>
                <span id="totalItems" class="font-medium text-gray-900">0</span>
            </div>
            <div class="flex justify-between items-center pt-2 border-t border-gray-200">
                <span class="text-lg font-semibold text-gray-900">Total:</span>
                <span id="totalHarga" class="text-lg font-bold text-green-600">Rp 0</span>
            </div>
            <div class="flex justify-between items-center text-sm bg-green-50 p-3 rounded-lg">
                <span class="text-green-700 font-medium">Poin yang didapat:</span>
                <span id="poinEarned" class="font-bold text-green-600">0 poin</span>
            </div>
        </div>

        <!-- Submit Buttons -->
        <div class="flex justify-end gap-2 pt-4 border-t border-gray-200">
            <a href="{{ route('pegawai.transaksi.index') }}" class="px-4 py-1.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded hover:bg-gray-50 transition-all">
                Batal
            </a>
            <button type="submit" class="px-4 py-1.5 text-sm font-medium text-white bg-green-600 rounded hover:bg-green-700 transition-all flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                Simpan Transaksi
            </button>
        </div>
    </form>
</div>

<script>
let produkList = @json($produk);
let productRowCount = 0;

document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('transaksiForm').addEventListener('submit', function(e) {
        e.preventDefault();
        saveTransaksi();
    });

    // Add initial product row
    addProductRow();
});

function addProductRow() {
    productRowCount++;
    const container = document.getElementById('produkContainer');

    const row = document.createElement('div');
    row.className = 'flex items-end gap-3 p-3 bg-gray-50 rounded-lg border border-gray-200';
    row.id = `productRow${productRowCount}`;

    row.innerHTML = `
        <div class="flex-1">
            <label class="block text-xs font-medium text-gray-700 mb-1">Produk</label>
            <select name="produk[${productRowCount}][id_produk]" required
                    class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-green-600 focus:border-green-600 product-select"
                    onchange="updateProductPrice(this, ${productRowCount})">
                <option value="">Pilih Produk</option>
                ${produkList.map(p => `<option value="${p.id_produk}" data-harga="${p.harga}" data-stok="${p.stok}">${p.nama} - Rp ${parseInt(p.harga).toLocaleString('id-ID')} (Stok: ${p.stok})</option>`).join('')}
            </select>
        </div>
        <div class="w-28">
            <label class="block text-xs font-medium text-gray-700 mb-1">Jumlah</label>
            <input type="number" name="produk[${productRowCount}][jumlah]" min="1" value="1" required
                   class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-green-600 focus:border-green-600 quantity-input"
                   onchange="updateProductPrice(this.closest('.flex').querySelector('.product-select'), ${productRowCount}); calculateTotal();">
        </div>
        <div class="w-36">
            <label class="block text-xs font-medium text-gray-700 mb-1">Subtotal</label>
            <input type="text" readonly class="w-full px-3 py-1.5 text-sm bg-gray-100 border border-gray-300 rounded subtotal-display font-semibold text-gray-900" value="Rp 0">
        </div>
        <button type="button" onclick="removeProductRow(${productRowCount})" class="p-2 text-red-600 hover:bg-red-50 rounded transition-all" title="Hapus produk">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
            </svg>
        </button>
    `;

    container.appendChild(row);
    calculateTotal();
}

function removeProductRow(rowId) {
    const row = document.getElementById(`productRow${rowId}`);
    if (row) {
        // Check if this is the last row
        const remainingRows = document.querySelectorAll('[id^="productRow"]').length;
        if (remainingRows <= 1) {
            showAlert('Minimal harus ada 1 produk', 'error');
            return;
        }
        
        row.remove();
        calculateTotal();
    }
}

function updateProductPrice(select, rowId) {
    const selectedOption = select.options[select.selectedIndex];
    const harga = parseFloat(selectedOption.getAttribute('data-harga')) || 0;
    const stok = parseInt(selectedOption.getAttribute('data-stok')) || 0;
    
    const row = select.closest('.flex');
    const quantityInput = row.querySelector('.quantity-input');
    const subtotalDisplay = row.querySelector('.subtotal-display');

    // Set max quantity based on stock
    quantityInput.max = stok;
    
    // Reset quantity to 1 when product changes
    if (quantityInput.value > stok) {
        quantityInput.value = Math.min(1, stok);
    }

    const quantity = parseInt(quantityInput.value) || 0;
    const subtotal = harga * quantity;
    
    subtotalDisplay.value = `Rp ${subtotal.toLocaleString('id-ID')}`;

    // Validate stock
    if (quantity > stok) {
        showAlert(`Stok tidak mencukupi! Stok tersedia: ${stok}`, 'error');
        quantityInput.value = stok;
        updateProductPrice(select, rowId);
        return;
    }

    calculateTotal();
}

function calculateTotal() {
    let total = 0;
    let totalItems = 0;
    
    document.querySelectorAll('.subtotal-display').forEach(display => {
        const value = display.value.replace(/[^\d]/g, '');
        total += parseInt(value) || 0;
    });

    document.querySelectorAll('.quantity-input').forEach(input => {
        totalItems += parseInt(input.value) || 0;
    });

    // Calculate points (1 point per 1000 IDR)
    const points = Math.floor(total / 1000);

    document.getElementById('subtotalHarga').textContent = `Rp ${total.toLocaleString('id-ID')}`;
    document.getElementById('totalHarga').textContent = `Rp ${total.toLocaleString('id-ID')}`;
    document.getElementById('totalItems').textContent = totalItems;
    document.getElementById('poinEarned').textContent = `${points} poin`;
}

function saveTransaksi() {
    const form = document.getElementById('transaksiForm');
    const formData = new FormData(form);

    // Disable submit button
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalBtnText = submitBtn.innerHTML;
    submitBtn.disabled = true;
    submitBtn.innerHTML = `
        <svg class="animate-spin h-4 w-4 mr-2 inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        Menyimpan...
    `;

    // Validate form
    if (!formData.get('id_pelanggan')) {
        showAlert('Pilih pelanggan terlebih dahulu', 'error');
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalBtnText;
        return;
    }

    if (!formData.get('metode_pembayaran')) {
        showAlert('Pilih metode pembayaran terlebih dahulu', 'error');
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalBtnText;
        return;
    }

    // Convert form data to the expected format
    const produkData = [];
    for (let i = 1; i <= productRowCount; i++) {
        const row = document.getElementById(`productRow${i}`);
        if (row) {
            const idProduk = formData.get(`produk[${i}][id_produk]`);
            const jumlah = formData.get(`produk[${i}][jumlah]`);
            if (idProduk && jumlah && parseInt(jumlah) > 0) {
                produkData.push({
                    id_produk: idProduk,
                    jumlah: parseInt(jumlah)
                });
            }
        }
    }

    // Validasi minimal 1 produk
    if (produkData.length === 0) {
        showAlert('Minimal tambahkan 1 produk!', 'error');
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalBtnText;
        return;
    }

    const data = {
        id_pelanggan: formData.get('id_pelanggan'),
        metode_pembayaran: formData.get('metode_pembayaran'),
        produk: produkData,
        _token: formData.get('_token')
    };

    fetch('{{ route("pegawai.transaksi.store") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
        },
        body: JSON.stringify(data)
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(err => Promise.reject(err));
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            // Show success message
            showAlert('Transaksi berhasil dibuat!', 'success');
            
            // Redirect to detail page for printing
            setTimeout(() => {
                if (data.redirect) {
                    window.location.href = data.redirect;
                } else {
                    window.location.href = '{{ route("pegawai.transaksi.index") }}';
                }
            }, 1000);
        } else {
            throw new Error(data.message || 'Terjadi kesalahan');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        
        let errorMessage = 'Terjadi kesalahan saat menyimpan transaksi';
        
        if (error.message) {
            errorMessage = error.message;
        } else if (error.errors) {
            // Handle validation errors
            const firstError = Object.values(error.errors)[0];
            errorMessage = Array.isArray(firstError) ? firstError[0] : firstError;
        }
        
        showAlert(errorMessage, 'error');
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalBtnText;
    });
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
            if (document.body.contains(alertDiv)) {
                document.body.removeChild(alertDiv);
            }
        }, 300);
    }, 3000);
}
</script>
@endsection