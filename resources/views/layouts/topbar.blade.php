<header class="h-16 bg-white border-b border-gray-200/80 flex items-center justify-between px-6 sm:px-8 lg:pl-12 lg:pr-8 shadow-sm">
    <!-- Left side: Hamburger button (mobile) & Welcome message (desktop) -->
    <div class="flex items-center space-x-4">
        <!-- Mobile Sidebar Hamburger Toggle -->
        <button @click="mobileSidebarOpen = true" 
                class="text-charcoal hover:text-espresso p-2 rounded-xl hover:bg-gray-100 focus:outline-none lg:hidden transition-colors duration-200">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>
        
        <!-- Mobile App Logo/Name -->
        <div class="flex items-center space-x-2 lg:hidden">
            <span class="p-1 bg-latte rounded text-espresso flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z" />
                </svg>
            </span>
            <span class="font-extrabold text-espresso tracking-wide text-lg">DCoffee</span>
        </div>

        <!-- Desktop Welcome
        <h1 class="hidden lg:block text-sm font-medium text-charcoal/70">
            Selamat Datang di Dashboard, <span class="text-espresso font-bold">{{ Auth::user()->name }}</span>
        </h1> -->
    </div>

    <!-- Right side: User Dropdown menu -->
    <div class="flex items-center space-x-4">
        <x-dropdown align="right" width="48">
            <x-slot name="trigger">
                <button class="inline-flex items-center px-3 py-1.5 border border-transparent text-sm leading-4 font-semibold rounded-xl text-charcoal/80 bg-gray-50 hover:bg-gray-100 hover:text-charcoal focus:outline-none transition ease-in-out duration-150">
                    <div class="w-7 h-7 rounded-full bg-latte/30 text-espresso-dark flex items-center justify-center font-bold mr-2 text-xs border border-latte/50 shadow-inner">
                        {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                    </div>
                    <span class="hidden sm:inline-block max-w-[120px] truncate">{{ Auth::user()->name }}</span>
                    <svg class="fill-current h-4 w-4 ms-1 opacity-60" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
            </x-slot>

            <x-slot name="content">
                <x-dropdown-link :href="route('profile.edit')" class="hover:bg-latte/15 transition-colors">
                    {{ __('Profile') }}
                </x-dropdown-link>

                <div class="border-t border-gray-100"></div>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-dropdown-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();"
                            class="text-red-600 hover:bg-red-50 hover:text-red-700 transition-colors">
                        {{ __('Log Out') }}
                    </x-dropdown-link>
                </form>
            </x-slot>
        </x-dropdown>
    </div>
</header>
