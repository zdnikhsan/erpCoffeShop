<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="DCoffee — Sistem ERP lengkap untuk manajemen kedai kopi Anda. Kelola kasir, produk, stok, dan laporan keuangan.">

        <title>{{ config('app.name', 'DCoffee') }} — Coffee Shop ERP</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            .hero-pattern {
                background-image:
                    radial-gradient(circle at 20% 50%, rgba(212,163,115,0.15) 0%, transparent 50%),
                    radial-gradient(circle at 80% 20%, rgba(74,53,37,0.08) 0%, transparent 40%),
                    radial-gradient(circle at 60% 80%, rgba(212,163,115,0.1) 0%, transparent 45%);
            }
            .coffee-steam {
                animation: steam 3s ease-in-out infinite;
            }
            @keyframes steam {
                0%, 100% { transform: translateY(0) scaleX(1); opacity: 0.5; }
                50% { transform: translateY(-8px) scaleX(1.1); opacity: 0.8; }
            }
            .float-up {
                animation: floatUp 6s ease-in-out infinite;
            }
            @keyframes floatUp {
                0%, 100% { transform: translateY(0); }
                50% { transform: translateY(-12px); }
            }
            .slide-in {
                animation: slideIn 0.8s ease-out both;
            }
            @keyframes slideIn {
                from { opacity: 0; transform: translateY(30px); }
                to { opacity: 1; transform: translateY(0); }
            }
            .fade-in {
                animation: fadeIn 1s ease-out both;
            }
            @keyframes fadeIn {
                from { opacity: 0; }
                to { opacity: 1; }
            }
        </style>
    </head>
    <body class="font-sans antialiased bg-offwhite text-charcoal">
        {{-- Full-Page Hero --}}
        <div class="min-h-screen flex flex-col hero-pattern">

            {{-- Top Navigation --}}
            <header class="w-full py-5 px-6 lg:px-12 fade-in" style="animation-delay: 0.1s;">
                <div class="max-w-7xl mx-auto flex items-center justify-between">
                    {{-- Brand --}}
                    <a href="/" class="flex items-center space-x-3 group">
                        <span class="p-2 bg-espresso rounded-xl text-latte flex items-center justify-center transition-transform group-hover:scale-105 duration-200 shadow-md shadow-espresso/20">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                            </svg>
                        </span>
                        <span class="font-extrabold text-2xl tracking-wider text-espresso group-hover:text-espresso-light transition-colors duration-200">
                            DCoffee
                        </span>
                    </a>

                    {{-- Auth Navigation --}}
                    @if (Route::has('login'))
                        <nav class="flex items-center gap-3">
                            @auth
                                <a href="{{ url('/dashboard') }}"
                                   class="inline-flex items-center px-5 py-2.5 bg-espresso hover:bg-espresso-light text-white text-sm font-semibold rounded-xl shadow-md shadow-espresso/20 transition-all duration-200 hover:shadow-lg hover:shadow-espresso/30 active:scale-95">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z" />
                                    </svg>
                                    Dashboard
                                </a>
                            @else
                                <a href="{{ route('login') }}"
                                   class="inline-flex items-center px-5 py-2.5 text-espresso hover:text-espresso-light text-sm font-semibold rounded-xl transition-all duration-200 hover:bg-espresso/5 active:scale-95">
                                    Masuk
                                </a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}"
                                       class="inline-flex items-center px-5 py-2.5 bg-espresso hover:bg-espresso-light text-white text-sm font-semibold rounded-xl shadow-md shadow-espresso/20 transition-all duration-200 hover:shadow-lg hover:shadow-espresso/30 active:scale-95">
                                        Daftar
                                    </a>
                                @endif
                            @endauth
                        </nav>
                    @endif
                </div>
            </header>

            {{-- Hero Content --}}
            <main class="flex-1 flex items-center justify-center px-6 lg:px-12 py-12">
                <div class="max-w-6xl mx-auto w-full flex flex-col lg:flex-row items-center gap-12 lg:gap-20">
                    
                    {{-- Left: Text Content --}}
                    <div class="flex-1 text-center lg:text-left slide-in" style="animation-delay: 0.2s;">
                        <div class="inline-flex items-center px-4 py-1.5 bg-latte/20 border border-latte/30 rounded-full mb-6">
                            <span class="w-2 h-2 bg-latte-dark rounded-full mr-2 coffee-steam"></span>
                            <span class="text-xs font-semibold text-espresso uppercase tracking-wider">Coffee Shop ERP System</span>
                        </div>
                        
                        <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-espresso leading-tight mb-6">
                            Kelola Kedai Kopi
                            <span class="relative">
                                <span class="relative z-10">Lebih Mudah</span>
                                <span class="absolute bottom-2 left-0 w-full h-3 bg-latte/30 rounded-full -z-0"></span>
                            </span>
                        </h1>

                        <p class="text-lg text-charcoal/70 max-w-lg mx-auto lg:mx-0 mb-8 leading-relaxed">
                            Sistem ERP lengkap untuk manajemen kasir, produk, stok bahan baku, purchase order, dan laporan keuangan kedai kopi Anda — semua dalam satu platform.
                        </p>

                        <div class="flex flex-col sm:flex-row items-center gap-4 justify-center lg:justify-start">
                            @auth
                                <a href="{{ url('/dashboard') }}"
                                   class="inline-flex items-center px-8 py-3.5 bg-espresso hover:bg-espresso-light text-white font-bold rounded-xl shadow-lg shadow-espresso/25 transition-all duration-200 hover:shadow-xl hover:shadow-espresso/30 active:scale-95 text-base">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                    </svg>
                                    Buka Dashboard
                                </a>
                            @else
                                <a href="{{ route('login') }}"
                                   class="inline-flex items-center px-8 py-3.5 bg-espresso hover:bg-espresso-light text-white font-bold rounded-xl shadow-lg shadow-espresso/25 transition-all duration-200 hover:shadow-xl hover:shadow-espresso/30 active:scale-95 text-base">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                    </svg>
                                    Mulai Sekarang
                                </a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}"
                                       class="inline-flex items-center px-8 py-3.5 bg-white hover:bg-latte/10 text-espresso font-bold rounded-xl border-2 border-espresso/15 hover:border-espresso/25 transition-all duration-200 active:scale-95 text-base shadow-sm">
                                        Buat Akun
                                    </a>
                                @endif
                            @endauth
                        </div>

                        {{-- Feature Pills --}}
                        <div class="flex flex-wrap items-center gap-2 mt-8 justify-center lg:justify-start">
                            @foreach (['POS Kasir', 'Manajemen Produk', 'Stok & Resep', 'Purchase Order', 'Laporan Keuangan'] as $feature)
                                <span class="inline-flex items-center px-3 py-1.5 bg-white border border-gray-200/60 rounded-lg text-xs font-medium text-charcoal/60 shadow-sm">
                                    <svg class="w-3 h-3 mr-1.5 text-latte-dark" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                    </svg>
                                    {{ $feature }}
                                </span>
                            @endforeach
                        </div>
                    </div>

                    {{-- Right: Visual --}}
                    <div class="flex-shrink-0 slide-in" style="animation-delay: 0.5s;">
                        <div class="relative float-up">
                            {{-- Card Stack --}}
                            <div class="w-72 sm:w-80 lg:w-96">
                                {{-- Background Card --}}
                                <div class="absolute inset-0 bg-latte/20 rounded-3xl transform rotate-3 scale-95 -z-10"></div>
                                <div class="absolute inset-0 bg-latte/10 rounded-3xl transform -rotate-2 scale-[0.97] -z-10"></div>
                                
                                {{-- Main Card --}}
                                <div class="bg-white rounded-3xl shadow-xl shadow-espresso/10 border border-gray-200/60 overflow-hidden">
                                    {{-- Card Header --}}
                                    <div class="bg-espresso px-6 py-5">
                                        <div class="flex items-center justify-between mb-3">
                                            <div class="flex items-center space-x-2">
                                                <span class="p-1.5 bg-latte/20 rounded-lg">
                                                    <svg class="w-5 h-5 text-latte" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                                    </svg>
                                                </span>
                                                <span class="text-latte font-bold text-sm">DCoffee POS</span>
                                            </div>
                                            <span class="text-latte/60 text-xs">Hari Ini</span>
                                        </div>
                                        <div class="text-white">
                                            <p class="text-latte/80 text-xs mb-1">Total Penjualan</p>
                                            <p class="text-2xl font-extrabold tracking-tight">Rp 2.450.000</p>
                                        </div>
                                    </div>

                                    {{-- Card Body --}}
                                    <div class="p-5 space-y-3">
                                        {{-- Mini Stat --}}
                                        <div class="flex items-center justify-between p-3 bg-green-50 rounded-xl">
                                            <div class="flex items-center space-x-3">
                                                <div class="p-2 bg-green-100 rounded-lg">
                                                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                                                    </svg>
                                                </div>
                                                <div>
                                                    <p class="text-xs text-green-600 font-medium">Transaksi</p>
                                                    <p class="text-sm font-bold text-green-800">47 Order</p>
                                                </div>
                                            </div>
                                            <span class="text-xs font-semibold text-green-600 bg-green-100 px-2 py-1 rounded-lg">+12%</span>
                                        </div>

                                        {{-- Order Items --}}
                                        @foreach ([
                                            ['Espresso', 'Rp 18.000', 'x3'],
                                            ['Cappuccino', 'Rp 25.000', 'x5'],
                                            ['Latte Caramel', 'Rp 28.000', 'x2'],
                                        ] as [$name, $price, $qty])
                                            <div class="flex items-center justify-between py-2 border-b border-gray-100 last:border-0">
                                                <div class="flex items-center space-x-3">
                                                    <div class="w-8 h-8 bg-latte/20 rounded-lg flex items-center justify-center">
                                                        <svg class="w-4 h-4 text-espresso" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                                                        </svg>
                                                    </div>
                                                    <div>
                                                        <p class="text-sm font-semibold text-charcoal">{{ $name }}</p>
                                                        <p class="text-xs text-charcoal/50">{{ $price }}</p>
                                                    </div>
                                                </div>
                                                <span class="text-xs font-semibold text-espresso bg-latte/20 px-2 py-1 rounded-lg">{{ $qty }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>

            {{-- Footer --}}
            <footer class="py-6 px-6 text-center fade-in" style="animation-delay: 0.8s;">
                <p class="text-sm text-charcoal/40">
                    &copy; {{ date('Y') }} DCoffee ERP — Built with
                    <svg class="w-4 h-4 inline-block text-latte-dark -mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd" />
                    </svg>
                    & Laravel
                </p>
            </footer>
        </div>
    </body>
</html>
