<x-guest-layout>
    {{-- Header --}}
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-espresso">Verifikasi Email Anda</h2>
        <p class="text-sm text-charcoal/60 mt-1">
            Terima kasih telah mendaftar! Silakan verifikasi alamat email Anda dengan mengklik tautan yang baru saja kami kirimkan ke email Anda.
        </p>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 font-semibold text-sm text-green-600">
            Tautan verifikasi baru telah dikirimkan ke alamat email yang Anda daftarkan.
        </div>
    @endif

    <div class="mt-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf

            <x-primary-button class="py-2.5 text-xs">
                Kirim Ulang Email Verifikasi
            </x-primary-button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button type="submit" class="text-sm text-espresso/70 hover:text-espresso font-semibold transition-colors hover:underline rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-latte">
                Keluar
            </button>
        </form>
    </div>
</x-guest-layout>
