<x-guest-layout>
    {{-- Header --}}
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-espresso">Konfirmasi Kata Sandi</h2>
        <p class="text-sm text-charcoal/60 mt-1">
            Ini adalah area aman. Silakan konfirmasi kata sandi Anda sebelum melanjutkan.
        </p>
    </div>

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Kata Sandi')" class="text-charcoal font-semibold" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        {{-- Submit Button --}}
        <div class="mt-6">
            <x-primary-button class="w-full justify-center py-3 text-sm tracking-wider">
                Konfirmasi
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
