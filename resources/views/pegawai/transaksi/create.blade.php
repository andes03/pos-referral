@extends('layouts.pegawai')

@section('title', 'Tambah Transaksi')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">Tambah Transaksi</h1>
                    <p class="text-gray-500 mt-1">Kelola transaksi penjualan dengan mudah</p>
                </div>
                <a href="{{ route('pegawai.transaksi.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition-all shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Kembali
                </a>
            </div>
        </div>

        <form id="transaksiForm" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            @csrf

            <!-- Left Panel - Products & Cart -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Transaction Info Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-base font-semibold text-gray-800 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        Informasi Transaksi
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Pelanggan *</label>
                            <select id="id_pelanggan" name="id_pelanggan" required class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-green-600 focus:border-green-600 bg-white transition-all" onchange="resetReferral()">
                                <option value="">Pilih Pelanggan</option>
                                @foreach($pelanggan as $p)
                                    <option value="{{ $p->id_pelanggan }}">{{ $p->nama }} - {{ $p->email }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Metode Pembayaran *</label>
                            <select id="metode_pembayaran" name="metode_pembayaran" required class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-green-600 focus:border-green-600 bg-white transition-all" onchange="togglePaymentFields()">
                                <option value="">Pilih Metode</option>
                                <option value="cash">Cash</option>
                                <option value="qris">QRIS</option>
                                <option value="transfer">Transfer Bank</option>
                            </select>
                        </div>
                    </div>

                    <!-- Referral Code Section -->
                    <div class="mt-5 pt-5 border-t border-gray-100">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <span class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                </svg>
                                Kode Referral (Opsional)
                            </span>
                        </label>
                        <div class="relative">
                            <input type="text" id="kode_referal" name="kode_referal" placeholder="Masukkan kode untuk diskon 10%" class="w-full px-4 py-2.5 pr-12 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-green-600 focus:border-green-600 transition-all" oninput="handleReferralInput()">
                            <div id="referralLoader" class="hidden absolute right-4 top-1/2 transform -translate-y-1/2">
                                <svg class="animate-spin h-5 w-5 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </div>
                        </div>
                        <div id="referralStatus" class="mt-2.5 text-sm hidden"></div>
                    </div>
                </div>

                <!-- Product Search & Filter Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-base font-semibold text-gray-800 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        Cari Produk
                    </h3>

                    <div class="mb-4 relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <input type="text" id="productSearch" placeholder="Ketik nama produk atau scan barcode..." class="w-full pl-12 pr-4 py-3 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-green-600 focus:border-green-600 transition-all" onkeypress="handleSearchKeyPress(event)">
                        <div id="searchResults" class="absolute z-20 w-full bg-white border border-gray-200 rounded-lg mt-2 shadow-xl max-h-80 overflow-y-auto hidden"></div>
                    </div>

                    <!-- Category Buttons -->
                    <div class="flex flex-wrap gap-2">
                        <button type="button" onclick="loadProductsByCategory('all')" class="category-btn px-4 py-2 text-sm font-medium bg-green-600 text-white rounded-lg hover:bg-green-700 transition-all shadow-sm" data-category="all">
                            Semua Kategori
                        </button>
                        @foreach(\App\Models\Kategori::all() as $kategori)
                            <button type="button" onclick="loadProductsByCategory({{ $kategori->id_kategori }})" class="category-btn px-4 py-2 text-sm font-medium bg-white text-gray-700 border border-gray-200 rounded-lg hover:bg-gray-50 hover:border-gray-300 transition-all" data-category="{{ $kategori->id_kategori }}">
                                {{ $kategori->nama_kategori }}
                            </button>
                        @endforeach
                    </div>
                </div>

                <!-- Product Grid -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <div class="flex justify-between items-center mb-5">
                        <h3 class="text-base font-semibold text-gray-800">Daftar Produk</h3>
                        <span id="productCount" class="px-3 py-1 bg-green-50 text-green-700 text-sm font-medium rounded-full">{{ count($produk) }} produk</span>
                    </div>
                    <div id="productGrid" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4"></div>
                    <div id="emptyProduct" class="hidden text-center py-16">
                        <svg class="w-20 h-20 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                        </svg>
                        <p class="text-base font-medium text-gray-600">Tidak ada produk</p>
                        <p class="text-sm text-gray-500 mt-1">Coba pilih kategori lain</p>
                    </div>
                </div>

                <!-- Cart -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-6 border-b border-gray-100 bg-gradient-to-r from-green-50 to-emerald-50">
                        <div class="flex justify-between items-center">
                            <h3 class="text-base font-semibold text-gray-800 flex items-center gap-2">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                                Keranjang Belanja
                            </h3>
                            <span id="cartItemCount" class="px-3 py-1.5 bg-green-600 text-white text-sm font-bold rounded-full shadow-sm">0</span>
                        </div>
                    </div>
                    <div class="max-h-96 overflow-y-auto">
                        <div id="cartItems" class="p-6">
                            <div class="text-center text-gray-500 py-16">
                                <svg class="w-20 h-20 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                                <p class="text-base font-medium">Keranjang masih kosong</p>
                                <p class="text-sm text-gray-400 mt-1">Mulai tambahkan produk ke keranjang</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Panel - Summary -->
            <div class="space-y-6">
                <!-- Summary Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 sticky top-6">
                    <h3 class="text-base font-semibold text-gray-800 mb-5 flex items-center gap-2">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                        Ringkasan Belanja
                    </h3>

                    <div class="space-y-3 text-sm mb-5">
                        <div class="flex justify-between items-center py-2">
                            <span class="text-gray-600">Total Item</span>
                            <span id="totalItems" class="font-semibold text-gray-900">0</span>
                        </div>
                        <div class="flex justify-between items-center py-2">
                            <span class="text-gray-600">Subtotal</span>
                            <span id="subtotalHarga" class="font-semibold text-gray-900">Rp 0</span>
                        </div>

                        <!-- Discount Info -->
                        <div id="discountInfo" class="hidden pt-3 border-t border-gray-100">
                            <div class="flex items-center justify-between py-2 bg-green-50 rounded-lg px-3 mb-2">
                                <span class="text-green-700 flex items-center gap-2 font-medium">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Diskon Referral
                                </span>
                                <span class="font-bold text-green-700">10%</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Potongan Harga</span>
                                <span id="diskonAmount" class="text-red-600 font-semibold">-Rp 0</span>
                            </div>
                        </div>
                    </div>

                    <div class="pt-5 border-t border-gray-100">
                        <div class="flex justify-between items-center mb-4">
                            <span class="text-base font-semibold text-gray-900">Total Pembayaran</span>
                            <span id="totalHarga" class="text-2xl font-bold text-green-600">Rp 0</span>
                        </div>

                        <div class="bg-green-50 rounded-lg p-3 flex items-center gap-2">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                            </svg>
                            <div class="flex-1">
                                <div class="text-xs text-green-700 font-medium">Poin Didapat</div>
                                <div id="poinEarned" class="text-sm font-bold text-green-900">0 poin</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Cash Payment Card -->
                <div id="cashPaymentFields" class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hidden">
                    <h4 class="text-base font-semibold text-gray-800 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        Pembayaran Cash
                    </h4>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah Dibayar</label>
                            <input type="number" id="jumlahDibayar" min="0" step="1000" class="w-full px-4 py-3 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-green-600 focus:border-green-600 transition-all" placeholder="Masukkan nominal" oninput="calculateChange()">
                        </div>

                        <div class="grid grid-cols-3 gap-2">
                            <button type="button" onclick="setQuickAmount(50000)" class="px-3 py-2.5 text-sm font-medium bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-all">50k</button>
                            <button type="button" onclick="setQuickAmount(100000)" class="px-3 py-2.5 text-sm font-medium bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-all">100k</button>
                            <button type="button" onclick="setQuickAmount(200000)" class="px-3 py-2.5 text-sm font-medium bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-all">200k</button>
                        </div>

                        <div class="pt-4 border-t border-gray-100 space-y-2">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Total Tagihan</span>
                                <span id="totalBayarDisplay" class="font-semibold text-gray-900">Rp 0</span>
                            </div>
                            <div class="flex justify-between items-center bg-green-50 rounded-lg px-4 py-3">
                                <span class="text-sm font-medium text-green-700">Kembalian</span>
                                <span id="kembalian" class="text-lg font-bold text-green-700">Rp 0</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="space-y-3">
                    <button type="button" onclick="clearCart()" class="w-full px-4 py-3 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 hover:border-gray-400 transition-all flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        Kosongkan Keranjang
                    </button>
                    <button type="submit" id="submitBtn" class="w-full px-4 py-3.5 text-sm font-semibold text-white bg-green-600 rounded-lg hover:bg-green-700 disabled:opacity-50 disabled:cursor-not-allowed transition-all shadow-sm hover:shadow-md flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
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
let referralTimeout;
let currentCategory = 'all';
let storageUrl = '{{ asset("storage") }}/';
let referralVerified = false;
let referralDiscount = 0;

document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('transaksiForm').addEventListener('submit', function(e) {
        e.preventDefault();
        saveTransaksi();
    });

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

    loadProductsByCategory('all');
});

