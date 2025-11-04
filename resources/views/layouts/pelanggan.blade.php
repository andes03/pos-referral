<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'POS & Referral System') }} - Customer Dashboard</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100" x-data="{ showProfileInfo: false }" @click="showProfileInfo = false">
    <div class="min-h-screen flex flex-col">
        <!-- Top Bar -->
        <header class="bg-white shadow-sm px-6 py-4 z-10">
            <div class="flex items-center justify-between">
                <h2 class="text-2xl font-semibold text-gray-900">@yield('title', 'Dashboard')</h2>
                <div class="flex items-center space-x-4">
                    <!-- Clickable Profile Section -->
                    <div class="relative">
                        <button @click.stop="showProfileInfo = !showProfileInfo" class="flex items-center space-x-3 hover:bg-gray-50 px-3 py-2 rounded-lg transition-colors cursor-pointer">
                            @if(Auth::guard('pelanggan')->user()->image)
                                <img src="{{ asset('storage/' . Auth::guard('pelanggan')->user()->image) }}" alt="Profile Image" class="w-10 h-10 rounded-full object-cover">
                            @else
                                <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center">
                                    <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                            @endif
                            <div class="flex flex-col text-left">
                                <span class="text-sm font-medium text-gray-900">{{ Auth::guard('pelanggan')->user()->nama }}</span>
                                <span class="text-xs text-gray-500">{{ Auth::guard('pelanggan')->user()->email }}</span>
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
                                    @if(Auth::guard('pelanggan')->user()->image)
                                        <img src="{{ asset('storage/' . Auth::guard('pelanggan')->user()->image) }}" alt="Profile Image" class="w-12 h-12 rounded-full object-cover">
                                    @else
                                        <div class="w-12 h-12 rounded-full bg-gray-200 flex items-center justify-center">
                                            <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                            </svg>
                                        </div>
                                    @endif
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ Auth::guard('pelanggan')->user()->nama }}</p>
                                        <p class="text-xs text-gray-500">{{ Auth::guard('pelanggan')->user()->email }}</p>
                                    </div>
                                </div>

                                <!-- Quick Info -->
                                <div class="space-y-3">
                                    <div class="flex items-center justify-between">
                                        <span class="text-xs text-gray-500">Telepon</span>
                                        <span class="text-sm text-gray-900">{{ Auth::guard('pelanggan')->user()->no_telp ?: '-' }}</span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <span class="text-xs text-gray-500">Member sejak</span>
                                        <span class="text-sm text-gray-900">{{ Auth::guard('pelanggan')->user()->created_at->format('M Y') }}</span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <span class="text-xs text-gray-500">Kode Referral</span>
                                        <div class="flex items-center space-x-2">
                                            <span class="text-sm font-mono text-gray-900">{{ Auth::guard('pelanggan')->user()->kode_referal }}</span>
                                            <button onclick="copyToClipboard('{{ Auth::guard('pelanggan')->user()->kode_referal }}')" class="text-gray-500 hover:text-gray-700">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Actions -->
                                <div class="mt-4 pt-3 border-t border-gray-200 space-y-2">
                                    <a href="{{ route('pelanggan.profile') }}" class="flex items-center space-x-2 text-sm text-green-600 hover:text-green-800">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                        <span>Edit Profil</span>
                                    </a>
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
        <main class="flex-1 p-6">
            @yield('content')
        </main>


    </div>

    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- html2canvas for card download/save -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

    <!-- jsPDF for PDF generation -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

    <!-- Modal Container -->
    <div id="modal-container"></div>

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
</body>
</html>
