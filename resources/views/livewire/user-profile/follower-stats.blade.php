<div>
    <div class="flex flex-wrap justify-center gap-6 text-sm text-gray-600">
        <button type="button"
                wire:click="openModal('following')"
                class="inline-flex items-center gap-2 px-3 py-1 rounded-full border border-gray-200 hover:border-teal-400 hover:text-teal-600 transition">
            <span class="uppercase tracking-wide text-[11px] text-gray-500">Đang theo dõi</span>
            <span class="font-semibold text-gray-800">{{ $followingsCount }}</span>
        </button>
        <button type="button"
                wire:click="openModal('followers')"
                class="inline-flex items-center gap-2 px-3 py-1 rounded-full border border-gray-200 hover:border-teal-400 hover:text-teal-600 transition">
            <span class="uppercase tracking-wide text-[11px] text-gray-500">Người theo dõi</span>
            <span class="font-semibold text-gray-800">{{ $followersCount }}</span>
        </button>
    </div>

    @if($modalOpen)
        <div class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">
            <div class="bg-white rounded-xl shadow-2xl w-full max-w-md mx-4 overflow-hidden">
                <div class="flex items-center justify-between px-6 py-4 border-b">
                    <div class="flex gap-4">
                        <button wire:click="$set('activeTab','followers')"
                                class="font-semibold {{ $activeTab === 'followers' ? 'text-teal-600' : 'text-gray-500' }}">
                            Người theo dõi ({{ $followersCount }})
                        </button>
                        <button wire:click="$set('activeTab','following')"
                                class="font-semibold {{ $activeTab === 'following' ? 'text-teal-600' : 'text-gray-500' }}">
                            Đang theo dõi ({{ $followingsCount }})
                        </button>
                    </div>
                    <button wire:click="closeModal" class="text-gray-500 hover:text-gray-800 text-2xl">&times;</button>
                </div>

                <div class="max-h-96 overflow-y-auto">
                    @php
                        $list = $activeTab === 'followers' ? $followers : $followings;
                    @endphp
                    @if($list->isEmpty())
                        <p class="text-center text-gray-500 py-8">Chưa có dữ liệu.</p>
                    @else
                        <ul class="divide-y divide-gray-100">
                            @foreach($list as $person)
                                <li class="flex items-center px-6 py-4 gap-3">
                                    <a href="{{ route('profile.show', ['id' => $person->id]) }}" wire:navigate>
                                        <img class="h-10 w-10 rounded-full object-cover"
                                             src="{{ $person->avatar ? asset('storage/users/' . $person->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($person->name).'&color=0d9488&background=94ffd8' }}"
                                             alt="{{ $person->name }}">
                                    </a>
                                    <div class="flex-1">
                                        <a href="{{ route('profile.show', ['id' => $person->id]) }}"
                                           wire:navigate
                                           class="font-semibold text-gray-800 hover:underline">
                                            {{ $person->name }}
                                        </a>
                                        <p class="text-xs text-gray-500">
                                            {{ optional(optional($person->ward)->province)->name ?? 'Chưa cập nhật địa điểm' }}
                                        </p>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    @endif
</div>
