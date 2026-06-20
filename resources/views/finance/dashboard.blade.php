<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <h2 class="font-bold text-2xl text-espresso leading-tight">
                    {{ __('Dashboard Keuangan') }}
                </h2>
                <p class="text-sm text-charcoal/60 mt-1">
                    Laporan laba rugi &amp; analisis penjualan DCoffee
                </p>
            </div>

            {{-- Period Filter --}}
            <form method="GET" action="{{ route('finance.dashboard') }}" 
                  class="flex flex-wrap items-center gap-2" 
                  x-data="{ period: '{{ $period }}' }">
                <select name="period" x-model="period" @change="if(period !== 'custom') $el.form.submit()"
                        class="px-3 py-2 border border-gray-200 rounded-xl text-sm text-charcoal focus:ring-2 focus:ring-latte focus:border-latte transition-colors duration-200">
                    <option value="today">Hari Ini</option>
                    <option value="yesterday">Kemarin</option>
                    <option value="this_week">Minggu Ini</option>
                    <option value="this_month">Bulan Ini</option>
                    <option value="last_month">Bulan Lalu</option>
                    <option value="this_year">Tahun Ini</option>
                    <option value="custom">Custom</option>
                </select>
                <template x-if="period === 'custom'">
                    <div class="flex items-center gap-2">
                        <input type="date" name="start_date" value="{{ $startDate->toDateString() }}"
                               class="px-3 py-2 border border-gray-200 rounded-xl text-sm text-charcoal focus:ring-2 focus:ring-latte focus:border-latte transition-colors duration-200" />
                        <span class="text-charcoal/40 text-sm">—</span>
                        <input type="date" name="end_date" value="{{ $endDate->toDateString() }}"
                               class="px-3 py-2 border border-gray-200 rounded-xl text-sm text-charcoal focus:ring-2 focus:ring-latte focus:border-latte transition-colors duration-200" />
                        <button type="submit"
                                class="px-4 py-2 bg-espresso hover:bg-espresso-light text-white text-sm font-semibold rounded-xl transition-all duration-200 active:scale-95">
                            Filter
                        </button>
                    </div>
                </template>
            </form>
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
            box-shadow: 0 8px 25px -5px rgba(0, 0, 0, 0.1);
        }
        .kpi-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            border-radius: 8px 8px 0 0;
        }
        .kpi-revenue::before { background: linear-gradient(90deg, #10b981, #059669); }
        .kpi-cogs::before    { background: linear-gradient(90deg, #f59e0b, #d97706); }
        .kpi-opex::before    { background: linear-gradient(90deg, #ef4444, #dc2626); }
        .kpi-profit::before  { background: linear-gradient(90deg, #8b5cf6, #7c3aed); }

        .chart-bar {
            transition: height 0.6s cubic-bezier(0.34, 1.56, 0.64, 1);
        }
    </style>
    @endpush

    <div class="py-6 space-y-6">

        {{-- ═══════════ KPI CARDS ═══════════ --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
            {{-- Total Pendapatan --}}
            <div class="kpi-card kpi-revenue bg-white border border-gray-200/60 rounded-2xl shadow-sm p-5">
                <div class="flex items-center justify-between mb-3">
                    <span class="p-2.5 bg-emerald-100 rounded-xl">
                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </span>
                    <span class="text-xs font-semibold text-emerald-600 bg-emerald-50 px-2 py-1 rounded-lg">Pendapatan</span>
                </div>
                <p class="text-2xl font-extrabold text-charcoal">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
                <p class="text-xs text-charcoal/50 mt-1">{{ $orderCount }} transaksi</p>
            </div>

            {{-- Total HPP --}}
            <div class="kpi-card kpi-cogs bg-white border border-gray-200/60 rounded-2xl shadow-sm p-5">
                <div class="flex items-center justify-between mb-3">
                    <span class="p-2.5 bg-amber-100 rounded-xl">
                        <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                    </span>
                    <span class="text-xs font-semibold text-amber-600 bg-amber-50 px-2 py-1 rounded-lg">HPP (COGS)</span>
                </div>
                <p class="text-2xl font-extrabold text-charcoal">Rp {{ number_format($totalCOGS, 0, ',', '.') }}</p>
                <p class="text-xs text-charcoal/50 mt-1">Purchase Order (completed)</p>
            </div>

            {{-- Total Pengeluaran Operasional --}}
            <div class="kpi-card kpi-opex bg-white border border-gray-200/60 rounded-2xl shadow-sm p-5">
                <div class="flex items-center justify-between mb-3">
                    <span class="p-2.5 bg-red-100 rounded-xl">
                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </span>
                    <span class="text-xs font-semibold text-red-600 bg-red-50 px-2 py-1 rounded-lg">Biaya Operasional</span>
                </div>
                <p class="text-2xl font-extrabold text-charcoal">Rp {{ number_format($totalOperationalCost, 0, ',', '.') }}</p>
                <p class="text-xs text-charcoal/50 mt-1">Gaji, listrik, sewa, dll.</p>
            </div>

            {{-- Laba Bersih --}}
            <div class="kpi-card kpi-profit bg-white border border-gray-200/60 rounded-2xl shadow-sm p-5">
                <div class="flex items-center justify-between mb-3">
                    <span class="p-2.5 {{ $netProfit >= 0 ? 'bg-violet-100' : 'bg-red-100' }} rounded-xl">
                        <svg class="w-5 h-5 {{ $netProfit >= 0 ? 'text-violet-600' : 'text-red-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                        </svg>
                    </span>
                    <span class="text-xs font-semibold {{ $netProfit >= 0 ? 'text-violet-600 bg-violet-50' : 'text-red-600 bg-red-50' }} px-2 py-1 rounded-lg">
                        Laba Bersih
                    </span>
                </div>
                <p class="text-2xl font-extrabold {{ $netProfit >= 0 ? 'text-charcoal' : 'text-red-600' }}">
                    {{ $netProfit < 0 ? '-' : '' }}Rp {{ number_format(abs($netProfit), 0, ',', '.') }}
                </p>
                <p class="text-xs mt-1 {{ $profitMargin >= 0 ? 'text-emerald-600' : 'text-red-500' }}">
                    Margin: {{ number_format($profitMargin, 1) }}%
                </p>
            </div>
        </div>

        {{-- ═══════════ SECONDARY METRICS ═══════════ --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="bg-white border border-gray-200/60 rounded-2xl shadow-sm p-5 flex items-center gap-4">
                <span class="p-3 bg-latte/20 rounded-xl">
                    <svg class="w-6 h-6 text-espresso" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                </span>
                <div>
                    <p class="text-xs text-charcoal/50 uppercase tracking-wider font-semibold">Total Transaksi</p>
                    <p class="text-xl font-bold text-charcoal">{{ number_format($orderCount) }}</p>
                </div>
            </div>
            <div class="bg-white border border-gray-200/60 rounded-2xl shadow-sm p-5 flex items-center gap-4">
                <span class="p-3 bg-latte/20 rounded-xl">
                    <svg class="w-6 h-6 text-espresso" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                    </svg>
                </span>
                <div>
                    <p class="text-xs text-charcoal/50 uppercase tracking-wider font-semibold">Rata-rata per Transaksi</p>
                    <p class="text-xl font-bold text-charcoal">Rp {{ number_format($averageOrder, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        {{-- ═══════════ CHART: SALES TREND ═══════════ --}}
        <div class="bg-white border border-gray-200/60 rounded-2xl shadow-sm overflow-hidden">
            <div class="p-5 sm:p-6 border-b border-gray-100">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div>
                        <h3 class="font-bold text-lg text-espresso">Tren Penjualan</h3>
                        <p class="text-xs text-charcoal/50 mt-0.5">
                            @if($chartMode === 'hourly') Jam sibuk (Peak Hours) @elseif($chartMode === 'monthly') Bulanan @else Harian @endif
                        </p>
                    </div>
                    <div class="flex items-center bg-gray-100 rounded-xl p-1">
                        @foreach (['daily' => 'Harian', 'monthly' => 'Bulanan', 'hourly' => 'Jam Sibuk'] as $mode => $label)
                            <a href="{{ route('finance.dashboard', array_merge(request()->query(), ['chart_mode' => $mode])) }}"
                               class="px-3 py-1.5 text-xs font-semibold rounded-lg transition-all duration-200 {{ $chartMode === $mode ? 'bg-espresso text-white shadow-sm' : 'text-charcoal/60 hover:text-charcoal' }}">
                                {{ $label }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="p-5 sm:p-6" x-data="salesChart()" x-init="init()">
                @if(count($chartData) > 0)
                    <div class="relative h-64">
                        {{-- Y-axis labels --}}
                        <div class="absolute left-0 top-0 bottom-6 w-20 flex flex-col justify-between text-right pr-3">
                            <span class="text-[10px] text-charcoal/40 font-medium" x-text="formatCurrency(maxValue)"></span>
                            <span class="text-[10px] text-charcoal/40 font-medium" x-text="formatCurrency(maxValue * 0.75)"></span>
                            <span class="text-[10px] text-charcoal/40 font-medium" x-text="formatCurrency(maxValue * 0.5)"></span>
                            <span class="text-[10px] text-charcoal/40 font-medium" x-text="formatCurrency(maxValue * 0.25)"></span>
                            <span class="text-[10px] text-charcoal/40 font-medium">0</span>
                        </div>

                        {{-- Grid lines --}}
                        <div class="absolute left-20 right-0 top-0 bottom-6 flex flex-col justify-between">
                            <div class="border-b border-dashed border-gray-100"></div>
                            <div class="border-b border-dashed border-gray-100"></div>
                            <div class="border-b border-dashed border-gray-100"></div>
                            <div class="border-b border-dashed border-gray-100"></div>
                            <div class="border-b border-gray-200/60"></div>
                        </div>

                        {{-- Bars --}}
                        <div class="absolute left-20 right-0 top-0 bottom-6 flex items-end gap-1 px-1">
                            @foreach ($chartData as $i => $item)
                                <div class="flex-1 h-full flex flex-col justify-end items-center group relative" style="min-width: 0;">
                                    {{-- Tooltip --}}
                                    <div class="absolute bottom-full mb-2 hidden group-hover:flex flex-col items-center z-10">
                                        <div class="bg-charcoal text-white text-[10px] font-medium px-2.5 py-1.5 rounded-lg shadow-lg whitespace-nowrap">
                                            <span class="font-bold">Rp {{ number_format($item['total'], 0, ',', '.') }}</span>
                                            <br><span class="text-white/70">{{ $item['count'] }} transaksi</span>
                                        </div>
                                        <div class="w-2 h-2 bg-charcoal rotate-45 -mt-1"></div>
                                    </div>
                                    {{-- Bar --}}
                                    <div class="chart-bar w-full max-w-[40px] bg-gradient-to-t from-espresso to-latte rounded-t-lg hover:from-espresso-light hover:to-latte-light cursor-pointer"
                                         :style="'height: ' + (active ? getBarHeight({{ $item['total'] }}) : '0%')">
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        {{-- X-axis labels --}}
                        <div class="absolute left-20 right-0 bottom-0 h-6 flex gap-1 px-1">
                            @foreach ($chartData as $item)
                                <div class="flex-1 text-center" style="min-width: 0;">
                                    <span class="text-[9px] sm:text-[10px] text-charcoal/50 font-medium truncate block">
                                        {{ $chartMode === 'daily' ? \Carbon\Carbon::parse($item['label'])->format('d/m') : $item['label'] }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    <div class="h-64 flex items-center justify-center">
                        <div class="text-center">
                            <svg class="w-10 h-10 text-charcoal/15 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                            <p class="text-sm text-charcoal/40 font-medium">Belum ada data penjualan</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        {{-- ═══════════ BOTTOM SECTION: TOP PRODUCTS + EXPENSE BREAKDOWN + RECENT ═══════════ --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- Top Products --}}
            <div class="bg-white border border-gray-200/60 rounded-2xl shadow-sm overflow-hidden">
                <div class="p-5 border-b border-gray-100">
                    <h3 class="font-bold text-base text-espresso flex items-center gap-2">
                        <svg class="w-5 h-5 text-latte-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                        </svg>
                        Top 5 Produk Terlaris
                    </h3>
                </div>
                <div class="p-5">
                    @forelse ($topProducts as $index => $product)
                        @php
                            $maxQty = $topProducts[0]->total_qty ?? 1;
                            $percentage = ($product->total_qty / $maxQty) * 100;
                            $medals = ['🥇', '🥈', '🥉'];
                        @endphp
                        <div class="mb-4 last:mb-0">
                            <div class="flex items-center justify-between mb-1.5">
                                <div class="flex items-center gap-2">
                                    <span class="text-sm">{{ $medals[$index] ?? '▪️' }}</span>
                                    <span class="text-sm font-semibold text-charcoal truncate max-w-[120px]">{{ $product->name }}</span>
                                </div>
                                <span class="text-xs font-bold text-espresso">{{ number_format($product->total_qty) }} pcs</span>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-2">
                                <div class="bg-gradient-to-r from-latte to-espresso h-2 rounded-full transition-all duration-700"
                                     style="width: {{ $percentage }}%"></div>
                            </div>
                            <p class="text-[10px] text-charcoal/40 mt-1">Rp {{ number_format($product->total_revenue, 0, ',', '.') }}</p>
                        </div>
                    @empty
                        <div class="text-center py-6">
                            <p class="text-sm text-charcoal/40">Belum ada data</p>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Expense Breakdown --}}
            <div class="bg-white border border-gray-200/60 rounded-2xl shadow-sm overflow-hidden">
                <div class="p-5 border-b border-gray-100">
                    <h3 class="font-bold text-base text-espresso flex items-center gap-2">
                        <svg class="w-5 h-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" />
                        </svg>
                        Rincian Biaya Operasional
                    </h3>
                </div>
                <div class="p-5">
                    @php
                        $expenseColors = [
                            'Gaji'           => ['bg' => 'bg-blue-500',   'text' => 'text-blue-600', 'light' => 'bg-blue-100'],
                            'Listrik & Air'  => ['bg' => 'bg-yellow-500', 'text' => 'text-yellow-600', 'light' => 'bg-yellow-100'],
                            'Sewa Tempat'    => ['bg' => 'bg-purple-500', 'text' => 'text-purple-600', 'light' => 'bg-purple-100'],
                            'Maintenance'    => ['bg' => 'bg-orange-500', 'text' => 'text-orange-600', 'light' => 'bg-orange-100'],
                            'Lainnya'        => ['bg' => 'bg-gray-500',   'text' => 'text-gray-600', 'light' => 'bg-gray-100'],
                        ];
                        $totalExp = collect($expenseBreakdown)->sum('total');
                    @endphp
                    @forelse ($expenseBreakdown as $item)
                        @php
                            $color = $expenseColors[$item['category']] ?? $expenseColors['Lainnya'];
                            $pct = $totalExp > 0 ? ($item['total'] / $totalExp) * 100 : 0;
                        @endphp
                        <div class="mb-4 last:mb-0">
                            <div class="flex items-center justify-between mb-1.5">
                                <div class="flex items-center gap-2">
                                    <span class="w-2.5 h-2.5 rounded-full {{ $color['bg'] }}"></span>
                                    <span class="text-sm font-medium text-charcoal">{{ $item['category'] }}</span>
                                </div>
                                <span class="text-xs font-bold {{ $color['text'] }}">{{ number_format($pct, 1) }}%</span>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-2">
                                <div class="{{ $color['bg'] }} h-2 rounded-full transition-all duration-700"
                                     style="width: {{ $pct }}%"></div>
                            </div>
                            <p class="text-[10px] text-charcoal/40 mt-1">Rp {{ number_format($item['total'], 0, ',', '.') }}</p>
                        </div>
                    @empty
                        <div class="text-center py-6">
                            <p class="text-sm text-charcoal/40">Belum ada pengeluaran</p>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Recent Orders --}}
            <div class="bg-white border border-gray-200/60 rounded-2xl shadow-sm overflow-hidden">
                <div class="p-5 border-b border-gray-100">
                    <h3 class="font-bold text-base text-espresso flex items-center gap-2">
                        <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Transaksi Terbaru
                    </h3>
                </div>
                <div class="divide-y divide-gray-50">
                    @forelse ($recentOrders as $order)
                        <div class="px-5 py-3.5 flex items-center justify-between hover:bg-latte/5 transition-colors duration-150">
                            <div class="min-w-0">
                                <p class="text-xs font-mono font-semibold text-espresso truncate">{{ $order->invoice_number }}</p>
                                <p class="text-[10px] text-charcoal/40 mt-0.5">
                                    {{ $order->created_at->diffForHumans() }}
                                    · {{ $order->cashier->name ?? '-' }}
                                </p>
                            </div>
                            <span class="text-sm font-bold text-charcoal whitespace-nowrap ml-3">
                                Rp {{ number_format($order->total_pay, 0, ',', '.') }}
                            </span>
                        </div>
                    @empty
                        <div class="p-5 text-center">
                            <p class="text-sm text-charcoal/40">Belum ada transaksi</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- ═══════════ PROFIT & LOSS SUMMARY TABLE ═══════════ --}}
        <div class="bg-white border border-gray-200/60 rounded-2xl shadow-sm overflow-hidden">
            <div class="p-5 sm:p-6 border-b border-gray-100">
                <h3 class="font-bold text-lg text-espresso">Ringkasan Laba Rugi</h3>
                <p class="text-xs text-charcoal/50 mt-0.5">
                    Periode: {{ $startDate->format('d M Y') }} — {{ $endDate->format('d M Y') }}
                </p>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <tbody class="divide-y divide-gray-100">
                        <tr class="bg-emerald-50/50">
                            <td class="px-6 py-4 font-semibold text-charcoal">Total Pendapatan (Revenue)</td>
                            <td class="px-6 py-4 text-right font-bold text-emerald-700">
                                Rp {{ number_format($totalRevenue, 0, ',', '.') }}
                            </td>
                        </tr>
                        <tr>
                            <td class="px-6 py-4 text-charcoal/80 pl-10">(-) Harga Pokok Penjualan (COGS)</td>
                            <td class="px-6 py-4 text-right font-semibold text-amber-700">
                                Rp {{ number_format($totalCOGS, 0, ',', '.') }}
                            </td>
                        </tr>
                        <tr>
                            <td class="px-6 py-4 text-charcoal/80 pl-10">(-) Biaya Operasional</td>
                            <td class="px-6 py-4 text-right font-semibold text-red-600">
                                Rp {{ number_format($totalOperationalCost, 0, ',', '.') }}
                            </td>
                        </tr>
                        <tr class="{{ $netProfit >= 0 ? 'bg-violet-50/50' : 'bg-red-50/50' }} border-t-2 border-gray-200">
                            <td class="px-6 py-5 font-bold text-charcoal text-base">
                                Laba Bersih (Net Profit)
                            </td>
                            <td class="px-6 py-5 text-right font-extrabold text-lg {{ $netProfit >= 0 ? 'text-violet-700' : 'text-red-600' }}">
                                {{ $netProfit < 0 ? '-' : '' }}Rp {{ number_format(abs($netProfit), 0, ',', '.') }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function salesChart() {
            return {
                maxValue: 0,
                active: false,
                init() {
                    const data = @json(array_column($chartData, 'total'));
                    this.maxValue = Math.max(...data.map(Number), 1);
                    // Round up to a nice number
                    const magnitude = Math.pow(10, Math.floor(Math.log10(this.maxValue)));
                    this.maxValue = Math.ceil(this.maxValue / magnitude) * magnitude;
                    
                    // Trigger the chart animation reactively
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
