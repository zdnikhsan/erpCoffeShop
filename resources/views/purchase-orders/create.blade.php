<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-bold text-2xl text-espresso leading-tight">
                {{ __('Buat Purchase Order') }}
            </h2>
            <a href="{{ route('purchase-orders.index') }}"
               class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-charcoal/70 text-sm font-medium rounded-xl transition-all duration-200 active:scale-95">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-6 space-y-6">
        {{-- Error Flash --}}
        @if (session('error'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
                 x-transition:leave="transition ease-in duration-300"
                 x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                 class="flex items-center p-4 bg-red-50 border border-red-200 text-red-800 rounded-xl">
                <svg class="w-5 h-5 mr-3 text-red-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <span class="text-sm font-medium">{{ session('error') }}</span>
            </div>
        @endif

        <div class="bg-white border border-gray-200/60 overflow-hidden shadow-sm rounded-2xl">
            @php
                $oldIngredients = old('ingredients');
                if (empty($oldIngredients)) {
                    $oldIngredients = [['ingredient_id' => '', 'quantity' => '', 'unit_price' => '']];
                } else {
                    $oldIngredients = array_values(array_map(fn($item) => [
                        'ingredient_id' => $item['ingredient_id'] ?? '',
                        'quantity'      => $item['quantity'] ?? '',
                        'unit_price'    => $item['unit_price'] ?? '',
                    ], $oldIngredients));
                }
            @endphp

            <form method="POST" action="{{ route('purchase-orders.store') }}" class="p-6 sm:p-8 space-y-8"
                  x-data="{
                      rows: {{ json_encode($oldIngredients) }},
                      addRow() {
                          this.rows.push({ ingredient_id: '', quantity: '', unit_price: '' });
                      },
                      removeRow(index) {
                          if (this.rows.length > 1) {
                              this.rows.splice(index, 1);
                          } else {
                              alert('Minimal harus ada 1 bahan baku.');
                          }
                      },
                      get totalAmount() {
                          return this.rows.reduce((sum, row) => {
                              const qty = parseFloat(row.quantity) || 0;
                              const price = parseFloat(row.unit_price) || 0;
                              return sum + (qty * price);
                          }, 0);
                      },
                      formatRupiah(val) {
                          return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(val);
                      }
                  }">
                @csrf

                {{-- Informasi PO --}}
                <div class="space-y-6">
                    <h3 class="text-lg font-bold text-espresso border-b border-gray-100 pb-3 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-latte-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Informasi Purchase Order
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Supplier --}}
                        <div>
                            <label for="supplier_id" class="block text-sm font-semibold text-charcoal mb-2">
                                Supplier <span class="text-red-500">*</span>
                            </label>
                            <select id="supplier_id" name="supplier_id"
                                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm text-charcoal focus:ring-2 focus:ring-latte focus:border-latte transition-colors duration-200 {{ $errors->has('supplier_id') ? 'border-red-400 focus:ring-red-300 focus:border-red-400' : '' }}">
                                <option value="" disabled {{ old('supplier_id') ? '' : 'selected' }}>Pilih supplier...</option>
                                @foreach ($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                        {{ $supplier->name }} — {{ $supplier->phone }}
                                    </option>
                                @endforeach
                            </select>
                            @error('supplier_id')
                                <p class="mt-1.5 text-xs text-red-500 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Tanggal Order --}}
                        <div>
                            <label for="order_date" class="block text-sm font-semibold text-charcoal mb-2">
                                Tanggal Order <span class="text-red-500">*</span>
                            </label>
                            <input type="date" id="order_date" name="order_date" value="{{ old('order_date', now()->format('Y-m-d')) }}"
                                   class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm text-charcoal focus:ring-2 focus:ring-latte focus:border-latte transition-colors duration-200 {{ $errors->has('order_date') ? 'border-red-400 focus:ring-red-300 focus:border-red-400' : '' }}" />
                            @error('order_date')
                                <p class="mt-1.5 text-xs text-red-500 font-medium">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Daftar Bahan Baku --}}
                <div class="space-y-6">
                    <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                        <h3 class="text-lg font-bold text-espresso flex items-center">
                            <svg class="w-5 h-5 mr-2 text-latte-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                            </svg>
                            Daftar Bahan Baku Pesanan
                        </h3>
                        <button type="button" @click="addRow()"
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
                            <div class="flex flex-col sm:flex-row items-start sm:items-end gap-4 bg-gray-50/50 border border-gray-200/40 p-4 rounded-xl">

                                {{-- Pilih Bahan --}}
                                <div class="flex-1 w-full">
                                    <label class="block text-xs font-bold text-charcoal/60 mb-1">Bahan Baku <span class="text-red-500">*</span></label>
                                    <select :name="'ingredients['+index+'][ingredient_id]'"
                                            x-model="row.ingredient_id"
                                            class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm text-charcoal focus:ring-2 focus:ring-latte focus:border-latte transition-colors duration-200"
                                            required>
                                        <option value="" disabled>Pilih bahan...</option>
                                        @foreach($ingredients as $ingredient)
                                            <option value="{{ $ingredient->id }}">
                                                {{ $ingredient->name }} ({{ $ingredient->unit }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Jumlah --}}
                                <div class="w-full sm:w-40">
                                    <label class="block text-xs font-bold text-charcoal/60 mb-1">Jumlah <span class="text-red-500">*</span></label>
                                    <input type="number" step="0.01" min="0.01"
                                           :name="'ingredients['+index+'][quantity]'"
                                           x-model="row.quantity"
                                           placeholder="0.00"
                                           class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm text-charcoal focus:ring-2 focus:ring-latte focus:border-latte transition-colors duration-200"
                                           required />
                                </div>

                                {{-- Harga Satuan --}}
                                <div class="w-full sm:w-48">
                                    <label class="block text-xs font-bold text-charcoal/60 mb-1">Harga Satuan (Rp) <span class="text-red-500">*</span></label>
                                    <input type="number" step="0.01" min="0"
                                           :name="'ingredients['+index+'][unit_price]'"
                                           x-model="row.unit_price"
                                           placeholder="0"
                                           class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm text-charcoal focus:ring-2 focus:ring-latte focus:border-latte transition-colors duration-200"
                                           required />
                                </div>

                                {{-- Subtotal Display --}}
                                <div class="w-full sm:w-44">
                                    <label class="block text-xs font-bold text-charcoal/60 mb-1">Subtotal</label>
                                    <div class="px-3 py-2 bg-espresso/5 border border-espresso/10 rounded-lg text-sm font-bold text-espresso text-right"
                                         x-text="formatRupiah((parseFloat(row.quantity) || 0) * (parseFloat(row.unit_price) || 0))">
                                        Rp 0
                                    </div>
                                </div>

                                {{-- Hapus --}}
                                <div class="w-full sm:w-auto text-right">
                                    <button type="button" @click="removeRow(index)"
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

                    {{-- Validation errors --}}
                    @if ($errors->any())
                        @php
                            $ingredientErrors = collect($errors->all())->filter(fn($e) => str_contains($e, 'bahan') || str_contains($e, 'harga') || str_contains($e, 'jumlah'));
                        @endphp
                        @if ($ingredientErrors->isNotEmpty())
                            <div class="p-4 bg-red-50 border border-red-100 rounded-xl space-y-1">
                                <p class="text-xs font-bold text-red-800">Detail Kesalahan:</p>
                                <ul class="list-disc list-inside text-xs text-red-700">
                                    @foreach ($ingredientErrors as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    @endif

                    {{-- Grand Total --}}
                    <div class="flex items-center justify-end gap-4 p-4 bg-espresso/5 border border-espresso/10 rounded-xl">
                        <span class="text-sm font-bold text-charcoal/70">Total Estimasi:</span>
                        <span class="text-xl font-extrabold text-espresso" x-text="formatRupiah(totalAmount)">Rp 0</span>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="flex items-center gap-3 pt-6 border-t border-gray-100">
                    <button type="submit"
                            class="inline-flex items-center px-6 py-2.5 bg-espresso hover:bg-espresso-light text-white text-sm font-semibold rounded-xl shadow-md shadow-espresso/20 transition-all duration-200 hover:shadow-lg active:scale-95">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Simpan Purchase Order
                    </button>
                    <a href="{{ route('purchase-orders.index') }}"
                       class="inline-flex items-center px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-charcoal/70 text-sm font-medium rounded-xl transition-all duration-200">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
