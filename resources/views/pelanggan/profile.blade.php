@extends('layouts.pelanggan')

@section('title', 'Profile')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8 flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Profile Pengguna</h1>
                <p class="mt-2 text-sm text-gray-600">Kelola informasi pribadi dan pengaturan akun Anda</p>
            </div>
            <a href="{{ route('pelanggan.dashboard') }}" class="px-4 py-2 bg-green-200 text-green-700 rounded-lg hover:bg-green-300 transition-colors">
                Kembali
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Profile Form -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-sm border border-green-200 overflow-hidden">
                    <div class="px-6 py-4 bg-white border-b border-green-200">
                        <h3 class="text-lg font-semibold text-green-900">Informasi Pribadi</h3>
                        <p class="text-green-600 text-sm">Perbarui data diri Anda</p>
                    </div>

                    <form action="{{ route('pelanggan.profile.update') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
                        @csrf
                        @method('PUT')

                        <!-- Profile Image -->
                        <div class="flex items-center space-x-6">
                            <div class="flex-shrink-0">
                                <img id="imagePreview" data-original-src="{{ $pelanggan->image ? asset('storage/' . $pelanggan->image) : '' }}" src="{{ $pelanggan->image ? asset('storage/' . $pelanggan->image) : '' }}" alt="Profile Picture" class="w-20 h-20 rounded-full object-cover border-4 border-white shadow-lg {{ $pelanggan->image ? '' : 'hidden' }}">
                                <div id="imagePlaceholder" class="w-20 h-20 rounded-full bg-green-300 flex items-center justify-center border-4 border-white shadow-lg {{ $pelanggan->image ? 'hidden' : '' }}">
                                    <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="flex-1">
                                <label for="image" class="block text-sm font-medium text-gray-700 mb-2">Foto Profil</label>
                                <input type="file" name="image" id="image" accept="image/*" onchange="previewImage(event)"
                                       class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200 transition-colors">
                                <p class="mt-1 text-xs text-gray-500">Format: JPG, PNG, GIF. Maksimal 2MB.</p>
                                @error('image')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Personal Information Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Name -->
                            <div class="md:col-span-2">
                                <label for="nama" class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                                <input type="text" name="nama" id="nama" value="{{ old('nama', $pelanggan->nama) }}" required
                                       class="block w-full px-4 py-3 border border-green-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors">
                                @error('nama')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                                <input type="email" name="email" id="email" value="{{ old('email', $pelanggan->email) }}" required
                                       class="block w-full px-4 py-3 border border-green-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors">
                                @error('email')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Phone -->
                            <div>
                                <label for="no_telp" class="block text-sm font-medium text-gray-700 mb-2">No. Telepon</label>
                                <input type="text" name="no_telp" id="no_telp" value="{{ old('no_telp', $pelanggan->no_telp) }}"
                                       class="block w-full px-4 py-3 border border-green-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors">
                                @error('no_telp')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Address -->
                        <div>
                            <label for="alamat" class="block text-sm font-medium text-gray-700 mb-2">Alamat Lengkap</label>
                            <textarea name="alamat" id="alamat" rows="4"
                                      class="block w-full px-4 py-3 border border-green-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors resize-none">{{ old('alamat', $pelanggan->alamat) }}</textarea>
                            @error('alamat')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Password Change (Optional) -->
                        <div class="border-t border-green-200 pt-6">
                            <h4 class="text-sm font-medium text-gray-900 mb-4">Ubah Password (Opsional)</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password Baru</label>
                                    <input type="password" name="password" id="password"
                                           class="block w-full px-4 py-3 border border-green-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors">
                                    @error('password')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi Password</label>
                                    <input type="password" name="password_confirmation" id="password_confirmation"
                                           class="block w-full px-4 py-3 border border-green-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors">
                                    @error('password_confirmation')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex justify-end pt-4">
                            <button type="submit" class="px-6 py-3 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-all duration-200 shadow-sm">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Account Information Sidebar -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-sm border border-green-200 overflow-hidden">
                    <div class="px-6 py-4 bg-white border-b border-green-200">
                        <h3 class="text-lg font-semibold text-green-900">Informasi Akun</h3>
                        <p class="text-green-600 text-sm">Ringkasan akun Anda</p>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="bg-green-50 rounded-lg p-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-green-900">Kode Referral</p>
                                </div>
                                <span class="text-sm font-mono bg-white px-3 py-1 rounded-md border">{{ $pelanggan->kode_referal }}</span>
                            </div>
                        </div>

                        <div class="bg-green-50 rounded-lg p-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-green-900">Bergabung Sejak</p>
                                </div>
                                <span class="text-sm font-medium text-green-800">{{ $pelanggan->created_at->format('d M Y') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
