<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="description" content="Masuk atau daftar ke DCoffee ERP — Kelola kedai kopi Anda lebih mudah.">

        <title>{{ config('app.name', 'DCoffee') }} — Masuk / Daftar</title>

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
                50% { transform: translateY(-6px) scaleX(1.05); opacity: 0.8; }
            }
            .float-up {
                animation: floatUp 6s ease-in-out infinite;
            }
            @keyframes floatUp {
                0%, 100% { transform: translateY(0); }
                50% { transform: translateY(-10px); }
            }
            .slide-in {
                animation: slideIn 0.8s ease-out both;
            }
            @keyframes slideIn {
                from { opacity: 0; transform: translateY(20px); }
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
        <div class="min-h-screen flex lg:grid lg:grid-cols-12 hero-pattern">
            
            {{-- Left Side: Branding and Showcase (Desktop only) --}}
            <div class="hidden lg:flex lg:col-span-5 bg-espresso text-white flex-col justify-between p-12 relative overflow-hidden shadow-2xl">
                {{-- Decorative background gradients --}}
                <div class="absolute inset-0 bg-gradient-to-br from-espresso-dark via-espresso to-espresso-light opacity-95"></div>
                <div class="absolute -top-40 -left-40 w-96 h-96 bg-latte/10 rounded-full blur-3xl"></div>
                <div class="absolute -bottom-40 -right-40 w-96 h-96 bg-latte/15 rounded-full blur-3xl"></div>
                
                {{-- Logo and Brand --}}
                <a href="/" class="flex items-center space-x-3 group z-10">
                    <span class="p-2.5 bg-latte rounded-2xl text-espresso flex items-center justify-center transition-transform group-hover:scale-105 duration-200 shadow-lg shadow-espresso-dark/30">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                        </svg>
                    </span>
                    <span class="font-extrabold text-2xl tracking-wider text-latte group-hover:text-latte-light transition-colors duration-200">
                        DCoffee
                    </span>
                </a>

                {{-- Visual Showcase / Slogan --}}
                <div class="my-auto space-y-8 z-10 slide-in" style="animation-delay: 0.2s;">
                    <div class="space-y-4">
                        <div class="inline-flex items-center px-3.5 py-1 bg-latte/10 border border-latte/20 rounded-full text-xs font-semibold text-latte uppercase tracking-widest">
                            Coffee Shop ERP
                        </div>
                        <h1 class="text-3xl lg:text-4xl font-extrabold text-white leading-tight">
                            Kelola Operasional Kedai Kopi Anda dengan Lebih Mudah
                        </h1>
                        <p class="text-white/70 text-sm leading-relaxed max-w-md">
                            Sistem ERP terintegrasi untuk kasir (POS), pengelolaan resep, stok bahan baku, pembelian, hingga laporan keuangan.
                        </p>
                    </div>

                    {{-- Floating Feature Preview Card --}}
                    <div class="bg-white/10 backdrop-blur-md border border-white/10 rounded-2xl p-5 shadow-lg max-w-sm float-up">
                        <div class="flex items-center space-x-3 mb-3">
                            <div class="w-8 h-8 bg-latte rounded-lg flex items-center justify-center text-espresso">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                </svg>
                            </div>
                            <div>
                                <h4 class="text-xs text-white/50 font-bold uppercase tracking-wider">Statistik Hari Ini</h4>
                                <p class="text-sm font-bold text-white">Laporan Keuangan & Penjualan</p>
                            </div>
                        </div>
                        <div class="space-y-2 text-xs">
                            <div class="flex justify-between py-1 border-b border-white/5">
                                <span class="text-white/60">Total Penjualan</span>
                                <span class="font-bold text-latte">Rp 2.450.000</span>
                            </div>
                            <div class="flex justify-between py-1 border-b border-white/5">
                                <span class="text-white/60">Transaksi Kasir</span>
                                <span class="font-bold text-green-400">47 Transaksi</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-white/60">Stok Bahan Habis</span>
                                <span class="font-bold text-red-400">0 Peringatan</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Footer --}}
                <div class="z-10 text-xs text-white/40">
                    &copy; {{ date('Y') }} DCoffee ERP. Semua hak dilindungi.
                </div>
            </div>

            {{-- Right Side: Slot content wrapper --}}
            <div class="col-span-12 lg:col-span-7 flex flex-col justify-center px-6 sm:px-12 lg:px-20 py-12 relative z-10">
                <div class="w-full max-w-md mx-auto">
                    
                    {{-- Mobile Logo Branding (visible only on mobile) --}}
                    <div class="flex flex-col items-center mb-8 lg:hidden">
                        <a href="/" class="flex items-center space-x-3 group">
                            <span class="p-2.5 bg-espresso rounded-xl text-latte flex items-center justify-center shadow-md">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                                </svg>
                            </span>
                            <span class="font-extrabold text-2xl tracking-wider text-espresso">
                                DCoffee
                            </span>
                        </a>
                        <p class="text-charcoal/50 text-xs mt-2 font-semibold uppercase tracking-widest">Coffee Shop ERP</p>
                    </div>

                    {{-- Slot card --}}
                    <div class="bg-white px-8 py-10 rounded-3xl shadow-xl shadow-espresso/5 border border-gray-200/50 slide-in">
                        {{ $slot }}
                    </div>
                </div>
            </div>

        </div>
    </body>
</html>
