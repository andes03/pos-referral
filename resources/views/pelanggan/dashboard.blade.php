@extends('layouts.pelanggan')

@section('title', 'Dashboard')

@section('content')
<div x-data="{ activeTab: 'dashboard', showEditModal: false }" class="space-y-6">
    <!-- Tabs -->
    <div class="bg-white rounded-lg shadow">
        <div class="border-b border-gray-200">
            <nav class="-mb-px flex space-x-8 px-6" aria-label="Tabs">
                <button @click="activeTab = 'dashboard'" :class="activeTab === 'dashboard' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    Dashboard
                </button>
                <button @click="activeTab = 'transactions'" :class="activeTab === 'transactions' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    Riwayat Transaksi
                </button>
                <button @click="activeTab = 'referrals'" :class="activeTab === 'referrals' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    Kode Referral
                </button>
                <button @click="activeTab = 'profile'" :class="activeTab === 'profile' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    Profile
                </button>
            </nav>
        </div>
    </div>

    <!-- Dashboard Tab -->
    <div x-show="activeTab === 'dashboard'" class="space-y-6">
        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-2 bg-green-100 rounded-lg">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Transaksi</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ number_format($stats['total_transaksi']) }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-2 bg-purple-100 rounded-lg">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Referral</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ number_format($stats['total_referral']) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Referral Code Section -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Kode Referral Anda</h3>
            <div class="flex items-center space-x-4">
                <div class="flex-1">
                    <input type="text" value="{{ $pelanggan->kode_referal }}" readonly class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50 text-center font-mono text-lg">
                </div>
                <button onclick="copyToClipboard('{{ $pelanggan->kode_referal }}')" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                    </svg>
                    Copy
                </button>
            </div>
            <p class="text-sm text-gray-600 mt-2">Bagikan kode referral ini kepada teman-teman Anda untuk mendapatkan poin bonus!</p>
        </div>

        <!-- Recent Transactions -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Transaksi Terbaru</h3>
            </div>
            <div class="p-6">
                @if($transactions->count() > 0)
                    <div class="space-y-4">
                        @foreach($transactions->take(5) as $transaction)
                        <div class="flex items-center justify-between py-2">
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $transaction->tanggal_transaksi->format('d M Y H:i') }}</p>
                                <p class="text-sm text-gray-500">{{ $transaction->detailTransaksi->count() }} item(s)</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-medium text-gray-900">Rp {{ number_format($transaction->total, 0, ',', '.') }}</p>
                                <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full
                                    {{ $transaction->status_pembayaran === 'paid' ? 'bg-green-100 text-green-800' :
                                       ($transaction->status_pembayaran === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                    {{ ucfirst($transaction->status_pembayaran) }}
                                </span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <div class="mt-4">
                        <button @click="activeTab = 'transactions'" class="text-blue-600 hover:text-blue-800 text-sm font-medium">Lihat semua transaksi →</button>
                    </div>
                @else
                    <p class="text-gray-500 text-center py-4">Belum ada transaksi</p>
                @endif
            </div>
        </div>


    </div>

    <!-- Transactions Tab -->
    <div x-show="activeTab === 'transactions'" class="space-y-6">
        <div class="bg-white rounded-lg shadow overflow-hidden">
            @if($transactions->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status Pembayaran</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Items</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($transactions as $transaction)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $transaction->tanggal_transaksi->format('d M Y') }}</div>
                                    <div class="text-sm text-gray-500">{{ $transaction->tanggal_transaksi->format('H:i') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">Rp {{ number_format($transaction->total, 0, ',', '.') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full
                                        {{ $transaction->status_pembayaran === 'paid' ? 'bg-green-100 text-green-800' :
                                           ($transaction->status_pembayaran === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                        {{ ucfirst($transaction->status_pembayaran) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $transaction->detailTransaksi->count() }} item(s)
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('pelanggan.transaction.show', $transaction->id_transaksi) }}" class="text-blue-600 hover:text-blue-900">Lihat Detail</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                    {{ $transactions->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada transaksi</h3>
                    <p class="mt-1 text-sm text-gray-500">Anda belum melakukan transaksi apapun.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Referrals Tab -->
    <div x-show="activeTab === 'referrals'" class="space-y-6">
        <!-- Referral Code Section -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Kode Referral Anda</h3>
            <div class="flex items-center space-x-4">
                <div class="flex-1">
                    <input type="text" value="{{ $pelanggan->kode_referal }}" readonly class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50 text-center font-mono text-lg">
                </div>
                <button onclick="copyToClipboard('{{ $pelanggan->kode_referal }}')" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                    </svg>
                    Copy
                </button>
            </div>
            <p class="text-sm text-gray-600 mt-2">Bagikan kode referral ini kepada teman-teman Anda untuk mendapatkan poin bonus!</p>
        </div>

        <!-- Referrals List -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Daftar Orang yang Menggunakan Kode Referral Anda</h3>
            </div>

            @if($referrals->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Bergabung</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($referrals as $referral)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $referral->nama }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-500">{{ $referral->email }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $referral->created_at->format('d M Y') }}</div>
                                    <div class="text-sm text-gray-500">{{ $referral->created_at->format('H:i') }}</div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                    {{ $referrals->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada referral</h3>
                    <p class="mt-1 text-sm text-gray-500">Belum ada orang yang menggunakan kode referral Anda.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Profile Tab -->
    <div x-show="activeTab === 'profile'" class="space-y-6">
        <!-- Physical Card Style -->
        <div class="max-w-4xl mx-auto">
            <div class="bg-gradient-to-br from-blue-600 via-purple-600 to-indigo-700 rounded-2xl shadow-2xl overflow-hidden transform rotate-1 hover:rotate-0 transition-transform duration-300">
                <div class="relative">
                    <!-- Card Background Pattern -->
                    <div class="absolute inset-0 opacity-20">
                        <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent"></div>
                        <div class="absolute inset-0" style="background-image: radial-gradient(circle at 25% 25%, rgba(255,255,255,0.1) 2px, transparent 2px); background-size: 20px 20px;"></div>
                    </div>

                    <!-- Card Header -->
                    <div class="relative bg-gradient-to-r from-yellow-400 to-orange-500 p-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center shadow-lg">
                                    <svg class="w-6 h-6 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-gray-800">POS & Referral System</h3>
                                    <p class="text-sm text-gray-700">Member Card</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="bg-white/20 backdrop-blur-sm rounded-lg px-3 py-2">
                                    <div class="text-xl font-bold text-white">{{ number_format($pelanggan->poin) }}</div>
                                    <div class="text-xs text-yellow-100">Points</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Card Body -->
                    <div class="relative p-6 bg-white">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Left Side - Personal Info -->
                            <div class="space-y-4">
                                <div>
                                    <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Card Holder</label>
                                    <p class="text-lg font-bold text-gray-900">{{ $pelanggan->nama }}</p>
                                </div>

                                <div>
                                    <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Email</label>
                                    <p class="text-sm text-gray-700">{{ $pelanggan->email }}</p>
                                </div>

                                <div>
                                    <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Phone</label>
                                    <p class="text-sm text-gray-700">{{ $pelanggan->no_telp ?: 'Not provided' }}</p>
                                </div>

                                <div>
                                    <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Address</label>
                                    <p class="text-sm text-gray-700">{{ $pelanggan->alamat ?: 'Not provided' }}</p>
                                </div>
                            </div>

                            <!-- Right Side - Card Details -->
                            <div class="space-y-4">
                                <div>
                                    <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Member Since</label>
                                    <p class="text-sm text-gray-700">{{ $pelanggan->created_at->format('F Y') }}</p>
                                </div>

                                <div>
                                    <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Referral Code</label>
                                    <div class="flex items-center space-x-2">
                                        <span class="bg-gray-100 px-2 py-1 rounded text-sm font-mono text-gray-800">{{ $pelanggan->kode_referal }}</span>
                                        <button onclick="copyToClipboard('{{ $pelanggan->kode_referal }}')" class="text-gray-500 hover:text-gray-700">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>

                                <div>
                                    <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Card Number</label>
                                    <p class="text-sm font-mono text-gray-700">{{ strtoupper(substr(md5($pelanggan->id_pelanggan), 0, 16)) }}</p>
                                </div>

                                <div class="flex justify-end">
                                    <button @click="showEditModal = true" class="bg-blue-600 text-white px-4 py-2 rounded-lg font-medium hover:bg-blue-700 transition-colors shadow-md">
                                        Edit Profile
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Card Footer -->
                        <div class="mt-6 pt-4 border-t border-gray-200">
                            <div class="flex items-center justify-between text-xs text-gray-500">
                                <span>Valid from: {{ $pelanggan->created_at->format('m/y') }}</span>
                                <span>POS System Member</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Profile Modal -->
        <div x-show="showEditModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="showEditModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="showEditModal = false" aria-hidden="true"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div x-show="showEditModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4" id="modal-title">
                                    Edit Profile
                                </h3>

                                <form id="profile-form" action="{{ route('pelanggan.profile.update') }}" method="POST" class="space-y-4">
                                    @csrf
                                    @method('PUT')

                                    <!-- Name -->
                                    <div>
                                        <label for="nama_modal" class="block text-sm font-medium text-gray-700">Nama</label>
                                        <input type="text" name="nama" id="nama_modal" value="{{ old('nama', $pelanggan->nama) }}" required
                                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                        @error('nama')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Email -->
                                    <div>
                                        <label for="email_modal" class="block text-sm font-medium text-gray-700">Email</label>
                                        <input type="email" name="email" id="email_modal" value="{{ old('email', $pelanggan->email) }}" required
                                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                        @error('email')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Phone -->
                                    <div>
                                        <label for="no_telp_modal" class="block text-sm font-medium text-gray-700">No. Telepon</label>
                                        <input type="text" name="no_telp" id="no_telp_modal" value="{{ old('no_telp', $pelanggan->no_telp) }}"
                                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                        @error('no_telp')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Address -->
                                    <div>
                                        <label for="alamat_modal" class="block text-sm font-medium text-gray-700">Alamat</label>
                                        <textarea name="alamat" id="alamat_modal" rows="3"
                                                  class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">{{ old('alamat', $pelanggan->alamat) }}</textarea>
                                        @error('alamat')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Password Change Section -->
                                    <div class="border-t border-gray-200 pt-4">
                                        <h4 class="text-md font-medium text-gray-900 mb-4">Ubah Password (Opsional)</h4>

                                        <div class="space-y-4">
                                            <div>
                                                <label for="password_modal" class="block text-sm font-medium text-gray-700">Password Baru</label>
                                                <input type="password" name="password" id="password_modal"
                                                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                                @error('password')
                                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                                @enderror
                                            </div>

                                            <div>
                                                <label for="password_confirmation_modal" class="block text-sm font-medium text-gray-700">Konfirmasi Password Baru</label>
                                                <input type="password" name="password_confirmation" id="password_confirmation_modal"
                                                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" form="profile-form" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Simpan Perubahan
                        </button>
                        <button type="button" @click="showEditModal = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Batal
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        // You can add a toast notification here
        alert('Kode referral berhasil disalin!');
    }, function(err) {
        console.error('Could not copy text: ', err);
    });
}
</script>
@endsection
