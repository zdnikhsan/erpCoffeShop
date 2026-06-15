<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-bold text-2xl text-espresso leading-tight">
                {{ __('Detail Purchase Order') }}
            </h2>
            <div class="flex items-center gap-2">
                @if ($purchaseOrder->status === 'draft')
                    <a href="{{ route('purchase-orders.edit', $purchaseOrder) }}"
                       class="inline-flex items-center px-4 py-2 bg-espresso hover:bg-espresso-light text-white text-sm font-semibold rounded-xl shadow-sm transition-all duration-200 active:scale-95">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Edit
                    </a>
                @endif
                <a href="{{ route('purchase-orders.index') }}"
                   class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-charcoal/70 text-sm font-medium rounded-xl transition-all duration-200 active:scale-95">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali
                </a>
            </div>
        </div>
    </x-slot>

    @php
        $badgeClasses = match($purchaseOrder->status) {
            'draft' => 'bg-gray-100 text-gray-700 border-gray-300',
            'sent' => 'bg-blue-50 text-blue-700 border-blue-200',
            'on_delivery' => 'bg-amber-50 text-amber-700 border-amber-200',
            'completed' => 'bg-green-50 text-green-700 border-green-200',
            'cancelled' => 'bg-red-50 text-red-700 border-red-200',
            default => 'bg-gray-100 text-gray-700 border-gray-300',
        };
        $statusLabel = match($purchaseOrder->status) {
            'draft' => 'Draft',
            'sent' => 'Dikirim ke Supplier',
            'on_delivery' => 'Dalam Perjalanan',
            'completed' => 'Selesai (Barang Diterima)',
            'cancelled' => 'Dibatalkan',
            default => $purchaseOrder->status,
        };
    @endphp

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

        {{-- PO Header Info --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            {{-- Left Column: PO Info --}}
            <div class="bg-white border border-gray-200/60 overflow-hidden shadow-sm rounded-2xl p-6 space-y-5">
                <h3 class="text-md font-bold text-espresso border-b border-gray-100 pb-3 flex items-center">
                    <svg class="w-4 h-4 mr-2 text-latte-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Informasi PO
                </h3>

                <div class="space-y-4">
                    <div>
                        <span class="block text-xs font-bold text-charcoal/40 uppercase tracking-wider">No. PO</span>
                        <span class="inline-flex items-center mt-1 px-2.5 py-1 rounded-lg text-sm font-mono font-bold bg-gray-100 text-charcoal/80">
                            {{ $purchaseOrder->po_number }}
                        </span>
                    </div>
                    <div>
                        <span class="block text-xs font-bold text-charcoal/40 uppercase tracking-wider">Status</span>
                        <span class="inline-flex items-center mt-1 px-3 py-1.5 rounded-lg text-xs font-semibold border {{ $badgeClasses }}">
                            {{ $statusLabel }}
                        </span>
                    </div>
                    <div>
                        <span class="block text-xs font-bold text-charcoal/40 uppercase tracking-wider">Supplier</span>
                        <span class="text-sm font-bold text-charcoal">{{ $purchaseOrder->supplier->name }}</span>
                        <span class="block text-xs text-charcoal/50">{{ $purchaseOrder->supplier->phone }}</span>
                    </div>
                    <div>
                        <span class="block text-xs font-bold text-charcoal/40 uppercase tracking-wider">Tanggal Order</span>
                        <span class="text-sm font-semibold text-charcoal">{{ $purchaseOrder->order_date->format('d M Y') }}</span>
                    </div>
                    @if ($purchaseOrder->received_date)
                    <div>
                        <span class="block text-xs font-bold text-charcoal/40 uppercase tracking-wider">Tanggal Diterima</span>
                        <span class="text-sm font-semibold text-green-700">{{ $purchaseOrder->received_date->format('d M Y') }}</span>
                    </div>
                    @endif
                    <div>
                        <span class="block text-xs font-bold text-charcoal/40 uppercase tracking-wider">Total Pembelian</span>
                        <span class="text-lg font-extrabold text-espresso">Rp {{ number_format($purchaseOrder->total_amount, 2, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            {{-- Right Column: Ingredients Table --}}
            <div class="bg-white border border-gray-200/60 overflow-hidden shadow-sm rounded-2xl p-6 md:col-span-2 space-y-5">
                <h3 class="text-md font-bold text-espresso border-b border-gray-100 pb-3 flex items-center">
                    <svg class="w-4 h-4 mr-2 text-latte-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                    </svg>
                    Daftar Bahan Baku
                </h3>

                <div class="overflow-hidden border border-gray-100 rounded-xl">
                    <table class="w-full text-sm text-left">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-100">
                                <th class="px-5 py-3 font-semibold text-charcoal/70 text-xs uppercase">No</th>
                                <th class="px-5 py-3 font-semibold text-charcoal/70 text-xs uppercase">Bahan Baku</th>
                                <th class="px-5 py-3 font-semibold text-charcoal/70 text-xs uppercase text-right">Qty Pesan</th>
                                <th class="px-5 py-3 font-semibold text-charcoal/70 text-xs uppercase text-right">Qty Diterima</th>
                                <th class="px-5 py-3 font-semibold text-charcoal/70 text-xs uppercase text-right">Harga Satuan</th>
                                <th class="px-5 py-3 font-semibold text-charcoal/70 text-xs uppercase text-right">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse ($purchaseOrder->ingredients as $ingredient)
                                <tr class="hover:bg-gray-50/55 transition-colors">
                                    <td class="px-5 py-3 text-charcoal/60">{{ $loop->iteration }}</td>
                                    <td class="px-5 py-3">
                                        <span class="font-semibold text-charcoal">{{ $ingredient->name }}</span>
                                        <span class="block text-xs text-charcoal/50">{{ $ingredient->unit }}</span>
                                    </td>
                                    <td class="px-5 py-3 text-right font-semibold text-charcoal">{{ number_format($ingredient->pivot->quantity, 2) }}</td>
                                    <td class="px-5 py-3 text-right font-bold {{ $ingredient->pivot->quantity_received > 0 ? 'text-green-700' : 'text-charcoal/40' }}">
                                        {{ number_format($ingredient->pivot->quantity_received, 2) }}
                                    </td>
                                    <td class="px-5 py-3 text-right text-charcoal/80">Rp {{ number_format($ingredient->pivot->unit_price, 0, ',', '.') }}</td>
                                    <td class="px-5 py-3 text-right font-bold text-espresso">
                                        Rp {{ number_format($ingredient->pivot->quantity * $ingredient->pivot->unit_price, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-5 py-8 text-center text-charcoal/50">Tidak ada bahan baku.</td>
                                </tr>
                            @endforelse
                        </tbody>
                        <tfoot>
                            <tr class="bg-espresso/5 border-t border-espresso/10">
                                <td colspan="5" class="px-5 py-3 text-right text-sm font-bold text-charcoal/70">Grand Total</td>
                                <td class="px-5 py-3 text-right text-lg font-extrabold text-espresso">
                                    Rp {{ number_format($purchaseOrder->total_amount, 2, ',', '.') }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        {{-- Status Update Section (only if PO is not completed or cancelled) --}}
        @if (!in_array($purchaseOrder->status, ['completed', 'cancelled']))
            <div class="bg-white border border-gray-200/60 overflow-hidden shadow-sm rounded-2xl p-6 space-y-6"
                 x-data="{
                     selectedStatus: '',
                     receivedDate: '{{ now()->format('Y-m-d') }}',
                     showReceivedForm: false,
                     init() {
                         this.$watch('selectedStatus', value => {
                             this.showReceivedForm = (value === 'completed');
                         });
                     }
                 }">
                <h3 class="text-lg font-bold text-espresso border-b border-gray-100 pb-3 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-latte-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    Update Status Purchase Order
                </h3>

                <form method="POST" action="{{ route('purchase-orders.update-status', $purchaseOrder) }}">
                    @csrf
                    @method('PATCH')

                    {{-- Status Selection --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label for="status" class="block text-sm font-semibold text-charcoal mb-2">
                                Status Baru <span class="text-red-500">*</span>
                            </label>
                            <select id="status" name="status" x-model="selectedStatus"
                                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm text-charcoal focus:ring-2 focus:ring-latte focus:border-latte transition-colors duration-200 {{ $errors->has('status') ? 'border-red-400' : '' }}" required>
                                <option value="" disabled selected>Pilih status baru...</option>
                                @if ($purchaseOrder->status === 'draft')
                                    <option value="sent">Dikirim ke Supplier</option>
                                    <option value="cancelled">Batal</option>
                                @endif
                                @if ($purchaseOrder->status === 'sent')
                                    <option value="on_delivery">Dalam Perjalanan</option>
                                    <option value="cancelled">Batal</option>
                                @endif
                                @if ($purchaseOrder->status === 'on_delivery')
                                    <option value="completed">Selesai (Barang Diterima)</option>
                                @endif
                                {{-- Allow completed from any non-terminal status --}}
                                @if (!in_array($purchaseOrder->status, ['on_delivery', 'completed', 'cancelled']))
                                    <option value="completed">Selesai (Barang Diterima)</option>
                                @endif
                            </select>
                            @error('status')
                                <p class="mt-1.5 text-xs text-red-500 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Received Date (only when completing) --}}
                        <div x-show="showReceivedForm" x-transition>
                            <label for="received_date" class="block text-sm font-semibold text-charcoal mb-2">
                                Tanggal Diterima <span class="text-red-500">*</span>
                            </label>
                            <input type="date" id="received_date" name="received_date" x-model="receivedDate"
                                   class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm text-charcoal focus:ring-2 focus:ring-latte focus:border-latte transition-colors duration-200 {{ $errors->has('received_date') ? 'border-red-400' : '' }}" />
                            @error('received_date')
                                <p class="mt-1.5 text-xs text-red-500 font-medium">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Quantity Received Form (only when status = completed) --}}
                    <div x-show="showReceivedForm" x-transition class="space-y-4 mb-6">
                        <div class="p-4 bg-amber-50 border border-amber-200 rounded-xl">
                            <p class="text-sm font-semibold text-amber-800 flex items-center">
                                <svg class="w-4 h-4 mr-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Masukkan jumlah fisik bahan baku yang benar-benar diterima di kafe. Stok akan otomatis bertambah.
                            </p>
                        </div>

                        <div class="overflow-hidden border border-gray-200 rounded-xl">
                            <table class="w-full text-sm text-left">
                                <thead>
                                    <tr class="bg-gray-50 border-b border-gray-100">
                                        <th class="px-5 py-3 font-semibold text-charcoal/70 text-xs uppercase">Bahan Baku</th>
                                        <th class="px-5 py-3 font-semibold text-charcoal/70 text-xs uppercase text-right">Qty Pesan</th>
                                        <th class="px-5 py-3 font-semibold text-charcoal/70 text-xs uppercase text-center">Qty Diterima</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-50">
                                    @foreach ($purchaseOrder->ingredients as $index => $ingredient)
                                        <tr class="hover:bg-gray-50/55">
                                            <td class="px-5 py-3">
                                                <span class="font-semibold text-charcoal">{{ $ingredient->name }}</span>
                                                <span class="text-xs text-charcoal/50 ml-1">({{ $ingredient->unit }})</span>
                                                <input type="hidden" name="ingredients[{{ $index }}][ingredient_id]" value="{{ $ingredient->id }}" />
                                            </td>
                                            <td class="px-5 py-3 text-right font-semibold text-charcoal/70">
                                                {{ number_format($ingredient->pivot->quantity, 2) }}
                                            </td>
                                            <td class="px-5 py-3">
                                                <input type="number" step="0.01" min="0"
                                                       name="ingredients[{{ $index }}][quantity_received]"
                                                       value="{{ old('ingredients.' . $index . '.quantity_received', $ingredient->pivot->quantity) }}"
                                                       class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm text-charcoal text-center font-semibold focus:ring-2 focus:ring-latte focus:border-latte transition-colors duration-200" />
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Validation errors --}}
                    @if ($errors->any())
                        <div class="p-4 bg-red-50 border border-red-100 rounded-xl space-y-1 mb-6">
                            <p class="text-xs font-bold text-red-800">Terjadi Kesalahan:</p>
                            <ul class="list-disc list-inside text-xs text-red-700">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Submit --}}
                    <div class="flex items-center gap-3 pt-4 border-t border-gray-100">
                        <button type="submit"
                                class="inline-flex items-center px-6 py-2.5 bg-espresso hover:bg-espresso-light text-white text-sm font-semibold rounded-xl shadow-md shadow-espresso/20 transition-all duration-200 hover:shadow-lg active:scale-95"
                                x-bind:disabled="!selectedStatus"
                                :class="{ 'opacity-50 cursor-not-allowed': !selectedStatus }">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            Update Status
                        </button>
                    </div>
                </form>
            </div>
        @endif
    </div>
</x-app-layout>
