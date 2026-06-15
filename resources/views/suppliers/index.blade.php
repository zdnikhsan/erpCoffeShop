<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-bold text-2xl text-espresso leading-tight">
                {{ __('Supplier') }}
            </h2>
            <a href="{{ route('suppliers.create') }}"
               class="inline-flex items-center px-5 py-2.5 bg-espresso hover:bg-espresso-light text-white text-sm font-semibold rounded-xl shadow-md shadow-espresso/20 transition-all duration-200 hover:shadow-lg hover:shadow-espresso/30 active:scale-95">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Tambah Supplier
            </a>
        </div>
    </x-slot>

    <div class="py-6 space-y-6">
        {{-- Flash Message --}}
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

        {{-- Search Bar --}}
        <div class="bg-white border border-gray-200/60 rounded-2xl shadow-sm p-4">
            <form method="GET" action="{{ route('suppliers.index') }}" class="flex items-center gap-3">
                <div class="relative flex-1">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-charcoal/40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama atau telepon supplier..."
                           class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl text-sm text-charcoal placeholder-charcoal/40 focus:ring-2 focus:ring-latte focus:border-latte transition-colors duration-200" />
                </div>
                <button type="submit"
                        class="inline-flex items-center px-5 py-2.5 bg-latte hover:bg-latte-dark text-espresso text-sm font-semibold rounded-xl transition-all duration-200 active:scale-95">
                    Cari
                </button>
                @if ($search)
                    <a href="{{ route('suppliers.index') }}"
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
                            <th class="px-6 py-4 font-semibold text-espresso text-xs uppercase tracking-wider">Nama</th>
                            <th class="px-6 py-4 font-semibold text-espresso text-xs uppercase tracking-wider">Telepon</th>
                            <th class="px-6 py-4 font-semibold text-espresso text-xs uppercase tracking-wider">Alamat</th>
                            <th class="px-6 py-4 font-semibold text-espresso text-xs uppercase tracking-wider">Tempo</th>
                            <th class="px-6 py-4 font-semibold text-espresso text-xs uppercase tracking-wider text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($suppliers as $supplier)
                            <tr class="hover:bg-latte/5 transition-colors duration-150">
                                <td class="px-6 py-4 text-charcoal/70 font-medium">
                                    {{ $loop->iteration + ($suppliers->currentPage() - 1) * $suppliers->perPage() }}
                                </td>
                                <td class="px-6 py-4 font-semibold text-charcoal">{{ $supplier->name }}</td>
                                <td class="px-6 py-4 text-charcoal/80">{{ $supplier->phone }}</td>
                                <td class="px-6 py-4 text-charcoal/70 max-w-xs truncate">{{ $supplier->address }}</td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold {{ $supplier->payment_terms === 0 ? 'bg-green-100 text-green-700' : 'bg-latte/20 text-espresso' }}">
                                        {{ $supplier->payment_terms === 0 ? 'COD' : $supplier->payment_terms . ' Hari' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-center gap-1">
                                        <a href="{{ route('suppliers.show', $supplier) }}"
                                           class="inline-flex items-center p-2 text-charcoal/50 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all duration-200" title="Lihat">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </a>
                                        <a href="{{ route('suppliers.edit', $supplier) }}"
                                           class="inline-flex items-center p-2 text-charcoal/50 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition-all duration-200" title="Edit">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>
                                        <form method="POST" action="{{ route('suppliers.destroy', $supplier) }}"
                                              x-data x-ref="deleteForm"
                                              class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button"
                                                    @click="if(confirm('Yakin ingin menghapus supplier \'{{ $supplier->name }}\'?')) $refs.deleteForm.submit()"
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
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <svg class="w-12 h-12 text-charcoal/20 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                        </svg>
                                        <p class="text-charcoal/50 font-medium">Belum ada data supplier.</p>
                                        <a href="{{ route('suppliers.create') }}" class="mt-2 text-sm text-latte-dark hover:text-espresso font-semibold transition-colors duration-200">
                                            + Tambah supplier pertama
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if ($suppliers->hasPages())
                <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
                    {{ $suppliers->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
