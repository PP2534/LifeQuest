<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component
{
    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }
}; ?>
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
            <img src="https://i.pravatar.cc/40" alt="User avatar" class="w-10 h-10 rounded-full" />
            <span class="hidden md:block">{{ Auth::user()->name ?? 'Admin' }}</span>
        </button>

        <div x-show="dropdownOpen" @click.away="dropdownOpen = false" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-20" x-cloak>
            <a href="{{ route('profile') }}" wire:navigate class="block px-4 py-2 text-sm text-gray-700 hover:bg-teal-100">Hồ sơ</a>
            <a wire:click="logout" class="block px-4 py-2 text-sm text-gray-700 hover:bg-teal-100">
                Đăng xuất
            </a>
        </div>
    </div>
</header>