<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-bold text-2xl text-espresso leading-tight">
                {{ __('Operasional Kasir (POS)') }}
            </h2>
            <div class="flex items-center space-x-2 text-sm text-charcoal/60 bg-white px-4 py-2 rounded-xl border border-gray-200/50 shadow-sm">
                <span class="w-2.5 h-2.5 bg-green-500 rounded-full animate-pulse"></span>
                <span>Kasir Aktif: <strong class="text-espresso font-semibold">{{ Auth::user()->name }}</strong></span>
            </div>
        </div>
    </x-slot>

    <!-- Custom CSS for thermal print -->
    @push('styles')
    <style>
        @media print {
            body * {
                visibility: hidden;
            }
            #thermal-receipt, #thermal-receipt * {
                visibility: visible;
            }
            #thermal-receipt {
                position: absolute;
                left: 0;
                top: 0;
                width: 80mm;
                padding: 5px;
                background: white;
                color: black;
                font-family: 'Courier New', Courier, monospace;
                font-size: 12px;
            }
            .no-print {
                display: none !important;
            }
        }
    </style>
    @endpush

    <!-- Main Container x-data Alpine.js -->
    <div x-data='posApp(@json($products))' 
         x-init="$nextTick(() => { 
             const applyHeight = () => {
                 if (window.innerWidth >= 768) {
                     const r = $el.getBoundingClientRect(); 
                     $el.style.height = (window.innerHeight - r.top - 16) + 'px';
                     $el.style.overflow = 'hidden';
                     $el.closest('.overflow-y-auto')?.classList.add('overflow-hidden');
                 } else {
                     $el.style.height = 'auto';
                     $el.style.overflow = 'visible';
                     $el.closest('.overflow-y-auto')?.classList.remove('overflow-hidden');
                 }
             };
             applyHeight();
             window.addEventListener('resize', applyHeight);
         })"
         class="py-2 flex flex-col md:flex-row gap-4 relative" x-cloak>
        
        <!-- TOP ERROR & SUCCESS TOASTS -->
        <div class="fixed top-6 right-6 z-[100] flex flex-col gap-3 pointer-events-none max-w-md w-full">
            <!-- Error Toast -->
            <template x-if="errorMessage">
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => { show = false; errorMessage = ''; }, 6000)"
                     x-transition:enter="transition ease-out duration-300 transform"
                     x-transition:enter-start="opacity-0 translate-y-2 lg:translate-y-0 lg:translate-x-2"
                     x-transition:enter-end="opacity-100 translate-y-0 lg:translate-x-0"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     class="flex items-start p-4 bg-red-50 border-l-4 border-red-500 rounded-xl shadow-lg text-red-800 pointer-events-auto">
                    <svg class="w-5 h-5 mr-3 text-red-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div class="flex-1">
                        <h4 class="font-bold text-sm text-red-900">Transaksi Gagal</h4>
                        <p class="text-xs mt-1 text-red-800 font-medium" x-text="errorMessage"></p>
                    </div>
                    <button @click="errorMessage = ''" class="text-red-400 hover:text-red-600 pl-2 shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </template>

            <!-- Success Toast -->
            <template x-if="successMessage">
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => { show = false; successMessage = ''; }, 4000)"
                     x-transition:enter="transition ease-out duration-300 transform"
                     x-transition:enter-start="opacity-0 translate-y-2 lg:translate-y-0 lg:translate-x-2"
                     x-transition:enter-end="opacity-100 translate-y-0 lg:translate-x-0"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     class="flex items-start p-4 bg-green-50 border-l-4 border-green-500 rounded-xl shadow-lg text-green-800 pointer-events-auto">
                    <svg class="w-5 h-5 mr-3 text-green-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div class="flex-1">
                        <h4 class="font-bold text-sm text-green-900">Berhasil</h4>
                        <p class="text-xs mt-1 text-green-800 font-medium" x-text="successMessage"></p>
                    </div>
                    <button @click="successMessage = ''" class="text-green-400 hover:text-green-600 pl-2 shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </template>
        </div>

        <!-- MOBILE: Floating Cart Button -->
        <div x-show="cart.length > 0" 
             class="md:hidden fixed bottom-4 right-4 z-50"
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="scale-0 opacity-0"
             x-transition:enter-end="scale-100 opacity-100">
            <a href="#mobile-cart" 
               class="flex items-center space-x-2 bg-espresso hover:bg-espresso-light text-white px-5 py-3 rounded-2xl shadow-xl shadow-espresso/30 transition-all active:scale-95">
                <svg class="w-5 h-5 text-latte" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <span class="font-bold text-sm">Keranjang</span>
                <span class="bg-latte text-espresso text-xs font-black px-2 py-0.5 rounded-full" x-text="cartTotalQuantity()"></span>
                <span class="font-bold text-sm" x-text="formatRupiah(totalPay())"></span>
            </a>
        </div>

        <!-- LEFT COLUMN: KATALOG PRODUK -->
        <div class="flex-1 flex flex-col space-y-3 min-h-0 min-w-0 md:overflow-hidden">
            
            <!-- Filter & Search Controls -->
            <div class="bg-white border border-gray-200/60 rounded-2xl shadow-sm p-3 flex flex-col md:flex-row gap-3 justify-between items-center shrink-0">
                <!-- Search Box -->
                <div class="relative w-full md:max-w-xs">
                    <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-charcoal/40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input type="text" x-model="search" placeholder="Cari nama menu / SKU..."
                           class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-xl text-sm text-charcoal placeholder-charcoal/40 focus:ring-2 focus:ring-latte focus:border-latte transition-all duration-200" />
                </div>

                <!-- Category Filters (Dropdown) -->
                <div class="w-full md:w-48 shrink-0">
                    <select x-model="activeCategory" 
                            class="w-full pl-3 pr-8 py-2 border border-gray-200 rounded-xl text-sm font-bold text-espresso bg-white focus:ring-2 focus:ring-latte focus:border-latte transition-all duration-200 shadow-sm">
                        <option value="Semua">Semua Kategori</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}">{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Products Grid -->
            <div class="md:flex-1 md:overflow-y-auto md:min-h-0 pr-1 -mr-1">
                <!-- Empty State -->
                <template x-if="filteredProducts.length === 0">
                    <div class="flex flex-col items-center justify-center py-20 text-center space-y-4">
                        <div class="p-5 bg-gradient-to-br from-gray-100 to-gray-50 rounded-2xl shadow-inner">
                            <svg class="w-10 h-10 text-charcoal/20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-charcoal/50 font-bold">Produk Tidak Ditemukan</p>
                            <p class="text-xs text-charcoal/30 mt-1">Coba ubah kata kunci pencarian atau filter kategori.</p>
                        </div>
                    </div>
                </template>

                <!-- Product Cards Grid -->
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-3">
                    <template x-for="product in filteredProducts" :key="product.id">
                        <div class="group relative bg-white border border-gray-200/60 rounded-2xl shadow-sm hover:shadow-lg hover:border-gray-300/80 transition-all duration-300 flex flex-col overflow-hidden"
                             :class="{ 'opacity-50 grayscale pointer-events-none': isOutOfStock(product, 1) && getCartQty(product.id) === 0 }">
                            
                            <!-- Cart Qty Badge (floating top-left) -->
                            <div x-show="getCartQty(product.id) > 0"
                                 x-transition:enter="transition ease-out duration-200 transform"
                                 x-transition:enter-start="scale-0 opacity-0"
                                 x-transition:enter-end="scale-100 opacity-100"
                                 class="absolute top-2 left-2 z-10 bg-espresso text-white text-[10px] font-black w-6 h-6 flex items-center justify-center rounded-full shadow-md ring-2 ring-white"
                                 x-text="getCartQty(product.id)">
                            </div>

                            <!-- Out of Stock Badge -->
                            <template x-if="isOutOfStock(product, 1) && getCartQty(product.id) === 0">
                                <div class="absolute top-2 right-2 z-10 bg-red-500 text-white text-[9px] font-black px-2 py-0.5 rounded-full shadow">
                                    HABIS
                                </div>
                            </template>

                            <!-- Product Image -->
                            <div class="relative w-full aspect-square bg-gradient-to-br from-gray-100 to-gray-50 overflow-hidden">
                                <img :src="product.image_url" 
                                     :alt="product.name"
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                <!-- Fallback icon if no image -->
                                <div class="w-full h-full items-center justify-center text-charcoal/15 hidden absolute inset-0">
                                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <!-- Hover overlay: quick add -->
                                <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end justify-center pb-3">
                                    <button @click="addToCart(product)"
                                            class="bg-white/95 hover:bg-white text-espresso font-bold text-xs px-5 py-2 rounded-xl shadow-lg transform translate-y-2 group-hover:translate-y-0 transition-all duration-300 flex items-center space-x-1.5 backdrop-blur-sm">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                                        </svg>
                                        <span>Tambah</span>
                                    </button>
                                </div>
                            </div>

                            <!-- Product Info -->
                            <div class="flex-1 p-3 flex flex-col justify-between gap-2">
                                <div>
                                    <!-- Category Badge -->
                                    <span class="inline-block text-[9px] font-bold tracking-wider uppercase px-2 py-0.5 rounded-md bg-latte/30 text-espresso/70 mb-1.5"
                                          x-text="product.category"></span>
                                    <!-- Name -->
                                    <h3 class="font-bold text-sm text-espresso leading-snug line-clamp-2" x-text="product.name"></h3>
                                    <!-- SKU -->
                                    <p class="text-[10px] text-charcoal/40 font-mono mt-0.5" x-text="product.sku"></p>
                                </div>

                                <!-- Price & Add Controls Row -->
                                <div class="flex items-center justify-between gap-1 mt-1">
                                    <!-- Price -->
                                    <span class="text-sm font-extrabold text-espresso-dark" x-text="formatRupiah(product.price)"></span>

                                    <!-- Quick Add/Qty Controls -->
                                    <div class="shrink-0">
                                        <!-- If NOT in cart: show Add button -->
                                        <template x-if="getCartQty(product.id) === 0">
                                            <button @click="addToCart(product)"
                                                    class="w-8 h-8 flex items-center justify-center bg-espresso hover:bg-espresso-light text-white rounded-xl transition-all duration-200 shadow-sm hover:shadow active:scale-90">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                                                </svg>
                                            </button>
                                        </template>
                                        <!-- If IN cart: show -/qty/+ controls -->
                                        <template x-if="getCartQty(product.id) > 0">
                                            <div class="flex items-center space-x-1 bg-espresso/5 border border-espresso/20 rounded-xl p-0.5">
                                                <button @click="decrementQty(cart.find(i => i.id === product.id))"
                                                        class="w-6 h-6 flex items-center justify-center bg-white hover:bg-red-50 text-espresso hover:text-red-500 rounded-lg transition-colors text-xs font-bold shadow-sm">
                                                    −
                                                </button>
                                                <span class="text-xs font-black text-espresso w-5 text-center" x-text="getCartQty(product.id)"></span>
                                                <button @click="addToCart(product)"
                                                        class="w-6 h-6 flex items-center justify-center bg-espresso hover:bg-espresso-light text-white rounded-lg transition-colors text-xs font-bold shadow-sm">
                                                    +
                                                </button>
                                            </div>
                                        </template>
                                    </div>
                                </div>

                                <!-- Stock indicator bar -->
                                <div class="mt-1">
                                    <template x-if="portionsAvailable(product) <= 10 && portionsAvailable(product) > 0">
                                        <div class="flex items-center space-x-1">
                                            <div class="flex-1 h-1 bg-gray-100 rounded-full overflow-hidden">
                                                <div class="h-full bg-amber-400 rounded-full transition-all duration-500"
                                                     :style="'width:' + Math.min(100, (portionsAvailable(product) / 10) * 100) + '%'"></div>
                                            </div>
                                            <span class="text-[9px] font-bold text-amber-600" x-text="'Sisa ' + portionsAvailable(product)"></span>
                                        </div>
                                    </template>
                                    <template x-if="portionsAvailable(product) > 10">
                                        <div class="flex items-center space-x-1">
                                            <span class="w-1.5 h-1.5 bg-green-500 rounded-full"></span>
                                            <span class="text-[9px] font-semibold text-green-600">Stok Tersedia</span>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        <!-- RIGHT COLUMN: SHOPPING CART (KERANJANG BELANJA) -->
        <div id="mobile-cart" class="w-full md:w-80 lg:w-96 shrink-0 flex flex-col min-h-0 md:overflow-hidden scroll-mt-4">
            <div class="bg-white border border-gray-200/60 rounded-2xl shadow-sm flex flex-col justify-between h-full overflow-hidden">
                <!-- Cart Header -->
                <div class="p-4 border-b border-gray-100 flex items-center justify-between bg-espresso-dark rounded-t-2xl text-white">
                    <div class="flex items-center space-x-2">
                        <svg class="w-5 h-5 text-latte" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <span class="font-extrabold text-sm tracking-wide">KERANJANG BELANJA</span>
                        <span class="bg-latte text-espresso text-xs font-black px-2 py-0.5 rounded-full" x-text="cartTotalQuantity()"></span>
                    </div>
                    <button @click="clearCart()" x-show="cart.length > 0" class="text-xs text-white/70 hover:text-white font-bold hover:underline transition-all">
                        Reset
                    </button>
                </div>

                <!-- Cart Items List (Scrollable) -->
                <div class="flex-1 overflow-y-auto p-4 space-y-3.5 min-h-0">
                    <template x-for="item in cart" :key="item.id">
                        <div class="flex items-center justify-between gap-3 p-2 bg-gray-50 border border-gray-100 rounded-xl hover:border-gray-200 transition-all">
                            <!-- Small thumbnail -->
                            <img :src="item.image_url" class="w-10 h-10 object-cover rounded-lg shrink-0 border border-gray-200/50">
                            
                            <!-- Item name and cost -->
                            <div class="flex-1 min-w-0">
                                <h4 class="font-bold text-xs text-espresso truncate" x-text="item.name"></h4>
                                <span class="text-[10px] text-charcoal/50 font-semibold" x-text="formatRupiah(item.price)"></span>
                            </div>

                            <!-- Qty Controls -->
                            <div class="flex items-center space-x-1.5 shrink-0 bg-white border border-gray-200/60 rounded-lg p-0.5">
                                <button @click="decrementQty(item)" class="w-5 h-5 flex items-center justify-center bg-gray-50 text-charcoal hover:bg-espresso-light hover:text-white rounded transition-colors text-xs font-bold">-</button>
                                <span class="text-xs font-bold text-charcoal w-4 text-center" x-text="item.qty"></span>
                                <button @click="incrementQty(item)" class="w-5 h-5 flex items-center justify-center bg-gray-50 text-charcoal hover:bg-espresso-light hover:text-white rounded transition-colors text-xs font-bold">+</button>
                            </div>

                            <!-- Total & Delete -->
                            <div class="text-right shrink-0 flex flex-col items-end">
                                <span class="text-xs font-extrabold text-espresso-dark" x-text="formatRupiah(item.price * item.qty)"></span>
                                <button @click="removeItem(item)" class="text-[10px] text-red-500 hover:text-red-700 font-bold mt-0.5">Hapus</button>
                            </div>
                        </div>
                    </template>

                    <!-- Empty State Cart -->
                    <template x-if="cart.length === 0">
                        <div class="flex flex-col items-center justify-center py-12 text-center space-y-3">
                            <div class="p-3 bg-gray-100 text-charcoal/30 rounded-full">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                </svg>
                            </div>
                            <p class="text-xs text-charcoal/40 font-bold">Keranjang Masih Kosong</p>
                            <span class="text-[10px] text-charcoal/30 px-4">Klik tombol tambah (+) pada daftar produk di sebelah kiri untuk berbelanja.</span>
                        </div>
                    </template>
                </div>

                <!-- Transaction Detail Inputs -->
                <div class="p-4 border-t border-gray-100 bg-gray-50/50 space-y-3 rounded-b-2xl shrink-0 overflow-y-auto max-h-[50%]">
                    <!-- Order Type Selector -->
                    <div class="space-y-1">
                        <label class="text-[10px] font-black text-espresso/60 tracking-wider block">TIPE PESANAN</label>
                        <div class="grid grid-cols-3 gap-1 bg-gray-100 p-0.5 rounded-lg border border-gray-200/50">
                            <button @click="orderType = 'dine_in'" :class="orderType === 'dine_in' ? 'bg-white text-espresso font-bold shadow-sm' : 'text-charcoal/70'"
                                    class="py-1 text-xs rounded-md transition-all">Dine In</button>
                            <button @click="orderType = 'takeaway'" :class="orderType === 'takeaway' ? 'bg-white text-espresso font-bold shadow-sm' : 'text-charcoal/70'"
                                    class="py-1 text-xs rounded-md transition-all">Takeaway</button>
                            <button @click="orderType = 'delivery'" :class="orderType === 'delivery' ? 'bg-white text-espresso font-bold shadow-sm' : 'text-charcoal/70'"
                                    class="py-1 text-xs rounded-md transition-all font-medium">Delivery</button>
                        </div>
                    </div>

                    <!-- Table Number (Only for Dine In) -->
                    <div x-show="orderType === 'dine_in'" x-collapse class="space-y-1">
                        <label class="text-[10px] font-black text-espresso/60 tracking-wider block">NOMOR MEJA</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-charcoal/30 text-xs">#</span>
                            <input type="text" x-model="tableNumber" placeholder="Contoh: Meja 05"
                                   class="w-full pl-7 pr-3 py-1.5 border border-gray-200 rounded-xl text-xs focus:ring-1 focus:ring-latte focus:border-latte transition-all" />
                        </div>
                    </div>

                    <!-- Payment Method Selector -->
                    <div class="space-y-1">
                        <label class="text-[10px] font-black text-espresso/60 tracking-wider block">METODE PEMBAYARAN</label>
                        <div class="grid grid-cols-3 gap-1">
                            <button @click="paymentMethod = 'Cash'" :class="paymentMethod === 'Cash' ? 'border-espresso bg-espresso/5 text-espresso font-bold' : 'border-gray-200 text-charcoal/60 bg-white'"
                                    class="py-1.5 text-xs border rounded-xl transition-all flex items-center justify-center space-x-1">
                                <span class="text-[10px]">💵</span> <span>Cash</span>
                            </button>
                            <button @click="paymentMethod = 'QRIS'" :class="paymentMethod === 'QRIS' ? 'border-espresso bg-espresso/5 text-espresso font-bold' : 'border-gray-200 text-charcoal/60 bg-white'"
                                    class="py-1.5 text-xs border rounded-xl transition-all flex items-center justify-center space-x-1">
                                <span class="text-[10px]">📱</span> <span>QRIS</span>
                            </button>
                            <button @click="paymentMethod = 'Debit'" :class="paymentMethod === 'Debit' ? 'border-espresso bg-espresso/5 text-espresso font-bold' : 'border-gray-200 text-charcoal/60 bg-white'"
                                    class="py-1.5 text-xs border rounded-xl transition-all flex items-center justify-center space-x-1">
                                <span class="text-[10px]">💳</span> <span>Debit</span>
                            </button>
                        </div>
                    </div>

                    <!-- Discount Input -->
                    <div class="space-y-1">
                        <label class="text-[10px] font-black text-espresso/60 tracking-wider block">DISKON / POTONGAN (RP)</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-charcoal/40 text-xs">Rp</span>
                            <input type="number" x-model.number="discount" min="0" placeholder="0"
                                   class="w-full pl-8 pr-3 py-1.5 border border-gray-200 rounded-xl text-xs font-bold focus:ring-1 focus:ring-latte focus:border-latte transition-all" />
                        </div>
                    </div>

                    <!-- Receipt Calculations Summary -->
                    <div class="border-t border-dashed border-gray-200 pt-3 space-y-1.5 text-xs">
                        <div class="flex justify-between text-charcoal/75 font-medium">
                            <span>Subtotal</span>
                            <span x-text="formatRupiah(subtotal)"></span>
                        </div>
                        <template x-if="discount > 0">
                            <div class="flex justify-between text-red-500 font-medium">
                                <span>Diskon</span>
                                <span>- <span x-text="formatRupiah(discount)"></span></span>
                            </div>
                        </template>
                        <div class="flex justify-between text-charcoal/75 font-medium">
                            <span>Pajak PB1 (10%)</span>
                            <span x-text="formatRupiah(tax())"></span>
                        </div>
                        <div class="flex justify-between items-center text-espresso font-black text-sm border-t border-gray-100 pt-2">
                            <span>TOTAL BAYAR</span>
                            <span class="text-base text-espresso-dark font-black" x-text="formatRupiah(totalPay())"></span>
                        </div>
                    </div>

                    <!-- Cash Received & Change (Only for Cash Payment) -->
                    <div x-show="paymentMethod === 'Cash'" x-collapse class="pt-2 border-t border-gray-100 space-y-2">
                        <div class="flex items-center gap-3">
                            <div class="flex-1 space-y-1">
                                <label class="text-[10px] font-black text-espresso/60 tracking-wider block">UANG DITERIMA (RP)</label>
                                <input type="number" x-model.number="cashAmount" placeholder="Input nominal..."
                                       class="w-full px-3 py-1.5 border border-gray-200 rounded-xl text-xs font-bold focus:ring-1 focus:ring-latte focus:border-latte transition-all" />
                            </div>
                            <div class="w-24 shrink-0 space-y-1 text-right">
                                <span class="text-[10px] font-black text-espresso/60 tracking-wider block">KEMBALIAN</span>
                                <span class="text-xs font-black text-green-600 block pt-1.5" x-text="formatRupiah(changeAmount())"></span>
                            </div>
                        </div>
                        <!-- Quick Cash Shortcuts -->
                        <div class="flex flex-wrap gap-1">
                            <template x-for="shortcut in getCashShortcuts()" :key="shortcut">
                                <button @click="cashAmount = shortcut" class="text-[10px] bg-white border border-gray-200 px-2 py-1 rounded-md hover:bg-gray-100 transition-colors font-semibold"
                                        x-text="formatRupiahShort(shortcut)"></button>
                            </template>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button @click="checkout()" 
                            :disabled="cart.length === 0 || isCheckingOut"
                            :class="(cart.length === 0 || isCheckingOut) ? 'bg-gray-300 text-gray-500 cursor-not-allowed' : 'bg-espresso hover:bg-espresso-light text-white shadow-md active:scale-[0.98]'"
                            class="w-full mt-3 py-3 rounded-xl font-bold text-sm transition-all flex items-center justify-center space-x-2">
                        <template x-if="isCheckingOut">
                            <span class="flex items-center justify-center space-x-2">
                                <svg class="animate-spin h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span>Memproses...</span>
                            </span>
                        </template>
                        <template x-if="!isCheckingOut">
                            <span class="flex items-center justify-center space-x-2">
                                <span>Bayar Sekarang</span>
                            </span>
                        </template>
                    </button>
                </div>
            </div>
        </div>

        <!-- MODAL: STRUK BELANJA (THERMAL RECEIPT) -->
        <div x-show="showReceiptModal" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-50 overflow-y-auto bg-charcoal/40 backdrop-blur-sm flex items-center justify-center p-4">
            
            <div @click.away="showReceiptModal = false" 
                 class="bg-white rounded-2xl max-w-sm w-full shadow-2xl p-6 relative flex flex-col justify-between border border-gray-100">
                
                <!-- Receipt Paper Mockup Area -->
                <div class="border border-gray-200 rounded-xl p-4 bg-gray-50/50 shadow-inner overflow-hidden select-none">
                    
                    <div id="thermal-receipt" class="bg-white p-3 text-charcoal border border-gray-100 text-xs font-mono">
                        <!-- Header -->
                        <div class="text-center space-y-1 mb-4">
                            <h3 class="font-black text-base tracking-wider text-espresso">DCOFFEE SHOP</h3>
                            <p class="text-[10px] text-charcoal/60 leading-none">Ruko Coffee Boulevard, Jakarta</p>
                            <p class="text-[9px] text-charcoal/40 leading-none">Telp: 021-9990001</p>
                        </div>

                        <!-- Divider -->
                        <div class="border-t border-dashed border-gray-300 my-2"></div>

                        <!-- Transaction Metadata -->
                        <div class="space-y-1 text-[10px] text-charcoal/70">
                            <div class="flex justify-between">
                                <span>No. Struk:</span>
                                <span class="font-bold text-charcoal" x-text="receipt?.invoice_number"></span>
                            </div>
                            <div class="flex justify-between">
                                <span>Tanggal:</span>
                                <span x-text="formatDate(receipt?.created_at)"></span>
                            </div>
                            <div class="flex justify-between">
                                <span>Kasir:</span>
                                <span x-text="receipt?.cashier?.name"></span>
                            </div>
                            <div class="flex justify-between">
                                <span>Tipe Order:</span>
                                <span class="capitalize" x-text="receipt?.order_type.replace('_', ' ')"></span>
                            </div>
                            <template x-if="receipt?.table_number">
                                <div class="flex justify-between">
                                    <span>No. Meja:</span>
                                    <span class="font-bold" x-text="receipt?.table_number"></span>
                                </div>
                            </template>
                        </div>

                        <!-- Divider -->
                        <div class="border-t border-dashed border-gray-300 my-2"></div>

                        <!-- Item list -->
                        <div class="space-y-2 text-[10px]">
                            <template x-for="item in receipt?.products" :key="item.id">
                                <div>
                                    <div class="flex justify-between font-bold">
                                        <span x-text="item.name"></span>
                                        <span x-text="formatRupiah(item.pivot.price * item.pivot.quantity)"></span>
                                    </div>
                                    <div class="text-charcoal/50 text-[9px] pl-1">
                                        <span x-text="item.pivot.quantity"></span> x <span x-text="formatRupiah(item.pivot.price)"></span>
                                    </div>
                                </div>
                            </template>
                        </div>

                        <!-- Divider -->
                        <div class="border-t border-dashed border-gray-300 my-2"></div>

                        <!-- Computations -->
                        <div class="space-y-1 text-[10px]">
                            <div class="flex justify-between">
                                <span>Subtotal</span>
                                <span x-text="formatRupiah(receipt?.subtotal)"></span>
                            </div>
                            <template x-if="Number(receipt?.discount) > 0">
                                <div class="flex justify-between text-red-500 font-bold">
                                    <span>Diskon</span>
                                    <span>- <span x-text="formatRupiah(receipt?.discount)"></span></span>
                                </div>
                            </template>
                            <div class="flex justify-between">
                                <span>Pajak PB1 (10%)</span>
                                <span x-text="formatRupiah(receipt?.tax)"></span>
                            </div>
                            <div class="flex justify-between font-bold border-t border-gray-100 pt-1 mt-1 text-charcoal">
                                <span>TOTAL BAYAR</span>
                                <span x-text="formatRupiah(receipt?.total_pay)"></span>
                            </div>
                        </div>

                        <!-- Divider -->
                        <div class="border-t border-dashed border-gray-300 my-2"></div>

                        <!-- Payment metadata -->
                        <div class="space-y-1 text-[10px] text-charcoal/70">
                            <div class="flex justify-between">
                                <span>Metode:</span>
                                <span class="font-bold" x-text="receipt?.payment_method"></span>
                            </div>
                            <template x-if="receipt?.payment_method === 'Cash'">
                                <div class="flex justify-between">
                                    <span>Bayar (Cash):</span>
                                    <span x-text="formatRupiah(cashAmount)"></span>
                                </div>
                            </template>
                            <template x-if="receipt?.payment_method === 'Cash'">
                                <div class="flex justify-between font-bold text-green-700">
                                    <span>Kembali:</span>
                                    <span x-text="formatRupiah(Math.max(0, Number(cashAmount) - Number(receipt?.total_pay)))"></span>
                                </div>
                            </template>
                        </div>

                        <!-- Footer notes -->
                        <div class="text-center text-[9px] text-charcoal/40 mt-4 leading-none">
                            <p>Terima Kasih Atas Kunjungan Anda</p>
                            <p class="mt-1">Design Premium ala E-Commerce</p>
                        </div>
                    </div>

                </div>

                <!-- Modal Actions -->
                <div class="flex gap-3 mt-4 no-print">
                    <button @click="printReceipt()"
                            class="flex-1 py-2.5 bg-espresso hover:bg-espresso-light text-white text-xs font-bold rounded-xl transition-all flex items-center justify-center space-x-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                        </svg>
                        <span>Cetak Struk</span>
                    </button>
                    <button @click="showReceiptModal = false"
                            class="flex-1 py-2.5 bg-gray-100 hover:bg-gray-200 text-charcoal text-xs font-bold rounded-xl transition-all">
                        Transaksi Baru
                    </button>
                </div>
            </div>

        </div>

    </div>

    <!-- Script to register Alpine POS controller logic -->
    @push('scripts')
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('posApp', (initialProducts) => ({
                products: initialProducts,
                search: '',
                activeCategory: 'Semua',
                cart: [],
                discount: 0,
                orderType: 'dine_in',
                tableNumber: '',
                paymentMethod: 'Cash',
                isCheckingOut: false,
                showReceiptModal: false,
                receipt: null,
                cashAmount: '',
                errorMessage: '',
                successMessage: '',

                // Get categories dynamically
                get categories() {
                    const cats = [...new Set(this.products.map(p => p.category))];
                    return cats.sort();
                },

                getCartQty(productId) {
                    const item = this.cart.find(i => i.id === productId);
                    return item ? item.qty : 0;
                },

                portionsAvailable(product) {
                    if (!product.ingredients || product.ingredients.length === 0) return 999;
                    let minPortions = Infinity;
                    product.ingredients.forEach(ing => {
                        const stock = parseFloat(ing.stock);
                        const req = parseFloat(ing.pivot.quantity);
                        if (req > 0) {
                            const portions = Math.floor(stock / req);
                            if (portions < minPortions) {
                                minPortions = portions;
                            }
                        }
                    });
                    return minPortions === Infinity ? 0 : minPortions;
                },

                isOutOfStock(product, extraQty = 0) {
                    const available = this.portionsAvailable(product);
                    const cartQty = this.getCartQty(product.id);
                    return (cartQty + extraQty) > available;
                },

                // Filters product catalog
                get filteredProducts() {
                    return this.products.filter(p => {
                        const matchesSearch = p.name.toLowerCase().includes(this.search.toLowerCase()) || 
                                              p.sku.toLowerCase().includes(this.search.toLowerCase());
                        const matchesCategory = this.activeCategory === 'Semua' || p.category === this.activeCategory;
                        return matchesSearch && matchesCategory;
                    });
                },

                // Calculated Cart subtotal
                get subtotal() {
                    return this.cart.reduce((sum, item) => sum + (item.price * item.qty), 0);
                },

                // Calculate PB1 tax (10% of subtotal minus discount)
                tax() {
                    return Math.max(0, this.subtotal - this.discount) * 0.10;
                },

                // Total limpio a pagar
                totalPay() {
                    return Math.max(0, this.subtotal - this.discount) + this.tax();
                },

                // Calculated change
                changeAmount() {
                    if (this.paymentMethod !== 'Cash' || !this.cashAmount) return 0;
                    return Math.max(0, Number(this.cashAmount) - this.totalPay());
                },

                // Get shortcuts list based on total payment
                getCashShortcuts() {
                    const total = this.totalPay();
                    if (total <= 0) return [10000, 20000, 50000, 100000];
                    
                    const options = [];
                    // Next roundings
                    const standardNotes = [10000, 20000, 50000, 100000];
                    
                    // Exact cash
                    options.push(total);
                    
                    // Add closest standard banknotes that are greater than total
                    standardNotes.forEach(note => {
                        if (note > total && options.indexOf(note) === -1) {
                            options.push(note);
                        }
                    });
                    
                    // Multiples of 50k and 100k
                    const round50 = Math.ceil(total / 50000) * 50000;
                    const round100 = Math.ceil(total / 100000) * 100000;
                    if (options.indexOf(round50) === -1) options.push(round50);
                    if (options.indexOf(round100) === -1) options.push(round100);
                    
                    return options.sort((a, b) => a - b).slice(0, 4);
                },

                // Cart Operations
                addToCart(product) {
                    if (this.isOutOfStock(product, 1)) {
                        this.errorMessage = `Stok bahan baku untuk ${product.name} tidak mencukupi.`;
                        return;
                    }
                    const existingItem = this.cart.find(item => item.id === product.id);
                    if (existingItem) {
                        existingItem.qty++;
                    } else {
                        this.cart.push({
                            id: product.id,
                            name: product.name,
                            sku: product.sku,
                            price: Number(product.price),
                            qty: 1,
                            image_url: product.image_url
                        });
                    }
                    this.successMessage = `Ditambahkan: ${product.name}`;
                },

                incrementQty(item) {
                    const product = this.products.find(p => p.id === item.id);
                    if (product && this.isOutOfStock(product, 1)) {
                        this.errorMessage = `Stok bahan baku untuk ${item.name} tidak mencukupi.`;
                        return;
                    }
                    item.qty++;
                },

                decrementQty(item) {
                    item.qty--;
                    if (item.qty < 1) {
                        this.removeItem(item);
                    }
                },

                removeItem(item) {
                    this.cart = this.cart.filter(i => i.id !== item.id);
                },

                cartTotalQuantity() {
                    return this.cart.reduce((sum, item) => sum + item.qty, 0);
                },

                clearCart() {
                    this.cart = [];
                    this.discount = 0;
                    this.tableNumber = '';
                    this.cashAmount = '';
                    this.paymentMethod = 'Cash';
                    this.errorMessage = '';
                    this.successMessage = '';
                },

                // Submit POS transaction via Fetch AJAX API
                async checkout() {
                    if (this.cart.length === 0) return;
                    
                    // Auto-fill cash amount if empty (exact cash pay)
                    if (this.paymentMethod === 'Cash' && !this.cashAmount) {
                        this.cashAmount = this.totalPay();
                    }
                    
                    this.isCheckingOut = true;
                    this.errorMessage = '';
                    this.successMessage = '';

                    const payload = {
                        order_type: this.orderType,
                        table_number: this.orderType === 'dine_in' ? this.tableNumber : null,
                        payment_method: this.paymentMethod,
                        discount: this.discount,
                        items: this.cart.map(item => ({
                            id: item.id,
                            qty: item.qty
                        }))
                    };

                    try {
                        const response = await fetch("{{ route('pos.store') }}", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "Accept": "application/json",
                                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify(payload)
                        });

                        const contentType = response.headers.get('content-type') || '';
                        if (!contentType.includes('application/json')) {
                            throw new Error('Sesi telah berakhir atau terjadi kesalahan server. Silakan muat ulang halaman (F5) dan coba lagi.');
                        }

                        const result = await response.json();

                        if (!response.ok) {
                            // Handle validation errors
                            if (result.errors) {
                                const firstError = Object.values(result.errors).flat()[0];
                                throw new Error(firstError || 'Validasi gagal.');
                            }
                            throw new Error(result.message || "Terjadi kesalahan saat checkout.");
                        }

                        if (result.success) {
                            this.receipt = result.data;
                            this.successMessage = "Transaksi berhasil!";
                            
                            // Deduct stock locally
                            this.cart.forEach(cartItem => {
                                const product = this.products.find(p => p.id === cartItem.id);
                                if (product && product.ingredients) {
                                    product.ingredients.forEach(ing => {
                                        ing.stock = parseFloat(ing.stock) - (parseFloat(ing.pivot.quantity) * cartItem.qty);
                                    });
                                }
                            });

                            this.showReceiptModal = true;
                            // Clear cart
                            this.cart = [];
                            this.discount = 0;
                            this.tableNumber = '';
                            this.cashAmount = '';
                        } else {
                            throw new Error(result.message || "Transaksi gagal.");
                        }

                    } catch (error) {
                        this.errorMessage = error.message;
                    } finally {
                        this.isCheckingOut = false;
                    }
                },

                // Helpers
                formatRupiah(amount) {
                    if (amount === null || amount === undefined) return 'Rp 0';
                    return 'Rp ' + Number(amount).toLocaleString('id-ID', { minimumFractionDigits: 0, maximumFractionDigits: 0 });
                },

                formatRupiahShort(amount) {
                    if (amount >= 1000) {
                        return (amount / 1000) + 'k';
                    }
                    return amount;
                },

                formatDate(dateStr) {
                    if (!dateStr) return '';
                    const date = new Date(dateStr);
                    return date.toLocaleString('id-ID', {
                        day: 'numeric',
                        month: 'short',
                        year: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit'
                    });
                },

                printReceipt() {
                    window.print();
                }
            }));
        });
    </script>
    @endpush

</x-app-layout>
