<x-guest-layout>
    {{-- Header --}}
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-espresso">Daftar Akun Baru</h2>
        <p class="text-sm text-charcoal/60 mt-1">Gabung sekarang untuk mulai mengelola kedai kopi Anda.</p>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Nama Lengkap')" class="text-charcoal font-semibold" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" class="text-charcoal font-semibold" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Kata Sandi')" class="text-charcoal font-semibold" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Konfirmasi Kata Sandi')" class="text-charcoal font-semibold" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        {{-- Submit Button --}}
        <div class="mt-6">
            <x-primary-button class="w-full justify-center py-3 text-sm tracking-wider">
                Daftar
            </x-primary-button>
        </div>

        {{-- Login Link --}}
        <div class="mt-6 text-center">
            <p class="text-sm text-charcoal/60">
                Sudah memiliki akun?
                <a href="{{ route('login') }}" class="font-semibold text-espresso hover:text-espresso-light transition-colors hover:underline">
                    Masuk Di Sini
                </a>
            </p>
        </div>
    </form>
</x-guest-layout>
