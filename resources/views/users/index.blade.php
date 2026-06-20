<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-bold text-2xl text-espresso leading-tight">
                {{ __('Manajemen User') }}
            </h2>
            <a href="{{ route('users.create') }}"
               class="inline-flex items-center px-5 py-2.5 bg-espresso hover:bg-espresso-light text-white text-sm font-semibold rounded-xl shadow-md shadow-espresso/20 transition-all duration-200 hover:shadow-lg hover:shadow-espresso/30 active:scale-95">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Tambah User
            </a>
        </div>
    </x-slot>

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
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 6000)"
                 x-transition:leave="transition ease-in duration-300"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 -translate-y-2"
                 class="flex items-center p-4 bg-red-50 border border-red-200 text-red-800 rounded-xl">
                <svg class="w-5 h-5 mr-3 text-red-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="text-sm font-medium">{{ session('error') }}</span>
            </div>
        @endif

        {{-- Search Bar --}}
        <div class="bg-white border border-gray-200/60 rounded-2xl shadow-sm p-4">
            <form method="GET" action="{{ route('users.index') }}" class="flex items-center gap-3">
                <div class="relative flex-1">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-charcoal/40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama atau email user..."
                           class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl text-sm text-charcoal placeholder-charcoal/40 focus:ring-2 focus:ring-latte focus:border-latte transition-colors duration-200" />
                </div>
                <button type="submit"
                        class="inline-flex items-center px-5 py-2.5 bg-latte hover:bg-latte-dark text-espresso text-sm font-semibold rounded-xl transition-all duration-200 active:scale-95">
                    Cari
                </button>
                @if ($search)
                    <a href="{{ route('users.index') }}"
                       class="inline-flex items-center px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-charcoal/70 text-sm font-medium rounded-xl transition-all duration-200">
                        Reset
                    </a>
                @endif
            </form>
        </div>

        {{-- Stats Cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            @php
                $totalUsers = $users->total();
                $roleColors = [
                    'owner'   => ['bg' => 'bg-purple-50', 'border' => 'border-purple-200', 'text' => 'text-purple-700', 'icon' => 'text-purple-500'],
                    'manager' => ['bg' => 'bg-blue-50', 'border' => 'border-blue-200', 'text' => 'text-blue-700', 'icon' => 'text-blue-500'],
                    'cashier' => ['bg' => 'bg-emerald-50', 'border' => 'border-emerald-200', 'text' => 'text-emerald-700', 'icon' => 'text-emerald-500'],
                ];
            @endphp
            <div class="bg-white border border-gray-200/60 rounded-2xl shadow-sm p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold text-charcoal/50 uppercase tracking-wider">Total User</p>
                        <p class="text-2xl font-bold text-espresso mt-1">{{ $totalUsers }}</p>
                    </div>
                    <div class="p-3 bg-latte/20 rounded-xl">
                        <svg class="w-6 h-6 text-espresso" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                </div>
            </div>
            @foreach (['owner' => 'Owner', 'manager' => 'Manager'] as $roleKey => $roleLabel)
                @php $count = \App\Models\User::role($roleKey)->count(); @endphp
                <div class="bg-white border border-gray-200/60 rounded-2xl shadow-sm p-5">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold text-charcoal/50 uppercase tracking-wider">{{ $roleLabel }}</p>
                            <p class="text-2xl font-bold text-espresso mt-1">{{ $count }}</p>
                        </div>
                        <div class="p-3 {{ $roleColors[$roleKey]['bg'] }} rounded-xl">
                            <svg class="w-6 h-6 {{ $roleColors[$roleKey]['icon'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Table --}}
        <div class="bg-white border border-gray-200/60 overflow-hidden shadow-sm rounded-2xl">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead>
                        <tr class="bg-espresso/5 border-b border-gray-200/60">
                            <th class="px-6 py-4 font-semibold text-espresso text-xs uppercase tracking-wider">No</th>
                            <th class="px-6 py-4 font-semibold text-espresso text-xs uppercase tracking-wider">Nama</th>
                            <th class="px-6 py-4 font-semibold text-espresso text-xs uppercase tracking-wider">Email</th>
                            <th class="px-6 py-4 font-semibold text-espresso text-xs uppercase tracking-wider">Role</th>
                            <th class="px-6 py-4 font-semibold text-espresso text-xs uppercase tracking-wider">Bergabung</th>
                            <th class="px-6 py-4 font-semibold text-espresso text-xs uppercase tracking-wider text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($users as $user)
                            @php
                                $roleName = $user->roles->first()?->name ?? '-';
                                $badgeClasses = match($roleName) {
                                    'owner'   => 'bg-purple-100 text-purple-700 ring-purple-600/20',
                                    'manager' => 'bg-blue-100 text-blue-700 ring-blue-600/20',
                                    'cashier' => 'bg-emerald-100 text-emerald-700 ring-emerald-600/20',
                                    default   => 'bg-gray-100 text-gray-700 ring-gray-600/20',
                                };
                            @endphp
                            <tr class="hover:bg-latte/5 transition-colors duration-150">
                                <td class="px-6 py-4 text-charcoal/70 font-medium">
                                    {{ $loop->iteration + ($users->currentPage() - 1) * $users->perPage() }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-full bg-espresso/10 flex items-center justify-center text-espresso font-bold text-sm shrink-0">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <p class="font-semibold text-charcoal">{{ $user->name }}</p>
                                            @if ($user->id === auth()->id())
                                                <span class="text-xs text-latte-dark font-medium">(Anda)</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-charcoal/80">{{ $user->email }}</td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold ring-1 ring-inset {{ $badgeClasses }}">
                                        {{ ucfirst($roleName) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-charcoal/70 text-sm">
                                    {{ $user->created_at->format('d M Y') }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-center gap-1">
                                        <a href="{{ route('users.edit', $user) }}"
                                           class="inline-flex items-center p-2 text-charcoal/50 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition-all duration-200" title="Edit">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>
                                        @if ($user->id !== auth()->id())
                                            <form method="POST" action="{{ route('users.destroy', $user) }}"
                                                  x-data x-ref="deleteForm"
                                                  class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button"
                                                        @click="if(confirm('Yakin ingin menghapus user \'{{ $user->name }}\'? Tindakan ini tidak dapat dibatalkan.')) $refs.deleteForm.submit()"
                                                        class="inline-flex items-center p-2 text-charcoal/50 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all duration-200" title="Hapus">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </form>
                                        @else
                                            <span class="inline-flex items-center p-2 text-charcoal/20 cursor-not-allowed" title="Tidak dapat menghapus akun sendiri">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <svg class="w-12 h-12 text-charcoal/20 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                        </svg>
                                        <p class="text-charcoal/50 font-medium">Belum ada data user.</p>
                                        <a href="{{ route('users.create') }}" class="mt-2 text-sm text-latte-dark hover:text-espresso font-semibold transition-colors duration-200">
                                            + Tambah user pertama
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if ($users->hasPages())
                <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
                    {{ $users->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
