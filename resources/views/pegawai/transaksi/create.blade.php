@extends('layouts.pegawai')

@section('title', 'Tambah Transaksi')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Tambah Transaksi</h1>
                    <p class="text-gray-500 mt-1 text-sm">Kelola transaksi penjualan dengan mudah</p>
                </div>
                <a href="{{ route('pegawai.transaksi.index') }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition-all shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Kembali
                </a>
            </div>
        </div>

        <form id="transaksiForm" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            @csrf

            <!-- Left Panel - Products -->
            <div class="lg:col-span-2 space-y-4">
                <!-- Product Search & Filter Card - COMPACT -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4">
                    <div class="mb-3 relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <input type="text" id="productSearch" placeholder="Cari produk..." class="w-full pl-10 pr-10 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-green-600 focus:border-green-600 transition-all" oninput="handleSearchInput()">
                        <button type="button" id="clearSearchBtn" onclick="clearSearch()" class="absolute inset-y-0 right-0 pr-3 flex items-center">
                            <svg class="w-4 h-4 text-gray-400 hover:text-gray-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    <!-- Category Buttons -->
                    <div class="flex flex-wrap gap-2">
                        <button type="button" onclick="loadProductsByCategory('all')" class="category-btn px-3 py-1.5 text-xs font-medium bg-green-600 text-white rounded-lg hover:bg-green-700 transition-all" data-category="all">
                            Semua
                        </button>
                        @foreach(\App\Models\Kategori::all() as $kategori)
                            <button type="button" onclick="loadProductsByCategory({{ $kategori->id_kategori }})" class="category-btn px-3 py-1.5 text-xs font-medium bg-white text-gray-700 border border-gray-200 rounded-lg hover:bg-gray-50 hover:border-gray-300 transition-all" data-category="{{ $kategori->id_kategori }}">
                                {{ $kategori->nama_kategori }}
                            </button>
                        @endforeach
                    </div>
                </div>

                <!-- Product Grid -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4">
                    <div class="flex justify-between items-center mb-3">
                        <h3 class="text-sm font-semibold text-gray-800">Daftar Produk</h3>
                        <span id="productCount" class="px-2.5 py-0.5 bg-green-50 text-green-700 text-xs font-medium rounded-full">{{ count($produk) }} produk</span>
                    </div>
                    <div id="productGrid" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3"></div>
                    <div id="emptyProduct" class="hidden text-center py-12">
                        <svg class="w-16 h-16 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                        </svg>
                        <p class="text-sm font-medium text-gray-600">Tidak ada produk</p>
                        <p class="text-xs text-gray-500 mt-1">Coba pilih kategori lain</p>
                    </div>
                </div>
            </div>

            <!-- Right Panel - Transaction Info, Cart & Summary -->
            <div class="space-y-4">
                <!-- Transaction Info Card - Moved to Top Right -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4">
                    <div class="grid grid-cols-1 gap-3">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1.5">Pelanggan *</label>
                            <select id="id_pelanggan" name="id_pelanggan" required class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-green-600 focus:border-green-600 bg-white transition-all" onchange="resetReferral()">
                                <option value="">Pilih Pelanggan</option>
                                @foreach($pelanggan as $p)
                                    <option value="{{ $p->id_pelanggan }}">{{ $p->nama }} - {{ $p->email }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Referral Code Section -->
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1.5">
                                Kode Referral (Opsional)
                            </label>
                            <div class="relative">
                                <input type="text" id="kode_referal" name="kode_referal" placeholder="Masukkan kode untuk diskon 10%" class="w-full px-3 py-2 pr-10 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-green-600 focus:border-green-600 transition-all" oninput="handleReferralInput()">
                                <div id="referralLoader" class="hidden absolute right-3 top-1/2 transform -translate-y-1/2">
                                    <svg class="animate-spin h-4 w-4 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div id="referralStatus" class="mt-2 text-xs hidden"></div>
                        </div>

                        <!-- Metode Pembayaran -->
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1.5">Metode Pembayaran *</label>
                            <div class="grid grid-cols-3 gap-2">
                                <!-- Cash Card -->
                                <label class="relative cursor-pointer group">
                                    <input type="radio" name="metode_pembayaran" value="cash" class="peer sr-only" onclick="togglePaymentFields()">
                                    <div class="flex flex-col items-center justify-center p-2 bg-white border-2 border-gray-200 rounded-lg transition-all duration-200 peer-checked:border-green-600 peer-checked:bg-green-50 hover:border-green-300">
                                        <svg class="w-5 h-5 mb-1 text-gray-400 peer-checked:text-green-600 group-hover:text-green-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                                        </svg>
                                        <span class="text-xs font-semibold text-gray-700 peer-checked:text-green-700">Cash</span>
                                    </div>
                                </label>

                                <!-- QRIS Card -->
                                <label class="relative cursor-pointer group">
                                    <input type="radio" name="metode_pembayaran" value="qris" class="peer sr-only" onclick="togglePaymentFields()">
                                    <div class="flex flex-col items-center justify-center p-2 bg-white border-2 border-gray-200 rounded-lg transition-all duration-200 peer-checked:border-green-600 peer-checked:bg-green-50 hover:border-green-300">
                                        <svg class="w-5 h-5 mb-1 text-gray-400 peer-checked:text-green-600 group-hover:text-green-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                                        </svg>
                                        <span class="text-xs font-semibold text-gray-700 peer-checked:text-green-700">QRIS</span>
                                    </div>
                                </label>

                                <!-- Transfer Card -->
                                <label class="relative cursor-pointer group">
                                    <input type="radio" name="metode_pembayaran" value="transfer" class="peer sr-only" onclick="togglePaymentFields()">
                                    <div class="flex flex-col items-center justify-center p-2 bg-white border-2 border-gray-200 rounded-lg transition-all duration-200 peer-checked:border-green-600 peer-checked:bg-green-50 hover:border-green-300">
                                        <svg class="w-5 h-5 mb-1 text-gray-400 peer-checked:text-green-600 group-hover:text-green-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                        </svg>
                                        <span class="text-xs font-semibold text-gray-700 peer-checked:text-green-700">Transfer</span>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Cart Table -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden sticky top-6">
                    <div class="p-3 border-b border-gray-100 bg-gradient-to-r from-green-50 to-emerald-50">
                        <div class="flex justify-between items-center">
                            <h3 class="text-sm font-semibold text-gray-800 flex items-center gap-2">
                                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                                Keranjang
                            </h3>
                            <span id="cartItemCount" class="px-2 py-0.5 bg-green-600 text-white text-xs font-bold rounded-full">0</span>
                        </div>
                    </div>
                    
                    <div class="max-h-64 overflow-y-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50 sticky top-0">
                                <tr>
                                    <th class="px-2 py-1.5 text-left text-xs font-semibold text-gray-600 uppercase">Produk</th>
                                    <th class="px-2 py-1.5 text-center text-xs font-semibold text-gray-600 uppercase">Qty</th>
                                    <th class="px-2 py-1.5 text-right text-xs font-semibold text-gray-600 uppercase">Total</th>
                                    <th class="px-2 py-1.5 text-center text-xs font-semibold text-gray-600 uppercase w-12">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="cartItems" class="divide-y divide-gray-100">
                                <tr>
                                    <td colspan="4" class="px-2 py-12 text-center">
                                        <svg class="w-12 h-12 mx-auto mb-2 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                                        </svg>
                                        <p class="text-xs font-medium text-gray-500">Keranjang kosong</p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Summary Section -->
                    <div class="p-3 border-t border-gray-100 bg-gray-50">
                        <div class="space-y-1.5 text-xs mb-2">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Total Item</span>
                                <span id="totalItems" class="font-semibold text-gray-900">0</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Subtotal</span>
                                <span id="subtotalHarga" class="font-semibold text-gray-900">Rp 0</span>
                            </div>

                            <!-- Discount Info -->
                            <div id="discountInfo" class="hidden pt-1.5 border-t border-gray-200">
                                <div class="flex items-center justify-between py-1 bg-green-50 rounded-lg px-2 mb-1">
                                    <span class="text-green-700 flex items-center gap-1 font-medium text-xs">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        Diskon Referral
                                    </span>
                                    <span class="font-bold text-green-700 text-xs">10%</span>
                                </div>
                                <div class="flex justify-between text-xs">
                                    <span class="text-gray-600">Potongan</span>
                                    <span id="diskonAmount" class="text-red-600 font-semibold">-Rp 0</span>
                                </div>
                            </div>
                        </div>

                        <div class="pt-2 border-t border-gray-200">
                            <div class="flex justify-between items-center">
                                <span class="text-xs font-semibold text-gray-900">Total Bayar</span>
                                <span id="totalHarga" class="text-lg font-bold text-green-600">Rp 0</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Cash Payment Card -->
                <div id="cashPaymentFields" class="bg-white rounded-lg shadow-sm border border-gray-100 p-3 hidden">
                    <h4 class="text-xs font-semibold text-gray-800 mb-2 flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        Pembayaran Cash
                    </h4>

                    <div class="space-y-2">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Jumlah Dibayar</label>
                            <input type="number" id="jumlahDibayar" min="0" step="1000" class="w-full px-3 py-1.5 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-green-600 focus:border-green-600 transition-all" placeholder="Masukkan nominal" oninput="calculateChange()">
                        </div>

                        <div class="grid grid-cols-3 gap-1.5">
                            <button type="button" onclick="setQuickAmount(50000)" class="px-2 py-1 text-xs font-medium bg-gray-100 text-gray-700 rounded hover:bg-gray-200 transition-all">50k</button>
                            <button type="button" onclick="setQuickAmount(100000)" class="px-2 py-1 text-xs font-medium bg-gray-100 text-gray-700 rounded hover:bg-gray-200 transition-all">100k</button>
                            <button type="button" onclick="setQuickAmount(200000)" class="px-2 py-1 text-xs font-medium bg-gray-100 text-gray-700 rounded hover:bg-gray-200 transition-all">200k</button>
                        </div>

                        <div class="pt-2 border-t border-gray-100 space-y-1">
                            <div class="flex justify-between text-xs">
                                <span class="text-gray-600">Total Tagihan</span>
                                <span id="totalBayarDisplay" class="font-semibold text-gray-900">Rp 0</span>
                            </div>
                            <div class="flex justify-between items-center bg-green-50 rounded-lg px-2 py-1.5">
                                <span class="text-xs font-medium text-green-700">Kembalian</span>
                                <span id="kembalian" class="text-sm font-bold text-green-700">Rp 0</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="space-y-2">
                    <button type="button" onclick="clearCart()" class="w-full px-3 py-2 text-xs font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 hover:border-gray-400 transition-all flex items-center justify-center gap-2">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        Kosongkan Keranjang
                    </button>
                    <button type="submit" id="submitBtn" class="w-full px-3 py-2.5 text-sm font-semibold text-white bg-green-600 rounded-lg hover:bg-green-700 disabled:opacity-50 disabled:cursor-not-allowed transition-all shadow-sm hover:shadow-md flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
let referralTimeout;
let currentCategory = 'all';
let searchQuery = '';
let storageUrl = '{{ asset("storage") }}/';
let referralVerified = false;
let referralDiscount = 0;

document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('transaksiForm').addEventListener('submit', function(e) {
        e.preventDefault();
        saveTransaksi();
    });

    const searchInput = document.getElementById('productSearch');
    searchInput.addEventListener('input', handleSearchInput);

    loadProductsByCategory('all');
});