function resetReferral() {
    document.getElementById('kode_referal').value = '';
    referralVerified = false;
    referralDiscount = 0;
    document.getElementById('referralStatus').classList.add('hidden');
    document.getElementById('discountInfo').classList.add('hidden');
    document.getElementById('kode_referal').classList.remove('border-green-500', 'bg-green-50', 'border-red-500');
    document.getElementById('kode_referal').readOnly = false;
    calculateTotal();
}

function handleReferralInput() {
    const kodeReferal = document.getElementById('kode_referal').value.trim();
    const input = document.getElementById('kode_referal');
    const loader = document.getElementById('referralLoader');
    
    clearTimeout(referralTimeout);
    
    if (!kodeReferal) {
        referralVerified = false;
        referralDiscount = 0;
        document.getElementById('referralStatus').classList.add('hidden');
        document.getElementById('discountInfo').classList.add('hidden');
        input.classList.remove('border-green-500', 'bg-green-50', 'border-red-500');
        input.readOnly = false;
        loader.classList.add('hidden');
        calculateTotal();
        return;
    }
    
    loader.classList.remove('hidden');
    
    if (referralVerified) {
        referralVerified = false;
        referralDiscount = 0;
        document.getElementById('discountInfo').classList.add('hidden');
        input.classList.remove('border-green-500', 'bg-green-50', 'border-red-500');
        input.readOnly = false;
        calculateTotal();
    }
    
    referralTimeout = setTimeout(() => {
        verifyReferral();
    }, 300);
}

