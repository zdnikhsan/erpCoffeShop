<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-bold text-2xl text-espresso leading-tight">
                {{ __('Detail Bahan Baku') }}
            </h2>
            <div class="flex items-center gap-2">
                @hasanyrole('owner|manager')
                <a href="{{ route('ingredients.edit', $ingredient) }}"
                   class="inline-flex items-center px-4 py-2 bg-latte hover:bg-latte-dark text-espresso text-sm font-semibold rounded-xl transition-all duration-200 active:scale-95">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Edit
                </a>
                @endhasanyrole
                <a href="{{ route('ingredients.index') }}"
                   class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-charcoal/70 text-sm font-medium rounded-xl transition-all duration-200 active:scale-95">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="bg-white border border-gray-200/60 overflow-hidden shadow-sm rounded-2xl">
            <div class="p-6 sm:p-8">
                {{-- Ingredient Info Card --}}
                <div class="flex items-start space-x-4 mb-8">
                    <span class="p-3 bg-latte/20 rounded-2xl text-espresso shrink-0">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                        </svg>
                    </span>
                    <div>
                        <h3 class="text-xl font-bold text-espresso">{{ $ingredient->name }}</h3>
                        <p class="text-sm text-charcoal/50 mt-1">Ditambahkan {{ $ingredient->created_at->translatedFormat('d F Y, H:i') }}</p>
                    </div>
                </div>

                {{-- Detail Fields --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    {{-- SKU --}}
                    <div class="bg-gray-50/80 rounded-xl p-4 border border-gray-100">
                        <p class="text-xs font-semibold text-charcoal/40 uppercase tracking-wider mb-1">Kode SKU</p>
                        <span class="inline-flex items-center px-3 py-1 rounded-lg text-sm font-mono font-semibold bg-gray-100 text-charcoal/80">
                            {{ $ingredient->sku }}
                        </span>
                    </div>

                    {{-- Satuan --}}
                    <div class="bg-gray-50/80 rounded-xl p-4 border border-gray-100">
                        <p class="text-xs font-semibold text-charcoal/40 uppercase tracking-wider mb-1">Satuan</p>
                        <p class="text-sm font-semibold text-charcoal">{{ $ingredient->unit }}</p>
                    </div>

                    {{-- Stok Saat Ini --}}
                    <div class="bg-gray-50/80 rounded-xl p-4 border border-gray-100">
                        <p class="text-xs font-semibold text-charcoal/40 uppercase tracking-wider mb-1">Stok Saat Ini</p>
                        <span class="inline-flex items-center px-3 py-1 rounded-lg text-sm font-semibold {{ $ingredient->isLowStock() ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700' }}">
                            @if($ingredient->isLowStock())
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z" />
                                </svg>
                                Stok Rendah —
                            @endif
                            {{ number_format($ingredient->stock, 2) }} {{ $ingredient->unit }}
                        </span>
                    </div>

                    {{-- Safety Stock --}}
                    <div class="bg-gray-50/80 rounded-xl p-4 border border-gray-100">
                        <p class="text-xs font-semibold text-charcoal/40 uppercase tracking-wider mb-1">Safety Stock</p>
                        <p class="text-sm font-semibold text-charcoal">{{ number_format($ingredient->safety_stock, 2) }} {{ $ingredient->unit }}</p>
                    </div>
                </div>

                {{-- Metadata --}}
                <div class="mt-8 pt-6 border-t border-gray-100">
                    <div class="flex flex-wrap gap-x-8 gap-y-2 text-xs text-charcoal/40">
                        <span>Dibuat: {{ $ingredient->created_at->translatedFormat('d M Y, H:i') }}</span>
                        <span>Diperbarui: {{ $ingredient->updated_at->translatedFormat('d M Y, H:i') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
