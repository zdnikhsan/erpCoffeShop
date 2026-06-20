<x-guest-layout>
    {{-- Header --}}
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-espresso">Lupa Kata Sandi?</h2>
        <p class="text-sm text-charcoal/60 mt-1">
            Masukkan alamat email Anda dan kami akan mengirimkan tautan untuk mengatur ulang kata sandi Anda.
        </p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" class="text-charcoal font-semibold" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        {{-- Submit Button --}}
        <div class="mt-6">
            <x-primary-button class="w-full justify-center py-3 text-sm tracking-wider">
                Kirim Tautan Reset Sandi
            </x-primary-button>
        </div>

        {{-- Back to Login Link --}}
        <div class="mt-6 text-center">
            <a href="{{ route('login') }}" class="font-semibold text-sm text-espresso hover:text-espresso-light transition-colors hover:underline">
                Kembali ke Halaman Masuk
            </a>
        </div>
    </form>
</x-guest-layout>
