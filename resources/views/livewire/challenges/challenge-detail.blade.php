<div> <main role="main" class="container mx-auto px-4 py-12 max-w-4xl">
    <article aria-label="Challenge detail" class="bg-white rounded-lg shadow p-8">
        
        @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg mb-6" role="alert">
                <p class="font-bold">Thành công!</p>
                <p>{{ session('success') }}</p>
            </div>
        @endif
        @if (session('info'))
            <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 rounded-lg mb-6" role="alert">
                <p>{{ session('info') }}</p>
            </div>
        @endif

        <header class="mb-6">
            <h1 class="text-3xl font-bold text-teal-600 mb-2">{{ $challenge->title }}</h1>
            <p class="text-sm text-teal-700 font-semibold mb-1">
                {{ $challenge->categories->first()->name ?? 'Chưa phân loại' }}
            </p>
            <p class="text-sm text-gray-600">Thời gian thử thách: {{ $challenge->duration_days }} ngày</p>
        </header>

        <section aria-label="Challenge description" class="mb-8">
            <h2 class="text-xl font-semibold mb-2">Mô tả thử thách</h2>
            <div class="text-gray-700 leading-relaxed prose max-w-none">
                {!! $challenge->description !!}
            </div>
        </section>

        <section aria-label="Join challenge" class="mb-8">
            @auth
                @if ($isParticipant)
                    <button wire:click="leaveChallenge" wire:confirm="Bạn có chắc chắn muốn rời khỏi thử thách này không?"
                            class="bg-red-600 hover:bg-red-700 text-white font-semibold px-6 py-3 rounded-lg focus:outline-none focus:ring-4 focus:ring-red-400" 
                            aria-pressed="true">
                        Đã tham gia (Rời khỏi)
                    </button>
                @else
                    <button wire:click="joinChallenge"
                            class="bg-teal-600 hover:bg-teal-700 text-white font-semibold px-6 py-3 rounded-lg focus:outline-none focus:ring-4 focus:ring-teal-400" 
                            aria-pressed="false">
                        Tham gia thử thách
                    </button>
                @endif
            @else
                <a href="{{ route('login') }}" wire:navigate
                   class="bg-teal-600 hover:bg-teal-700 text-white font-semibold px-6 py-3 rounded-lg focus:outline-none focus:ring-4 focus:ring-teal-400">
                    Đăng nhập để tham gia
                </a>
            @endauth
        </section>

        @if ($isParticipant && $myParticipation)
            <section aria-label="Progress bar" class="mb-8">
                <h2 class="text-xl font-semibold mb-4">Tiến trình của bạn</h2>
                <div class="w-full bg-gray-200 rounded-full h-6 relative overflow-hidden" 
                     role="progressbar" aria-valuemin="0" aria-valuemax="100" 
                     aria-valuenow="{{ $myParticipation->progress_percent }}" 
                     aria-label="Tiến trình phần trăm">
                    
                    <div id="progress-fill" class="bg-teal-600 h-6 rounded-full transition-width duration-1000 ease-in-out" 
                         style="width: {{ $myParticipation->progress_percent }}%;">
                    </div>
                    
                    <span class="absolute right-3 top-0 text-white font-semibold text-sm leading-6">
                        {{ $myParticipation->progress_percent }}%
                    </span>
                </div>
                @if ($myParticipation->streak > 0)
                    <div class="inline-block mt-2 bg-amber-400 text-amber-900 text-xs font-semibold px-3 py-1 rounded-full select-none" 
                         aria-label="Chuỗi ngày liên tiếp">
                        Chuỗi {{ $myParticipation->streak }} ngày 
                        </div>
                @endif
            </section>
        @endif

        <section aria-label="Leaderboard" class="mb-8">
            <h2 class="text-xl font-semibold mb-4">Bảng xếp hạng ({{ $leaderboard->count() }} thành viên)</h2>
            <div class="bg-white border border-gray-300 rounded shadow max-h-64 overflow-y-auto">
                <table class="w-full text-left text-gray-700 text-sm">
                    <thead class="bg-gray-100 sticky top-0">
                        <tr>
                            <th class="px-4 py-2">Hạng</th>
                            <th class="px-4 py-2">Tên người dùng</th>
                            <th class="px-4 py-2">Tiến trình</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($leaderboard as $index => $participant)
                            @if ($participant->user)
                                <tr class="border-t border-gray-200 hover:bg-teal-50 
                                    {{ ($myParticipation && $participant->user_id == $myParticipation->user_id) ? 'bg-teal-100 font-bold' : '' }}">
                                    <td class="px-4 py-2 font-semibold">{{ $index + 1 }}</td>
                                    <td class="px-4 py-2 flex items-center">
                                        <img src="{{ $participant->user->avatar ?? 'https://i.pravatar.cc/40?u='.$participant->user_id }}" alt="Avatar" class="w-8 h-8 rounded-full mr-2" />
                                        {{ $participant->user->name }}
                                        @if($participant->role == 'creator')
                                            <span class="ml-2 text-xs bg-indigo-100 text-indigo-800 px-2 py-0.5 rounded-full">Người tạo</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-2">{{ $participant->progress_percent }}%</td>
                                </tr>
                            @endif
                        @empty
                            <tr class="border-t border-gray-200">
                                <td colspan="3" class="px-4 py-3 text-center text-gray-500">
                                    Chưa có ai tham gia thử thách này.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <section aria-label="Comments" class="mb-8">
            <h2 class="text-xl font-semibold mb-4">Bình luận ({{ $challenge->comments->count() }})</h2>

            @auth
                <form wire:submit="addComment" class="flex items-start mb-6">
                    <img src="{{ Auth::user()->avatar ?? 'https://i.pravatar.cc/40?u=me' }}" alt="Avatar" class="w-10 h-10 rounded-full mr-3" />
                    <div class="flex-1">
                        <textarea wire:model="newComment" placeholder="Viết bình luận..." rows="2"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-teal-500 focus:border-teal-500"></textarea>
                        @error('newComment') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        <div class="text-right mt-2">
                            <button type="submit" class="bg-teal-600 hover:bg-teal-700 text-white text-sm font-semibold px-4 py-2 rounded-lg">
                                Gửi
                            </button>
                        </div>
                    </div>
                </form>
            @else
                <div class="text-center bg-gray-50 p-4 rounded-lg mb-6">
                    <a href="{{ route('login') }}" wire:navigate class="font-semibold text-teal-600 hover:underline">Đăng nhập</a>
                    <span class="text-gray-600"> để tham gia thảo luận.</span>
                </div>
            @endauth

            <ul wire:poll.5s class="space-y-4">
                @forelse ($challenge->comments->sortByDesc('created_at') as $comment)
                    @if ($comment->user)
                        <li class="flex items-start">
                            <img src="{{ $comment->user->avatar ?? 'https://i.pravatar.cc/40?u='.$comment->user_id }}" alt="Avatar" class="w-10 h-10 rounded-full mr-3" />
                            <div class="flex-1 bg-gray-100 rounded-lg px-4 py-3">
                                <div class="flex justify-between items-center mb-1">
                                    <span class="font-semibold text-sm">{{ $comment->user->name }}</span>
                                    <span class="text-xs text-gray-500">{{ $comment->created_at->diffForHumans() }}</span>
                                </div>
                                <p class="text-sm text-gray-700">{!! nl2br(e($comment->content)) !!}</p>
                            </div>
                        </li>
                    @endif
                @empty
                    <li class="text-center text-gray-500 text-sm">
                        Chưa có bình luận nào.
                    </li>
                @endforelse
            </ul>
        </section>

        <section aria-label="Reactions" class="mb-8">
            <h2 class="text-xl font-semibold mb-4">Cảm xúc</h2>
            <div class="flex space-x-4">
                <button class="flex items-center space-x-1 px-3 py-2 border rounded-full hover:bg-gray-100">
                    <span>👍</span><span class="text-sm">12</span>
                </button>
                <button class="flex items-center space-x-1 px-3 py-2 border rounded-full hover:bg-gray-100">
                    <span>❤️</span><span class="text-sm">8</span>
                </button>
                <button class="flex items-center space-x-1 px-3 py-2 border rounded-full hover:bg-gray-100">
                    <span>🔥</span><span class="text-sm">5</span>
                </button>
                <button class="flex items-center space-x-1 px-3 py-2 border rounded-full hover:bg-gray-100">
                    <span>🎉</span><span class="text-sm">3</span>
                </button>
                <button class="flex items-center space-x-1 px-3 py-2 border rounded-full hover:bg-gray-100">
                    <span>🙌</span><span class="text-sm">7</span>
                </button>
            </div>
        </section>

    </article>
    </main>

</div>