// ==================== REFERRAL FUNCTIONS ====================
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
            <div class="flex items-center gap-1.5 text-amber-700 bg-amber-50 rounded-lg p-2 border border-amber-200">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                <div class="flex items-center gap-1.5 text-green-700 bg-green-50 rounded-lg p-2 border border-green-200">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
            <div class="flex items-center gap-1.5 text-red-700 bg-red-50 rounded-lg p-2 border border-red-200">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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

// ==================== SEARCH FUNCTIONS ====================
function handleSearchInput() {
    const query = document.getElementById('productSearch').value.trim();
    searchQuery = query.toLowerCase();
    
    const clearBtn = document.getElementById('clearSearchBtn');
    if (query) {
        clearBtn.classList.remove('hidden');
    } else {
        clearBtn.classList.add('hidden');
    }
    
    filterAndDisplayProducts();
}

function clearSearch() {
    document.getElementById('productSearch').value = '';
    searchQuery = '';
    document.getElementById('clearSearchBtn').classList.add('hidden');
    filterAndDisplayProducts();
}

function filterAndDisplayProducts() {
    let filteredProducts = produkList;

    // Filter berdasarkan kategori
    if (currentCategory !== 'all') {
        filteredProducts = filteredProducts.filter(product => product.id_kategori == currentCategory);
    }

    // Filter berdasarkan search query
    if (searchQuery) {
        filteredProducts = filteredProducts.filter(product =>
            product.nama.toLowerCase().includes(searchQuery)
        );
    }

    // Update jumlah produk
    document.getElementById('productCount').textContent = `${filteredProducts.length} produk`;

    // Display produk
    displayProducts(filteredProducts);
}

