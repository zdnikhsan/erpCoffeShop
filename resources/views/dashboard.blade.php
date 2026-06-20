<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="font-bold text-2xl text-espresso leading-tight">
                {{ __('Dashboard Ringkasan') }}
            </h2>
            <p class="text-sm text-charcoal/60 mt-1">
                Kinerja penjualan harian, tren jam sibuk, dan stok bahan baku kafe
            </p>
        </div>
    </x-slot>

    @push('styles')
    <style>
        .kpi-card {
            position: relative;
            overflow: hidden;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .kpi-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px -5px rgba(0, 0, 0, 0.08);
        }
        .kpi-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3.5px;
            border-radius: 8px 8px 0 0;
        }
        .kpi-omset::before     { background: linear-gradient(90deg, #10b981, #059669); }
        .kpi-transaksi::before { background: linear-gradient(90deg, #3b82f6, #2563eb); }
        .kpi-avg::before       { background: linear-gradient(90deg, #f59e0b, #d97706); }
        .kpi-stok::before      { background: linear-gradient(90deg, #ef4444, #dc2626); }

        .chart-bar {
            transition: height 0.6s cubic-bezier(0.34, 1.56, 0.64, 1);
        }
    </style>
    @endpush

    <div class="py-6 space-y-6">

        {{-- ═══════════ TOP KPI CARDS ═══════════ --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
            
            {{-- Omset Hari Ini --}}
            <div class="kpi-card kpi-omset bg-white border border-gray-200/60 rounded-2xl shadow-sm p-5 flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <span class="p-2.5 bg-emerald-100 rounded-xl">
                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </span>
                        @if($omsetChange >= 0)
                            <span class="text-xs font-semibold text-emerald-600 bg-emerald-50 px-2 py-1 rounded-lg">
                                ↑ {{ number_format($omsetChange, 1) }}%
                            </span>
                        @else
                            <span class="text-xs font-semibold text-red-600 bg-red-50 px-2 py-1 rounded-lg">
                                ↓ {{ number_format(abs($omsetChange), 1) }}%
                            </span>
                        @endif
                    </div>
                    <p class="text-xs text-charcoal/50 font-semibold uppercase tracking-wider">Omset Hari Ini</p>
                    <p class="text-2xl font-extrabold text-charcoal mt-1">Rp {{ number_format($omsetToday, 0, ',', '.') }}</p>
                </div>
                <p class="text-[10px] text-charcoal/40 mt-3 border-t border-gray-100 pt-2">
                    Kemarin: Rp {{ number_format($omsetYesterday, 0, ',', '.') }}
                </p>
            </div>

            {{-- Jumlah Transaksi --}}
            <div class="kpi-card kpi-transaksi bg-white border border-gray-200/60 rounded-2xl shadow-sm p-5 flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <span class="p-2.5 bg-blue-100 rounded-xl">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                            </svg>
                        </span>
                        @if($transaksiChange >= 0)
                            <span class="text-xs font-semibold text-blue-600 bg-blue-50 px-2 py-1 rounded-lg">
                                ↑ {{ number_format($transaksiChange, 1) }}%
                            </span>
                        @else
                            <span class="text-xs font-semibold text-red-600 bg-red-50 px-2 py-1 rounded-lg">
                                ↓ {{ number_format(abs($transaksiChange), 1) }}%
                            </span>
                        @endif
                    </div>
                    <p class="text-xs text-charcoal/50 font-semibold uppercase tracking-wider">Jumlah Transaksi</p>
                    <p class="text-2xl font-extrabold text-charcoal mt-1">{{ number_format($transaksiToday) }} Order</p>
                </div>
                <p class="text-[10px] text-charcoal/40 mt-3 border-t border-gray-100 pt-2">
                    Kemarin: {{ number_format($transaksiYesterday) }} Order
                </p>
            </div>

            {{-- Rata-rata Keranjang --}}
            <div class="kpi-card kpi-avg bg-white border border-gray-200/60 rounded-2xl shadow-sm p-5 flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <span class="p-2.5 bg-amber-100 rounded-xl">
                            <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                            </svg>
                        </span>
                        @if($avgBasketChange >= 0)
                            <span class="text-xs font-semibold text-amber-600 bg-amber-50 px-2 py-1 rounded-lg">
                                ↑ {{ number_format($avgBasketChange, 1) }}%
                            </span>
                        @else
                            <span class="text-xs font-semibold text-red-600 bg-red-50 px-2 py-1 rounded-lg">
                                ↓ {{ number_format(abs($avgBasketChange), 1) }}%
                            </span>
                        @endif
                    </div>
                    <p class="text-xs text-charcoal/50 font-semibold uppercase tracking-wider">Rata-rata Keranjang</p>
                    <p class="text-2xl font-extrabold text-charcoal mt-1">Rp {{ number_format($avgBasketToday, 0, ',', '.') }}</p>
                </div>
                <p class="text-[10px] text-charcoal/40 mt-3 border-t border-gray-100 pt-2">
                    Kemarin: Rp {{ number_format($avgBasketYesterday, 0, ',', '.') }}
                </p>
            </div>

            {{-- Status Stok Kritis --}}
            <div class="kpi-card kpi-stok bg-white border border-gray-200/60 rounded-2xl shadow-sm p-5 flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <span class="p-2.5 {{ $stokKritisCount > 0 ? 'bg-red-100 text-red-600' : 'bg-green-100 text-green-600' }} rounded-xl">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </span>
                        @if($stokKritisCount > 0)
                            <span class="text-xs font-semibold text-red-600 bg-red-50 px-2.5 py-1 rounded-lg animate-pulse">
                                Butuh Reorder
                            </span>
                        @else
                            <span class="text-xs font-semibold text-green-600 bg-green-50 px-2.5 py-1 rounded-lg">
                                Stok Aman
                            </span>
                        @endif
                    </div>
                    <p class="text-xs text-charcoal/50 font-semibold uppercase tracking-wider">Stok Kritis</p>
                    <p class="text-2xl font-extrabold text-charcoal mt-1">
                        {{ $stokKritisCount }} Bahan
                    </p>
                </div>
                <a href="{{ route('ingredients.index') }}" class="text-[10px] text-latte-dark hover:text-espresso font-semibold mt-3 border-t border-gray-100 pt-2 flex items-center justify-between group transition-colors duration-150">
                    <span>Lihat detail bahan baku</span>
                    <span class="transform group-hover:translate-x-0.5 transition-transform">→</span>
                </a>
            </div>
        </div>

        {{-- ═══════════ ANALYTICS CHARTS ═══════════ --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            
            {{-- Peak Hours (Hourly Trend) --}}
            <div class="bg-white border border-gray-200/60 rounded-2xl shadow-sm overflow-hidden flex flex-col justify-between">
                <div class="p-5 sm:p-6 border-b border-gray-100">
                    <h3 class="font-bold text-lg text-espresso flex items-center gap-2">
                        <svg class="w-5 h-5 text-latte-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Jam Sibuk Kafe (Peak Hours Hari Ini)
                    </h3>
                    <p class="text-xs text-charcoal/50 mt-0.5">Penjualan per jam dari 08:00 sampai 22:00</p>
                </div>
                <div class="p-5 sm:p-6" x-data="hourlyChart()" x-init="init()">
                    @if(collect($hourlyChartData)->sum() > 0)
                        <div class="relative h-56">
                            {{-- Y-axis --}}
                            <div class="absolute left-0 top-0 bottom-6 w-16 flex flex-col justify-between text-right pr-2">
                                <span class="text-[10px] text-charcoal/40 font-medium" x-text="formatCurrency(maxValue)"></span>
                                <span class="text-[10px] text-charcoal/40 font-medium" x-text="formatCurrency(maxValue * 0.5)"></span>
                                <span class="text-[10px] text-charcoal/40 font-medium">0</span>
                            </div>
                            {{-- Grid lines --}}
                            <div class="absolute left-16 right-0 top-0 bottom-6 flex flex-col justify-between">
                                <div class="border-b border-dashed border-gray-100"></div>
                                <div class="border-b border-dashed border-gray-100"></div>
                                <div class="border-b border-gray-200/60"></div>
                            </div>
                            {{-- Bars --}}
                            <div class="absolute left-16 right-0 top-0 bottom-6 flex items-end gap-0.5 px-0.5">
                                @foreach ($hourlyChartData as $hour => $total)
                                    <div class="flex-1 h-full flex flex-col justify-end items-center group relative" style="min-width: 0;">
                                        {{-- Tooltip --}}
                                        <div class="absolute bottom-full mb-1.5 hidden group-hover:flex flex-col items-center z-10">
                                            <div class="bg-charcoal text-white text-[9px] font-medium px-2 py-1 rounded shadow-lg whitespace-nowrap">
                                                <span class="font-bold">{{ $hour }}</span>: Rp {{ number_format($total, 0, ',', '.') }}
                                            </div>
                                            <div class="w-1.5 h-1.5 bg-charcoal rotate-45 -mt-0.5"></div>
                                        </div>
                                        {{-- Bar --}}
                                        <div class="chart-bar w-full max-w-[20px] bg-gradient-to-t from-espresso to-latte rounded-t-sm hover:from-espresso-light hover:to-latte-light cursor-pointer"
                                             :style="'height: ' + (active ? getBarHeight({{ $total }}) : '0%')">
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            {{-- X-axis --}}
                            <div class="absolute left-16 right-0 bottom-0 h-5 flex gap-0.5 px-0.5">
                                @foreach ($hourlyChartData as $hour => $total)
                                    <div class="flex-1 text-center" style="min-width: 0;">
                                        <span class="text-[8px] text-charcoal/40 font-medium truncate block leading-5">
                                            {{ substr($hour, 0, 2) }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <div class="h-56 flex items-center justify-center">
                            <div class="text-center">
                                <svg class="w-8 h-8 text-charcoal/15 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <p class="text-xs text-charcoal/40 font-medium">Belum ada transaksi hari ini</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Weekly Trend (7 Days) --}}
            <div class="bg-white border border-gray-200/60 rounded-2xl shadow-sm overflow-hidden flex flex-col justify-between">
                <div class="p-5 sm:p-6 border-b border-gray-100">
                    <h3 class="font-bold text-lg text-espresso flex items-center gap-2">
                        <svg class="w-5 h-5 text-latte-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        Tren Omset Mingguan
                    </h3>
                    <p class="text-xs text-charcoal/50 mt-0.5">Pendapatan harian selama 7 hari terakhir</p>
                </div>
                <div class="p-5 sm:p-6" x-data="weeklyChart()" x-init="init()">
                    @if(collect($weeklyChartData)->sum() > 0)
                        <div class="relative h-56">
                            {{-- Y-axis --}}
                            <div class="absolute left-0 top-0 bottom-6 w-16 flex flex-col justify-between text-right pr-2">
                                <span class="text-[10px] text-charcoal/40 font-medium" x-text="formatCurrency(maxValue)"></span>
                                <span class="text-[10px] text-charcoal/40 font-medium" x-text="formatCurrency(maxValue * 0.5)"></span>
                                <span class="text-[10px] text-charcoal/40 font-medium">0</span>
                            </div>
                            {{-- Grid lines --}}
                            <div class="absolute left-16 right-0 top-0 bottom-6 flex flex-col justify-between">
                                <div class="border-b border-dashed border-gray-100"></div>
                                <div class="border-b border-dashed border-gray-100"></div>
                                <div class="border-b border-gray-200/60"></div>
                            </div>
                            {{-- Bars --}}
                            <div class="absolute left-16 right-0 top-0 bottom-6 flex items-end gap-2 px-2">
                                @foreach ($weeklyChartData as $dateLabel => $total)
                                    <div class="flex-1 h-full flex flex-col justify-end items-center group relative" style="min-width: 0;">
                                        {{-- Tooltip --}}
                                        <div class="absolute bottom-full mb-1.5 hidden group-hover:flex flex-col items-center z-10">
                                            <div class="bg-charcoal text-white text-[9px] font-medium px-2 py-1 rounded shadow-lg whitespace-nowrap">
                                                Rp {{ number_format($total, 0, ',', '.') }}
                                            </div>
                                            <div class="w-1.5 h-1.5 bg-charcoal rotate-45 -mt-0.5"></div>
                                        </div>
                                        {{-- Bar --}}
                                        <div class="chart-bar w-full max-w-[32px] bg-gradient-to-t from-espresso to-latte rounded-t-sm hover:from-espresso-light hover:to-latte-light cursor-pointer"
                                             :style="'height: ' + (active ? getBarHeight({{ $total }}) : '0%')">
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            {{-- X-axis --}}
                            <div class="absolute left-16 right-0 bottom-0 h-5 flex gap-2 px-2">
                                @foreach ($weeklyChartData as $dateLabel => $total)
                                    <div class="flex-1 text-center" style="min-width: 0;">
                                        <span class="text-[9px] text-charcoal/40 font-medium truncate block leading-5">
                                            {{ $dateLabel }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <div class="h-56 flex items-center justify-center">
                            <div class="text-center">
                                <svg class="w-8 h-8 text-charcoal/15 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                </svg>
                                <p class="text-xs text-charcoal/40 font-medium">Belum ada transaksi dalam 7 hari terakhir</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- ═══════════ TOP SELLING PRODUCTS & LIVE ORDER MONITOR ═══════════ --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            
            {{-- Top 5 Selling Products --}}
            <div class="bg-white border border-gray-200/60 rounded-2xl shadow-sm overflow-hidden flex flex-col justify-between">
                <div class="p-5 border-b border-gray-100">
                    <h3 class="font-bold text-base text-espresso flex items-center gap-2">
                        <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                        </svg>
                        Top 5 Produk Terlaris Kafe
                    </h3>
                    <p class="text-xs text-charcoal/50 mt-0.5">Produk dengan jumlah kuantitas penjualan tertinggi</p>
                </div>
                <div class="p-5 flex-1 flex flex-col justify-center">
                    @forelse ($topProducts as $index => $product)
                        @php
                            $maxQty = $topProducts[0]->total_qty ?? 1;
                            $percentage = ($product->total_qty / $maxQty) * 100;
                            $medals = ['🥇', '🥈', '🥉'];
                        @endphp
                        <div class="mb-4 last:mb-0">
                            <div class="flex items-center justify-between mb-1">
                                <div class="flex items-center gap-2">
                                    <span class="text-sm font-semibold text-espresso">
                                        {{ $medals[$index] ?? ($index + 1) . '.' }}
                                    </span>
                                    <span class="text-sm font-bold text-charcoal truncate max-w-[180px]">{{ $product->name }}</span>
                                </div>
                                <span class="text-xs font-bold text-espresso">{{ number_format($product->total_qty) }} pcs</span>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-2">
                                <div class="bg-gradient-to-r from-latte to-espresso h-2 rounded-full transition-all duration-700"
                                     style="width: {{ $percentage }}%"></div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12">
                            <p class="text-sm text-charcoal/40 font-medium">Belum ada data penjualan produk</p>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Live Order Monitor --}}
            <div class="bg-white border border-gray-200/60 rounded-2xl shadow-sm overflow-hidden flex flex-col justify-between">
                <div class="p-5 border-b border-gray-100 flex items-center justify-between">
                    <div>
                        <h3 class="font-bold text-base text-espresso flex items-center gap-2">
                            <span class="flex h-2 w-2 relative">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                            </span>
                            Live Order Monitor
                        </h3>
                        <p class="text-xs text-charcoal/50 mt-0.5">Daftar transaksi terbaru hari ini</p>
                    </div>
                    <span class="text-[10px] bg-emerald-50 text-emerald-700 px-2 py-0.5 rounded-md font-semibold font-mono">
                        {{ $liveOrders->count() }} hari ini
                    </span>
                </div>
                <div class="divide-y divide-gray-50 flex-1 flex flex-col justify-start">
                    @forelse ($liveOrders as $order)
                        <div class="px-5 py-3.5 flex items-center justify-between hover:bg-latte/5 transition-colors duration-150">
                            <div class="min-w-0 flex flex-col">
                                <div class="flex items-center gap-2">
                                    <span class="text-xs font-mono font-bold text-espresso truncate">
                                        {{ $order->invoice_number }}
                                    </span>
                                    
                                    {{-- Order Type Badges --}}
                                    @if($order->order_type === 'dine_in')
                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[9px] font-semibold bg-blue-50 text-blue-700 border border-blue-100">
                                            Dine In
                                        </span>
                                    @elseif($order->order_type === 'takeaway')
                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[9px] font-semibold bg-amber-50 text-amber-700 border border-amber-100">
                                            Takeaway
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[9px] font-semibold bg-teal-50 text-teal-700 border border-teal-100">
                                            Delivery
                                        </span>
                                    @endif
                                </div>
                                <p class="text-[10px] text-charcoal/40 mt-1">
                                    {{ $order->created_at->format('H:i') }} · Kasir: <span class="font-medium">{{ $order->cashier->name ?? '-' }}</span>
                                </p>
                            </div>
                            <span class="text-sm font-extrabold text-charcoal whitespace-nowrap ml-3">
                                Rp {{ number_format($order->total_pay, 0, ',', '.') }}
                            </span>
                        </div>
                    @empty
                        <div class="text-center py-12 flex-1 flex flex-col items-center justify-center">
                            <svg class="w-8 h-8 text-charcoal/15 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012-2" />
                            </svg>
                            <p class="text-xs text-charcoal/40 font-medium">Belum ada transaksi hari ini</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function hourlyChart() {
            return {
                maxValue: 0,
                active: false,
                init() {
                    const data = @json(array_values($hourlyChartData));
                    this.maxValue = Math.max(...data.map(Number), 1);
                    const magnitude = Math.pow(10, Math.max(Math.floor(Math.log10(this.maxValue)), 0));
                    this.maxValue = Math.ceil(this.maxValue / magnitude) * magnitude;
                    
                    setTimeout(() => this.active = true, 100);
                },
                getBarHeight(value) {
                    return Math.max((value / this.maxValue) * 100, 2) + '%';
                },
                formatCurrency(value) {
                    if (value >= 1000000) return 'Rp ' + (value / 1000000).toFixed(1) + 'jt';
                    if (value >= 1000) return 'Rp ' + (value / 1000).toFixed(0) + 'rb';
                    return 'Rp ' + value;
                }
            }
        }

        function weeklyChart() {
            return {
                maxValue: 0,
                active: false,
                init() {
                    const data = @json(array_values($weeklyChartData));
                    this.maxValue = Math.max(...data.map(Number), 1);
                    const magnitude = Math.pow(10, Math.max(Math.floor(Math.log10(this.maxValue)), 0));
                    this.maxValue = Math.ceil(this.maxValue / magnitude) * magnitude;
                    
                    setTimeout(() => this.active = true, 100);
                },
                getBarHeight(value) {
                    return Math.max((value / this.maxValue) * 100, 2) + '%';
                },
                formatCurrency(value) {
                    if (value >= 1000000) return 'Rp ' + (value / 1000000).toFixed(1) + 'jt';
                    if (value >= 1000) return 'Rp ' + (value / 1000).toFixed(0) + 'rb';
                    return 'Rp ' + value;
                }
            }
        }
    </script>
    @endpush
</x-app-layout>
