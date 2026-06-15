<!-- Mobile Backdrop -->
<div x-show="mobileSidebarOpen" 
     x-transition:enter="transition-opacity ease-linear duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition-opacity ease-linear duration-300"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     @click="mobileSidebarOpen = false" 
     class="fixed inset-0 z-40 bg-charcoal/40 backdrop-blur-sm lg:hidden"
     style="display: none;"></div>

<!-- Sidebar Container -->
<div :class="mobileSidebarOpen ? 'translate-x-0' : '-translate-x-full'" 
     class="fixed inset-y-0 left-0 h-screen w-64 bg-espresso text-white flex flex-col justify-between overflow-y-auto transform transition-transform duration-300 ease-in-out lg:translate-x-0 z-50 lg:z-30 shadow-2xl border-r border-espresso-dark"
     style="display: none;"
     x-cloak
     x-init="$el.style.display = ''">
    
    <div>
        <!-- Brand / Logo -->
        <div class="h-16 flex items-center justify-between px-6 bg-espresso-dark border-b border-espresso-light/20">
            <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 group">
                <span class="p-1.5 bg-latte rounded-lg text-espresso flex items-center justify-center transition-transform group-hover:scale-105 duration-200">
                    <!-- Coffee Mug Icon -->
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                    </svg>
                </span>
                <span class="font-extrabold text-xl tracking-wider text-latte group-hover:text-latte-light transition-colors duration-200">
                    DCoffee
                </span>
            </a>
            
            <!-- Mobile Close Button -->
            <button @click="mobileSidebarOpen = false" class="text-white/70 hover:text-white p-1 rounded-md focus:outline-none lg:hidden">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Navigation Links -->
        <nav class="mt-6 px-4 space-y-1">
            <!-- Dashboard Link -->
            <a href="{{ route('dashboard') }}" 
               class="flex items-center space-x-3 px-4 py-3 rounded-xl transition-all duration-200 group {{ request()->routeIs('dashboard') ? 'bg-latte text-espresso font-semibold shadow-md shadow-latte/20' : 'text-white/80 hover:text-white hover:bg-espresso-light/40' }}">
                <span class="{{ request()->routeIs('dashboard') ? 'text-espresso' : 'text-latte group-hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z" />
                    </svg>
                </span>
                <span>Dashboard</span>
            </a>

            <!-- Profile Link -->
            <a href="{{ route('profile.edit') }}" 
               class="flex items-center space-x-3 px-4 py-3 rounded-xl transition-all duration-200 group {{ request()->routeIs('profile.edit') ? 'bg-latte text-espresso font-semibold shadow-md shadow-latte/20' : 'text-white/80 hover:text-white hover:bg-espresso-light/40' }}">
                <span class="{{ request()->routeIs('profile.edit') ? 'text-espresso' : 'text-latte group-hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </span>
                <span>Profile</span>
            </a>

            <!-- Supplier Link (hanya owner & manager) -->
            @hasanyrole('owner|manager')
            <a href="{{ route('suppliers.index') }}"
               class="flex items-center space-x-3 px-4 py-3 rounded-xl transition-all duration-200 group {{ request()->routeIs('suppliers.*') ? 'bg-latte text-espresso font-semibold shadow-md shadow-latte/20' : 'text-white/80 hover:text-white hover:bg-espresso-light/40' }}">
                <span class="{{ request()->routeIs('suppliers.*') ? 'text-espresso' : 'text-latte group-hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                </span>
                <span>Supplier</span>
            </a>

            <!-- Produk Link (hanya owner & manager) -->
            <a href="{{ route('products.index') }}"
               class="flex items-center space-x-3 px-4 py-3 rounded-xl transition-all duration-200 group {{ request()->routeIs('products.*') ? 'bg-latte text-espresso font-semibold shadow-md shadow-latte/20' : 'text-white/80 hover:text-white hover:bg-espresso-light/40' }}">
                <span class="{{ request()->routeIs('products.*') ? 'text-espresso' : 'text-latte group-hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                </span>
                <span>Produk & Resep</span>
            </a>

            <!-- Purchase Orders Link (hanya owner & manager) -->
            <a href="{{ route('purchase-orders.index') }}"
               class="flex items-center space-x-3 px-4 py-3 rounded-xl transition-all duration-200 group {{ request()->routeIs('purchase-orders.*') ? 'bg-latte text-espresso font-semibold shadow-md shadow-latte/20' : 'text-white/80 hover:text-white hover:bg-espresso-light/40' }}">
                <span class="{{ request()->routeIs('purchase-orders.*') ? 'text-espresso' : 'text-latte group-hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </span>
                <span>Purchase Orders</span>
            </a>
            @endhasanyrole

            <!-- Bahan Baku Link (owner, manager, cashier) -->
            @hasanyrole('owner|manager|cashier')
            <a href="{{ route('ingredients.index') }}"
               class="flex items-center space-x-3 px-4 py-3 rounded-xl transition-all duration-200 group {{ request()->routeIs('ingredients.*') ? 'bg-latte text-espresso font-semibold shadow-md shadow-latte/20' : 'text-white/80 hover:text-white hover:bg-espresso-light/40' }}">
                <span class="{{ request()->routeIs('ingredients.*') ? 'text-espresso' : 'text-latte group-hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                    </svg>
                </span>
                <span>Bahan Baku</span>
            </a>
            @endhasanyrole
        </nav>
    </div>

    <!-- Sidebar Footer / Logout -->
    <div class="p-4 border-t border-espresso-light/20 bg-espresso-dark/50">
        <div class="flex items-center justify-between mb-4 px-2">
            <div class="truncate">
                <p class="text-sm font-semibold text-white truncate">{{ Auth::user()->name }}</p>
                <p class="text-xs text-white/60 truncate">{{ Auth::user()->email }}</p>
            </div>
        </div>
        
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" 
                    class="w-full flex items-center justify-center space-x-2 px-4 py-2.5 bg-espresso-light/30 hover:bg-red-950/40 text-white hover:text-red-200 border border-white/10 hover:border-red-900/50 rounded-xl transition-all duration-200 font-medium text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
                <span>Keluar</span>
            </button>
        </form>
    </div>
</div>
