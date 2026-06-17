<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-bold text-2xl text-espresso leading-tight">
                {{ __('Produk & Resep') }}
            </h2>
            <a href="{{ route('products.create') }}"
               class="inline-flex items-center px-5 py-2.5 bg-espresso hover:bg-espresso-light text-white text-sm font-semibold rounded-xl shadow-md shadow-espresso/20 transition-all duration-200 hover:shadow-lg hover:shadow-espresso/30 active:scale-95">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Tambah Produk
            </a>
        </div>
    </x-slot>

    <div class="py-6 space-y-6">
        {{-- Flash Messages --}}
        @if (session('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
                 x-transition:leave="transition ease-in duration-300"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 -translate-y-2"
                 class="flex items-center p-4 bg-green-50 border border-green-200 text-green-800 rounded-xl">
                <svg class="w-5 h-5 mr-3 text-green-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="text-sm font-medium">{{ session('success') }}</span>
            </div>
        @endif

        @if (session('error'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
                 x-transition:leave="transition ease-in duration-300"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 -translate-y-2"
                 class="flex items-center p-4 bg-red-50 border border-red-200 text-red-800 rounded-xl">
                <svg class="w-5 h-5 mr-3 text-red-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="text-sm font-medium">{{ session('error') }}</span>
            </div>
        @endif

        {{-- Search Bar --}}
        <div class="bg-white border border-gray-200/60 rounded-2xl shadow-sm p-4">
            <form method="GET" action="{{ route('products.index') }}" class="flex items-center gap-3">
                <div class="relative flex-1">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-charcoal/40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama, SKU, atau kategori produk..."
                           class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl text-sm text-charcoal placeholder-charcoal/40 focus:ring-2 focus:ring-latte focus:border-latte transition-colors duration-200" />
                </div>
                <button type="submit"
                        class="inline-flex items-center px-5 py-2.5 bg-latte hover:bg-latte-dark text-espresso text-sm font-semibold rounded-xl transition-all duration-200 active:scale-95">
                    Cari
                </button>
                @if ($search)
                    <a href="{{ route('products.index') }}"
                       class="inline-flex items-center px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-charcoal/70 text-sm font-medium rounded-xl transition-all duration-200">
                        Reset
                    </a>
                @endif
            </form>
        </div>

        {{-- Table --}}
        <div class="bg-white border border-gray-200/60 overflow-hidden shadow-sm rounded-2xl">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead>
                        <tr class="bg-espresso/5 border-b border-gray-200/60">
                            <th class="px-6 py-4 font-semibold text-espresso text-xs uppercase tracking-wider">No</th>
                            <th class="px-6 py-4 font-semibold text-espresso text-xs uppercase tracking-wider">Foto</th>
                            <th class="px-6 py-4 font-semibold text-espresso text-xs uppercase tracking-wider">Nama Produk</th>
                            <th class="px-6 py-4 font-semibold text-espresso text-xs uppercase tracking-wider">SKU</th>
                            <th class="px-6 py-4 font-semibold text-espresso text-xs uppercase tracking-wider">Kategori</th>
                            <th class="px-6 py-4 font-semibold text-espresso text-xs uppercase tracking-wider">Harga</th>
                            <th class="px-6 py-4 font-semibold text-espresso text-xs uppercase tracking-wider">Bahan Baku</th>
                            <th class="px-6 py-4 font-semibold text-espresso text-xs uppercase tracking-wider text-center">Status</th>
                            <th class="px-6 py-4 font-semibold text-espresso text-xs uppercase tracking-wider text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($products as $product)
                            <tr class="hover:bg-latte/5 transition-colors duration-150">
                                <td class="px-6 py-4 text-charcoal/70 font-medium">
                                    {{ $loop->iteration + ($products->currentPage() - 1) * $products->perPage() }}
                                </td>
                                <td class="px-6 py-4">
                                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-10 h-10 object-cover rounded-lg border border-gray-200" loading="lazy">
                                </td>
                                <td class="px-6 py-4 font-semibold text-charcoal">{{ $product->name }}</td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-mono font-semibold bg-gray-100 text-charcoal/70">
                                        {{ $product->sku }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-charcoal/80">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold bg-amber-50 text-amber-800 border border-amber-100">
                                        {{ $product->category }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 font-bold text-espresso">
                                    Rp {{ number_format($product->price, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 text-charcoal/70">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-espresso/5 text-espresso">
                                        {{ $product->ingredients->count() }} Bahan
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $product->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $product->is_active ? 'Aktif' : 'Non-Aktif' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-center gap-1">
                                        {{-- Lihat --}}
                                        <a href="{{ route('products.show', $product) }}"
                                           class="inline-flex items-center p-2 text-charcoal/50 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all duration-200" title="Detail & Resep">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </a>

                                        {{-- Edit --}}
                                        <a href="{{ route('products.edit', $product) }}"
                                           class="inline-flex items-center p-2 text-charcoal/50 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition-all duration-200" title="Edit">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>

                                        {{-- Hapus --}}
                                        <form method="POST" action="{{ route('products.destroy', $product) }}"
                                              x-data x-ref="deleteForm"
                                              class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button"
                                                    @click="if(confirm('Yakin ingin menghapus produk \'{{ $product->name }}\'? Semua data resep terkait akan ikut terhapus.')) $refs.deleteForm.submit()"
                                                    class="inline-flex items-center p-2 text-charcoal/50 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all duration-200" title="Hapus">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <svg class="w-12 h-12 text-charcoal/20 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                        </svg>
                                        <p class="text-charcoal/50 font-medium">Belum ada data produk.</p>
                                        <a href="{{ route('products.create') }}" class="mt-2 text-sm text-latte-dark hover:text-espresso font-semibold transition-colors duration-200">
                                            + Tambah produk pertama
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if ($products->hasPages())
                <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
                    {{ $products->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
