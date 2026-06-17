<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-bold text-2xl text-espresso leading-tight">
                {{ __('Edit Produk & Resep') }}
            </h2>
            <a href="{{ route('products.index') }}"
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
            <form method="POST" action="{{ route('products.update', $product) }}" enctype="multipart/form-data" class="p-6 sm:p-8 space-y-8">
                @csrf
                @method('PUT')

                {{-- Product Information Section --}}
                <div class="space-y-6">
                    <h3 class="text-lg font-bold text-espresso border-b border-gray-100 pb-3 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-latte-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Informasi Produk
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Nama Produk --}}
                        <div>
                            <label for="name" class="block text-sm font-semibold text-charcoal mb-2">
                                Nama Produk <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="name" name="name" value="{{ old('name', $product->name) }}"
                                   placeholder="Contoh: Es Kopi Susu Gula Aren"
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
                            <input type="text" id="sku" name="sku" value="{{ old('sku', $product->sku) }}"
                                   placeholder="Contoh: PRD-KOPI-AREN"
                                   class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm text-charcoal placeholder-charcoal/40 focus:ring-2 focus:ring-latte focus:border-latte transition-colors duration-200 {{ $errors->has('sku') ? 'border-red-400 focus:ring-red-300 focus:border-red-400' : '' }}" />
                            @error('sku')
                                <p class="mt-1.5 text-xs text-red-500 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Harga Jual --}}
                        <div>
                            <label for="price" class="block text-sm font-semibold text-charcoal mb-2">
                                Harga Jual (Rp) <span class="text-red-500">*</span>
                            </label>
                            <input type="number" id="price" name="price" value="{{ old('price', $product->price) }}" min="0" step="0.01"
                                   placeholder="Contoh: 18000"
                                   class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm text-charcoal placeholder-charcoal/40 focus:ring-2 focus:ring-latte focus:border-latte transition-colors duration-200 {{ $errors->has('price') ? 'border-red-400 focus:ring-red-300 focus:border-red-400' : '' }}" />
                            @error('price')
                                <p class="mt-1.5 text-xs text-red-500 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Kategori --}}
                        <div>
                            <label for="category" class="block text-sm font-semibold text-charcoal mb-2">
                                Kategori <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="category" name="category" value="{{ old('category', $product->category) }}"
                                   placeholder="Contoh: Kopi, Non-Kopi, Makanan"
                                   class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm text-charcoal placeholder-charcoal/40 focus:ring-2 focus:ring-latte focus:border-latte transition-colors duration-200 {{ $errors->has('category') ? 'border-red-400 focus:ring-red-300 focus:border-red-400' : '' }}" />
                            @error('category')
                                <p class="mt-1.5 text-xs text-red-500 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Foto Produk --}}
                        <div class="col-span-1 md:col-span-2">
                            <label for="image" class="block text-sm font-semibold text-charcoal mb-2">
                                Foto Produk
                            </label>
                            @if ($product->image)
                                <div class="mb-3 flex items-center space-x-3 bg-gray-50 p-2.5 rounded-xl border border-gray-100 max-w-max">
                                    <img src="{{ $product->image_url }}" alt="Preview" class="w-12 h-12 object-cover rounded-lg border border-gray-200">
                                    <span class="text-xs text-charcoal/50 font-semibold">Foto saat ini</span>
                                </div>
                            @endif
                            <input type="file" id="image" name="image" accept="image/*"
                                   class="w-full px-4 py-2 border border-gray-200 rounded-xl text-sm text-charcoal file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-espresso/10 file:text-espresso hover:file:bg-espresso/20 transition-colors duration-200 {{ $errors->has('image') ? 'border-red-400' : '' }}" />
                            <p class="mt-1.5 text-xs text-charcoal/50">Format: JPG, JPEG, PNG, WEBP. Maksimal 2MB. Biarkan kosong jika tidak ingin mengubah.</p>
                            @error('image')
                                <p class="mt-1.5 text-xs text-red-500 font-medium">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Status Aktif --}}
                    <div class="flex items-center space-x-3 bg-gray-50 p-4 rounded-xl border border-gray-100 max-w-max">
                        <input type="checkbox" id="is_active" name="is_active" value="1" 
                               {{ old('is_active', $product->is_active ? '1' : '0') == '1' ? 'checked' : '' }}
                               class="w-4 h-4 text-espresso border-gray-300 rounded focus:ring-latte" />
                        <label for="is_active" class="text-sm font-semibold text-charcoal">
                            Aktif (Dapat dijual ke customer)
                        </label>
                    </div>
                </div>

                {{-- Recipe / Bill of Materials Section --}}
                @php
                    $oldIngredients = old('ingredients');
                    if (empty($oldIngredients)) {
                        $oldIngredients = [];
                        foreach ($product->ingredients as $ing) {
                            $oldIngredients[] = [
                                'ingredient_id' => (string) $ing->id,
                                'quantity' => (string) $ing->pivot->quantity
                            ];
                        }
                    } else {
                        $oldIngredients = array_values(array_map(function($item) {
                            return [
                                'ingredient_id' => $item['ingredient_id'] ?? '',
                                'quantity' => $item['quantity'] ?? ''
                            ];
                        }, $oldIngredients));
                    }

                    if (empty($oldIngredients)) {
                        $oldIngredients = [['ingredient_id' => '', 'quantity' => '']];
                    }
                @endphp

                <div class="space-y-6"
                     x-data="{ 
                         rows: {{ json_encode($oldIngredients) }},
                         addIngredient() {
                             this.rows.push({ ingredient_id: '', quantity: '' });
                         },
                         removeIngredient(index) {
                             if (this.rows.length > 1) {
                                 this.rows.splice(index, 1);
                             } else {
                                 alert('Minimal harus ada 1 bahan baku di dalam resep.');
                             }
                         }
                     }">
                    
                    <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                        <h3 class="text-lg font-bold text-espresso flex items-center">
                            <svg class="w-5 h-5 mr-2 text-latte-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                            </svg>
                            Resep & Takaran Bahan Baku (Bill of Materials)
                        </h3>
                        <button type="button" @click="addIngredient()"
                                class="inline-flex items-center px-4 py-2 bg-latte hover:bg-latte-dark text-espresso text-xs font-bold rounded-xl transition-all duration-200 shadow-sm hover:shadow active:scale-95">
                            <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Tambah Bahan
                        </button>
                    </div>

                    @if ($errors->has('ingredients'))
                        <div class="p-3.5 bg-red-50 border border-red-200 text-red-700 text-xs font-semibold rounded-xl">
                            {{ $errors->first('ingredients') }}
                        </div>
                    @endif

                    {{-- Ingredient Rows --}}
                    <div class="space-y-4">
                        <template x-for="(row, index) in rows" :key="index">
                            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4 bg-gray-50/50 border border-gray-200/40 p-4 rounded-xl">
                                
                                {{-- Dropdown Pilihan Bahan --}}
                                <div class="flex-1 w-full">
                                    <label class="block text-xs font-bold text-charcoal/60 mb-1">Pilih Bahan Baku <span class="text-red-500">*</span></label>
                                    <select :name="'ingredients['+index+'][ingredient_id]'" 
                                            x-model="row.ingredient_id"
                                            class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm text-charcoal focus:ring-2 focus:ring-latte focus:border-latte transition-colors duration-200"
                                            required>
                                        <option value="" disabled>Pilih bahan...</option>
                                        @foreach($ingredients as $ingredient)
                                            <option value="{{ $ingredient->id }}">
                                                {{ $ingredient->name }} (SKU: {{ $ingredient->sku }}, Satuan: {{ $ingredient->unit }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Input Quantity --}}
                                <div class="w-full sm:w-48">
                                    <label class="block text-xs font-bold text-charcoal/60 mb-1">Takaran / Porsi <span class="text-red-500">*</span></label>
                                    <div class="relative">
                                        <input type="number" step="0.01" min="0.01" :name="'ingredients['+index+'][quantity]'" 
                                               x-model="row.quantity"
                                               placeholder="0.00"
                                               class="w-full pl-3 pr-12 py-2 border border-gray-200 rounded-lg text-sm text-charcoal focus:ring-2 focus:ring-latte focus:border-latte transition-colors duration-200"
                                               required />
                                        
                                        <span class="absolute right-3 top-1/2 -translate-y-1/2 text-xs font-semibold text-charcoal/40">
                                            nilai
                                        </span>
                                    </div>
                                </div>

                                {{-- Tombol Hapus Baris --}}
                                <div class="sm:pt-5 w-full sm:w-auto text-right">
                                    <button type="button" @click="removeIngredient(index)"
                                            class="inline-flex items-center justify-center p-2 text-charcoal/40 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all duration-200"
                                            title="Hapus Bahan">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </template>
                    </div>

                    {{-- Manual Error display for array validations --}}
                    @if ($errors->any())
                        <div class="p-4 bg-red-50 border border-red-100 rounded-xl space-y-1">
                            <p class="text-xs font-bold text-red-800">Detail Kesalahan Resep:</p>
                            <ul class="list-disc list-inside text-xs text-red-700">
                                @foreach ($errors->all() as $error)
                                    @if(str_contains($error, 'resep') || str_contains($error, 'bahan baku') || str_contains($error, 'takaran'))
                                        <li>{{ $error }}</li>
                                    @endif
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>

                {{-- Action Buttons --}}
                <div class="flex items-center gap-3 pt-6 border-t border-gray-100">
                    <button type="submit"
                            class="inline-flex items-center px-6 py-2.5 bg-espresso hover:bg-espresso-light text-white text-sm font-semibold rounded-xl shadow-md shadow-espresso/20 transition-all duration-200 hover:shadow-lg active:scale-95">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Simpan Perubahan
                    </button>
                    <a href="{{ route('products.index') }}"
                       class="inline-flex items-center px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-charcoal/70 text-sm font-medium rounded-xl transition-all duration-200">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
