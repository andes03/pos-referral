@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-white">
    <!-- Navigation -->
    <nav class="fixed w-full bg-white/95 backdrop-blur-md shadow-sm z-50 transition-all duration-300" id="navbar">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-green-600 to-emerald-800 rounded-full flex items-center justify-center">
                        <span class="text-white font-bold text-xl">11</span>
                    </div>
                    <h1 class="text-2xl font-bold bg-gradient-to-r from-emerald-800 to-green-600 bg-clip-text text-transparent">
                        Sebelas Coffee
                    </h1>
                </div>
                <div class="hidden md:flex items-center space-x-8">
                    <a href="#hero" class="text-gray-700 hover:text-green-600 transition-colors font-medium">Home</a>
                    <a href="#about" class="text-gray-700 hover:text-green-600 transition-colors font-medium">Tentang</a>
                    <a href="#menu" class="text-gray-700 hover:text-green-600 transition-colors font-medium">Menu</a>
                    <a href="#contact" class="text-gray-700 hover:text-green-600 transition-colors font-medium">Kontak</a>
                </div>
                <div class="flex items-center space-x-3">
                    <a href="{{ route('login') }}" class="text-gray-700 hover:text-green-600 px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                        Login
                    </a>
                    <a href="{{ route('register') }}" class="bg-gradient-to-r from-green-600 to-emerald-700 hover:from-green-700 hover:to-emerald-800 text-white px-6 py-2 rounded-lg text-sm font-medium transition-all duration-300 shadow-md hover:shadow-lg">
                        Daftar
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="hero" class="relative h-screen flex items-center justify-center overflow-hidden">
        <!-- Background Images with Parallax Effect -->
        <div class="absolute inset-0 w-full h-full">
            <div class="absolute inset-0 bg-gradient-to-r from-black/70 via-black/50 to-black/70 z-10"></div>
            <img src="{{ asset('bg1.jpeg') }}" alt="Coffee Background" class="absolute inset-0 w-full h-full object-cover opacity-80" id="bg-image">
        </div>
        
        <!-- Hero Content -->
        <div class="relative z-20 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="animate-fade-in-up">
                <h1 class="text-5xl md:text-7xl font-bold text-white mb-6 leading-tight">
                    Nikmati Setiap Tetes
                    <span class="block text-green-300 mt-2">Kopi Pilihan Kami</span>
                </h1>
                <p class="text-xl md:text-2xl text-gray-200 mb-10 max-w-3xl mx-auto">
                    Pengalaman kopi terbaik dengan biji pilihan dan racikan sempurna untuk setiap momen istimewa Anda
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                    <a href="#menu" class="group bg-gradient-to-r from-green-600 to-emerald-700 hover:from-green-700 hover:to-emerald-800 text-white px-10 py-4 rounded-full text-lg font-semibold transition-all duration-300 shadow-xl hover:shadow-2xl hover:scale-105 flex items-center space-x-2">
                        <span>Lihat Menu</span>
                        <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                        </svg>
                    </a>
                    <a href="{{ route('register') }}" class="bg-white/10 backdrop-blur-md hover:bg-white/20 text-white px-10 py-4 rounded-full text-lg font-semibold border-2 border-white/30 transition-all duration-300 hover:scale-105">
                        Gabung Sekarang
                    </a>
                </div>
            </div>
        </div>

        <!-- Scroll Indicator -->
        <div class="absolute bottom-10 left-1/2 transform -translate-x-1/2 z-20 animate-bounce">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
            </svg>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="py-24 bg-gradient-to-b from-white to-green-50/30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-2 gap-16 items-center">
                <div class="space-y-6">
                    <h2 class="text-4xl md:text-5xl font-bold text-gray-900 leading-tight">
                        Cerita di Balik
                        <span class="block text-green-600">Sebelas Coffee</span>
                    </h2>
                    <p class="text-lg text-gray-600 leading-relaxed">
                        kami telah berkomitmen menghadirkan pengalaman kopi terbaik dengan memilih biji kopi pilihan dari berbagai daerah di Indonesia. Setiap cangkir adalah hasil dari dedikasi dan passion kami terhadap kopi.
                    </p>
                    <div class="grid grid-cols-3 gap-6 pt-6">
                        
                    </div>
                </div>
                <div class="relative">
                    <div class="absolute -inset-4 bg-gradient-to-r from-green-400 to-emerald-600 rounded-3xl blur-2xl opacity-20"></div>
                    <img src="{{ asset('bg2.jpeg') }}" alt="About Coffee" class="relative rounded-3xl shadow-2xl w-full h-[500px] object-cover">
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">Mengapa Memilih Kami?</h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">Pengalaman kopi yang berbeda dengan layanan terbaik</p>
            </div>
            <div class="grid md:grid-cols-3 gap-8">
                <div class="group p-8 rounded-2xl bg-gradient-to-br from-green-50 to-white hover:shadow-xl transition-all duration-300 border border-green-100 hover:border-green-300">
                    <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Biji Kopi Pilihan</h3>
                    <p class="text-gray-600">Kami menggunakan biji kopi premium yang dipilih langsung dari petani lokal terbaik</p>
                </div>
                <div class="group p-8 rounded-2xl bg-gradient-to-br from-green-50 to-white hover:shadow-xl transition-all duration-300 border border-green-100 hover:border-green-300">
                    <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Racikan Sempurna</h3>
                    <p class="text-gray-600">Setiap cangkir dibuat dengan teknik brewing yang tepat dan konsisten</p>
                </div>
                <div class="group p-8 rounded-2xl bg-gradient-to-br from-green-50 to-white hover:shadow-xl transition-all duration-300 border border-green-100 hover:border-green-300">
                    <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Program Referral</h3>
                    <p class="text-gray-600">Dapatkan reward menarik dengan menukarkan kode referal</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Menu Section -->
    <section id="menu" class="py-24 bg-gradient-to-b from-green-50/30 to-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">Menu Spesial Kami</h2>
                <p class="text-lg text-gray-600">Temukan kopi favorit Anda dari koleksi menu pilihan kami</p>
            </div>

            @if($products->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($products as $product)
                <div class="group bg-white rounded-2xl shadow-md hover:shadow-2xl transition-all duration-300 overflow-hidden border border-gray-100 hover:border-green-200 hover:-translate-y-2">
                    <div class="relative aspect-square overflow-hidden bg-gradient-to-br from-green-50 to-gray-50">
                        @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->nama }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                        @else
                        <div class="w-full h-full bg-gradient-to-br from-green-100 to-emerald-50 flex items-center justify-center">
                            <svg class="w-24 h-24 text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                        </div>
                        @endif
                        <div class="absolute top-4 right-4 bg-white/90 backdrop-blur-sm px-3 py-1 rounded-full">
                            <span class="text-xs font-semibold text-green-600">{{ $product->kategori->nama_kategori ?? 'Menu' }}</span>
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-2 group-hover:text-green-600 transition-colors">{{ $product->nama }}</h3>
                        <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ $product->deskripsi ?: 'Nikmati cita rasa kopi yang sempurna' }}</p>
                        <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                            <div>
                                <span class="text-2xl font-bold text-green-600">Rp {{ number_format($product->harga, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="px-3 py-1 bg-green-50 text-green-700 text-sm font-medium rounded-lg">
                                    Stok: {{ $product->stok }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-20">
                <div class="w-24 h-24 bg-green-50 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-12 h-12 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-2">Menu Segera Hadir</h3>
                <p class="text-gray-600">Kami sedang menyiapkan menu-menu terbaik untuk Anda</p>
            </div>
            @endif
        </div>
    </section>

    <!-- CTA Section -->
    <section id="contact" class="relative py-24 overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-r from-green-900 via-emerald-800 to-green-900"></div>
        <div class="absolute inset-0 opacity-10">
            <img src="{{ asset('bg1.jpeg') }}" alt="Background" class="w-full h-full object-cover">
        </div>
        <div class="relative z-10 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-4xl md:text-5xl font-bold text-white mb-6">Siap Menikmati Kopi Terbaik?</h2>
            <p class="text-xl text-green-100 mb-10 max-w-2xl mx-auto">
                Bergabunglah dengan ribuan pecinta kopi lainnya dan nikmati berbagai keuntungan eksklusif
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('register') }}" class="bg-white hover:bg-gray-100 text-green-900 px-10 py-4 rounded-full text-lg font-semibold transition-all duration-300 shadow-xl hover:shadow-2xl hover:scale-105">
                    Daftar Sekarang
                </a>
                <a href="{{ route('login') }}" class="bg-green-700/50 backdrop-blur-md hover:bg-green-700/70 text-white px-10 py-4 rounded-full text-lg font-semibold border-2 border-white/30 transition-all duration-300">
                    Login
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gradient-to-br from-gray-900 to-gray-800 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-4 gap-12 mb-12">
                <div class="md:col-span-2">
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="w-10 h-10 bg-gradient-to-br from-green-600 to-emerald-800 rounded-full flex items-center justify-center">
                            <span class="text-white font-bold text-xl">11</span>
                        </div>
                        <h3 class="text-2xl font-bold">Sebelas Coffee</h3>
                    </div>
                    <p class="text-gray-400 mb-6">Menghadirkan pengalaman kopi terbaik dengan cita rasa yang sempurna untuk setiap momen istimewa Anda.</p>
            
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-4">Menu</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#hero" class="hover:text-green-400 transition-colors">Home</a></li>
                        <li><a href="#about" class="hover:text-green-400 transition-colors">Tentang</a></li>
                        <li><a href="#menu" class="hover:text-green-400 transition-colors">Menu</a></li>
                        <li><a href="#contact" class="hover:text-green-400 transition-colors">Kontak</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-4">Kontak</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li>Phone: 082117549291</li>
                        <li>Yogyakarta, Indonesia</li>
                    </ul>
                </div>
            </div>
            <div class="pt-8 border-t border-gray-700 text-center text-gray-400">
                <p>&copy; 2025 Sebelas Coffee. All rights reserved.</p>
            </div>
        </div>
    </footer>
</div>

<style>
@keyframes fade-in-up {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in-up {
    animation: fade-in-up 1s ease-out;
}

.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Smooth scroll */
html {
    scroll-behavior: smooth;
}

/* Navbar scroll effect */
#navbar.scrolled {
    background: rgba(255, 255, 255, 0.98);
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
}
</style>

<script>
// Navbar scroll effect
window.addEventListener('scroll', function() {
    const navbar = document.getElementById('navbar');
    if (window.scrollY > 50) {
        navbar.classList.add('scrolled');
    } else {
        navbar.classList.remove('scrolled');
    }
});

// Parallax effect for hero background
window.addEventListener('scroll', function() {
    const scrolled = window.pageYOffset;
    const bgImage = document.getElementById('bg-image');
    if (bgImage) {
        bgImage.style.transform = 'translateY(' + scrolled * 0.5 + 'px)';
    }
});

// Alternate background images on hero section
let currentBg = 1;
setInterval(() => {
    const bgImage = document.getElementById('bg-image');
    if (bgImage) {
        currentBg = currentBg === 1 ? 2 : 1;
        bgImage.style.transition = 'opacity 1s ease-in-out';
        bgImage.style.opacity = '0';
        setTimeout(() => {
            bgImage.src = `{{ asset('bg${currentBg}.jpeg') }}`.replace('${currentBg}', currentBg);
            bgImage.style.opacity = '0.8';
        }, 1000);
    }
}, 8000);
</script>
@endsection