<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-bold text-2xl text-espresso leading-tight">
                {{ __('Tambah Supplier') }}
            </h2>
            <a href="{{ route('suppliers.index') }}"
               class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-charcoal/70 text-sm font-medium rounded-xl transition-all duration-200 active:scale-95">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="bg-white border border-gray-200/60 overflow-hidden shadow-sm rounded-2xl">
            <form method="POST" action="{{ route('suppliers.store') }}" class="p-6 sm:p-8 space-y-6">
                @csrf

                {{-- Nama Supplier --}}
                <div>
                    <label for="name" class="block text-sm font-semibold text-charcoal mb-2">
                        Nama Supplier <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}"
                           placeholder="Contoh: PT. Kopi Nusantara"
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm text-charcoal placeholder-charcoal/40 focus:ring-2 focus:ring-latte focus:border-latte transition-colors duration-200 {{ $errors->has('name') ? 'border-red-400 focus:ring-red-300 focus:border-red-400' : '' }}" />
                    @error('name')
                        <p class="mt-1.5 text-xs text-red-500 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Telepon --}}
                <div>
                    <label for="phone" class="block text-sm font-semibold text-charcoal mb-2">
                        Nomor Kontak / WhatsApp <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="phone" name="phone" value="{{ old('phone') }}"
                           placeholder="Contoh: 08123456789"
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm text-charcoal placeholder-charcoal/40 focus:ring-2 focus:ring-latte focus:border-latte transition-colors duration-200 {{ $errors->has('phone') ? 'border-red-400 focus:ring-red-300 focus:border-red-400' : '' }}" />
                    @error('phone')
                        <p class="mt-1.5 text-xs text-red-500 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Alamat --}}
                <div>
                    <label for="address" class="block text-sm font-semibold text-charcoal mb-2">
                        Alamat <span class="text-red-500">*</span>
                    </label>
                    <textarea id="address" name="address" rows="3"
                              placeholder="Contoh: Jl. Kopi No. 10, Jakarta Selatan"
                              class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm text-charcoal placeholder-charcoal/40 focus:ring-2 focus:ring-latte focus:border-latte transition-colors duration-200 resize-none {{ $errors->has('address') ? 'border-red-400 focus:ring-red-300 focus:border-red-400' : '' }}">{{ old('address') }}</textarea>
                    @error('address')
                        <p class="mt-1.5 text-xs text-red-500 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Tempo Pembayaran --}}
                <div>
                    <label for="payment_terms" class="block text-sm font-semibold text-charcoal mb-2">
                        Tempo Pembayaran (Hari) <span class="text-red-500">*</span>
                    </label>
                    <input type="number" id="payment_terms" name="payment_terms" value="{{ old('payment_terms', 0) }}" min="0"
                           placeholder="0"
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm text-charcoal placeholder-charcoal/40 focus:ring-2 focus:ring-latte focus:border-latte transition-colors duration-200 {{ $errors->has('payment_terms') ? 'border-red-400 focus:ring-red-300 focus:border-red-400' : '' }}" />
                    <p class="mt-1.5 text-xs text-charcoal/50">Masukkan 0 untuk COD (Cash on Delivery).</p>
                    @error('payment_terms')
                        <p class="mt-1 text-xs text-red-500 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Buttons --}}
                <div class="flex items-center gap-3 pt-4 border-t border-gray-100">
                    <button type="submit"
                            class="inline-flex items-center px-6 py-2.5 bg-espresso hover:bg-espresso-light text-white text-sm font-semibold rounded-xl shadow-md shadow-espresso/20 transition-all duration-200 hover:shadow-lg active:scale-95">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Simpan
                    </button>
                    <a href="{{ route('suppliers.index') }}"
                       class="inline-flex items-center px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-charcoal/70 text-sm font-medium rounded-xl transition-all duration-200">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