function loadProductsByCategory(categoryId) {
    currentCategory = categoryId;

    // Update tampilan tombol kategori
    document.querySelectorAll('.category-btn').forEach(btn => {
        if (btn.dataset.category == categoryId) {
            btn.classList.remove('bg-white', 'text-gray-700', 'border-gray-200');
            btn.classList.add('bg-green-600', 'text-white');
        } else {
            btn.classList.remove('bg-green-600', 'text-white');
            btn.classList.add('bg-white', 'text-gray-700', 'border', 'border-gray-200');
        }
    });

    // Filter dan tampilkan produk
    filterAndDisplayProducts();
}

function displayProducts(products) {
    const productGrid = document.getElementById('productGrid');
    const emptyProduct = document.getElementById('emptyProduct');

    if (products.length === 0) {
        productGrid.classList.add('hidden');
        emptyProduct.classList.remove('hidden');
        
        // Update pesan kosong berdasarkan context
        const emptyMessage = emptyProduct.querySelector('p:first-of-type');
        const emptySubtext = emptyProduct.querySelector('p:last-of-type');
        
        if (searchQuery) {
            emptyMessage.textContent = 'Produk tidak ditemukan';
            emptySubtext.textContent = `Tidak ada hasil untuk "${document.getElementById('productSearch').value}"`;
        } else {
            emptyMessage.textContent = 'Tidak ada produk';
            emptySubtext.textContent = 'Coba pilih kategori lain';
        }
        return;
    }

    productGrid.classList.remove('hidden');
    emptyProduct.classList.add('hidden');

    productGrid.innerHTML = products.map(product => {
        const isOutOfStock = product.stok < 1;
        const isLowStock = product.stok > 0 && product.stok <= 5;
        
        // Highlight nama produk jika ada search query
        let displayName = product.nama;
        if (searchQuery) {
            const regex = new RegExp(`(${searchQuery})`, 'gi');
            displayName = product.nama.replace(regex, '<mark class="bg-yellow-200 px-1 rounded">$1</mark>');
        }
        
        return `
            <div class="group relative bg-white border border-gray-200 rounded-lg overflow-hidden hover:shadow-lg transition-all duration-300 ${isOutOfStock ? 'opacity-60' : 'hover:border-green-300 cursor-pointer'}" 
                 ${!isOutOfStock ? `onclick="addToCart(${JSON.stringify(product).replace(/"/g, '&quot;')})"` : ''}>
                
                <div class="relative aspect-square bg-gradient-to-br from-gray-50 to-gray-100 overflow-hidden">
                    ${product.image ?
                        `<img src="${storageUrl}${product.image}" alt="${product.nama}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">` : ''}
                    <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-gray-100 to-gray-200 ${product.image ? 'hidden' : ''}">
                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                        </svg>
                    </div>
                    
                    <div class="absolute top-1.5 right-1.5">
                        ${isOutOfStock ? 
                            '<span class="px-2 py-0.5 text-xs font-bold bg-red-600 text-white rounded-full shadow-lg">Habis</span>' :
                            isLowStock ?
                            `<span class="px-2 py-0.5 text-xs font-bold bg-amber-500 text-white rounded-full shadow-lg">${product.stok}</span>` :
                            `<span class="px-2 py-0.5 text-xs font-bold bg-green-600 text-white rounded-full shadow-lg">${product.stok}</span>`
                        }
                    </div>

                    ${!isOutOfStock ?
                        `<div class="absolute inset-0 bg-transparent group-hover:bg-gradient-to-t group-hover:from-green-600/20 group-hover:to-transparent transition-all duration-300 flex items-center justify-center">
                            <div class="transform scale-0 group-hover:scale-100 transition-transform duration-300">
                                <div class="bg-white rounded-full p-2 shadow-2xl">
                                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                    </svg>
                                </div>
                            </div>
                        </div>` : ''
                    }
                </div>

                <div class="p-3">
                    <h4 class="font-semibold text-xs text-gray-900 mb-1.5 line-clamp-2 group-hover:text-green-600 transition-colors min-h-[2rem]" title="${product.nama}">
                        ${displayName}
                    </h4>
                    <div class="flex items-center justify-between mt-2">
                        <div class="text-sm font-bold text-green-600">
                            Rp ${parseInt(product.harga).toLocaleString('id-ID')}
                        </div>
                        ${!isOutOfStock ? 
                            `<button type="button" onclick="event.stopPropagation(); addToCart(${JSON.stringify(product).replace(/"/g, '&quot;')})" class="p-1.5 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-all shadow-md hover:shadow-lg">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                </svg>
                            </button>` :
                            `<span class="text-xs text-red-600 font-semibold">Habis</span>`
                        }
                    </div>
                </div>
            </div>
        `;
    }).join('');
}

// ==================== CART FUNCTIONS ====================
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
}

function updateCartDisplay() {
    const cartContainer = document.getElementById('cartItems');
    const cartCount = document.getElementById('cartItemCount');

    cartCount.textContent = cartItems.length;

    if (cartItems.length === 0) {
        cartContainer.innerHTML = `
            <tr>
                <td colspan="4" class="px-2 py-12 text-center">
                    <svg class="w-12 h-12 mx-auto mb-2 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    <p class="text-xs font-medium text-gray-500">Keranjang kosong</p>
                </td>
            </tr>
        `;
        return;
    }

    cartContainer.innerHTML = cartItems.map((item, index) => `
        <tr class="hover:bg-gray-50 transition-colors">
            <td class="px-2 py-2">
                <div class="text-xs font-semibold text-gray-900 line-clamp-2" title="${item.nama}">${item.nama}</div>
                <div class="text-xs text-green-600 font-medium mt-0.5">Rp ${parseInt(item.harga).toLocaleString('id-ID')}</div>
            </td>
            <td class="px-2 py-2">
                <div class="flex items-center justify-center gap-1">
                    <button type="button" onclick="updateQuantity(${index}, -1)" class="w-5 h-5 bg-gray-100 border border-gray-300 rounded flex items-center justify-center hover:bg-gray-200 transition-all text-xs font-bold text-gray-700">
                        −
                    </button>
                    <span class="w-7 text-center text-xs font-bold text-gray-900">${item.jumlah}</span>
                    <button type="button" onclick="updateQuantity(${index}, 1)" class="w-5 h-5 bg-gray-100 border border-gray-300 rounded flex items-center justify-center hover:bg-gray-200 transition-all text-xs font-bold text-gray-700">
                        +
                    </button>
                </div>
            </td>
            <td class="px-2 py-2 text-right">
                <div class="text-xs font-bold text-gray-900">Rp ${(parseInt(item.harga) * item.jumlah).toLocaleString('id-ID')}</div>
            </td>
            <td class="px-2 py-2 text-center">
                <button type="button" onclick="removeFromCart(${index})" class="w-6 h-6 bg-red-100 text-red-600 rounded flex items-center justify-center hover:bg-red-200 transition-all mx-auto">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </button>
            </td>
        </tr>
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

