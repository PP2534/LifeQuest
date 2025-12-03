<div>
    @if($isOwnProfile)
        <a href="{{ route('profile.edit')}}"
           wire:navigate
           class="inline-flex p-2 rounded-full border border-gray-200 hover:border-teal-300"
           title="Chỉnh sửa thông tin cá nhân">
            <x-lucide-user-round-pen class="w-6 h-6 text-primary" />
        </a>
    @elseif(auth()->check())
        <button wire:click="toggleFollow"
                wire:loading.attr="disabled"
                class="px-5 py-2 rounded-full font-semibold text-sm transition focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed {{ $isFollowing ? 'bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 focus:ring-gray-300' : 'bg-teal-600 border border-teal-600 text-white hover:bg-teal-700 focus:ring-teal-500' }}">
            <span wire:loading.remove>{{ $isFollowing ? 'Bỏ theo dõi' : 'Theo dõi' }}</span>
            <span wire:loading>Đang xử lý...</span>
        </button>
    @endif
</div>
