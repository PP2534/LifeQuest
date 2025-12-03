<section>
    <h2 class="text-2xl font-semibold mb-6"> Hoạt động của người bạn theo dõi </h2>
    <ul class="space-y-4">
        @forelse($feed as $activity)
            <li class="bg-white p-4 rounded-lg shadow flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <a href="{{ route('profile.show', ['id' => $activity->user->id]) }}" wire:navigate>
                        <img
                            src="{{ $activity->user->avatar
                                ? asset('storage/users/' . $activity->user->avatar)
                                : 'https://ui-avatars.com/api/?name='.urlencode($activity->user->name).'&color=0d9488&background=94ffd8' }}"
                            alt="Avatar {{ $activity->user->name }}"
                            class="w-14 h-14 rounded-full object-cover"
                        >
                    </a>
                    <div>
                        @php
                            $challenge = $activity->type === 'create_challenge'
                                ? $challengeLookup->get((int) $activity->details)
                                : null;
                            $habit = $activity->type === 'create_habit'
                                ? $habitLookup->get((int) $activity->details)
                                : null;
                            $followedUser = $activity->type === 'follow'
                                ? $followedLookup->get((int) $activity->details)
                                : null;
                        @endphp
                        <p class="text-gray-700 text-sm">
                            <a href="{{ route('profile.show', ['id' => $activity->user->id]) }}" wire:navigate class="font-semibold text-teal-600 hover:underline">
                                {{ $activity->user->name }}
                            </a>
                            @if($activity->type == 'post')
                                vừa đăng bài viết mới
                            @elseif($activity->type == 'create_challenge')
                                vừa tạo một thử thách mới
                                @if($challenge && $challenge->type === 'public')
                                    <a href="{{ route('challenges.show', $challenge) }}" wire:navigate class="text-teal-600 font-semibold hover:underline">
                                        (Xem thử thách)
                                    </a>
                                @endif
                            @elseif($activity->type == 'create_habit')
                                vừa tạo một thói quen mới
                                @if($habit && $habit->type === 'group')
                                    <a href="{{ route('habits.show', ['habit' => $habit->id]) }}" wire:navigate class="text-teal-600 font-semibold hover:underline">
                                        (Xem nhóm)
                                    </a>
                                @endif
                            @elseif($activity->type == 'follow')
                                vừa theo dõi 
                                @if($followedUser)
                                    <a href="{{ route('profile.show', ['id' => $followedUser->id]) }}" wire:navigate class="font-semibold text-teal-600 hover:underline">
                                        {{ $followedUser->name }}
                                    </a>
                                @else
                                    ai đó
                                @endif
                            @endif
                            <span class="text-gray-400 text-xs">({{ $activity->created_at->locale(app()->getLocale() ?? 'vi')->diffForHumans() }})</span>
                        </p>
                    </div>
                </div>
            </li>
        @empty
            <p class="text-gray-500 text-center">Chưa có hoạt động nào từ những người bạn theo dõi.</p>
        @endforelse
    </ul>
</section>
