@extends('layouts.pegawai')

@section('title', 'Tambah Transaksi')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-5xl mx-auto px-6 py-6">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-semibold text-gray-900">Tambah Transaksi</h1>
                <a href="{{ route('pegawai.transaksi.index') }}" class="text-sm text-gray-500 hover:text-gray-700 transition-colors">
                    ← Kembali
                </a>
            </div>
        </div>

        <form id="transaksiForm" class="grid grid-cols-1 lg:grid-cols-3 gap-4">
            @csrf

            <!-- Left Panel - Products & Cart -->
            <div class="lg:col-span-2 space-y-4">
                <!-- Transaction Info -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Pelanggan</label>
                            <select id="id_pelanggan" name="id_pelanggan" required class="w-full px-3 py-2 text-sm border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-blue-500">
                                <option value="">Pilih Pelanggan</option>
                                @foreach($pelanggan as $p)
                                    <option value="{{ $p->id_pelanggan }}">{{ $p->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Pembayaran</label>
                            <select id="metode_pembayaran" name="metode_pembayaran" required class="w-full px-3 py-2 text-sm border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-blue-500" onchange="togglePaymentFields()">
                                <option value="">Pilih Metode</option>
                                <option value="cash">Cash</option>
                                <option value="qris">QRIS</option>
                                <option value="transfer">Transfer</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Product Search -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="mb-3">
                        <input type="text" id="productSearch" placeholder="Cari produk..." class="w-full px-3 py-2 text-sm border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-blue-500" onkeypress="handleSearchKeyPress(event)">
                        <div id="searchResults" class="absolute z-10 w-full bg-white border border-gray-300 rounded mt-1 max-h-60 overflow-y-auto hidden"></div>
                    </div>

                    <!-- Category Buttons -->
                    <div class="flex flex-wrap gap-2">
                        <button type="button" onclick="loadProductsByCategory('all')" class="category-btn px-3 py-1 text-xs bg-blue-600 text-white rounded" data-category="all">Semua</button>
                        @foreach(\App\Models\Kategori::all() as $kategori)
                            <button type="button" onclick="loadProductsByCategory({{ $kategori->id_kategori }})" class="category-btn px-3 py-1 text-xs bg-gray-200 text-gray-700 rounded hover:bg-gray-300" data-category="{{ $kategori->id_kategori }}">{{ $kategori->nama_kategori }}</button>
                        @endforeach
                    </div>
                </div>

                <!-- Product Grid -->
                <div class="bg-white border border-gray-200 rounded-lg p-4">
                    <div class="flex justify-between items-center mb-3">
                        <span class="text-sm font-medium">Produk</span>
                        <span id="productCount" class="text-xs text-gray-500">{{ count($produk) }}</span>
                    </div>
                    <div id="productGrid" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3"></div>
                    <div id="emptyProduct" class="hidden text-center py-8 text-gray-500 text-sm">Tidak ada produk</div>
                </div>

                <!-- Cart -->
                <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
                    <div class="p-4 border-b border-gray-200 bg-gray-50">
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium">Keranjang</span>
                            <span id="cartItemCount" class="px-2 py-1 bg-blue-600 text-white text-xs rounded-full">0</span>
                        </div>
                    </div>
                    <div class="max-h-64 overflow-y-auto">
                        <div id="cartItems" class="p-4">
                            <div class="text-center text-gray-500 py-8 text-sm">Keranjang kosong</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Panel - Summary -->
            <div class="space-y-4">
                <!-- Summary -->
                <div class="bg-white border border-gray-200 rounded-lg p-4">
                    <h3 class="text-sm font-medium mb-3">Ringkasan</h3>

                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Item:</span>
                            <span id="totalItems" class="font-medium">0</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Subtotal:</span>
                            <span id="subtotalHarga" class="font-medium">Rp 0</span>
                        </div>

                        <div class="pt-2 border-t border-gray-200">
                            <label class="block text-xs text-gray-600 mb-1">Diskon (%)</label>
                            <input type="number" id="diskonPersen" min="0" max="100" step="0.1" value="0" class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-blue-500" oninput="calculateTotal()">
                        </div>

                        <div class="flex justify-between text-xs">
                            <span class="text-gray-600">Diskon:</span>
                            <span id="diskonAmount" class="text-red-600">-Rp 0</span>
                        </div>
                    </div>

                    <div class="pt-3 border-t border-gray-200 mt-3">
                        <div class="flex justify-between items-center mb-2">
                            <span class="font-medium">Total:</span>
                            <span id="totalHarga" class="text-lg font-bold text-blue-600">Rp 0</span>
                        </div>

                        <div class="text-xs text-gray-600">
                            Poin: <span id="poinEarned" class="font-medium">0</span>
                        </div>
                    </div>
                </div>

                <!-- Cash Payment -->
                <div id="cashPaymentFields" class="bg-white border border-gray-200 rounded-lg p-4 hidden">
                    <h4 class="text-sm font-medium mb-3">Pembayaran Cash</h4>

                    <div class="space-y-3">
                        <input type="number" id="jumlahDibayar" min="0" step="1000" class="w-full px-3 py-2 text-sm border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-blue-500" placeholder="Jumlah dibayar" oninput="calculateChange()">

                        <div class="grid grid-cols-3 gap-2">
                            <button type="button" onclick="setQuickAmount(50000)" class="px-2 py-1 text-xs bg-gray-100 rounded hover:bg-gray-200">50k</button>
                            <button type="button" onclick="setQuickAmount(100000)" class="px-2 py-1 text-xs bg-gray-100 rounded hover:bg-gray-200">100k</button>
                            <button type="button" onclick="setQuickAmount(200000)" class="px-2 py-1 text-xs bg-gray-100 rounded hover:bg-gray-200">200k</button>
                        </div>

                        <div class="pt-2 border-t border-gray-200">
                            <div class="flex justify-between text-sm mb-1">
                                <span>Total:</span>
                                <span id="totalBayarDisplay">Rp 0</span>
                            </div>
                            <div class="flex justify-between text-sm font-medium">
                                <span>Kembalian:</span>
                                <span id="kembalian" class="text-green-600">Rp 0</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="space-y-2">
                    <button type="button" onclick="clearCart()" class="w-full px-4 py-2 text-sm text-gray-700 bg-gray-100 rounded hover:bg-gray-200">
                        Kosongkan Keranjang
                    </button>
                    <button type="submit" id="submitBtn" class="w-full px-4 py-2 text-sm text-white bg-blue-600 rounded hover:bg-blue-700 disabled:opacity-50">
                        <span id="submitText">Proses Transaksi</span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
let produkList = @json($produk);
let cartItems = [];
let searchTimeout;
let currentCategory = 'all';
let storageUrl = '{{ asset("storage") }}/';

document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('transaksiForm').addEventListener('submit', function(e) {
        e.preventDefault();
        saveTransaksi();
    });

    // Initialize search functionality
    const searchInput = document.getElementById('productSearch');
    searchInput.addEventListener('input', handleSearch);
    searchInput.addEventListener('focus', function() {
        if (this.value.trim().length > 0) {
            handleSearch();
        }
    });
    searchInput.addEventListener('blur', function() {
        setTimeout(() => hideSearchResults(), 200);
    });

    // Load all products initially
    loadProductsByCategory('all');
});

