<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-bold text-2xl text-espresso leading-tight">
                {{ __('Detail Pengeluaran') }}
            </h2>
            <a href="{{ route('expenses.index') }}"
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
            <div class="p-6 sm:p-8 space-y-6">
                {{-- Nomor & Tanggal --}}
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-6 border-b border-gray-100">
                    <div>
                        <p class="text-xs text-charcoal/50 uppercase tracking-wider font-semibold mb-1">Nomor Pengeluaran</p>
                        <p class="font-mono text-lg font-bold text-espresso">{{ $expense->expense_number }}</p>
                    </div>
                    <div class="text-left sm:text-right">
                        <p class="text-xs text-charcoal/50 uppercase tracking-wider font-semibold mb-1">Tanggal</p>
                        <p class="text-sm font-medium text-charcoal">{{ $expense->date->translatedFormat('l, d F Y') }}</p>
                    </div>
                </div>

                {{-- Kategori --}}
                <div>
                    <p class="text-xs text-charcoal/50 uppercase tracking-wider font-semibold mb-2">Kategori</p>
                    @php
                        $catColors = [
                            'Gaji'           => 'bg-blue-100 text-blue-700',
                            'Listrik & Air'  => 'bg-yellow-100 text-yellow-700',
                            'Sewa Tempat'    => 'bg-purple-100 text-purple-700',
                            'Maintenance'    => 'bg-orange-100 text-orange-700',
                            'Lainnya'        => 'bg-gray-100 text-gray-700',
                        ];
                    @endphp
                    <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-sm font-semibold {{ $catColors[$expense->category] ?? 'bg-gray-100 text-gray-700' }}">
                        {{ $expense->category }}
                    </span>
                </div>

                {{-- Nominal --}}
                <div>
                    <p class="text-xs text-charcoal/50 uppercase tracking-wider font-semibold mb-2">Nominal Pengeluaran</p>
                    <p class="text-2xl font-bold text-espresso">
                        Rp {{ number_format($expense->amount, 0, ',', '.') }}
                    </p>
                </div>

                {{-- Keterangan --}}
                <div>
                    <p class="text-xs text-charcoal/50 uppercase tracking-wider font-semibold mb-2">Keterangan</p>
                    <p class="text-sm text-charcoal/80 leading-relaxed">{{ $expense->note ?? '-' }}</p>
                </div>

                {{-- Actions --}}
                <div class="flex items-center gap-3 pt-6 border-t border-gray-100">
                    <a href="{{ route('expenses.edit', $expense) }}"
                       class="inline-flex items-center px-5 py-2.5 bg-espresso hover:bg-espresso-light text-white text-sm font-semibold rounded-xl shadow-md shadow-espresso/20 transition-all duration-200 hover:shadow-lg active:scale-95">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Edit
                    </a>
                    <form method="POST" action="{{ route('expenses.destroy', $expense) }}"
                          x-data x-ref="deleteForm"
                          class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="button"
                                @click="if(confirm('Yakin ingin menghapus pengeluaran \'{{ $expense->expense_number }}\'?')) $refs.deleteForm.submit()"
                                class="inline-flex items-center px-5 py-2.5 bg-red-50 hover:bg-red-100 text-red-700 text-sm font-semibold rounded-xl transition-all duration-200 active:scale-95">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
