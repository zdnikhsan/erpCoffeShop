<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-bold text-2xl text-espresso leading-tight">
                {{ __('Detail Supplier') }}
            </h2>
            <div class="flex items-center gap-2">
                <a href="{{ route('suppliers.edit', $supplier) }}"
                   class="inline-flex items-center px-4 py-2 bg-latte hover:bg-latte-dark text-espresso text-sm font-semibold rounded-xl transition-all duration-200 active:scale-95">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Edit
                </a>
                <a href="{{ route('suppliers.index') }}"
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
                {{-- Supplier Info Card --}}
                <div class="flex items-start space-x-4 mb-8">
                    <span class="p-3 bg-latte/20 rounded-2xl text-espresso shrink-0">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                    </span>
                    <div>
                        <h3 class="text-xl font-bold text-espresso">{{ $supplier->name }}</h3>
                        <p class="text-sm text-charcoal/50 mt-1">Ditambahkan {{ $supplier->created_at->translatedFormat('d F Y, H:i') }}</p>
                    </div>
                </div>

                {{-- Detail Fields --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    {{-- Telepon --}}
                    <div class="bg-gray-50/80 rounded-xl p-4 border border-gray-100">
                        <p class="text-xs font-semibold text-charcoal/40 uppercase tracking-wider mb-1">Nomor Kontak / WhatsApp</p>
                        <p class="text-sm font-semibold text-charcoal">{{ $supplier->phone }}</p>
                    </div>

                    {{-- Tempo Pembayaran --}}
                    <div class="bg-gray-50/80 rounded-xl p-4 border border-gray-100">
                        <p class="text-xs font-semibold text-charcoal/40 uppercase tracking-wider mb-1">Tempo Pembayaran</p>
                        <span class="inline-flex items-center px-3 py-1 rounded-lg text-sm font-semibold {{ $supplier->payment_terms === 0 ? 'bg-green-100 text-green-700' : 'bg-latte/20 text-espresso' }}">
                            {{ $supplier->payment_terms === 0 ? 'COD (Cash on Delivery)' : $supplier->payment_terms . ' Hari' }}
                        </span>
                    </div>

                    {{-- Alamat --}}
                    <div class="bg-gray-50/80 rounded-xl p-4 border border-gray-100 sm:col-span-2">
                        <p class="text-xs font-semibold text-charcoal/40 uppercase tracking-wider mb-1">Alamat</p>
                        <p class="text-sm text-charcoal leading-relaxed">{{ $supplier->address }}</p>
                    </div>
                </div>

                {{-- Metadata --}}
                <div class="mt-8 pt-6 border-t border-gray-100">
                    <div class="flex flex-wrap gap-x-8 gap-y-2 text-xs text-charcoal/40">
                        <span>Dibuat: {{ $supplier->created_at->translatedFormat('d M Y, H:i') }}</span>
                        <span>Diperbarui: {{ $supplier->updated_at->translatedFormat('d M Y, H:i') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
