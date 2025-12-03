<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="LifeQuest Admin - Trang quản trị." />

    <title>{{ config('app.name', 'Laravel') }} - Admin</title>

    <link rel="icon" href="{{ asset('favicon.png') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('favicon.png') }}" type="image/x-icon">
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Inter&family=Noto+Sans&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans&display=swap&subset=vietnamese" rel="stylesheet" />
    <style>
        body {
            font-family: 'Noto Sans', 'Inter', system-ui, sans-serif;
        }
    </style>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-100 text-gray-800">
    <div x-data="{ sidebarOpen: false }" class="flex h-screen bg-gray-100">
        <!-- Sidebar -->
        <aside 
            class="fixed inset-y-0 left-0 z-30 w-64 overflow-y-auto transition duration-300 transform bg-gray-800 text-white lg:translate-x-0 lg:static lg:inset-0"
            :class="{'translate-x-0 ease-out': sidebarOpen, '-translate-x-full ease-in': !sidebarOpen}"
            aria-label="Sidebar"
        >
            <div class="flex items-center justify-center p-4 border-b border-gray-700">
                <a href="{{ route('admin.home') }}" wire:navigate class="text-2xl font-extrabold text-teal-400">
                    <img src="{{asset('logo_lifequest.png')  }}">
                </a>
            </div>

            <nav class="mt-4">
                <a class="flex items-center mt-2 py-3 px-6 {{ request()->routeIs('admin.dashboard') ? 'bg-gray-700' : '' }} hover:bg-gray-700" href="{{ route('admin.dashboard') }}" wire:navigate>
                    <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    <span class="mx-3">Dashboard</span>
                </a>
                <a class="flex items-center mt-2 py-3 px-6 {{ request()->routeIs('admin.users.*') ? 'bg-gray-700' : '' }} hover:bg-gray-700" href="{{ route('admin.users.list') }}" wire:navigate>
                    <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M15 21a6 6 0 00-9-5.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-3-5.197M15 21a6 6 0 00-9-5.197"/></svg>
                    <span class="mx-3">Quản lý người dùng</span>
                </a>
                <a class="flex items-center mt-2 py-3 px-6 {{ request()->routeIs('admin.challenges.*') ? 'bg-gray-700' : '' }} hover:bg-gray-700" href="{{ route('admin.challenges.list') }}" wire:navigate>
                    <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                    <span class="mx-3">Quản lý thử thách</span>
                </a>
                <a class="flex items-center mt-2 py-3 px-6 {{ request()->routeIs('admin.categories.*') ? 'bg-gray-700' : '' }} hover:bg-gray-700" href="{{ route('admin.categories.list') }}" wire:navigate>
                    <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" /></svg>
                    <span class="mx-3">Quản lý danh mục</span>
                </a>
                <a class="flex items-center mt-2 py-3 px-6 hover:bg-gray-700" href="#">
                    <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" /></svg>
                    <span class="mx-3">Cấu hình thông báo</span>
                </a>
            </nav>
        </aside>

        <!-- Main content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Header -->
            <livewire:admin.layout.navigation />

            <!-- Page Content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100">
                <div class="container mx-auto px-6 py-8">
                    {{ $slot }}
                </div>
            </main>

            <livewire:admin.layout.footer />
        </div>
    </div>
</body>
</html>