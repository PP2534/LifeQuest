<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
         <meta name="description" content="LifeQuest - Nền tảng theo dõi thử thách và thói quen giúp bạn phát triển bản thân" />
        <link rel="icon" href="{{ asset('storage/root/favicon.png') }}" type="image/x-icon">
        <link rel="shortcut icon" href="{{ asset('storage/root/favicon.png') }}" type="image/x-icon">
        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css2?family=Inter&family=Noto+Sans&display=swap" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css2?family=Noto+Sans&display=swap&subset=vietnamese" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/nprogress@0.2.0/nprogress.css">
        <style>
            /* Small override for font-family */
            body {
            font-family: 'Noto Sans', 'Inter', system-ui, sans-serif;
            }
        </style>
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gray-50 text-gray-800">
        <!-- <div class="min-h-screen bg-gray-100"> -->
            <livewire:layout.navigation />

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white shadow">
                    <div class="container mx-auto py-6 px-4 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <div class="container mx-auto px-4 py-8 min-h-[80dvh]">
                <div class="lg:flex lg:space-x-8">
                    <main class="flex-1 min-w-0">
                        {{ $slot }}
                    </main>
                     @if (!request()->is('/') && !request()->is('challenges')&& !request()->is('community') && !request()->is('leaderboard') && !request()->is('forgot-password') && !request()->is('reset-password') && !request()->is('register') && !request()->is('login'))
                        <aside class="w-full lg:w-80 xxl:w-96 flex-shrink-0 mt-8 lg:mt-0">
                            <div class="sticky top-32 space-y-6">
                                <livewire:leaderboard />
                            </div>
                        </aside>
                    @endif
                </div>
            </div>
 
            <livewire:layout.footer />
        <!-- </div> -->
        <script data-navigate-once>
            function setupNavigation() {
                // Mobile nav toggle
                const navToggle = document.getElementById('nav-toggle');
                const primaryMenu = document.getElementById('primary-menu');
                if (navToggle && primaryMenu) {
                    navToggle.addEventListener('click', () => {
                        const expanded = navToggle.getAttribute('aria-expanded') === 'true' || false;
                        navToggle.setAttribute('aria-expanded', !expanded);
                        primaryMenu.classList.toggle('hidden');
                    });
                }
    
                // User menu dropdown toggle
                const userMenuButton = document.getElementById('user-menu-button');
                const userMenu = document.getElementById('user-menu');
                if (userMenuButton && userMenu) {
                    userMenuButton.addEventListener('click', () => {
                        const expanded = userMenuButton.getAttribute('aria-expanded') === 'true' || false;
                        userMenuButton.setAttribute('aria-expanded', !expanded);
                        userMenu.classList.toggle('hidden');
                    });
                }
    
                // Close menus on outside click
                document.addEventListener('click', (e) => {
                    if (userMenuButton && userMenu && !userMenuButton.contains(e.target) && !userMenu.contains(e.target)) {
                        userMenu.classList.add('hidden');
                        userMenuButton.setAttribute('aria-expanded', false);
                    }
                    if (navToggle && primaryMenu && !navToggle.contains(e.target) && !primaryMenu.contains(e.target)) {
                        primaryMenu.classList.add('hidden');
                        navToggle.setAttribute('aria-expanded', false);
                    }
                });
            }
            
            document.addEventListener('livewire:navigated', () => {
                setupNavigation();
            })
        </script>
        <script>
            document.addEventListener('livewire:init', () => {
                // Xóa nội dung Trix editor
                Livewire.on('trix-clear', () => {
                    document.querySelector('trix-editor').editor.loadHTML('');
                });
        
                // Chèn file đính kèm đã tải lên vào Trix
                Livewire.on('trix-attachment-upload-completed', (event) => {
                    const { url, href, attachment } = event[0];
                    const trixAttachment = document.querySelector(`trix-attachment[sgid='${attachment.sgid}']`);
                    if (trixAttachment) {
                        trixAttachment.setAttributes({ url, href });
                    }
                });
            });
        
            document.addEventListener('livewire:request-start', () => {
                NProgress.start();
            });

            document.addEventListener('livewire:request-end', () => {
                NProgress.done();
            });
        </script>
        <script src="https://cdn.jsdelivr.net/npm/nprogress@0.2.0/nprogress.js"></script>
    </body>
</html>