function handleSearchKeyPress(event) {
    if (event.key === 'Enter') {
        event.preventDefault();
        const firstResult = document.querySelector('#searchResults .search-result-item');
        if (firstResult) {
            firstResult.click();
        }
    }
}

function handleSearch() {
    clearTimeout(searchTimeout);
    const query = document.getElementById('productSearch').value.trim();

    if (query.length < 1) {
        hideSearchResults();
        return;
    }

    searchTimeout = setTimeout(() => {
        const filteredProducts = produkList.filter(product =>
            product.nama.toLowerCase().includes(query.toLowerCase())
        );

        showSearchResults(filteredProducts);
    }, 300);
}

function showSearchResults(products) {
    const resultsDiv = document.getElementById('searchResults');

    resultsDiv.innerHTML = '';

    if (products.length === 0) {
        resultsDiv.innerHTML = '<div class="p-4 text-gray-500 text-sm text-center">Tidak ada produk ditemukan</div>';
    } else {
        products.slice(0, 10).forEach(product => {
            const div = document.createElement('div');
            div.className = 'p-4 hover:bg-blue-50 cursor-pointer border-b border-gray-100 search-result-item transition-colors';
            div.onclick = () => addToCart(product);
            div.innerHTML = `
                <div class="flex justify-between items-center">
                    <div class="flex-1">
                        <div class="font-semibold text-sm text-gray-900">${product.nama}</div>
                        <div class="text-xs text-gray-500 mt-1">Stok: <span class="font-medium ${product.stok > 10 ? 'text-green-600' : 'text-orange-600'}">${product.stok}</span></div>
                    </div>
                    <div class="text-right ml-4">
                        <div class="text-base font-bold text-blue-600">Rp ${parseInt(product.harga).toLocaleString('id-ID')}</div>
                    </div>
                </div>
            `;
            resultsDiv.appendChild(div);
        });
    }

    resultsDiv.classList.remove('hidden');
}

