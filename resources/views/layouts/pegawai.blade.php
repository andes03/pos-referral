<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'POS & Referral System') }} - Pegawai Dashboard</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100" x-data="{ showProfileInfo: false }" @click="showProfileInfo = false">
    <div class="min-h-screen flex flex-col">
        <!-- Sidebar -->
        <div class="fixed left-0 top-0 h-screen w-64 bg-gradient-to-br from-green-800 via-green-700 to-emerald-900 shadow-2xl">
            <!-- Header -->
            <div class="flex items-center justify-center h-24 bg-gradient-to-r from-green-900 to-emerald-900 border-b border-green-700/50">
                <div class="text-center">
                    <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center mx-auto mb-2 backdrop-blur-sm">
                        <svg class="w-6 h-6 text-white" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M18 8h1a4 4 0 0 1 0 8h-1"></path>
                            <path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"></path>
                            <line x1="6" y1="1" x2="6" y2="4"></line>
                            <line x1="10" y1="1" x2="10" y2="4"></line>
                            <line x1="14" y1="1" x2="14" y2="4"></line>
                        </svg>
                    </div>
                    <h1 class="text-white text-lg font-bold tracking-wide">Sebelas Coffee</h1>
                </div>
            </div>

            <!-- Navigation -->
            <nav class="mt-8 px-4 space-y-2">
                <a href="{{ route('pegawai.dashboard') }}" class="group flex items-center px-4 py-3 text-green-100 hover:bg-white/10 hover:text-white rounded-xl transition-all duration-300 ease-in-out transform hover:scale-105 hover:shadow-lg {{ request()->routeIs('pegawai.dashboard') ? 'bg-white/20 text-white shadow-lg scale-105' : '' }}">
                    <x-heroicon-o-home class="w-5 h-5 mr-4 group-hover:rotate-12 transition-transform duration-300" />
                    <span class="font-medium">Dashboard</span>
                    @if(request()->routeIs('pegawai.dashboard'))
                        <div class="ml-auto w-2 h-2 bg-white rounded-full animate-pulse"></div>
                    @endif
                </a>

                @if(Auth::guard('pegawai')->user()->role === 'admin')
                <a href="{{ route('pegawai.pegawai.index') }}" class="group flex items-center px-4 py-3 text-green-100 hover:bg-white/10 hover:text-white rounded-xl transition-all duration-300 ease-in-out transform hover:scale-105 hover:shadow-lg {{ request()->routeIs('pegawai.pegawai.*') ? 'bg-white/20 text-white shadow-lg scale-105' : '' }}">
                    <x-heroicon-o-users class="w-5 h-5 mr-4 group-hover:rotate-12 transition-transform duration-300" />
                    <span class="font-medium">Pegawai</span>
                    @if(request()->routeIs('pegawai.pegawai.*'))
                        <div class="ml-auto w-2 h-2 bg-white rounded-full animate-pulse"></div>
                    @endif
                </a>
                @endif

                <a href="{{ route('pegawai.pelanggan.index') }}" class="group flex items-center px-4 py-3 text-green-100 hover:bg-white/10 hover:text-white rounded-xl transition-all duration-300 ease-in-out transform hover:scale-105 hover:shadow-lg {{ request()->routeIs('pegawai.pelanggan.*') ? 'bg-white/20 text-white shadow-lg scale-105' : '' }}">
                    <x-heroicon-o-user-group class="w-5 h-5 mr-4 group-hover:rotate-12 transition-transform duration-300" />
                    <span class="font-medium">Pelanggan</span>
                    @if(request()->routeIs('pegawai.pelanggan.*'))
                        <div class="ml-auto w-2 h-2 bg-white rounded-full animate-pulse"></div>
                    @endif
                </a>

                <a href="{{ route('pegawai.kategori.index') }}" class="group flex items-center px-4 py-3 text-green-100 hover:bg-white/10 hover:text-white rounded-xl transition-all duration-300 ease-in-out transform hover:scale-105 hover:shadow-lg {{ request()->routeIs('pegawai.kategori.*') ? 'bg-white/20 text-white shadow-lg scale-105' : '' }}">
                    <x-heroicon-o-folder class="w-5 h-5 mr-4 group-hover:rotate-12 transition-transform duration-300" />
                    <span class="font-medium">Kategori</span>
                    @if(request()->routeIs('pegawai.kategori.*'))
                        <div class="ml-auto w-2 h-2 bg-white rounded-full animate-pulse"></div>
                    @endif
                </a>

                <a href="{{ route('pegawai.produk.index') }}" class="group flex items-center px-4 py-3 text-green-100 hover:bg-white/10 hover:text-white rounded-xl transition-all duration-300 ease-in-out transform hover:scale-105 hover:shadow-lg {{ request()->routeIs('pegawai.produk.*') ? 'bg-white/20 text-white shadow-lg scale-105' : '' }}">
                    <x-heroicon-o-cube class="w-5 h-5 mr-4 group-hover:rotate-12 transition-transform duration-300" />
                    <span class="font-medium">Produk</span>
                    @if(request()->routeIs('pegawai.produk.*'))
                        <div class="ml-auto w-2 h-2 bg-white rounded-full animate-pulse"></div>
                    @endif
                </a>

                <a href="{{ route('pegawai.transaksi.index') }}" class="group flex items-center px-4 py-3 text-green-100 hover:bg-white/10 hover:text-white rounded-xl transition-all duration-300 ease-in-out transform hover:scale-105 hover:shadow-lg {{ request()->routeIs('pegawai.transaksi.*') ? 'bg-white/20 text-white shadow-lg scale-105' : '' }}">
                    <x-heroicon-o-document-text class="w-5 h-5 mr-4 group-hover:rotate-12 transition-transform duration-300" />
                    <span class="font-medium">Transaksi</span>
                    @if(request()->routeIs('pegawai.transaksi.*'))
                        <div class="ml-auto w-2 h-2 bg-white rounded-full animate-pulse"></div>
                    @endif
                </a>
            </nav>


        </div>

        <!-- Main Content -->
        <div class="ml-64 flex-1 flex flex-col h-screen overflow-y-auto">
            <!-- Top Bar -->
            <header class="fixed top-0 left-64 right-0 bg-white shadow-sm px-6 py-2 z-10">
                <div class="flex items-center justify-end">
                    <div class="flex items-center space-x-4">
                        <!-- Clickable Profile Section -->
                        <div class="relative">
                            <button @click.stop="showProfileInfo = !showProfileInfo" class="flex items-center space-x-3 hover:bg-gray-50 px-3 py-2 rounded-lg transition-colors cursor-pointer">
                                @if(Auth::guard('pegawai')->user()->image)
                                    <img src="{{ asset('storage/' . Auth::guard('pegawai')->user()->image) }}" alt="Profile Image" class="w-10 h-10 rounded-full object-cover">
                                @else
                                    <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center">
                                        <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                    </div>
                                @endif
                                <div class="flex flex-col text-left">
                                    <span class="text-sm font-medium text-gray-900">{{ Auth::guard('pegawai')->user()->nama }}</span>
                                    <span class="text-xs text-gray-500">{{ Auth::guard('pegawai')->user()->email }}</span>
                                </div>
                                <svg class="w-4 h-4 text-gray-400 transition-transform" :class="showProfileInfo ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>

                            <!-- Profile Dropdown Popup -->
                            <div x-show="showProfileInfo" @click.stop x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-lg border border-gray-200 z-50" style="display: none;">
                                <div class="p-4">
                                    <!-- Profile Header -->
                                    <div class="flex items-center space-x-3 mb-4 pb-3 border-b border-gray-200">
                                        @if(Auth::guard('pegawai')->user()->image)
                                            <img src="{{ asset('storage/' . Auth::guard('pegawai')->user()->image) }}" alt="Profile Image" class="w-12 h-12 rounded-full object-cover">
                                        @else
                                            <div class="w-12 h-12 rounded-full bg-gray-200 flex items-center justify-center">
                                                <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                </svg>
                                            </div>
                                        @endif
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">{{ Auth::guard('pegawai')->user()->nama }}</p>
                                            <p class="text-xs text-gray-500">{{ Auth::guard('pegawai')->user()->email }}</p>
                                        </div>
                                    </div>

                                    <!-- Quick Info -->
                                    <div class="space-y-3">
                                        <div class="flex items-center justify-between">
                                            <span class="text-xs text-gray-500">Role</span>
                                            <span class="text-sm text-gray-900">{{ ucfirst(Auth::guard('pegawai')->user()->role) }}</span>
                                        </div>
                                       
                                    </div>

                                    <!-- Actions -->
                                    <div class="mt-4 pt-3 border-t border-gray-200 space-y-2">
                                        <form action="{{ route('logout') }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="flex items-center space-x-2 text-sm text-red-600 hover:text-red-800 w-full text-left">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                                </svg>
                                                <span>Logout</span>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 p-6 pt-20">
                @yield('content')
            </main>

            <!-- Footer -->
            <footer class="bg-white border-t border-gray-200 mt-auto">
                <div class="px-6 py-4">
                    <div class="text-center">
                        <div class="text-sm text-gray-500">
                            © {{ date('Y') }} Sebelas Coffee. All rights reserved.
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Modal Container -->
    <div id="modal-container"></div>
</body>
</html>
