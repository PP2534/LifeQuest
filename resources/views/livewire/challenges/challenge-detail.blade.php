<div> <main role="main" class="container mx-auto px-4 py-12 max-w-4xl">
    <article aria-label="Challenge detail" class="bg-white rounded-lg shadow p-8">
        @if($challenge->start_date)
            <div
                @php
                    $countdownTarget = null;
                    $countdownMessage = '';
                    $countdownColor = 'teal-700'; // Default for active countdown
                    $displayDates = false; // Flag to show start/end dates

                    if ($challenge->time_mode == 'fixed') {
                        if (!$this->isLocked) {
                            // Fixed challenge, not started yet. Countdown to start date.
                            $countdownTarget = $challenge->start_date;
                            $countdownMessage = '🔥 Đếm ngược Thời gian';
                        } elseif (!$this->isEnded) {
                            // Fixed challenge, started and not ended yet. Countdown to end date.
                            $countdownTarget = $this->endDate;
                            $countdownMessage = 'Thử thách đang diễn ra - Thời gian còn lại';
                            $countdownColor = 'red-700';
                            $displayDates = true; // Show dates when ongoing fixed
                        } else {
                            // Fixed challenge, ended.
                            $countdownMessage = $this->lockedMessage; // "Thử thách đã kết thúc"
                            $countdownColor = 'red-600';
                            $displayDates = true; // Always show dates when ended
                        }
                    } elseif ($challenge->time_mode == 'rolling') {
                        if (!$this->isEnded) {
                            // Rolling challenge, not ended yet. Countdown to end date.
                            $countdownTarget = $this->endDate;
                            $countdownMessage = 'Thử thách đang diễn ra - Thời gian còn lại';
                        } else {
                            // Rolling challenge, ended.
                            $countdownMessage = $this->lockedMessage; // "Thử thách đã kết thúc"
                            $countdownColor = 'red-600';
                            $displayDates = true; // Always show dates when ended
                        }
                    }
                @endphp

                @if($countdownTarget)
                    x-data="countdown('{{ \Carbon\Carbon::parse($countdownTarget)->format('Y-m-d H:i:s') }}')"
                    x-init="init()"
                @endif
                class="mb-6 p-4 rounded-xl text-center shadow-inner border border-teal-100 bg-gradient-to-r from-teal-50 to-white">

                <div class="flex flex-col items-center text-gray-700">
                    <span class="text-2xl font-bold uppercase tracking-widest {{ $countdownColor }}">
                        {{ $countdownMessage }}
                    </span>
                    
                    @if($displayDates)
                        <div class="text-xs font-mono mt-2 text-gray-500 flex gap-4">
                            <span>Bắt đầu: {{ \Carbon\Carbon::parse($challenge->start_date)->format('d/m/Y H:i') }}</span>
                            <span>Kết thúc: {{ $this->endDate ? $this->endDate->format('d/m/Y H:i') : 'N/A' }}</span>
                        </div>
                    @endif

                    @if ($countdownTarget)
                        <div class="flex justify-center gap-4 font-mono text-3xl md:text-4xl font-bold text-{{ $countdownColor }}">
                            <div class="flex flex-col items-center">
                                <span x-text="days">00</span>
                                <span class="text-xs font-sans text-gray-400 font-normal">Ngày</span>
                            </div>
                            <span>:</span>
                            <div class="flex flex-col items-center">
                                <span x-text="hours">00</span>
                                <span class="text-xs font-sans text-gray-400 font-normal">Giờ</span>
                            </div>
                            <span>:</span>
                            <div class="flex flex-col items-center">
                                <span x-text="minutes">00</span>
                                <span class="text-xs font-sans text-gray-400 font-normal">Phút</span>
                            </div>
                            <span>:</span>
                            <div class="flex flex-col items-center">
                                <span x-text="seconds">00</span>
                                <span class="text-xs font-sans text-gray-400 font-normal">Giây</span>
                            </div>
                        </div>
                    @endif

                    @if(!$countdownTarget && !$displayDates && $challenge->start_date)
                        {{-- Fallback for scenarios like rolling not ended, but no end date (shouldn't happen with current logic) --}}
                        <p class="text-sm text-gray-500 mt-2">Thử thách đang chờ hoặc không yêu cầu đếm ngược cụ thể.</p>
                    @elseif(!$challenge->start_date)
                        <p class="text-sm text-gray-500 mt-2">Thời gian bắt đầu chưa được thiết lập cho thử thách này.</p>
                    @endif
                </div>
            </div>
        @endif
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

        <header class="mb-6 border-b pb-6 relative">
            @if($challenge->creator_id === Auth::id() && $challenge->start_date < now() && $challenge->time_mode === 'fixed')
                <button wire:click="$set('showDateModal', true)" 
                        class="absolute top-0 right-0 text-xs bg-gray-100 hover:bg-gray-200 text-gray-600 px-3 py-1 rounded-full border border-gray-300 flex items-center transition">
                    📅 {{ $challenge->start_date ? 'Đổi giờ' : 'Đặt thời gian' }}
                </button>
            @endif

            <h1 class="text-3xl font-bold text-teal-600 mb-2">{{ $challenge->title }}</h1>
        </header>

        <section aria-label="Challenge description" class="mb-8">
            <h2 class="text-xl font-semibold mb-2">Mô tả thử thách</h2>
            <div class="text-gray-700 leading-relaxed prose max-w-none">
                {!! $challenge->description !!}
            </div>
        </section>

       <section aria-label="Join challenge" class="mb-8 flex gap-4 align-middle">
                @auth
                     @if($canInvite && !$this->isChallengeDisplayLocked)
                        <button wire:click="openInviteModal"
                                class="bg-teal-600 hover:bg-teal-700 text-white font-semibold px-6 py-3 rounded-lg focus:outline-none focus:ring-4 focus:ring-indigo-300 transition">
                            Mời bạn bè
                        </button>
                    @endif
                    @if ($myParticipation)
                        @if ($myParticipation->status === 'kicked')
                            <div class="w-full bg-gray-400 text-white font-semibold px-6 py-3 rounded-lg text-center cursor-not-allowed shadow-inner">
                                🔒 Bạn đã bị loại khỏi thử thách
                            </div>
                        @endif
                    @endif
                @endauth
            </section>

       @if ($isParticipant && $myParticipation && $myParticipation->status === 'active')
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
                <div class="mt-6 flex justify-center">
                    <a href="{{ route('challenges.checkin', $challenge) }}" wire:navigate
                        class="inline-flex items-center px-6 py-3 bg-teal-600 hover:bg-teal-700 text-white font-bold rounded-lg shadow-lg transform transition hover:scale-105">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        Điểm danh & Xem Lịch
                    </a>
                </div>
            </section>
        @endif


        @if(!$isParticipant && !$this->isChallengeDisplayLocked)
            <section aria-label="Call to action" class="my-8">
                <div class="bg-teal-50 border-2 border-dashed border-teal-200 rounded-lg text-center p-8">
                    <h3 class="text-xl font-bold text-teal-800 mb-2">Sẵn sàng chinh phục thử thách?</h3>
                    <p class="text-teal-700 mb-6">Hãy tham gia ngay để bắt đầu ghi nhận tiến trình và leo lên bảng xếp hạng!</p>
                    
                    @auth
                        @if (!$myParticipation)
                            <button wire:click="joinChallenge"
                                    class="bg-teal-600 hover:bg-teal-700 text-white font-bold px-8 py-3 rounded-lg focus:outline-none focus:ring-4 focus:ring-teal-400 shadow-lg text-base transform hover:scale-105 transition">
                                Tham gia ngay!
                            </button>
                        @endif
                    @else
                        <a href="{{ route('login') }}" wire:navigate
                           class="bg-teal-600 hover:bg-teal-700 text-white font-bold px-8 py-3 rounded-lg focus:outline-none focus:ring-4 focus:ring-teal-400 inline-block shadow-lg text-base transform hover:scale-105 transition">
                            Đăng nhập để tham gia
                        </a>
                    @endauth
                </div>
            </section>
        @endif

        <section aria-label="Leaderboard" class="mb-8 relative">
            <div>
                <h2 class="text-xl font-semibold mb-4">Bảng xếp hạng ({{ $leaderboard->count() }} thành viên)</h2>
                <div class="bg-white border border-gray-300 rounded shadow max-h-[40rem] overflow-y-auto">
                    <table class="w-full text-left text-gray-700 text-sm">
                        <thead class="bg-gray-100 sticky top-0 z-10">
                            <tr>
                                <th class="px-4 py-2">Hạng</th>
                                <th class="px-4 py-2">Tên người dùng</th>
                                <th class="px-4 py-2">Tiến trình</th>
                                <th class="px-4 py-2">Streak</th>
                                
                                @if($challenge->creator_id === Auth::id())
                                    <th class="px-4 py-2 text-center">Quản lý</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($leaderboard as $index => $participant)
                                <tr class="border-t border-gray-200 hover:bg-teal-50 
                                    {{ $participant->user_id == Auth::id() ? 'bg-teal-100' : '' }}">
                                    
                                    <td class="px-4 py-2 font-semibold">{{ $index + 1 }}</td>
                                    
                                   <td class="px-4 py-2">
                                        <div class="flex items-center">
                                            <img src="{{ $participant->user->avatar ?? 'https://ui-avatars.com/api/?name='.urlencode($participant->user->name) }}" 
                                                 alt="{{ $participant->user->name }}" 
                                                 class="w-8 h-8 rounded-full mr-2" />
                                            
                                            <div class="flex flex-col">
                                                <div class="flex items-center">
                                                    <span class="font-medium text-gray-900">{{ $participant->user->name }}</span>
                                                    
                                                   @if($participant->role == 'creator')
                                                        <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-indigo-100 text-indigo-800 border border-indigo-200">
                                                            Người tạo
                                                        </span>
                                                    @else
                                                        <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-600 border border-gray-200">
                                                            Thành viên
                                                        </span>
                                                    @endif
                                                </div>

                                                @if($participant->status === 'kicked')
                                                    <span class="text-xs text-red-500 font-bold mt-0.5">
                                                        🚫 Đã bị khóa
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>

                                    <td class="px-4 py-2">{{ $participant->progress_percent }}%</td>
                                    <td class="px-4 py-2">{{ $participant->streak }}</td> 

                                    @if($challenge->creator_id === Auth::id())
                                        <td class="px-4 py-2 text-center">
                                            @if($participant->user_id !== Auth::id())
                                                @if($participant->status === 'active')
                                                    <button wire:click="kickMember({{ $participant->id }})" 
                                                            wire:confirm="Bạn có chắc chắn muốn khóa thành viên này khỏi thử thách?"
                                                            class="text-xs bg-red-100 text-red-600 px-3 py-1 rounded hover:bg-red-200 border border-red-200 font-semibold">
                                                        Kick 🚫
                                                    </button>
                                                @elseif($participant->status === 'kicked')
                                                    <button wire:click="restoreMember({{ $participant->id }})"
                                                            class="text-xs bg-green-100 text-green-600 px-3 py-1 rounded hover:bg-green-200 border border-green-200 font-semibold">
                                                        Bỏ Kick ✅
                                                    </button>
                                                @endif
                                            @endif
                                        </td>
                                    @endif
                                </tr>
                            @empty
                                @endforelse
                        </tbody>
                    </table>
                </div>
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
        
        @if ($myParticipation && $myParticipation->status !== 'kicked')
            <section aria-label="Leave challenge" class="mt-12 pt-8 border-t border-gray-200 text-center">
                 @if ($myParticipation->status === 'active')
                    <a wire:click="leaveChallenge" 
                            wire:confirm="Bạn có chắc chắn muốn rời khỏi thử thách này không? Mọi tiến trình sẽ bị mất."
                            class="text-red-600 cursor-pointer hover:underline font-semibold focus:outline-none focus:ring-4 focus:ring-red-400">
                        Rời khỏi thử thách
                    </a>
                    <p class="text-xs text-gray-500 mt-2">Hành động này không thể hoàn tác.</p>
                @endif
            </section>
        @endif

    </article>
    </main>@if($showInviteModal)
        <div class="fixed inset-0 bg-gray-900 bg-opacity-50 overflow-y-auto h-full w-full z-50 flex items-center justify-center">
            <div class="bg-white rounded-xl shadow-2xl max-w-md w-full mx-4 overflow-hidden transform transition-all">
                
                <div class="bg-teal-600 px-6 py-4 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-white">Mời bạn bè</h3>
                    <button wire:click="$set('showInviteModal', false)" class="text-white hover:text-gray-200 text-2xl">&times;</button>
                </div>

                <div class="p-6 max-h-96 overflow-y-auto">
                    @if($this->followings->count() > 0)
                        <ul class="space-y-4">
                            @foreach($this->followings as $friend)
                                <li class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                                    
                                    <div class="flex items-center">
                                        <img src="{{ $friend->avatar ? asset('storage/'.$friend->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($friend->name) }}" class="w-10 h-10 rounded-full mr-3 object-cover border border-gray-200">
                                        <div>
                                            <p class="font-semibold text-gray-800 text-sm">{{ $friend->name }}</p>
                                            <p class="text-xs text-gray-500">{{ $friend->email }}</p>
                                        </div>
                                    </div>

                                    <div>
                                        @if($friend->invite_status === 'active')
                                            <span class="text-xs font-semibold text-green-700 bg-green-100 px-3 py-1 rounded-full border border-green-200">
                                                Đã tham gia
                                            </span>

                                        @elseif($friend->invite_status === 'pending')
                                            <span class="text-xs font-semibold text-orange-700 bg-orange-100 px-3 py-1 rounded-full border border-orange-200">
                                                Đã mời
                                            </span>

                                        @elseif($friend->invite_status === 'rejected')
                                            <div class="flex flex-col items-end">
                                                <span class="text-[10px] text-red-500 font-medium mb-1">Đã từ chối</span>
                                                <button wire:click="inviteUser({{ $friend->id }})" 
                                                        wire:loading.attr="disabled"
                                                        class="text-xs bg-teal-100 text-teal-700 hover:bg-teal-200 px-3 py-1 rounded-full transition border border-teal-200">
                                                    Mời lại
                                                </button>
                                            </div>

                                        @else
                                            <button wire:click="inviteUser({{ $friend->id }})" 
                                                    wire:loading.attr="disabled"
                                                    class="text-xs bg-teal-600 hover:bg-teal-700 text-white px-4 py-1.5 rounded-lg transition shadow-sm font-medium">
                                                + Mời
                                            </button>
                                        @endif
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="text-center py-8">
                            <p class="text-gray-500">Bạn chưa theo dõi ai, hoặc tất cả bạn bè đã tham gia.</p>
                        </div>
                    @endif
                </div>
                
                <div class="bg-gray-50 px-6 py-3 text-right border-t border-gray-100">
                    <button wire:click="$set('showInviteModal', false)" class="text-gray-600 hover:text-gray-900 font-medium text-sm px-4 py-2 rounded hover:bg-gray-200 transition">
                        Đóng
                    </button>
                </div>
            </div>
        </div>
    @endif
    @if($pendingInvitation)
        <div class="fixed inset-0 bg-gray-900 bg-opacity-60 overflow-y-auto h-full w-full z-50 flex items-center justify-center backdrop-blur-sm">
            <div class="bg-white rounded-xl shadow-2xl max-w-md w-full mx-4 overflow-hidden transform transition-all scale-100">
                
                <div class="bg-gradient-to-r from-teal-500 to-teal-600 px-6 py-4 text-center">
                    <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-white mb-2">
                        <svg class="h-6 w-6 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-white">Bạn nhận được lời mời!</h3>
                </div>

                <div class="p-6 text-center">
                    <p class="text-gray-600 mb-4">
                        <span class="font-bold text-gray-800">{{ $pendingInvitation->inviter->name ?? 'Một người bạn' }}</span> 
                        đã mời bạn tham gia thử thách này:
                    </p>
                    <div class="bg-gray-50 p-3 rounded-lg mb-6 border border-gray-200">
                        <p class="font-bold text-teal-700 text-lg">"{{ $challenge->title }}"</p>
                    </div>
                    <p class="text-sm text-gray-500">Bạn có muốn chấp nhận tham gia cùng họ không?</p>
                </div>

                <div class="bg-gray-50 px-6 py-4 flex justify-center space-x-3 border-t border-gray-100">
                    <button wire:click="rejectInvitation" 
                            wire:loading.attr="disabled"
                            class="px-5 py-2.5 bg-white text-gray-700 font-semibold rounded-lg border border-gray-300 hover:bg-gray-100 focus:ring-2 focus:ring-gray-200 transition shadow-sm">
                        Từ chối
                    </button>

                    <button wire:click="acceptInvitation" 
                            wire:loading.attr="disabled"
                            class="px-5 py-2.5 bg-teal-600 text-white font-semibold rounded-lg hover:bg-teal-700 focus:ring-4 focus:ring-teal-300 transition shadow-lg flex items-center">
                        <span wire:loading.remove wire:target="acceptInvitation">Chấp nhận ngay</span>
                        <span wire:loading wire:target="acceptInvitation">Đang xử lý...</span>
                    </button>
                </div>
            </div>
        </div>
    @endif
    @if($showDateModal)
        <div class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-xl shadow-2xl w-full max-w-md overflow-hidden">
                <div class="bg-teal-600 px-6 py-4 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-white">Đặt thời gian bắt đầu</h3>
                    <button wire:click="$set('showDateModal', false)" class="text-white text-2xl">&times;</button>
                </div>
                <div class="p-6">
                    <p class="text-sm text-gray-600 mb-4">
                        @if($challenge->time_mode == 'fixed')
                            ⚠️ <strong>Chế độ Cố định:</strong> Sau thời gian này, thử thách sẽ bị KHÓA. Không ai có thể tham gia, mời hoặc điểm danh được nữa.
                        @else
                            ℹ️ <strong>Chế độ Linh hoạt:</strong> Đây chỉ là mốc thời gian để đếm ngược sự kiện. Mọi người vẫn có thể tham gia sau đó.
                        @endif
                    </p>
                    
                    <label class="block text-sm font-medium text-gray-700 mb-1">Ngày giờ bắt đầu:</label>
                    <input type="datetime-local" wire:model="newStartDate" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-teal-500 focus:border-teal-500">
                    @error('newStartDate') <span class="text-red-500 text-xs block mt-1">{{ $message }}</span> @enderror

                    <div class="mt-6 flex justify-end space-x-3">
                        <button wire:click="$set('showDateModal', false)" class="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-lg">Hủy</button>
                        <button wire:click="setStartDate" class="px-4 py-2 bg-teal-600 text-white font-bold rounded-lg hover:bg-teal-700">Lưu thiết lập</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

</div>
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('countdown', (endTime) => ({
            days: '00', hours: '00', minutes: '00', seconds: '00',
            endTime: new Date(endTime).getTime(),
            timer: null,
            init() {
                this.updateTimer();
                this.timer = setInterval(() => this.updateTimer(), 1000);
            },
            updateTimer() {
                const now = new Date().getTime();
                const distance = this.endTime - now;

                if (distance < 0) {
                    clearInterval(this.timer);
                    this.days = this.hours = this.minutes = this.seconds = '00';
                    // Tùy chọn: Refresh trang khi hết giờ để server cập nhật trạng thái Lock
                    // window.location.reload(); 
                    return;
                }

                this.days = String(Math.floor(distance / (1000 * 60 * 60 * 24))).padStart(2, '0');
                this.hours = String(Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60))).padStart(2, '0');
                this.minutes = String(Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60))).padStart(2, '0');
                this.seconds = String(Math.floor((distance % (1000 * 60)) / 1000)).padStart(2, '0');
            }
        }));
    });
</script>