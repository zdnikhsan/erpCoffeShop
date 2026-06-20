<x-guest-layout>
    {{-- Header --}}
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-espresso">Selamat Datang Kembali</h2>
        <p class="text-sm text-charcoal/60 mt-1">Silakan masuk untuk mengelola kedai kopi Anda.</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" class="text-charcoal font-semibold" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Kata Sandi')" class="text-charcoal font-semibold" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me & Forgot Password -->
        <div class="flex items-center justify-between mt-4">
            <label for="remember_me" class="inline-flex items-center cursor-pointer select-none">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-espresso shadow-sm focus:ring-latte focus:ring-offset-2" name="remember">
                <span class="ms-2 text-sm text-charcoal/70">{{ __('Ingat saya') }}</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-sm text-espresso/70 hover:text-espresso font-semibold transition-colors hover:underline rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-latte" href="{{ route('password.request') }}">
                    Lupa sandi?
                </a>
            @endif
        </div>

        {{-- Submit Button --}}
        <div class="mt-6">
            <x-primary-button class="w-full justify-center py-3 text-sm tracking-wider">
                Masuk
            </x-primary-button>
        </div>

        {{-- Register Link --}}
        <div class="mt-6 text-center">
            <p class="text-sm text-charcoal/60">
                Belum punya akun?
                <a href="{{ route('register') }}" class="font-semibold text-espresso hover:text-espresso-light transition-colors hover:underline">
                    Daftar Sekarang
                </a>
            </p>
        </div>
    </form>
</x-guest-layout>
