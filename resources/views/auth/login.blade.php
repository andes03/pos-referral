@extends('layouts.app')

@section('content')
<style>
    input:-webkit-autofill,
    input:-webkit-autofill:hover, 
    input:-webkit-autofill:focus, 
    input:-webkit-autofill:active,
    input:-internal-autofill-selected {
        -webkit-appearance: textfield !important;
        appearance: textfield !important;
        background-color: rgba(255, 255, 255, 0.2) !important;
        -webkit-box-shadow: 0 0 0 30px rgba(255, 255, 255, 0.2) inset !important;
        box-shadow: 0 0 0 30px rgba(255, 255, 255, 0.2) inset !important;
        -webkit-text-fill-color: #ffffff !important;
        color: #ffffff !important;
        border: 1px solid rgba(255, 255, 255, 0.3) !important;
        transition: background-color 5000s ease-in-out 0s;
    }
    input:-webkit-autofill::first-line {
        -webkit-text-fill-color: #ffffff !important;
    }
    input::selection {
        background: rgba(255, 255, 255, 0.2) !important;
        color: #ffffff !important;
    }
    input:-webkit-autofill::placeholder {
        color: #d1d5db !important;
    }
    input:focus {
        background-color: rgba(255, 255, 255, 0.2) !important;
    }
</style>
<div class="min-h-screen bg-gradient-to-br from-green-800 via-green-700 to-emerald-900 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <!-- Header -->
        <div class="text-center">
            <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center mx-auto mb-4 backdrop-blur-sm">
                <x-heroicon-o-shopping-bag class="w-8 h-8 text-white" />
            </div>
            <h2 class="text-3xl font-bold text-white mb-2">
                Sebelas Coffee
            </h2>
            <p class="text-green-100 text-sm">
                Masuk ke akun Anda
            </p>
        </div>

        <!-- Back to Home Icon -->
        <div class="flex justify-start mb-6">
            <a href="/" class="text-green-100 hover:text-white transition-colors duration-300 group">
                <x-heroicon-o-arrow-left class="w-6 h-6 group-hover:-translate-x-1 transition-transform duration-300" />
            </a>
        </div>

        <!-- Login Form -->
        <div class="bg-white/10 backdrop-blur-lg rounded-2xl p-8 shadow-2xl border border-white/20">
            <form class="space-y-6" action="{{ route('login') }}" method="POST">
                @csrf

                <!-- Email Field -->
                <div>
                    <label for="email" class="block text-sm font-medium text-green-100 mb-2">
                        <x-heroicon-o-envelope class="w-4 h-4 inline mr-2" />
                        Email
                    </label>
                    <input id="email" name="email" type="email" autocomplete="email" required
                           class="w-full px-4 py-3 bg-white/20 border border-white/30 rounded-xl text-white placeholder-green-200 focus:outline-none focus:ring-2 focus:ring-white/50 focus:border-white/50 transition-all duration-300"
                           placeholder="Masukkan email Anda" value="{{ old('email') }}">
                </div>

                <!-- Password Field -->
                <div>
                    <label for="password" class="block text-sm font-medium text-green-100 mb-2">
                        <x-heroicon-o-lock-closed class="w-4 h-4 inline mr-2" />
                        Password
                    </label>
                    <input id="password" name="password" type="password" autocomplete="current-password" required
                           class="w-full px-4 py-3 bg-white/20 border border-white/30 rounded-xl text-white placeholder-green-200 focus:outline-none focus:ring-2 focus:ring-white/50 focus:border-white/50 transition-all duration-300"
                           placeholder="Masukkan password Anda">
                </div>

                <!-- Error Messages -->
                @if ($errors->any())
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
                            <x-heroicon-o-arrow-right-on-rectangle class="w-5 h-5 text-white group-hover:rotate-12 transition-transform duration-300" />
                        </span>
                        Masuk
                    </button>
                </div>

                <!-- Register Link -->
                <div class="text-center">
                    <p class="text-sm text-green-100">
                        Belum punya akun?
                        <a href="{{ route('register') }}" class="font-medium text-white hover:text-green-200 transition-colors duration-300 underline">
                            Daftar di sini
                        </a>
                    </p>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
