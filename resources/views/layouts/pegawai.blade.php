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
        <div class="fixed left-0 top-0 h-screen w-64 bg-gray-800 shadow-lg">
            <div class="flex items-center justify-center h-16 bg-gray-900">
                <h1 class="text-white text-lg font-semibold">POS System</h1>
            </div>
            <nav class="mt-8">
                <a href="{{ route('pegawai.dashboard') }}" class="flex items-center px-6 py-3 text-gray-300 hover:bg-gray-700 hover:text-white {{ request()->routeIs('pegawai.dashboard') ? 'bg-gray-700 text-white' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v2H8V5z"></path>
                    </svg>
                    Dashboard
                </a>

                @if(Auth::guard('pegawai')->user()->role === 'admin')
                <a href="{{ route('pegawai.pegawai.index') }}" class="flex items-center px-6 py-3 text-gray-300 hover:bg-gray-700 hover:text-white {{ request()->routeIs('pegawai.pegawai.*') ? 'bg-gray-700 text-white' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                    </svg>
                    Pegawai
                </a>
                @endif

                <a href="{{ route('pegawai.pelanggan.index') }}" class="flex items-center px-6 py-3 text-gray-300 hover:bg-gray-700 hover:text-white {{ request()->routeIs('pegawai.pelanggan.*') ? 'bg-gray-700 text-white' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    Pelanggan
                </a>

                <a href="{{ route('pegawai.kategori.index') }}" class="flex items-center px-6 py-3 text-gray-300 hover:bg-gray-700 hover:text-white {{ request()->routeIs('pegawai.kategori.*') ? 'bg-gray-700 text-white' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                    Kategori
                </a>

                <a href="{{ route('pegawai.produk.index') }}" class="flex items-center px-6 py-3 text-gray-300 hover:bg-gray-700 hover:text-white {{ request()->routeIs('pegawai.produk.*') ? 'bg-gray-700 text-white' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                    Produk
                </a>

                <a href="{{ route('pegawai.transaksi.index') }}" class="flex items-center px-6 py-3 text-gray-300 hover:bg-gray-700 hover:text-white {{ request()->routeIs('pegawai.transaksi.*') ? 'bg-gray-700 text-white' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                    </svg>
                    Transaksi
                </a>

            </nav>

            <div class="absolute bottom-0 w-64 p-4">
                <form action="{{ route('logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 hover:text-white w-full rounded">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                        Logout
                    </button>
                </form>
            </div>
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
