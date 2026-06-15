<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-bold text-2xl text-espresso leading-tight">
                {{ __('Tambah Bahan Baku') }}
            </h2>
            <a href="{{ route('ingredients.index') }}"
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
            <form method="POST" action="{{ route('ingredients.store') }}" class="p-6 sm:p-8 space-y-6">
                @csrf

                {{-- Nama Bahan Baku --}}
                <div>
                    <label for="name" class="block text-sm font-semibold text-charcoal mb-2">
                        Nama Bahan Baku <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}"
                           placeholder="Contoh: Biji Kopi Arabika"
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm text-charcoal placeholder-charcoal/40 focus:ring-2 focus:ring-latte focus:border-latte transition-colors duration-200 {{ $errors->has('name') ? 'border-red-400 focus:ring-red-300 focus:border-red-400' : '' }}" />
                    @error('name')
                        <p class="mt-1.5 text-xs text-red-500 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Kode SKU --}}
                <div>
                    <label for="sku" class="block text-sm font-semibold text-charcoal mb-2">
                        Kode SKU <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="sku" name="sku" value="{{ old('sku') }}"
                           placeholder="Contoh: BB-ARABIKA-001"
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm text-charcoal placeholder-charcoal/40 focus:ring-2 focus:ring-latte focus:border-latte transition-colors duration-200 {{ $errors->has('sku') ? 'border-red-400 focus:ring-red-300 focus:border-red-400' : '' }}" />
                    <p class="mt-1.5 text-xs text-charcoal/50">Kode unik untuk identifikasi bahan baku.</p>
                    @error('sku')
                        <p class="mt-1 text-xs text-red-500 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Stok & Satuan (2 kolom) --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    {{-- Stok Awal --}}
                    <div>
                        <label for="stock" class="block text-sm font-semibold text-charcoal mb-2">
                            Stok Awal <span class="text-red-500">*</span>
                        </label>
                        <input type="number" id="stock" name="stock" value="{{ old('stock', '0.00') }}" min="0" step="0.01"
                               placeholder="0.00"
                               class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm text-charcoal placeholder-charcoal/40 focus:ring-2 focus:ring-latte focus:border-latte transition-colors duration-200 {{ $errors->has('stock') ? 'border-red-400 focus:ring-red-300 focus:border-red-400' : '' }}" />
                        @error('stock')
                            <p class="mt-1.5 text-xs text-red-500 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Satuan --}}
                    <div>
                        <label for="unit" class="block text-sm font-semibold text-charcoal mb-2">
                            Satuan <span class="text-red-500">*</span>
                        </label>
                        <select id="unit" name="unit"
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm text-charcoal focus:ring-2 focus:ring-latte focus:border-latte transition-colors duration-200 {{ $errors->has('unit') ? 'border-red-400 focus:ring-red-300 focus:border-red-400' : '' }}">
                            <option value="" disabled {{ old('unit') ? '' : 'selected' }}>Pilih satuan...</option>
                            <option value="gram" {{ old('unit') === 'gram' ? 'selected' : '' }}>Gram (g)</option>
                            <option value="kg" {{ old('unit') === 'kg' ? 'selected' : '' }}>Kilogram (kg)</option>
                            <option value="ml" {{ old('unit') === 'ml' ? 'selected' : '' }}>Mililiter (ml)</option>
                            <option value="liter" {{ old('unit') === 'liter' ? 'selected' : '' }}>Liter (L)</option>
                            <option value="pcs" {{ old('unit') === 'pcs' ? 'selected' : '' }}>Pieces (pcs)</option>
                        </select>
                        @error('unit')
                            <p class="mt-1.5 text-xs text-red-500 font-medium">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Safety Stock --}}
                <div>
                    <label for="safety_stock" class="block text-sm font-semibold text-charcoal mb-2">
                        Safety Stock <span class="text-red-500">*</span>
                    </label>
                    <input type="number" id="safety_stock" name="safety_stock" value="{{ old('safety_stock', '0.00') }}" min="0" step="0.01"
                           placeholder="0.00"
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm text-charcoal placeholder-charcoal/40 focus:ring-2 focus:ring-latte focus:border-latte transition-colors duration-200 {{ $errors->has('safety_stock') ? 'border-red-400 focus:ring-red-300 focus:border-red-400' : '' }}" />
                    <p class="mt-1.5 text-xs text-charcoal/50">Alert akan muncul jika stok di bawah angka ini.</p>
                    @error('safety_stock')
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
                    <a href="{{ route('ingredients.index') }}"
                       class="inline-flex items-center px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-charcoal/70 text-sm font-medium rounded-xl transition-all duration-200">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
