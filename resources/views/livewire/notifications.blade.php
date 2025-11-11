<div class="relative">
    <button class="relative">
        🔔
        @if ($unreadCount > 0)
            <span class="absolute top-0 right-0 bg-red-500 text-white text-xs px-2 rounded-full">
                {{ $unreadCount }}
            </span>
        @endif 
    </button>

    <div class="mt-3 bg-white border shadow-md rounded-lg w-80">
        @foreach ($notifications as $notification)
            <div class="p-3 border-b hover:bg-gray-50">
                <div class="{{ $notification->read_at ? 'text-gray-500' : 'font-semibold' }}">
                    {{ $notification->data['message'] }}
                </div>

                @if(!$notification->read_at)
                    <button wire:click="markAsRead('{{ $notification->id }}')" class="text-blue-500 text-xs mt-1">
                        Đánh dấu đã đọc
                    </button>
                @endif
            </div>
        @endforeach
    </div>
</div>
