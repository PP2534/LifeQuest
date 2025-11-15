<section>
    <h2 class="text-2xl font-semibold mb-6"> Hoạt động của người bạn theo dõi </h2>
    <ul class="space-y-4">
        @forelse($feed as $activity)
            <li class="bg-white p-4 rounded-lg shadow flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <img src="{{ $activity->user->avatar ?? 'https://i.pravatar.cc/60?u='.$activity->user->id }}" 
                         alt="Avatar {{ $activity->user->name }}" class="w-14 h-14 rounded-full object-cover">
                    <div>
                        <p class="text-gray-700 text-sm">
                            <span class="font-semibold text-teal-600">{{ $activity->user->name }}</span>
                            @if($activity->type == 'post')
                                vừa đăng bài viết mới
                            @elseif($activity->type == 'follow')
                                vừa theo dõi 
                                {{ \App\Models\User::find($activity->details)->name ?? 'ai đó' }}
                            @endif
                            <span class="text-gray-400 text-xs">({{ $activity->created_at->diffForHumans() }})</span>
                        </p>
                    </div>
                </div>
            </li>
        @empty
            <p class="text-gray-500 text-center">Chưa có hoạt động nào từ những người bạn theo dõi.</p>
        @endforelse
    </ul>
</section>
