<div x-data="{ open: false }" 
     x-on:open-follow-modal.window="open = true"
     x-show="open"
     style="display: none;"
     class="fixed inset-0 bg-gray-800 bg-opacity-50 flex justify-center items-center z-50">

    <div class="bg-white rounded-2xl shadow-lg w-full max-w-2xl p-6 relative">
        <h2 class="text-2xl font-semibold mb-4 text-gray-800">
            {{ $type === 'followers' ? 'Người theo dõi' : 'Đang theo dõi' }}
        </h2>

        <button @click="open = false" class="absolute top-3 right-3 text-gray-500 hover:text-gray-700">✕</button>

        @forelse($users as $u)
            <div class="flex items-center justify-between py-3 border-b border-gray-200">
                <div class="flex items-center space-x-3">
                    <img src="{{ $u->avatar ?? 'https://via.placeholder.com/50' }}" 
                         alt="avatar" 
                         class="w-12 h-12 rounded-full object-cover">
                    <div>
                        <p class="font-semibold text-gray-800">{{ $u->name }}</p>
                        <p class="text-sm text-gray-600">{{ $u->bio ?? 'Chưa có giới thiệu.' }}</p>
                    </div>
                </div>

                @if(auth()->user()->followingsUsers->contains($u->id))
                    <button wire:click="$emit('toggleFollow', {{ $u->id }})"
                            class="border border-teal-600 text-teal-600 px-4 py-1 rounded-lg hover:bg-teal-50">
                        Bỏ theo dõi
                    </button>
                @else
                    <button wire:click="$emit('toggleFollow', {{ $u->id }})"
                            class="bg-teal-600 text-white px-4 py-1 rounded-lg hover:bg-teal-700">
                        Theo dõi
                    </button>
                @endif
            </div>
        @empty
            <p class="text-gray-500 text-center py-4">Chưa có ai.</p>
        @endforelse
    </div>
</div>
