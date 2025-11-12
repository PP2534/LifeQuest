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
{{--
<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" wire:navigate>
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" wire:navigate>
                        {{ __('Dashboard') }}
                    </x-nav-link>
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div x-data="{{ json_encode(['name' => auth()->user()->name]) }}" x-text="name" x-on:profile-updated.window="name = $event.detail.name"></div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile')" wire:navigate>
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <button wire:click="logout" class="w-full text-start">
                            <x-dropdown-link>
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </button>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" wire:navigate>
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800" x-data="{{ json_encode(['name' => auth()->user()->name]) }}" x-text="name" x-on:profile-updated.window="name = $event.detail.name"></div>
                <div class="font-medium text-sm text-gray-500">{{ auth()->user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile')" wire:navigate>
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <button wire:click="logout" class="w-full text-start">
                    <x-responsive-nav-link>
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </button>
            </div>
        </div>
    </div>
</nav>
--}}
<header class="bg-white shadow sticky top-0 z-50" role="banner">
    <nav class="container mx-auto flex items-center justify-between p-4" aria-label="Primary Navigation">
      <a href="{{ route('homepage') }}" wire:navigate class="text-2xl font-extrabold text-teal-600" aria-label="LifeQuest logo">
        LifeQuest
      </a>
      <button id="nav-toggle" aria-controls="primary-menu" aria-expanded="false" aria-label="Toggle navigation menu" class="md:hidden text-teal-600 focus:outline-none focus:ring-2 focus:ring-teal-600">
        <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round"><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
      </button>
      <ul id="primary-menu" class="hidden md:flex md:items-center md:space-x-8" role="menu" aria-label="Main menu">
        <li role="none"><a href="{{route('homepage')}}" class="block py-2 px-3 hover:text-teal-600 focus:outline-none focus:text-teal-600" role="menuitem" tabindex="0">Trang chủ <!-- Home --></a></li>
        <li role="none"><a href="{{ route('habits.index') }}" class="block py-2 px-3 hover:text-teal-600 focus:outline-none focus:text-teal-600" role="menuitem" tabindex="0">Bài Tập <!-- Exercises --></a></li>
        <li role="none"><a href="challenges" class="block py-2 px-3 hover:text-teal-600 focus:outline-none focus:text-teal-600" role="menuitem" tabindex="0">Thử Thách <!-- Challenges --></a></li>
        <li role="none"><a href="{{ route('habits.index') }}" class="block py-2 px-3 hover:text-teal-600 focus:outline-none focus:text-teal-600" role="menuitem" tabindex="0">Thói Quen <!-- Habits --></a></li>
        <li role="none"><a href="{{route('profile')}}" wire:navigate class="block py-2 px-3 hover:text-teal-600 focus:outline-none focus:text-teal-600" role="menuitem" tabindex="0">Thông tin cá nhân <!-- Profile --></a></li>
      </ul>
      <!-- User avatar dropdown -->
      <div class="relative ml-4">
        <button id="user-menu-button" aria-haspopup="true" aria-expanded="false" aria-controls="user-menu" class="flex items-center focus:outline-none focus:ring-2 focus:ring-teal-600 rounded-full" tabindex="0">
            @auth
                <img src="https://i.pravatar.cc/40" alt="User avatar" class="w-10 h-10 rounded-full" />
            @else
                <img src="https://i.pravatar.cc/40" alt="User avatar" class="w-10 h-10 rounded-full" />
            @endauth
            
          <span class="sr-only">Menu người dùng <!-- User menu --></span>
        </button>
        <ul id="user-menu" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none" role="menu" aria-label="User menu">
        <!-- TODO: add auth check here -->
           @auth
            <li><a href="{{route('profile')}}" wire:navigate class="block px-4 py-2 text-gray-700 hover:bg-teal-100" role="menuitem" tabindex="-1">Hồ sơ của tôi <!-- My Profile --></a></li>
            <li><a wire:click="logout" class="block px-4 py-2 text-gray-700 hover:bg-teal-100 cursor-pointer" role="menuitem" tabindex="-1">Đăng xuất <!-- Logout --></a></li>
           @else
            <li><a href="{{route('login')}}" wire:navigate class="block px-4 py-2 text-gray-700 hover:bg-teal-100" role="menuitem" tabindex="-1">Đăng nhập</a></li>
            <li><a href="{{route('register')}}" wire:navigate class="block px-4 py-2 text-gray-700 hover:bg-teal-100" role="menuitem" tabindex="-1">Đăng ký</a></li>
           @endauth
          
        </ul>
      </div>
    </nav>
  </header>