// ==================== CALCULATION FUNCTIONS ====================
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

    document.getElementById('subtotalHarga').textContent = `Rp ${subtotal.toLocaleString('id-ID')}`;
    document.getElementById('totalItems').textContent = totalItems;
    document.getElementById('totalHarga').textContent = `Rp ${total.toLocaleString('id-ID')}`;

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

// ==================== PAYMENT FUNCTIONS ====================
function togglePaymentFields() {
    const method = document.querySelector('input[name="metode_pembayaran"]:checked')?.value;
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

// ==================== SAVE TRANSACTION ====================
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

    const selectedPaymentMethod = document.querySelector('input[name="metode_pembayaran"]:checked');
    if (!selectedPaymentMethod) {
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
    submitText.innerHTML = '<svg class="animate-spin h-4 w-4 mx-auto" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';

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
            cartItems = [];
            updateCartDisplay();
            calculateTotal();
            document.getElementById('transaksiForm').reset();
            document.getElementById('jumlahDibayar').value = '';
            togglePaymentFields();
            resetReferral();

            // Redirect langsung tanpa notifikasi
            if (data.redirect) {
                window.location.href = data.redirect;
            } else {
                window.location.href = '{{ route("pegawai.transaksi.index") }}';
            }
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

// ==================== UTILITY FUNCTIONS ====================
function showAlert(message, type) {
    const alertDiv = document.createElement('div');
    alertDiv.className = `fixed top-4 right-4 z-50 px-4 py-3 rounded-lg shadow-xl transform transition-all duration-300 flex items-center gap-2 ${
        type === 'success' ? 'bg-green-600' : type === 'error' ? 'bg-red-600' : 'bg-blue-600'
    } text-white max-w-sm border-l-4 ${
        type === 'success' ? 'border-green-400' : type === 'error' ? 'border-red-400' : 'border-blue-400'
    }`;

    const icons = {
        success: '<svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>',
        error: '<svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>',
        info: '<svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>'
    };

    alertDiv.innerHTML = `
        <div class="flex-shrink-0">
            ${icons[type] || icons.info}
        </div>
        <span class="text-sm font-medium flex-1">${message}</span>
        <button onclick="this.parentElement.remove()" class="flex-shrink-0 ml-2 hover:bg-white/20 rounded p-1 transition-colors">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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

// ==================== KEYBOARD SHORTCUTS ====================
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
        clearSearch();
    }
});
</script>
@endsection