function verifyReferral() {
    const pelangganId = document.getElementById('id_pelanggan').value;
    const kodeReferal = document.getElementById('kode_referal').value.trim();
    const statusDiv = document.getElementById('referralStatus');
    const input = document.getElementById('kode_referal');
    const loader = document.getElementById('referralLoader');

    if (!pelangganId) {
        loader.classList.add('hidden');
        statusDiv.innerHTML = `
            <div class="flex items-center gap-2 text-amber-700 bg-amber-50 rounded-lg p-3 border border-amber-200">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                <span class="font-medium">Pilih pelanggan terlebih dahulu</span>
            </div>
        `;
        statusDiv.classList.remove('hidden');
        return;
    }

    if (!kodeReferal) {
        loader.classList.add('hidden');
        return;
    }

    fetch('{{ route("pegawai.transaksi.verifyReferral") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            id_pelanggan: pelangganId,
            kode_referal: kodeReferal
        })
    })
    .then(response => response.json())
    .then(data => {
        loader.classList.add('hidden');
        
        if (data.success) {
            referralVerified = true;
            referralDiscount = data.diskon;
            
            statusDiv.innerHTML = `
                <div class="flex items-center gap-2 text-green-700 bg-green-50 rounded-lg p-3 border border-green-200">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="font-medium">${data.message}</span>
                </div>
            `;
            statusDiv.classList.remove('hidden');
            
            input.classList.remove('border-red-500');
            input.classList.add('border-green-500', 'bg-green-50');
            input.readOnly = true;
            
            calculateTotal();
            showAlert(data.message, 'success');
        } else {
            throw new Error(data.message);
        }
    })
    .catch(error => {
        loader.classList.add('hidden');
        referralVerified = false;
        referralDiscount = 0;
        
        statusDiv.innerHTML = `
            <div class="flex items-center gap-2 text-red-700 bg-red-50 rounded-lg p-3 border border-red-200">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
                <span class="font-medium">${error.message || 'Kode referral tidak valid'}</span>
            </div>
        `;
        statusDiv.classList.remove('hidden');
        
        input.classList.remove('border-green-500', 'bg-green-50');
        input.classList.add('border-red-500');
    });
}

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
        resultsDiv.innerHTML = '<div class="p-6 text-gray-500 text-sm text-center">Tidak ada produk ditemukan</div>';
    } else {
        products.slice(0, 10).forEach(product => {
            const div = document.createElement('div');
            div.className = 'p-4 hover:bg-green-50 cursor-pointer border-b border-gray-100 last:border-b-0 search-result-item transition-colors';
            div.onclick = () => addToCart(product);
            div.innerHTML = `
                <div class="flex justify-between items-center">
                    <div class="flex-1">
                        <div class="font-semibold text-sm text-gray-900">${product.nama}</div>
                        <div class="text-xs text-gray-500 mt-1">Stok: <span class="font-medium ${product.stok > 10 ? 'text-green-600' : 'text-amber-600'}">${product.stok}</span></div>
                    </div>
                    <div class="text-right ml-4">
                        <div class="text-base font-bold text-green-600">Rp ${parseInt(product.harga).toLocaleString('id-ID')}</div>
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

    document.querySelectorAll('.category-btn').forEach(btn => {
        if (btn.dataset.category == categoryId) {
            btn.classList.remove('bg-white', 'text-gray-700', 'border-gray-200');
            btn.classList.add('bg-green-600', 'text-white', 'shadow-sm');
        } else {
            btn.classList.remove('bg-green-600', 'text-white', 'shadow-sm');
            btn.classList.add('bg-white', 'text-gray-700', 'border', 'border-gray-200');
        }
    });

    document.getElementById('productCount').textContent = `${filteredProducts.length} produk`;

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
            <div class="group relative bg-white border border-gray-200 rounded-xl overflow-hidden hover:shadow-lg transition-all duration-300 ${isOutOfStock ? 'opacity-60' : 'hover:border-green-300 cursor-pointer'}" 
                 ${!isOutOfStock ? `onclick="addToCart(${JSON.stringify(product).replace(/"/g, '&quot;')})"` : ''}>
                
                <div class="relative aspect-square bg-gradient-to-br from-gray-50 to-gray-100 overflow-hidden">
                    ${product.image ?
                        `<img src="${storageUrl}${product.image}" alt="${product.nama}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">` : ''}
                    <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-gray-100 to-gray-200 ${product.image ? 'hidden' : ''}">
                        <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                        </svg>
                    </div>
                    
                    <div class="absolute top-2 right-2">
                        ${isOutOfStock ? 
                            '<span class="px-2.5 py-1 text-xs font-bold bg-red-600 text-white rounded-full shadow-lg">Habis</span>' :
                            isLowStock ?
                            `<span class="px-2.5 py-1 text-xs font-bold bg-amber-500 text-white rounded-full shadow-lg">${product.stok}</span>` :
                            `<span class="px-2.5 py-1 text-xs font-bold bg-green-600 text-white rounded-full shadow-lg">${product.stok}</span>`
                        }
                    </div>

                    ${!isOutOfStock ?
                        `<div class="absolute inset-0 bg-transparent group-hover:bg-gradient-to-t group-hover:from-green-600/20 group-hover:to-transparent transition-all duration-300 flex items-center justify-center">
                            <div class="transform scale-0 group-hover:scale-100 transition-transform duration-300">
                                <div class="bg-white rounded-full p-3 shadow-2xl">
                                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                    </svg>
                                </div>
                            </div>
                        </div>` : ''
                    }
                </div>

                <div class="p-4">
                    <h4 class="font-semibold text-sm text-gray-900 mb-2 line-clamp-2 group-hover:text-green-600 transition-colors min-h-[2.5rem]" title="${product.nama}">
                        ${product.nama}
                    </h4>
                    <div class="flex items-center justify-between mt-3">
                        <div class="text-lg font-bold text-green-600">
                            Rp ${parseInt(product.harga).toLocaleString('id-ID')}
                        </div>
                        ${!isOutOfStock ? 
                            `<button type="button" onclick="event.stopPropagation(); addToCart(${JSON.stringify(product).replace(/"/g, '&quot;')})" class="p-2.5 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-all shadow-md hover:shadow-lg">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                </svg>
                            </button>` :
                            `<span class="text-xs text-red-600 font-semibold">Stok Habis</span>`
                        }
                    </div>
                </div>
            </div>
        `;
    }).join('');
}

function addToCart(product) {
    if (typeof product === 'string') {
        product = JSON.parse(product);
    }

    const existingItem = cartItems.find(item => item.id_produk == product.id_produk);

    if (existingItem) {
        if (existingItem.jumlah >= product.stok) {
            showAlert('Stok tidak mencukupi!', 'error');
            return;
        }
        existingItem.jumlah += 1;
        showAlert(`${product.nama} (${existingItem.jumlah})`, 'success');
    } else {
        if (product.stok < 1) {
            showAlert('Produk habis stok!', 'error');
            return;
        }
        cartItems.push({
            id_produk: product.id_produk,
            nama: product.nama,
            harga: product.harga,
            stok: product.stok,
            jumlah: 1
        });
        showAlert(`${product.nama} ditambahkan`, 'success');
    }

    updateCartDisplay();
    calculateTotal();
    
    if (document.getElementById('productSearch').value) {
        document.getElementById('productSearch').value = '';
        hideSearchResults();
    }

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
            <div class="text-center text-gray-500 py-16">
                <svg class="w-20 h-20 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                <p class="text-base font-medium">Keranjang masih kosong</p>
                <p class="text-sm text-gray-400 mt-1">Mulai tambahkan produk ke keranjang</p>
            </div>
        `;
        return;
    }

    cartContainer.innerHTML = cartItems.map((item, index) => `
        <div class="flex items-center gap-4 p-4 bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl border border-green-100 hover:shadow-md transition-all mb-3">
            <div class="flex-1 min-w-0">
                <div class="font-semibold text-sm text-gray-900 truncate">${item.nama}</div>
                <div class="flex items-center gap-2 mt-2">
                    <span class="text-sm text-green-600 font-bold">Rp ${parseInt(item.harga).toLocaleString('id-ID')}</span>
                    <span class="text-xs text-gray-400">×</span>
                    <span class="text-xs text-gray-600 font-medium">${item.jumlah}</span>
                    <span class="text-xs text-gray-400">=</span>
                    <span class="text-sm font-bold text-gray-900">Rp ${(parseInt(item.harga) * item.jumlah).toLocaleString('id-ID')}</span>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <button type="button" onclick="updateQuantity(${index}, -1)" class="w-8 h-8 bg-white border border-gray-300 rounded-lg flex items-center justify-center hover:bg-gray-50 hover:border-gray-400 transition-all font-bold text-gray-700">
                    −
                </button>
                <span class="w-10 text-center font-bold text-gray-900">${item.jumlah}</span>
                <button type="button" onclick="updateQuantity(${index}, 1)" class="w-8 h-8 bg-white border border-gray-300 rounded-lg flex items-center justify-center hover:bg-gray-50 hover:border-gray-400 transition-all font-bold text-gray-700">
                    +
                </button>
                <button type="button" onclick="removeFromCart(${index})" class="w-8 h-8 bg-red-100 text-red-600 rounded-lg flex items-center justify-center hover:bg-red-200 transition-all ml-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
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

    const diskonPersen = referralVerified ? referralDiscount : 0;
    const diskonAmount = (subtotal * diskonPersen) / 100;
    const total = subtotal - diskonAmount;

    const points = Math.floor(total / 1000);

    document.getElementById('subtotalHarga').textContent = `Rp ${subtotal.toLocaleString('id-ID')}`;
    document.getElementById('totalItems').textContent = totalItems;
    document.getElementById('totalHarga').textContent = `Rp ${total.toLocaleString('id-ID')}`;
    document.getElementById('poinEarned').textContent = `${points} poin`;

    const discountInfo = document.getElementById('discountInfo');
    if (referralVerified && diskonPersen > 0) {
        document.getElementById('diskonAmount').textContent = `-Rp ${diskonAmount.toLocaleString('id-ID')}`;
        discountInfo.classList.remove('hidden');
    } else {
        discountInfo.classList.add('hidden');
    }

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

    if (!formData.get('id_pelanggan')) {
        showAlert('Pilih pelanggan terlebih dahulu', 'error');
        return;
    }

    if (!formData.get('metode_pembayaran')) {
        showAlert('Pilih metode pembayaran terlebih dahulu', 'error');
        return;
    }

    if (formData.get('metode_pembayaran') === 'cash') {
        const totalText = document.getElementById('totalHarga').textContent;
        const total = parseInt(totalText.replace(/[^\d]/g, '')) || 0;
        const paid = parseInt(document.getElementById('jumlahDibayar').value) || 0;

        if (paid < total) {
            showAlert('Jumlah pembayaran kurang dari total!', 'error');
            return;
        }
    }

    const submitBtn = document.getElementById('submitBtn');
    const submitText = document.getElementById('submitText');
    submitBtn.disabled = true;
    submitText.innerHTML = '<svg class="animate-spin h-5 w-5 mx-auto" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';

    const data = {
        id_pelanggan: formData.get('id_pelanggan'),
        metode_pembayaran: formData.get('metode_pembayaran'),
        kode_referal: referralVerified ? formData.get('kode_referal') : null,
        produk: cartItems.map(item => ({
            id_produk: item.id_produk,
            jumlah: item.jumlah
        })),
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
            showAlert('Transaksi berhasil dibuat!', 'success');

            cartItems = [];
            updateCartDisplay();
            calculateTotal();
            document.getElementById('transaksiForm').reset();
            document.getElementById('jumlahDibayar').value = '';
            togglePaymentFields();
            resetReferral();

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
        submitText.textContent = 'Proses Transaksi';
    });
}

function showAlert(message, type) {
    const alertDiv = document.createElement('div');
    alertDiv.className = `fixed top-6 right-6 z-50 px-6 py-4 rounded-xl shadow-2xl transform transition-all duration-300 flex items-center gap-3 ${
        type === 'success' ? 'bg-green-600' : type === 'error' ? 'bg-red-600' : 'bg-blue-600'
    } text-white max-w-md border-l-4 ${
        type === 'success' ? 'border-green-400' : type === 'error' ? 'border-red-400' : 'border-blue-400'
    }`;

    const icons = {
        success: '<svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>',
        error: '<svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>',
        info: '<svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>'
    };

    alertDiv.innerHTML = `
        <div class="flex-shrink-0">
            ${icons[type] || icons.info}
        </div>
        <span class="font-medium flex-1">${message}</span>
        <button onclick="this.parentElement.remove()" class="flex-shrink-0 ml-2 hover:bg-white/20 rounded p-1 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
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
    }, 4000);
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'F1') {
        e.preventDefault();
        document.getElementById('productSearch').focus();
    }

    if (e.key === 'F12') {
        e.preventDefault();
        if (!document.getElementById('submitBtn').disabled) {
            saveTransaksi();
        }
    }

    if (e.key === 'Escape') {
        document.getElementById('productSearch').value = '';
        hideSearchResults();
    }
});
</script>
@endsection