function hideSearchResults() {
    document.getElementById('searchResults').classList.add('hidden');
}

function loadProductsByCategory(categoryId) {
    currentCategory = categoryId;
    let filteredProducts = produkList;

    if (categoryId !== 'all') {
        filteredProducts = produkList.filter(product => product.id_kategori == categoryId);
    }

    // Update active category button
    document.querySelectorAll('.category-btn').forEach(btn => {
        if (btn.dataset.category == categoryId) {
            btn.classList.remove('bg-gray-100', 'text-gray-700');
            btn.classList.add('bg-blue-600', 'text-white', 'shadow-sm');
        } else {
            btn.classList.remove('bg-blue-600', 'text-white', 'shadow-sm');
            btn.classList.add('bg-gray-100', 'text-gray-700');
        }
    });

    // Update product count
    document.getElementById('productCount').textContent = `${filteredProducts.length} produk`;

    // Display products
    displayProducts(filteredProducts);
}

function displayProducts(products) {
    const productGrid = document.getElementById('productGrid');
    const emptyProduct = document.getElementById('emptyProduct');

    if (products.length === 0) {
        productGrid.classList.add('hidden');
        emptyProduct.classList.remove('hidden');
        return;
    }

    productGrid.classList.remove('hidden');
    emptyProduct.classList.add('hidden');

    productGrid.innerHTML = products.map(product => {
        const isOutOfStock = product.stok < 1;
        const isLowStock = product.stok > 0 && product.stok <= 5;
        
        return `
            <div class="group relative bg-white border border-gray-200 rounded-xl overflow-hidden hover:shadow-lg transition-all duration-300 ${isOutOfStock ? 'opacity-60' : 'hover:border-blue-300 cursor-pointer'}" 
                 ${!isOutOfStock ? `onclick="addToCart(${JSON.stringify(product).replace(/"/g, '&quot;')})"` : ''}>
                
                <!-- Product Image -->
                <div class="relative aspect-square bg-gray-100 overflow-hidden">
                    ${product.image ?
                        `<img src="${storageUrl}${product.image}" alt="${product.nama}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">` : ''}
                    <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-gray-100 to-gray-200 ${product.image ? 'hidden' : ''}">
                        <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                        </svg>
                    </div>
                    
                    <!-- Stock Badge -->
                    <div class="absolute top-2 right-2">
                        ${isOutOfStock ? 
                            '<span class="px-2 py-1 text-xs font-semibold bg-red-600 text-white rounded-full shadow-lg">Habis</span>' :
                            isLowStock ?
                            `<span class="px-2 py-1 text-xs font-semibold bg-orange-500 text-white rounded-full shadow-lg">Stok: ${product.stok}</span>` :
                            `<span class="px-2 py-1 text-xs font-semibold bg-green-600 text-white rounded-full shadow-lg">Stok: ${product.stok}</span>`
                        }
                    </div>

                    ${!isOutOfStock ?
                        `<!-- Hover Overlay -->
                        <div class="absolute inset-0 bg-transparent group-hover:bg-black group-hover:bg-opacity-20 transition-all duration-300 flex items-center justify-center pointer-events-none group-hover:pointer-events-auto">
                            <div class="transform scale-0 group-hover:scale-100 transition-transform duration-300">
                                <div class="bg-white rounded-full p-3 shadow-xl">
                                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>` : ''
                    }
                </div>

                <!-- Product Info -->
                <div class="p-3">
                    <h4 class="font-semibold text-sm text-gray-900 mb-1 line-clamp-2 group-hover:text-blue-600 transition-colors" title="${product.nama}">
                        ${product.nama}
                    </h4>
                    <div class="flex items-center justify-between mt-2">
                        <div class="text-lg font-bold text-blue-600">
                            Rp ${parseInt(product.harga).toLocaleString('id-ID')}
                        </div>
                        ${!isOutOfStock ? 
                            `<button type="button" onclick="event.stopPropagation(); addToCart(${JSON.stringify(product).replace(/"/g, '&quot;')})" class="p-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors shadow-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                            </button>` :
                            `<span class="text-xs text-red-600 font-medium">Stok Habis</span>`
                        }
                    </div>
                </div>
            </div>
        `;
    }).join('');
}

function addToCart(product) {
    // Parse product if it's a string (from onclick attribute)
    if (typeof product === 'string') {
        product = JSON.parse(product);
    }

    // Check if product already in cart
    const existingItem = cartItems.find(item => item.id_produk == product.id_produk);

    if (existingItem) {
        if (existingItem.jumlah >= product.stok) {
            showAlert('⚠️ Stok tidak mencukupi!', 'error');
            return;
        }
        existingItem.jumlah += 1;
        showAlert(`✓ ${product.nama} (${existingItem.jumlah})`, 'success');
    } else {
        if (product.stok < 1) {
            showAlert('❌ Produk habis stok!', 'error');
            return;
        }
        cartItems.push({
            id_produk: product.id_produk,
            nama: product.nama,
            harga: product.harga,
            stok: product.stok,
            jumlah: 1
        });
        showAlert(`✓ ${product.nama} ditambahkan`, 'success');
    }

    updateCartDisplay();
    calculateTotal();
    
    // Clear search if adding from search results
    if (document.getElementById('productSearch').value) {
        document.getElementById('productSearch').value = '';
        hideSearchResults();
    }

    // Scroll to cart on mobile
    if (window.innerWidth < 1024) {
        document.getElementById('cartItems').scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }
}

function updateCartDisplay() {
    const cartContainer = document.getElementById('cartItems');
    const cartCount = document.getElementById('cartItemCount');

    cartCount.textContent = cartItems.length;

    if (cartItems.length === 0) {
        cartContainer.innerHTML = `
            <div class="text-center text-gray-500 py-12">
                <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                <p class="text-lg font-medium">Keranjang kosong</p>
                <p class="text-sm mt-1">Cari dan tambahkan produk ke keranjang</p>
            </div>
        `;
        return;
    }

    cartContainer.innerHTML = cartItems.map((item, index) => `
        <div class="flex items-center gap-4 p-4 bg-gradient-to-r from-gray-50 to-gray-100 rounded-lg border border-gray-200 hover:shadow-md transition-shadow">
            <div class="flex-1 min-w-0">
                <div class="font-semibold text-sm text-gray-900 truncate">${item.nama}</div>
                <div class="flex items-center gap-2 mt-1">
                    <span class="text-sm text-blue-600 font-semibold">Rp ${parseInt(item.harga).toLocaleString('id-ID')}</span>
                    <span class="text-xs text-gray-500">× ${item.jumlah}</span>
                    <span class="text-xs text-gray-400">•</span>
                    <span class="text-sm font-bold text-gray-900">Rp ${(parseInt(item.harga) * item.jumlah).toLocaleString('id-ID')}</span>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <button type="button" onclick="updateQuantity(${index}, -1)" class="w-8 h-8 bg-white border border-gray-300 rounded-lg flex items-center justify-center hover:bg-gray-50 transition-colors font-semibold text-gray-700">
                    −
                </button>
                <span class="w-10 text-center font-bold text-gray-900">${item.jumlah}</span>
                <button type="button" onclick="updateQuantity(${index}, 1)" class="w-8 h-8 bg-white border border-gray-300 rounded-lg flex items-center justify-center hover:bg-gray-50 transition-colors font-semibold text-gray-700">
                    +
                </button>
                <button type="button" onclick="removeFromCart(${index})" class="w-8 h-8 bg-red-100 text-red-600 rounded-lg flex items-center justify-center hover:bg-red-200 transition-colors ml-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
    `).join('');
}

function updateQuantity(index, change) {
    const item = cartItems[index];
    const newQuantity = item.jumlah + change;

    if (newQuantity < 1) {
        removeFromCart(index);
        return;
    }

    if (newQuantity > item.stok) {
        showAlert('Stok tidak mencukupi!', 'error');
        return;
    }

    item.jumlah = newQuantity;
    updateCartDisplay();
    calculateTotal();
}

function removeFromCart(index) {
    const item = cartItems[index];
    if (confirm(`Hapus ${item.nama} dari keranjang?`)) {
        cartItems.splice(index, 1);
        updateCartDisplay();
        calculateTotal();
        showAlert('Item dihapus dari keranjang', 'info');
    }
}

function clearCart() {
    if (cartItems.length === 0) {
        showAlert('Keranjang sudah kosong', 'info');
        return;
    }

    if (confirm('Yakin ingin mengosongkan keranjang?')) {
        cartItems = [];
        updateCartDisplay();
        calculateTotal();
        showAlert('Keranjang dikosongkan', 'success');
    }
}

function calculateTotal() {
    let subtotal = 0;
    let totalItems = 0;

    cartItems.forEach(item => {
        subtotal += item.harga * item.jumlah;
        totalItems += item.jumlah;
    });

    const diskonPersen = parseFloat(document.getElementById('diskonPersen').value) || 0;
    const diskonAmount = (subtotal * diskonPersen) / 100;
    const total = subtotal - diskonAmount;

    // Calculate points (1 point per 1000 IDR)
    const points = Math.floor(total / 1000);

    document.getElementById('subtotalHarga').textContent = `Rp ${subtotal.toLocaleString('id-ID')}`;
    document.getElementById('totalItems').textContent = totalItems;
    document.getElementById('diskonAmount').textContent = `-Rp ${diskonAmount.toLocaleString('id-ID')}`;
    document.getElementById('totalHarga').textContent = `Rp ${total.toLocaleString('id-ID')}`;
    document.getElementById('poinEarned').textContent = `${points} poin`;

    // Update cash payment display
    document.getElementById('totalBayarDisplay').textContent = `Rp ${total.toLocaleString('id-ID')}`;
    calculateChange();
}

function togglePaymentFields() {
    const method = document.getElementById('metode_pembayaran').value;
    const cashFields = document.getElementById('cashPaymentFields');

    if (method === 'cash') {
        cashFields.classList.remove('hidden');
    } else {
        cashFields.classList.add('hidden');
        document.getElementById('jumlahDibayar').value = '';
        document.getElementById('kembalian').textContent = 'Rp 0';
    }
}

function setQuickAmount(amount) {
    document.getElementById('jumlahDibayar').value = amount;
    calculateChange();
}

function calculateChange() {
    const totalText = document.getElementById('totalHarga').textContent;
    const total = parseInt(totalText.replace(/[^\d]/g, '')) || 0;
    const paid = parseInt(document.getElementById('jumlahDibayar').value) || 0;
    const change = paid - total;

    document.getElementById('kembalian').textContent = `Rp ${Math.max(0, change).toLocaleString('id-ID')}`;
}

function saveTransaksi() {
    if (cartItems.length === 0) {
        showAlert('Keranjang masih kosong!', 'error');
        return;
    }

    const form = document.getElementById('transaksiForm');
    const formData = new FormData(form);

    // Validate required fields
    if (!formData.get('id_pelanggan')) {
        showAlert('Pilih pelanggan terlebih dahulu', 'error');
        return;
    }

    if (!formData.get('metode_pembayaran')) {
        showAlert('Pilih metode pembayaran terlebih dahulu', 'error');
        return;
    }

    // Validate cash payment
    if (formData.get('metode_pembayaran') === 'cash') {
        const totalText = document.getElementById('totalHarga').textContent;
        const total = parseInt(totalText.replace(/[^\d]/g, '')) || 0;
        const paid = parseInt(document.getElementById('jumlahDibayar').value) || 0;

        if (paid < total) {
            showAlert('Jumlah pembayaran kurang dari total!', 'error');
            return;
        }
    }

    // Disable submit button
    const submitBtn = document.getElementById('submitBtn');
    const submitText = document.getElementById('submitText');
    submitBtn.disabled = true;
    submitText.innerHTML = '<svg class="animate-spin h-5 w-5 mr-2" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Memproses...';

    const data = {
        id_pelanggan: formData.get('id_pelanggan'),
        metode_pembayaran: formData.get('metode_pembayaran'),
        produk: cartItems.map(item => ({
            id_produk: item.id_produk,
            jumlah: item.jumlah
        })),
        diskon_persen: parseFloat(document.getElementById('diskonPersen').value) || 0,
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
            showAlert('✓ Transaksi berhasil dibuat!', 'success');

            // Reset form
            cartItems = [];
            updateCartDisplay();
            calculateTotal();
            document.getElementById('transaksiForm').reset();
            document.getElementById('diskonPersen').value = '0';
            document.getElementById('jumlahDibayar').value = '';
            togglePaymentFields();

            // Redirect to detail page
            setTimeout(() => {
                if (data.redirect) {
                    window.location.href = data.redirect;
                } else {
                    window.location.href = '{{ route("pegawai.transaksi.index") }}';
                }
            }, 1500);
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
            const firstError = Object.values(error.errors)[0];
            errorMessage = Array.isArray(firstError) ? firstError[0] : firstError;
        }

        showAlert(errorMessage, 'error');
    })
    .finally(() => {
        submitBtn.disabled = false;
        submitText.innerHTML = '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg><span>Proses Transaksi</span>';
    });
}

function showAlert(message, type) {
    const alertDiv = document.createElement('div');
    alertDiv.className = `fixed top-4 right-4 z-50 px-6 py-4 rounded-xl shadow-2xl transform transition-all duration-300 flex items-center gap-3 ${
        type === 'success' ? 'bg-green-600' : type === 'error' ? 'bg-red-600' : 'bg-blue-600'
    } text-white max-w-md`;

    const icons = {
        success: '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>',
        error: '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>',
        info: '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>'
    };

    alertDiv.innerHTML = `
        <div class="flex-shrink-0">
            ${icons[type] || icons.info}
        </div>
        <span class="font-medium">${message}</span>
    `;
    
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

// Keyboard shortcuts
document.addEventListener('keydown', function(e) {
    // F1 for search focus
    if (e.key === 'F1') {
        e.preventDefault();
        document.getElementById('productSearch').focus();
    }

    // F12 for process transaction
    if (e.key === 'F12') {
        e.preventDefault();
        if (!document.getElementById('submitBtn').disabled) {
            saveTransaksi();
        }
    }

    // Escape to clear search
    if (e.key === 'Escape') {
        document.getElementById('productSearch').value = '';
        hideSearchResults();
    }
});
</script>
@endsection