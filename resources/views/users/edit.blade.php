<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-bold text-2xl text-espresso leading-tight">
                {{ __('Edit User') }}: {{ $user->name }}
            </h2>
            <a href="{{ route('users.index') }}"
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
            <form method="POST" action="{{ route('users.update', $user) }}" class="p-6 sm:p-8 space-y-6">
                @csrf
                @method('PUT')

                {{-- Info Banner jika mengedit diri sendiri --}}
                @if ($user->id === auth()->id())
                    <div class="flex items-center p-4 bg-amber-50 border border-amber-200 text-amber-800 rounded-xl">
                        <svg class="w-5 h-5 mr-3 text-amber-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <span class="text-sm font-medium">Anda sedang mengedit akun Anda sendiri. Berhati-hatilah saat mengubah role.</span>
                    </div>
                @endif

                {{-- Nama User --}}
                <div>
                    <label for="name" class="block text-sm font-semibold text-charcoal mb-2">
                        Nama Lengkap <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}"
                           placeholder="Contoh: John Doe"
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm text-charcoal placeholder-charcoal/40 focus:ring-2 focus:ring-latte focus:border-latte transition-colors duration-200 {{ $errors->has('name') ? 'border-red-400 focus:ring-red-300 focus:border-red-400' : '' }}" />
                    @error('name')
                        <p class="mt-1.5 text-xs text-red-500 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Email --}}
                <div>
                    <label for="email" class="block text-sm font-semibold text-charcoal mb-2">
                        Email <span class="text-red-500">*</span>
                    </label>
                    <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}"
                           placeholder="Contoh: john@dcoffee.com"
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm text-charcoal placeholder-charcoal/40 focus:ring-2 focus:ring-latte focus:border-latte transition-colors duration-200 {{ $errors->has('email') ? 'border-red-400 focus:ring-red-300 focus:border-red-400' : '' }}" />
                    @error('email')
                        <p class="mt-1.5 text-xs text-red-500 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password (Opsional) --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label for="password" class="block text-sm font-semibold text-charcoal mb-2">
                            Password Baru
                            <span class="text-charcoal/40 font-normal">(opsional)</span>
                        </label>
                        <input type="password" id="password" name="password"
                               placeholder="Kosongkan jika tidak ingin mengubah"
                               class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm text-charcoal placeholder-charcoal/40 focus:ring-2 focus:ring-latte focus:border-latte transition-colors duration-200 {{ $errors->has('password') ? 'border-red-400 focus:ring-red-300 focus:border-red-400' : '' }}" />
                        <p class="mt-1.5 text-xs text-charcoal/50">Kosongkan jika tidak ingin mengubah password lama.</p>
                        @error('password')
                            <p class="mt-1.5 text-xs text-red-500 font-medium">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="password_confirmation" class="block text-sm font-semibold text-charcoal mb-2">
                            Konfirmasi Password Baru
                        </label>
                        <input type="password" id="password_confirmation" name="password_confirmation"
                               placeholder="Ulangi password baru"
                               class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm text-charcoal placeholder-charcoal/40 focus:ring-2 focus:ring-latte focus:border-latte transition-colors duration-200" />
                    </div>
                </div>

                {{-- Role --}}
                <div>
                    <label for="role" class="block text-sm font-semibold text-charcoal mb-2">
                        Role <span class="text-red-500">*</span>
                    </label>
                    @php
                        $currentRole = old('role', $user->roles->first()?->name);
                    @endphp
                    <select id="role" name="role"
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm text-charcoal focus:ring-2 focus:ring-latte focus:border-latte transition-colors duration-200 {{ $errors->has('role') ? 'border-red-400 focus:ring-red-300 focus:border-red-400' : '' }}">
                        <option value="">— Pilih Role —</option>
                        @foreach ($roles as $role)
                            <option value="{{ $role }}" {{ $currentRole === $role ? 'selected' : '' }}>
                                {{ ucfirst($role) }}
                            </option>
                        @endforeach
                    </select>
                    <p class="mt-1.5 text-xs text-charcoal/50">
                        <strong>Owner</strong> = akses penuh &middot;
                        <strong>Manager</strong> = kelola produk, stok & supplier &middot;
                        <strong>Cashier</strong> = hanya kasir (POS)
                    </p>
                    @error('role')
                        <p class="mt-1.5 text-xs text-red-500 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Buttons --}}
                <div class="flex items-center gap-3 pt-4 border-t border-gray-100">
                    <button type="submit"
                            class="inline-flex items-center px-6 py-2.5 bg-espresso hover:bg-espresso-light text-white text-sm font-semibold rounded-xl shadow-md shadow-espresso/20 transition-all duration-200 hover:shadow-lg active:scale-95">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Perbarui User
                    </button>
                    <a href="{{ route('users.index') }}"
                       class="inline-flex items-center px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-charcoal/70 text-sm font-medium rounded-xl transition-all duration-200">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
