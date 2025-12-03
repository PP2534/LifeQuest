<div x-data="{ open: false }" @click.away="open = false" class="relative" @auth wire:poll.5s="refreshNotifications" @endauth>
    <!-- Nút chuông -->
    <button 
        @click="open = !open" 
        class="relative focus:outline-none transition transform hover:scale-110"
    >
        <div id="notification-bell" class="relative">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                class="w-7 h-7 stroke-black stroke-[0.5] transition hover:drop-shadow-md hover:scale-105">
                <defs>
                    <linearGradient id="bellGradient" x1="0%" y1="0%" x2="100%" y2="0%">
                    <stop offset="0%" stop-color="#facc15"/>
                    <stop offset="100%" stop-color="#fef08a"/>
                    </linearGradient>
                </defs>
                <path d="M18 8a6 6 0 0 0-12 0c0 7-3 9-3 9h18s-3-2-3-9" fill="url(#bellGradient)" />
                <path d="M13.73 21a2 2 0 0 1-3.46 0" fill="url(#bellGradient)" />
            </svg>

            @if ($unreadCount > 0)
                <span class="absolute -top-1 -right-1 bg-red-600 text-white text-xs w-5 h-5 flex items-center justify-center rounded-full shadow-md ring-2 ring-white">
                    {{ $unreadCount }}
                </span>
            @endif
        </div>

    </button>

    <!-- Dropdown thông báo -->
    <div 
        x-show="typeof open !== 'undefined' && open" 
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 translate-y-2"
        class="absolute right-0 mt-3 bg-white border border-gray-100 shadow-lg rounded-xl w-80 z-50 overflow-hidden"
        style="display: none;"
    >
        @auth 
            <div class="px-4 py-2 border-b flex justify-between items-center">
                <h3 class="font-semibold text-gray-800">Thông báo</h3>
                <button 
                    wire:click="markAllAsRead"
                    class="text-sm text-blue-500 hover:underline focus:outline-none"
                    @if($unreadCount == 0) disabled @endif
                    :class="{ 'opacity-50 cursor-not-allowed': {{ $unreadCount }} == 0 }"
                >
                    Đánh dấu tất cả là đã đọc
                </button>
            </div>

            <div class="max-h-80 overflow-y-auto">
                @forelse ($notifications as $notification)
                    <div 
                        wire:click.prevent="markAsRead('{{ $notification->id }}')"
                        class="p-3 border-b last:border-none transition duration-150 ease-in-out cursor-pointer {{ !$notification->read_at ? 'bg-blue-50 hover:bg-blue-100' : 'hover:bg-gray-50' }}"
                        title="{{ !$notification->read_at ? 'Click để đọc và xem chi tiết' : 'Xem chi tiết' }}"
                    >
                        <div class="flex items-start">
                            @if(!$notification->read_at)
                                <div class="w-2 h-2 bg-blue-500 rounded-full mt-2 mr-3 flex-shrink-0"></div>
                            @else
                                <div class="w-2 h-2 bg-transparent rounded-full mt-2 mr-3 flex-shrink-0"></div>
                            @endif
                            <div class="flex-grow">
                                <p class="{{ $notification->read_at ? 'text-gray-600' : 'font-semibold text-gray-800' }}">
                                    @if (isset($notification->data['challenge_invitation_id']))
                                        {{ $notification->data['message'] ?? 'Bạn có lời mời tham gia thử thách mới!' }}
                                    @elseif (isset($notification->data['habit_id']) || isset($notification->data['habit_invitation_id']))
                                        {{ $notification->data['message'] ?? 'Bạn có lời mời tham gia thói quen mới!' }}
                                    @else
                                        {{ $notification->data['message'] }}
                                    @endif
                                </p>
                                <div class="text-xs text-gray-400 mt-1">
                                    {{ $notification->created_at->locale(app()->getLocale() ?? 'vi')->diffForHumans() }}
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-4 text-center text-gray-500">
                        Không có thông báo nào
                    </div>
                @endforelse
            </div>
        @else
            <div class="p-4 text-center text-gray-500">
                Vui lòng đăng nhập để xem thông báo!
            </div>
        @endauth
    </div>

    <!-- Hiệu ứng rung chuông -->
    <style>
        @keyframes bell-shake {
            0%, 100% { transform: rotate(0); }
            20% { transform: rotate(-15deg); }
            40% { transform: rotate(10deg); }
            60% { transform: rotate(-10deg); }
            80% { transform: rotate(15deg); }
        }
        .animate-bounce {
            animation: bell-shake 1s ease-in-out;
        }
    </style>

    <script>
        document.addEventListener('new-notification', () => {
            const bell = document.getElementById('notification-bell');
            if (bell) {
                bell.classList.add('animate-bounce');
                setTimeout(() => bell.classList.remove('animate-bounce'), 1000);
            }
        });
    </script>
</div>
