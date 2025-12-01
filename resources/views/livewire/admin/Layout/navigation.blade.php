<header class="flex items-center justify-between p-4 bg-white border-b">
    <div class="flex items-center">
        <button @click="sidebarOpen = !sidebarOpen" class="text-gray-500 focus:outline-none lg:hidden">
            <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M4 6H20M4 12H20M4 18H20" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
            </svg>
        </button>
        <h1 class="text-xl font-semibold ml-4">@yield('header_title', 'Dashboard')</h1>
    </div>

    <div x-data="{ dropdownOpen: false }" class="relative">
        <button @click="dropdownOpen = !dropdownOpen" class="flex items-center space-x-2 relative focus:outline-none">
            <img 
                class="h-10 w-10 rounded-full object-cover"
                src="{{ $user->avatar 
                        ? asset('storage/users/' . $user->avatar) 
                        : 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&color=0d9488&background=94ffd8'}}"
                alt="{{ $user->name }}"
            > 
            <span class="hidden md:block">{{ $user->name ?? 'Any' }}</span>
        </button>
        
        <div x-show="dropdownOpen" @click.away="dropdownOpen = false" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-20" x-cloak>
            <a href="{{ route('profile') }}" wire:navigate class="block px-4 py-2 text-sm text-gray-700 hover:bg-teal-100">Hồ sơ</a>
            <button 
                wire:click="logout" 
                type="button" 
                class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-teal-100 cursor-pointer">
                Đăng xuất
            </button>
        </div>
    </div>
</header>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const navToggle = document.getElementById('nav-toggle');
        const primaryMenu = document.getElementById('primary-menu');
        const userMenuButton = document.getElementById('user-menu-button');
        const userMenu = document.getElementById('user-menu');

        navToggle.addEventListener('click', function () {
            primaryMenu.classList.toggle('hidden');
            const isExpanded = navToggle.getAttribute('aria-expanded') === 'true';
            navToggle.setAttribute('aria-expanded', !isExpanded);
        });

        userMenuButton.addEventListener('click', function (event) {
            userMenu.classList.toggle('hidden');
            const isExpanded = userMenuButton.getAttribute('aria-expanded') === 'true';
            userMenuButton.setAttribute('aria-expanded', !isExpanded);
            event.stopPropagation();
        });

        document.addEventListener('click', function (event) {
            if (!userMenu.contains(event.target) && !userMenuButton.contains(event.target)) {
                userMenu.classList.add('hidden');
                userMenuButton.setAttribute('aria-expanded', 'false');
            }
            if (!primaryMenu.contains(event.target) && !navToggle.contains(event.target)) {
                primaryMenu.classList.add('hidden');
                navToggle.setAttribute('aria-expanded', 'false');
            }
        });
    });
</script>