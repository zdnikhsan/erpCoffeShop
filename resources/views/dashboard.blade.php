<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-espresso leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="bg-white border border-gray-200/60 overflow-hidden shadow-sm rounded-2xl">
            <div class="p-8 text-charcoal">
                <div class="flex items-center space-x-4">
                    <span class="p-3 bg-latte/20 rounded-2xl text-espresso">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </span>
                    <div>
                        <h3 class="text-lg font-bold text-espresso">Halo, {{ Auth::user()->name }}!</h3>
                        <p class="text-charcoal/70 text-sm mt-1">Anda berhasil masuk ke sistem manajemen DCoffee. Selamat bekerja!</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
