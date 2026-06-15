<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-bold text-2xl text-espresso leading-tight">
                {{ __('Detail Produk & Resep') }}
            </h2>
            <div class="flex items-center gap-2">
                <a href="{{ route('products.edit', $product) }}"
                   class="inline-flex items-center px-4 py-2 bg-espresso hover:bg-espresso-light text-white text-sm font-semibold rounded-xl shadow-sm transition-all duration-200 active:scale-95">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Edit
                </a>
                <a href="{{ route('products.index') }}"
                   class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-charcoal/70 text-sm font-medium rounded-xl transition-all duration-200 active:scale-95">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6 space-y-6">
        {{-- Product Details Card --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            {{-- Product Metadata Column --}}
            <div class="bg-white border border-gray-200/60 overflow-hidden shadow-sm rounded-2xl p-6 space-y-6">
                <h3 class="text-md font-bold text-espresso border-b border-gray-100 pb-3 flex items-center">
                    <svg class="w-4 h-4 mr-2 text-latte-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Informasi Produk
                </h3>

                <div class="space-y-4">
                    <div>
                        <span class="block text-xs font-bold text-charcoal/40 uppercase tracking-wider">Nama Produk</span>
                        <span class="text-md font-bold text-charcoal">{{ $product->name }}</span>
                    </div>

                    <div>
                        <span class="block text-xs font-bold text-charcoal/40 uppercase tracking-wider">SKU (Stock Keeping Unit)</span>
                        <span class="inline-flex items-center mt-1 px-2.5 py-1 rounded-lg text-xs font-mono font-bold bg-gray-100 text-charcoal/70">
                            {{ $product->sku }}
                        </span>
                    </div>

                    <div>
                        <span class="block text-xs font-bold text-charcoal/40 uppercase tracking-wider">Kategori</span>
                        <span class="inline-flex items-center mt-1 px-2.5 py-1 rounded-lg text-xs font-semibold bg-amber-50 text-amber-800 border border-amber-100">
                            {{ $product->category }}
                        </span>
                    </div>

                    <div>
                        <span class="block text-xs font-bold text-charcoal/40 uppercase tracking-wider">Harga Jual</span>
                        <span class="text-lg font-extrabold text-espresso">
                            Rp {{ number_format($product->price, 0, ',', '.') }}
                        </span>
                    </div>

                    <div>
                        <span class="block text-xs font-bold text-charcoal/40 uppercase tracking-wider">Status Penjualan</span>
                        <span class="inline-flex items-center mt-1 px-2.5 py-1 rounded-full text-xs font-bold {{ $product->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $product->is_active ? 'Aktif (Dijual)' : 'Non-Aktif' }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- Recipe & Bill of Materials Column --}}
            <div class="bg-white border border-gray-200/60 overflow-hidden shadow-sm rounded-2xl p-6 md:col-span-2 space-y-6">
                <h3 class="text-md font-bold text-espresso border-b border-gray-100 pb-3 flex items-center">
                    <svg class="w-4 h-4 mr-2 text-latte-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                    Resep & Takaran (Bill of Materials) per Porsi
                </h3>

                <div class="overflow-hidden border border-gray-100 rounded-xl">
                    <table class="w-full text-sm text-left">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-100">
                                <th class="px-6 py-3 font-semibold text-charcoal/70 text-xs uppercase">No</th>
                                <th class="px-6 py-3 font-semibold text-charcoal/70 text-xs uppercase">Nama Bahan Baku</th>
                                <th class="px-6 py-3 font-semibold text-charcoal/70 text-xs uppercase">SKU</th>
                                <th class="px-6 py-3 font-semibold text-charcoal/70 text-xs uppercase text-right">Takaran Jual</th>
                                <th class="px-6 py-3 font-semibold text-charcoal/70 text-xs uppercase">Satuan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($product->ingredients as $ingredient)
                                <tr class="hover:bg-gray-50/55 transition-colors">
                                    <td class="px-6 py-3 text-charcoal/60">{{ $loop->iteration }}</td>
                                    <td class="px-6 py-3 font-semibold text-charcoal">{{ $ingredient->name }}</td>
                                    <td class="px-6 py-3">
                                        <span class="font-mono text-xs font-semibold text-charcoal/70">
                                            {{ $ingredient->sku }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-3 text-right font-bold text-espresso">
                                        {{ number_format($ingredient->pivot->quantity, 2) }}
                                    </td>
                                    <td class="px-6 py-3 text-charcoal/60">{{ $ingredient->unit }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-charcoal/50">
                                        Tidak ada bahan baku yang terdaftar di dalam resep produk ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
