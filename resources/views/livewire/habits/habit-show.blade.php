<div class="container mx-auto p-6">
    @if (session('status'))
        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg relative" role="alert">
            <span class="block sm:inline">{{ session('status') }}</span>
        </div>
    @endif

    <div>
        {{-- Cột nội dung chính --}}
        <div>
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                @if($habit->image)
                    <img src="{{ asset('storage/' . $habit->image) }}" alt="{{ $habit->title }}" class="w-full h-64 object-cover">
                @endif
                <div class="p-6">
                    <div class="flex justify-between items-start mb-4">
                        <div class="min-w-0 flex-1">
                            <h1 class="text-3xl font-bold text-gray-800 mb-2 break-words">{{ $habit->title }}</h1>
                            <p class="text-gray-600">{!! $habit->description !!}</p>
                        </div>

                        <div class="flex-shrink-0 ml-4 flex items-center space-x-2">
                            @if($isCreator)
                                <a href="{{ route('habits.edit', $habit) }}" wire:navigate class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Sửa</a>
                            @endif

                            @if ($habit->type === 'group' && !$isParticipant && !$isCreator)
                                @if ($participationStatus === 'pending_request')
                                    <button wire:click="cancelRequest" wire:confirm="Bạn có chắc chắn muốn hủy yêu cầu tham gia?" class="bg-yellow-500 text-white px-4 py-2 rounded-lg hover:bg-yellow-600">
                                        Hủy yêu cầu
                                    </button>
                                @elseif ($participationStatus === 'invited')
                                    <div class="flex items-center space-x-2">
                                        <button wire:click="acceptInvitation" class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600">Chấp nhận</button>
                                        <button wire:click="rejectInvitation" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600">Từ chối</button>
                                    </div>
                                @else
                                    @if (Auth::check() && !$isCreator)
                                        @if ($habit->allow_request_join)
                                            <button wire:click="requestToJoin" class="bg-teal-600 text-white px-4 py-2 rounded-lg hover:bg-teal-700">
                                                Yêu cầu tham gia
                                            </button>
                                        @endif
                                    @endif
                                @endif
                            @endif
                        </div>
                    </div>

                    <section class="mt-8" aria-label="Habit progress tracker">
                        <div class="bg-gradient-to-br from-teal-50 to-white border border-teal-100 rounded-2xl shadow-inner p-6">
                            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
                                <div>
                                    <p class="text-xs font-semibold text-teal-500 uppercase tracking-[0.2em]">Hành trình phát triển</p>
                                    <h2 class="text-2xl font-bold text-gray-900 mt-1">Nhật ký thói quen của bạn</h2>
                                    <p class="text-sm text-gray-600">Theo dõi chuỗi ngày và chủ động hoàn thành từng bước nhỏ.</p>
                                </div>
                                @if($isParticipant && $currentUserParticipant)
                                    <div class="text-center md:text-right">
                                        <p class="text-sm font-medium text-gray-500">Chuỗi hiện tại</p>
                                        <div class="flex items-center md:justify-end text-orange-500 mt-1">
                                            <svg class="w-8 h-8 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M9.26,2.19C8.94,1.5,8.08,1.23,7.4,1.55S6.18,2.47,6.5,3.15l0.82,1.72 c-1.29,0.59-2.39,1.5-3.13,2.63c-1.48,2.24-1.58,5.13-0.28,7.51c1.3,2.38,3.95,3.63,6.58,3.13c2.63-0.5,4.68-2.55,5.18-5.18 c0.5-2.63-0.75-5.28-3.13-6.58c-0.45-0.24-0.93-0.43-1.43-0.57L9.26,2.19z M10,15c-2.21,0-4-1.79-4-4s1.79-4,4-4s4,1.79,4,4 S12.21,15,10,15z" clip-rule="evenodd" />
                                            </svg>
                                            <span class="text-4xl font-extrabold text-orange-600">{{ $currentUserParticipant->streak ?? 0 }}</span>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            @if($isParticipant)
                                <div class="space-y-6">
                                    <div class="flex items-center justify-between">
                                        <button wire:click="previousMonth" aria-label="Tháng trước" class="p-2 rounded-full hover:bg-white focus:outline-none focus:ring-2 focus:ring-teal-500"><svg class="w-5 h-5 text-gray-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg></button>
                                        <span class="font-semibold text-lg capitalize text-gray-800">{{ $this->monthName }}</span>
                                        <button wire:click="nextMonth" aria-label="Tháng sau" class="p-2 rounded-full hover:bg-white focus:outline-none focus:ring-2 focus:ring-teal-500"><svg class="w-5 h-5 text-gray-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg></button>
                                    </div>

                                    <div class="grid grid-cols-7 gap-1 text-center text-sm">
                                        @foreach(['CN', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7'] as $dayName)
                                            <div class="font-semibold text-gray-500 py-2">{{ $dayName }}</div>
                                        @endforeach

                                        @foreach ($this->calendarGrid as $day)
                                            @if ($day)
                                                @php
                                                    $isDone = $day['status'] === 'done';
                                                    $isInteractive = $day['is_today'] && $isParticipant;
                                                    $baseClasses = 'w-9 h-9 flex items-center justify-center rounded-full transition-colors duration-200';
                                                    $ringClasses = $day['is_today'] ? 'ring-2 ring-teal-500' : '';
                                                    $cursorClasses = $isInteractive ? 'cursor-pointer' : 'cursor-default';
                                                    $colorClasses = '';

                                                    if ($isDone) {
                                                        $colorClasses = 'bg-green-500 text-white';
                                                        if ($isInteractive) {
                                                            $colorClasses .= ' hover:bg-green-600';
                                                        }
                                                    } else {
                                                        $colorClasses = $isInteractive ? 'bg-white hover:bg-teal-100 text-gray-700' : 'text-gray-400';
                                                    }
                                                @endphp
                                                <div
                                                    @if($isInteractive)
                                                        wire:click="toggleDayStatus('{{ $day['date'] }}')"
                                                        wire:loading.attr="disabled"
                                                        wire:loading.class="opacity-50 !cursor-wait"
                                                    @endif
                                                    class="{{ $baseClasses }} {{ $ringClasses }} {{ $cursorClasses }} {{ $colorClasses }}"
                                                    title="{{ \Carbon\Carbon::parse($day['date'])->isoFormat('LL') }}"
                                                >
                                                    @if ($isDone)
                                                        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                                                    @else
                                                        {{ $day['day'] }}
                                                    @endif
                                                </div>
                                            @else
                                                <div></div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            @else
                                <div class="text-center py-8">
                                    <p class="text-gray-600">Hãy tham gia thói quen để mở khóa lịch trình và bắt đầu ghi nhận tiến độ của bạn.</p>
                                </div>
                            @endif
                        </div>
                    </section>

                    @if ($habit->type === 'group')
                        {{-- Các section của nhóm (lời mời, thành viên,...) --}}
                        {{-- Pending Requests Section (for creator only) --}}
                        @if($this->pendingJoinRequests->isNotEmpty())
                            <div class="mt-6 border-t pt-6">
                                <h2 class="text-2xl font-semibold text-gray-800 mb-4">Yêu cầu tham gia đang chờ</h2>
                                <ul class="space-y-3">
                                    @foreach ($this->pendingJoinRequests as $invitation)
                                        <li class="flex items-center justify-between p-3 bg-yellow-50 rounded-lg shadow-sm">
                                            <div class="flex items-center flex-grow">
                                                <a href="{{ route('profile.show', ['id' => $invitation->invitee->id]) }}" wire:navigate>
                                                    <img src="{{ $invitation->invitee->avatar ? asset('storage/users/' . $invitation->invitee->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($invitation->invitee->name).'&color=0d9488&background=94ffd8' }}" alt="{{ $invitation->invitee->name }}" class="w-10 h-10 rounded-full mr-3">
                                                </a>
                                                <div>
                                                    <a href="{{ route('profile.show', ['id' => $invitation->invitee->id]) }}" wire:navigate class="font-medium text-gray-700 hover:underline">
                                                        {{ $invitation->invitee->name }}
                                                    </a>
                                                    <p class="text-xs text-gray-500">Đã gửi yêu cầu tham gia</p>
                                                </div>
                                            </div>
                                            <div class="flex items-center space-x-2">
                                                <button wire:click="approveRequest({{ $invitation->id }})" class="bg-green-500 text-white px-3 py-1 rounded-md hover:bg-green-600 text-sm">Duyệt</button>
                                                <button wire:click="rejectRequest({{ $invitation->id }})" class="bg-red-500 text-white px-3 py-1 rounded-md hover:bg-red-600 text-sm">Từ chối</button>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        {{-- Invite Member Section --}}
                        {{-- Hiển thị nếu người dùng là thành viên và thói quen cho phép mời --}}
                        @if(($isParticipant || $isCreator) && $habit->allow_member_invite)
                            <div class="mt-6 border-t pt-6">
                                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                                    <div>
                                        <h2 class="text-2xl font-semibold text-gray-800">Mời bạn bè</h2>
                                        <p class="text-gray-600 text-sm">Mời những người bạn đang theo dõi để xây dựng thói quen cùng nhau.</p>
                                    </div>
                                    <button wire:click="openInviteModal" class="inline-flex items-center justify-center bg-teal-600 hover:bg-teal-700 text-white font-semibold px-5 py-2.5 rounded-lg shadow transition">
                                        + Mời bạn bè
                                    </button>
                                </div>
                            </div>
                        @endif

                        {{-- Members Section --}}
                        @if ($isParticipant)
                            <div class="mt-6 border-t pt-6">
                                <h2 class="text-2xl font-semibold text-gray-800 mb-4">Thành viên</h2>
                                <ul class="space-y-3">
                                    @forelse ($this->activeParticipants as $participant)
                                        <li class="flex items-center justify-between p-3 bg-gray-50 rounded-lg shadow-sm">
                                            <div class="flex items-center flex-grow">
                                                <a href="{{ route('profile.show', ['id' => $participant->user->id]) }}" wire:navigate>
                                                    <img src="{{ $participant->user->avatar ? asset('storage/users/' . $participant->user->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($participant->user->name).'&color=0d9488&background=94ffd8' }}" alt="{{ $participant->user->name }}" class="w-10 h-10 rounded-full mr-3">
                                                </a>
                                                <div>
                                                    <a href="{{ route('profile.show', ['id' => $participant->user->id]) }}" wire:navigate class="font-medium text-gray-700 hover:underline">
                                                        {{ $participant->user->name }}
                                                    </a>
                                                    <p class="text-xs text-gray-500">Streak: {{ $participant->streak }}</p>
                                                </div>
                                            </div>
                                            <div class="flex items-center space-x-2">
                                                <span class="text-sm font-semibold px-3 py-1 rounded-full {{ $participant->role === 'creator' ? 'bg-indigo-100 text-indigo-800' : 'bg-green-100 text-green-800' }}">
                                                    {{ $participant->role === 'creator' ? 'Người tạo' : 'Thành viên' }}
                                                </span>
                                                {{-- Người tạo có thể xóa các thành viên khác --}}
                                                @if($isCreator && $participant->role !== 'creator')
                                                    <button wire:click="kickMember({{ $participant->id }})" wire:confirm="Bạn có chắc chắn muốn xóa thành viên này khỏi nhóm?" class="bg-red-500 text-white px-3 py-1 rounded-md hover:bg-red-600 text-sm">
                                                        Xóa
                                                    </button>
                                                @endif
                                            </div>
                                        </li>
                                    @empty
                                        <p class="text-gray-500">Chưa có thành viên nào tham gia.</p>
                                    @endforelse
                                </ul>
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>

    </div>

    <div class="mt-10 border-t pt-6 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        @if($isCreator)
            <div>
                <p class="text-sm text-gray-500">Hành động quản lý</p>
                <p class="text-base font-semibold text-gray-800">Bạn là người tạo thói quen này.</p>
            </div>
            <button wire:click="confirmDelete"
                    class="inline-flex items-center justify-center px-5 py-3 rounded-lg bg-red-600 text-white font-semibold shadow hover:bg-red-700 transition">
                Xóa thói quen
            </button>
        @elseif($isParticipant)
            <div>
                <p class="text-sm text-gray-500">Bạn đang tham gia thói quen này</p>
                <p class="text-base font-semibold text-gray-800">Có thể rời khỏi bất kỳ lúc nào.</p>
            </div>
            <button wire:click="leaveHabit"
                    wire:confirm="Bạn có chắc chắn muốn rời khỏi thói quen này?"
                    class="inline-flex items-center justify-center px-5 py-3 rounded-lg bg-white border border-red-200 text-red-600 font-semibold hover:bg-red-50 transition">
                Rời khỏi thói quen
            </button>
        @endif
    </div>

    @if($showInviteModal)
        <div class="fixed inset-0 bg-gray-900 bg-opacity-50 overflow-y-auto h-full w-full z-50 flex items-center justify-center">
            <div class="bg-white rounded-xl shadow-2xl max-w-md w-full mx-4 overflow-hidden transform transition-all">
                <div class="bg-teal-600 px-6 py-4 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-white">Mời bạn bè tham gia</h3>
                    <button wire:click="$set('showInviteModal', false)" class="text-white hover:text-gray-200 text-2xl">&times;</button>
                </div>

                <div class="p-6 max-h-96 overflow-y-auto">
                    @if($this->followings->count() > 0)
                        <ul class="space-y-4">
                            @foreach($this->followings as $friend)
                                <li class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                                    <div class="flex items-center">
                                        <a href="{{ route('profile.show', ['id' => $friend->id]) }}" wire:navigate>
                                            <img class="h-10 w-10 rounded-full object-cover" 
                                                 src="{{ $friend->avatar ? asset('storage/users/' . $friend->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($friend->name).'&color=0d9488&background=94ffd8' }}"
                                                 alt="{{ $friend->name }}">
                                        </a>
                                        <div class="ml-3">
                                            <p class="font-semibold text-gray-800 text-sm">{{ $friend->name }}</p>
                                            <p class="text-xs text-gray-500">{{ $friend->email }}</p>
                                        </div>
                                    </div>

                                    <div class="text-right">
                                        @if($friend->invite_status === 'active')
                                            <span class="text-xs font-semibold text-green-700 bg-green-100 px-3 py-1 rounded-full border border-green-200">Đã tham gia</span>
                                        @elseif($friend->invite_status === 'pending')
                                            <span class="text-xs font-semibold text-orange-700 bg-orange-100 px-3 py-1 rounded-full border border-orange-200">Đã mời</span>
                                        @elseif($friend->invite_status === 'rejected')
                                            <div class="flex flex-col items-end gap-1">
                                                <span class="text-[11px] text-red-500 font-medium">Đã từ chối</span>
                                                <button wire:click="inviteUser({{ $friend->id }})" 
                                                        wire:loading.attr="disabled"
                                                        class="text-xs bg-teal-100 text-teal-700 hover:bg-teal-200 px-3 py-1 rounded-full border border-teal-200">
                                                    Mời lại
                                                </button>
                                            </div>
                                        @else
                                            <button wire:click="inviteUser({{ $friend->id }})"
                                                    wire:loading.attr="disabled"
                                                    class="text-xs bg-teal-600 hover:bg-teal-700 text-white px-4 py-1.5 rounded-lg shadow-sm font-medium">
                                                + Mời
                                            </button>
                                        @endif
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="text-center py-8">
                            <p class="text-gray-500">Bạn chưa theo dõi ai hoặc tất cả bạn bè đã tham gia.</p>
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

    {{-- Modal Tải lên Bằng chứng --}}
    @if ($showProofModal)
    <div class="fixed inset-0 bg-gray-900 bg-opacity-60 z-50 flex justify-center items-center" wire:click.self="closeProofModal">
        <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-md" wire:key="proof-modal">
            <form wire:submit.prevent="saveLogWithProof">
                <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">
                    Minh chứng hoàn thành
                </h3>
                <p class="text-sm text-gray-500 mb-4">
                    Tải lên hình ảnh để chứng minh bạn đã hoàn thành thói quen cho ngày
                    <strong>{{ \Carbon\Carbon::parse($selectedDate)->isoFormat('LL') }}</strong>.
                </p>

                <div>
                    <label for="proofImage" class="block text-sm font-medium text-gray-700">Ảnh minh chứng</label>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                        <div class="space-y-1 text-center">
                            @if ($proofImage)
                                <img src="{{ $proofImage->temporaryUrl() }}" class="mx-auto h-24 w-auto rounded">
                            @else
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            @endif
                            <div class="flex text-sm text-gray-600 justify-center">
                                <label for="proofImage" class="relative cursor-pointer bg-white rounded-md font-medium text-teal-600 hover:text-teal-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-teal-500">
                                    <span>Tải lên một file</span>
                                    <input wire:model="proofImage" id="proofImage" name="proofImage" type="file" accept="image/*" class="sr-only">
                                </label>
                            </div>
                            <p class="text-xs text-gray-500">PNG, JPG, GIF tối đa 2MB</p>
                        </div>
                    </div>
                    @error('proofImage') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                <div class="mt-6 flex justify-end space-x-3">
                    <button type="button" wire:click="closeProofModal" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Hủy</button>
                    <button type="submit" class="inline-flex items-center justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-teal-600 hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500">
                        <div wire:loading wire:target="saveLogWithProof" class="animate-spin rounded-full h-5 w-5 border-b-2 border-white mr-3"></div>
                        Lưu
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif

    @if($showDeleteModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-60" wire:click.self="cancelDelete">
            <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full mx-4 p-6">
                <div class="flex items-start gap-3">
                    <div class="flex-shrink-0 w-12 h-12 rounded-full bg-red-100 text-red-600 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Xóa thói quen?</h3>
                        <p class="mt-1 text-sm text-gray-600">Hành động này không thể hoàn tác. Tất cả dữ liệu liên quan đến thói quen sẽ bị xóa.</p>
                    </div>
                </div>
                <div class="mt-6 flex justify-end gap-3">
                    <button wire:click="cancelDelete" class="px-4 py-2 rounded-lg border border-gray-200 text-gray-700 hover:bg-gray-50">Hủy</button>
                    <button wire:click="deleteHabit" class="px-4 py-2 rounded-lg bg-red-600 text-white hover:bg-red-700" wire:loading.attr="disabled">
                        <span wire:loading.remove wire:target="deleteHabit">Xóa ngay</span>
                        <span wire:loading wire:target="deleteHabit" class="flex items-center gap-2">
                            Đang xóa...
                        </span>
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>