@extends('layouts.pelanggan')

@section('title', 'Dashboard')

@section('content')
<div x-data="{
    activeTab: (() => {
        const hash = window.location.hash.substring(1);
        return hash && ['profile', 'member_card', 'transactions'].includes(hash) ? hash : 'profile';
    })(),
    showEditModal: false
}" x-init="
    // Listen for popstate events (back/forward navigation)
    window.addEventListener('popstate', function(event) {
        const hash = window.location.hash.substring(1);
        if (hash && ['profile', 'member_card', 'transactions'].includes(hash)) {
            activeTab = hash;
        }
    });
" class="space-y-6">
    <!-- Tabs -->
    <div class="bg-white rounded-lg shadow">
        <div class="border-b border-gray-200">
            <nav class="-mb-px flex justify-center space-x-8 px-6" aria-label="Tabs">
                <button @click="activeTab = 'profile'; window.history.pushState(null, null, '#profile')" :class="activeTab === 'profile' ? 'border-green-500 text-green-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    Profil
                </button>
                <button @click="activeTab = 'member_card'; window.history.pushState(null, null, '#member_card')" :class="activeTab === 'member_card' ? 'border-green-500 text-green-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    Kartu Member
                </button>
                <button @click="activeTab = 'transactions'; window.history.pushState(null, null, '#transactions')" :class="activeTab === 'transactions' ? 'border-green-500 text-green-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    Riwayat Transaksi
                </button>
            </nav>
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
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $transaction->detailTransaksi->count() }} item(s)
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('pelanggan.transaction.show', $transaction->id_transaksi) }}" class="text-green-600 hover:text-green-900">Lihat Detail</a>
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



    <!-- Member Card Tab -->
    <div x-show="activeTab === 'member_card'" class="space-y-6">
        <!-- Physical Card Style -->
        <div class="max-w-4xl mx-auto">
            <div id="member-card" class="bg-gradient-to-br from-green-600 via-green-600 to-green-700 rounded-2xl shadow-2xl overflow-hidden transform rotate-1 hover:rotate-0 transition-transform duration-300">
                <div class="relative">
                    <!-- Card Background Pattern -->
                    <div class="absolute inset-0 opacity-20">
                        <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent"></div>
                        <div class="absolute inset-0" style="background-image: radial-gradient(circle at 25% 25%, rgba(255,255,255,0.1) 2px, transparent 2px); background-size: 20px 20px;"></div>
                    </div>

                    <!-- Card Header -->
                    <div class="relative bg-gradient-to-r from-green-400 to-green-500 p-4">
                        <div class="flex items-center justify-center">
                            <div>
                                <h3 class="text-lg font-bold text-gray-800">Sebelas coffee</h3>
                               
                            </div>
                        </div>
                    </div>

                    <!-- Card Body -->
                    <div class="relative p-6 bg-white">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Left Side - Personal Info -->
                            <div class="space-y-4">
                                <div class="flex items-center space-x-4">
                                    @if($pelanggan->image)
                                        <img src="{{ asset('storage/' . $pelanggan->image) }}" alt="Profile Picture" class="w-16 h-16 rounded-full object-cover border-2 border-gray-300 shadow-md">
                                    @else
                                        <div class="w-16 h-16 rounded-full bg-gray-200 flex items-center justify-center border-2 border-gray-300 shadow-md">
                                            <svg class="w-8 h-8 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                            </svg>
                                        </div>
                                    @endif
                                    <div>
                                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Pemegang Kartu</label>
                                        <p class="text-lg font-bold text-gray-900">{{ $pelanggan->nama }}</p>
                                    </div>
                                </div>

                                <div>
                                    <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Email</label>
                                    <p class="text-sm text-gray-700">{{ $pelanggan->email }}</p>
                                </div>

                                <div>
                                    <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Telepon</label>
                                    <p class="text-sm text-gray-700">{{ $pelanggan->no_telp ?: 'Tidak disediakan' }}</p>
                                </div>
                            </div>

                            <!-- Right Side - Card Details -->
                            <div class="space-y-4">
                                <div>
                                    <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Anggota Sejak</label>
                                    <p class="text-sm text-gray-700">{{ $pelanggan->created_at->format('d F Y') }}</p>
                                </div>

                                <div>
                                    <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Kode Referral</label>
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
                                    <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Alamat</label>
                                    <p class="text-sm text-gray-700">{{ $pelanggan->alamat ?: 'Tidak disediakan' }}</p>
                                </div>
                            </div>
                        </div>


                    </div>

                    <!-- Card Footer -->
                    <div class="relative bg-white border-t border-gray-200 p-4">
                        <div class="flex justify-end">
                            <p class="text-sm text-gray-900">Kartu Anggota</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Profile Tab -->
    <div x-show="activeTab === 'profile'" class="space-y-6">
        <!-- Profile Header Card -->
        <div class="bg-white rounded-lg shadow border border-gray-200 overflow-hidden">
            <div class="px-6 py-6">
                <div class="flex flex-col md:flex-row items-center md:items-start space-y-4 md:space-y-0 md:space-x-6">
                    <!-- Profile Avatar -->
                    <div class="relative">
                        @if($pelanggan->image)
                            <img src="{{ asset('storage/' . $pelanggan->image) }}" alt="Profile Picture" class="w-20 h-20 rounded-full object-cover border-2 border-gray-200">
                        @else
                            <div class="w-20 h-20 rounded-full bg-gray-100 flex items-center justify-center border-2 border-gray-200">
                                <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                        @endif
                    </div>

                    <!-- Profile Info -->
                    <div class="flex-1 text-center md:text-left">
                        <h1 class="text-2xl font-bold text-gray-900 mb-1">{{ $pelanggan->nama }}</h1>
                        <p class="text-gray-600 mb-4">{{ $pelanggan->email }}</p>
                    </div>

                    <!-- Action Button -->
                    <div class="md:ml-auto">
                        <a href="{{ route('pelanggan.profile') }}" class="inline-flex items-center px-4 py-2 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition-colors">
                            <x-heroicon-o-pencil class="w-4 h-4 mr-2" />
                            Edit Profil
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Information Cards Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Personal Information Card -->
            <div class="bg-white rounded-lg shadow border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <x-heroicon-o-user class="w-5 h-5 mr-2 text-gray-500" />
                        Informasi Pribadi
                    </h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex items-center justify-between py-3 border-b border-gray-100">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center">
                                <x-heroicon-o-user class="w-4 h-4 text-gray-600" />
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Nama Lengkap</p>
                                <p class="text-gray-900 font-medium">{{ $pelanggan->nama }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-between py-3 border-b border-gray-100">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center">
                                <x-heroicon-o-envelope class="w-4 h-4 text-gray-600" />
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Email</p>
                                <p class="text-gray-900">{{ $pelanggan->email }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-between py-3 border-b border-gray-100">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center">
                                <x-heroicon-o-phone class="w-4 h-4 text-gray-600" />
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">No. Telepon</p>
                                <p class="text-gray-900">{{ $pelanggan->no_telp ?: 'Tidak disediakan' }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-between py-3">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center">
                                <x-heroicon-o-map-pin class="w-4 h-4 text-gray-600" />
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-500">Alamat</p>
                                <p class="text-gray-900">{{ $pelanggan->alamat ?: 'Tidak disediakan' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Account Information Card -->
            <div class="bg-white rounded-lg shadow border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <x-heroicon-o-shield-check class="w-5 h-5 mr-2 text-gray-500" />
                        Informasi Akun
                    </h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-gray-200 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-700">Member Sejak</p>
                                    <p class="text-base font-semibold text-gray-900">{{ $pelanggan->created_at->format('F Y') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-gray-200 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-700">Kode Referral</p>
                                    <div class="flex items-center space-x-2 mt-1">
                                        <span class="bg-white px-3 py-1 rounded text-sm font-mono text-gray-800 border">{{ $pelanggan->kode_referal }}</span>
                                        <button onclick="copyToClipboard('{{ $pelanggan->kode_referal }}')" class="p-1.5 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded transition-colors" title="Salin Kode">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!-- Edit Profile Modal -->
        <div x-show="showEditModal" x-cloak
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-50 overflow-y-auto"
             aria-labelledby="modal-title"
             role="dialog"
             aria-modal="true">

            <!-- Backdrop -->
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="showEditModal = false" aria-hidden="true"></div>

                <!-- Modal panel -->
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4" id="modal-title">
                                    Edit Profile
                                </h3>

                                <form id="profile-form" action="{{ route('pelanggan.profile.update') }}" method="POST" class="space-y-4" onsubmit="submitProfileForm(event)">
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

                                    <!-- Profile Image -->
                                    <div>
                                        <label for="image_modal" class="block text-sm font-medium text-gray-700">Foto Profil</label>
                                        <input type="file" name="image" id="image_modal" accept="image/*"
                                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                        @error('image')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                        <p class="mt-1 text-sm text-gray-500">Format: JPG, PNG, GIF. Maksimal 2MB.</p>
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
                        <button type="submit" form="profile-form" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm">
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

function submitProfileForm(event) {
    event.preventDefault();

    const form = event.target;
    const formData = new FormData(form);

    fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Profil berhasil diperbarui!');
            location.reload();
        } else {
            alert('Terjadi kesalahan saat memperbarui profil.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat memperbarui profil.');
    });
}






</script>
@endsection
