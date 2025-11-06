@extends('layouts.app')

@section('content')
<style>
    input:-webkit-autofill,
    input:-webkit-autofill:hover, 
    input:-webkit-autofill:focus, 
    input:-webkit-autofill:active,
    input:-internal-autofill-selected {
        appearance: menulist;
        background-image: none !important;
        background-color: rgba(255, 255, 255, 0.2) !important;
        -webkit-box-shadow: 0 0 0 1000px rgba(255, 255, 255, 0.2) inset !important;
        box-shadow: 0 0 0 1000px rgba(255, 255, 255, 0.2) inset !important;
        -webkit-text-fill-color: #ffffff !important;
        color: #ffffff !important;
        transition: background-color 5000s ease-in-out 0s;
    }
    input::selection {
        background: rgba(255, 255, 255, 0.2) !important;
        color: #ffffff !important;
    }
    input:-webkit-autofill::placeholder {
        color: #d1d5db !important;
    }
</style>
<div class="min-h-screen bg-gradient-to-br from-green-800 via-green-700 to-emerald-900 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <!-- Header -->
        <div class="text-center">
            <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center mx-auto mb-4 backdrop-blur-sm">
                <svg class="w-8 h-8 text-white" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M18 8h1a4 4 0 0 1 0 8h-1"></path>
                    <path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"></path>
                    <line x1="6" y1="1" x2="6" y2="4"></line>
                    <line x1="10" y1="1" x2="10" y2="4"></line>
                    <line x1="14" y1="1" x2="14" y2="4"></line>
                </svg>
            </div>
            <h2 class="text-3xl font-bold text-white mb-2">
                Sebelas Coffee
            </h2>
            <p class="text-green-100 text-sm">
                Bergabunglah dengan program loyalitas kami
            </p>
        </div>

        <!-- Back to Home Icon -->
        <div class="flex justify-start mb-6">
            <a href="/" class="text-green-100 hover:text-white transition-colors duration-300 group">
                <x-heroicon-o-arrow-left class="w-6 h-6 group-hover:-translate-x-1 transition-transform duration-300" />
            </a>
        </div>

        <!-- Register Form -->
        <div class="bg-white/10 backdrop-blur-lg rounded-2xl p-8 shadow-2xl border border-white/20">
            <form class="space-y-6" action="{{ route('register') }}" method="POST">
                @csrf

                <!-- Nama Field -->
                <div>
                    <label for="nama" class="block text-sm font-medium text-green-100 mb-2">
                        <x-heroicon-o-user class="w-4 h-4 inline mr-2" />
                        Nama Lengkap
                    </label>
                    <input id="nama" name="nama" type="text" required
                           class="w-full px-4 py-3 bg-white/20 border border-white/30 rounded-xl text-white placeholder-green-200 focus:outline-none focus:ring-2 focus:ring-white/50 focus:border-white/50 transition-all duration-300"
                           placeholder="Masukkan nama lengkap Anda" value="{{ old('nama') }}">
                    @error('nama')
                        <p class="mt-1 text-sm text-red-300 flex items-center">
                            <x-heroicon-o-exclamation-triangle class="w-4 h-4 mr-1" />
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Email Field -->
                <div>
                    <label for="email" class="block text-sm font-medium text-green-100 mb-2">
                        <x-heroicon-o-envelope class="w-4 h-4 inline mr-2" />
                        Email
                    </label>
                    <input id="email" name="email" type="email" autocomplete="email" required
                           class="w-full px-4 py-3 bg-white/20 border border-white/30 rounded-xl text-white placeholder-green-200 focus:outline-none focus:ring-2 focus:ring-white/50 focus:border-white/50 transition-all duration-300"
                           placeholder="Masukkan email Anda" value="{{ old('email') }}">
                    @error('email')
                        <p class="mt-1 text-sm text-red-300 flex items-center">
                            <x-heroicon-o-exclamation-triangle class="w-4 h-4 mr-1" />
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- No Telp Field -->
                <div>
                    <label for="no_telp" class="block text-sm font-medium text-green-100 mb-2">
                        <x-heroicon-o-device-phone-mobile class="w-4 h-4 inline mr-2" />
                        Nomor Telepon
                    </label>
                    <input id="no_telp" name="no_telp" type="text" required
                           class="w-full px-4 py-3 bg-white/20 border border-white/30 rounded-xl text-white placeholder-green-200 focus:outline-none focus:ring-2 focus:ring-white/50 focus:border-white/50 transition-all duration-300"
                           placeholder="Masukkan nomor telepon Anda" value="{{ old('no_telp') }}">
                    @error('no_telp')
                        <p class="mt-1 text-sm text-red-300 flex items-center">
                            <x-heroicon-o-exclamation-triangle class="w-4 h-4 mr-1" />
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Alamat Field -->
                <div>
                    <label for="alamat" class="block text-sm font-medium text-green-100 mb-2">
                        <x-heroicon-o-map-pin class="w-4 h-4 inline mr-2" />
                        Alamat
                    </label>
                    <textarea id="alamat" name="alamat" rows="3" required
                              class="w-full px-4 py-3 bg-white/20 border border-white/30 rounded-xl text-white placeholder-green-200 focus:outline-none focus:ring-2 focus:ring-white/50 focus:border-white/50 transition-all duration-300 resize-none"
                              placeholder="Masukkan alamat lengkap Anda">{{ old('alamat') }}</textarea>
                    @error('alamat')
                        <p class="mt-1 text-sm text-red-300 flex items-center">
                            <x-heroicon-o-exclamation-triangle class="w-4 h-4 mr-1" />
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Password Field -->
                <div>
                    <label for="password" class="block text-sm font-medium text-green-100 mb-2">
                        <x-heroicon-o-lock-closed class="w-4 h-4 inline mr-2" />
                        Kata Sandi
                    </label>
                    <input id="password" name="password" type="password" required
                           class="w-full px-4 py-3 bg-white/20 border border-white/30 rounded-xl text-white placeholder-green-200 focus:outline-none focus:ring-2 focus:ring-white/50 focus:border-white/50 transition-all duration-300"
                           placeholder="Buat kata sandi yang kuat">
                    @error('password')
                        <p class="mt-1 text-sm text-red-300 flex items-center">
                            <x-heroicon-o-exclamation-triangle class="w-4 h-4 mr-1" />
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Password Confirmation Field -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-green-100 mb-2">
                        <x-heroicon-o-lock-closed class="w-4 h-4 inline mr-2" />
                        Konfirmasi Kata Sandi
                    </label>
                    <input id="password_confirmation" name="password_confirmation" type="password" required
                           class="w-full px-4 py-3 bg-white/20 border border-white/30 rounded-xl text-white placeholder-green-200 focus:outline-none focus:ring-2 focus:ring-white/50 focus:border-white/50 transition-all duration-300"
                           placeholder="Konfirmasi kata sandi Anda">
                    @error('password_confirmation')
                        <p class="mt-1 text-sm text-red-300 flex items-center">
                            <x-heroicon-o-exclamation-triangle class="w-4 h-4 mr-1" />
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Error Messages -->
                @if ($errors->any() && !$errors->has(['nama', 'email', 'no_telp', 'alamat', 'password', 'password_confirmation']))
                    <div class="bg-red-500/20 border border-red-400/50 rounded-xl p-4">
                        <div class="flex">
                            <x-heroicon-o-exclamation-triangle class="w-5 h-5 text-red-400 mr-3 flex-shrink-0" />
                            <div>
                                <h3 class="text-sm font-medium text-red-400">
                                    Ada kesalahan dalam pengisian:
                                </h3>
                                <div class="mt-2 text-sm text-red-300">
                                    <ul role="list" class="list-disc pl-5 space-y-1">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Submit Button -->
                <div>
                    <button type="submit"
                            class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-xl text-white bg-white/20 hover:bg-white/30 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-white/50 transition-all duration-300 transform hover:scale-105 hover:shadow-lg">
                        <span class="absolute left-0 inset-y-0 flex items-center pl-4">
                            <x-heroicon-o-user-plus class="w-5 h-5 text-white group-hover:rotate-12 transition-transform duration-300" />
                        </span>
                        Buat Akun
                    </button>
                </div>

                <!-- Login Link -->
                <div class="text-center">
                    <p class="text-sm text-green-100">
                        Sudah memiliki akun?
                        <a href="{{ route('login') }}" class="font-medium text-white hover:text-green-200 transition-colors duration-300 underline">
                            Masuk di sini
                        </a>
                    </p>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
