<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PegawaiDashboardController;
use App\Http\Controllers\PelangganDashboardController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\TransaksiController;


// Landing page
Route::get('/', function () {
    return view('landing');
});

// Authentication routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

// Pegawai routes (protected by pegawai middleware)
Route::middleware(['auth.pegawai'])->prefix('pegawai')->name('pegawai.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [PegawaiDashboardController::class, 'index'])->name('dashboard');

    // Admin-only routes
    Route::middleware(['admin'])->group(function () {
        Route::resource('pegawai', PegawaiController::class);
    });

    // General pegawai routes
    Route::resource('pelanggan', PelangganController::class);
    Route::resource('kategori', KategoriController::class);
    Route::resource('produk', ProdukController::class);
    Route::resource('transaksi', TransaksiController::class);

});

// Pelanggan routes (protected by pelanggan middleware)
Route::middleware(['auth.pelanggan'])->prefix('pelanggan')->name('pelanggan.')->group(function () {
    Route::get('/dashboard', [PelangganDashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [PelangganDashboardController::class, 'profile'])->name('profile');
    Route::put('/profile', [PelangganDashboardController::class, 'updateProfile'])->name('profile.update');
    Route::post('/password', [PelangganDashboardController::class, 'updatePassword'])->name('password.update');
    Route::get('/transactions', [PelangganDashboardController::class, 'transactions'])->name('transactions');
    Route::get('/transaction/{id}', [PelangganDashboardController::class, 'showTransaction'])->name('transaction.show');
    Route::get('/referrals', [PelangganDashboardController::class, 'referrals'])->name('referrals');
});
