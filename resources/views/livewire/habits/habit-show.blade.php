<div class="container mx-auto p-6">
    @if (session('status'))
        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg relative" role="alert">
            <span class="block sm:inline">{{ session('status') }}</span>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:gap-8">
        {{-- Cột nội dung chính --}}
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                @if($habit->image)
                    <img src="{{ asset('storage/' . $habit->image) }}" alt="{{ $habit->title }}" class="w-full h-64 object-cover">
                @endif
                <div class="p-6">
                    <div class="flex justify-between items-start mb-4">
                        <div class="min-w-0 flex-1">
                            <h1 class="text-3xl font-bold text-gray-800 mb-2 break-words">{{ $habit->title }}</h1>
                            <div class="text-gray-600">{!!$habit->description!!}</div>
                        </div>

                        <div class="flex-shrink-0 ml-4 flex items-center space-x-2">
                            @if($isCreator)
                                <a href="{{ route('habits.edit', $habit) }}" wire:navigate class="bg-teal-600 text-white px-4 py-2 rounded-lg hover:bg-teal-700">Sửa</a>
                                <button wire:click="deleteHabit" wire:confirm="Bạn có chắc chắn muốn xóa thói quen này? Hành động này không thể hoàn tác." class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700">Xóa</button>
                            @endif

                            @if ($habit->type === 'group')
                                @if ($participationStatus === 'participant')
                                    @if (!$isCreator)
                                        <button wire:click="leaveHabit" wire:confirm="Bạn có chắc chắn muốn rời khỏi thói quen này?" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700">
                                            Rời khỏi
                                        </button>
                                    @endif
                                @elseif ($participationStatus === 'pending_request')
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

                    @if ($habit->type === 'group')
                        {{-- Các section của nhóm (lời mời, thành viên,...) --}}
                        {{-- Pending Requests Section (for creator only) --}}
                        @if($this->pendingInvitations->isNotEmpty())
                            <div class="mt-6 border-t pt-6">
                                <h2 class="text-2xl font-semibold text-gray-800 mb-4">Yêu cầu & Lời mời đang chờ</h2>
                                <ul class="space-y-3">
                                    @foreach ($this->pendingInvitations as $invitation)
                                        <li class="flex items-center justify-between p-3 bg-yellow-50 rounded-lg shadow-sm">
                                            <div class="flex items-center">
                                                <img src="{{ $invitation->invitee->avatar ?? 'https://ui-avatars.com/api/?name='.urlencode($invitation->invitee->name) }}" alt="{{ $invitation->invitee->name }}" class="w-10 h-10 rounded-full mr-3">
                                                <div>
                                                    <span class="font-medium text-gray-700">{{ $invitation->invitee->name }}</span>
                                                    @if($invitation->inviter_id !== $invitation->invitee_id)
                                                        <p class="text-xs text-gray-500">Được mời bởi {{ $invitation->inviter->name }}</p>
                                                    @else
                                                        <p class="text-xs text-gray-500">Yêu cầu tham gia</p>
                                                    @endif
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
                        @if($isParticipant && $habit->allow_member_invite)
                            <div class="mt-6 border-t pt-6">
                                <h2 class="text-2xl font-semibold text-gray-800 mb-4">Mời thành viên mới</h2>
                                <form wire:submit.prevent="inviteMember" class="flex items-center space-x-2">
                                    <input wire:model="inviteEmail" type="email" placeholder="Nhập email để mời..." class="w-full border rounded-md px-3 py-2 focus:ring-teal-500 focus:border-teal-500">
                                    <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 flex-shrink-0">Mời</button>
                                </form>
                                @error('inviteEmail') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                                @if(session('invite_error'))
                                    <div class="text-red-500 text-sm mt-1">{{ session('invite_error') }}</div>
                                @endif
                            </div>
                        @endif

                        {{-- Members Section --}}
                        @if ($isParticipant)
                            <div class="mt-6 border-t pt-6">
                                <h2 class="text-2xl font-semibold text-gray-800 mb-4">Thành viên</h2>
                                <ul class="space-y-3">
                                    @forelse ($this->activeParticipants as $participant)
                                        <li class="flex items-center justify-between p-3 bg-gray-50 rounded-lg shadow-sm">
                                            <div class="flex items-center">
                                                <img src="{{ $participant->user->avatar ?? 'https://ui-avatars.com/api/?name='.urlencode($participant->user->name) }}" alt="{{ $participant->user->name }}" class="w-10 h-10 rounded-full mr-3">
                                                <div>
                                                    <span class="font-medium text-gray-700">{{ $participant->user->name }}</span>
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

        {{-- Cột Lịch trình --}}
        <div class="lg:col-span-1 mt-6 lg:mt-0">
            <div class="bg-white rounded-lg shadow-md p-6 sticky top-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Bảng theo dõi</h2>

                @if($isParticipant)
                    {{-- Hiển thị Streak --}}
                    <div class="mb-6 text-center border-b pb-6">
                        <p class="text-sm font-medium text-gray-500">Streak hiện tại</p>
                        <div class="flex items-center justify-center text-orange-500 mt-1">
                            <svg class="w-8 h-8 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M9.26,2.19C8.94,1.5,8.08,1.23,7.4,1.55S6.18,2.47,6.5,3.15l0.82,1.72 c-1.29,0.59-2.39,1.5-3.13,2.63c-1.48,2.24-1.58,5.13-0.28,7.51c1.3,2.38,3.95,3.63,6.58,3.13c2.63-0.5,4.68-2.55,5.18-5.18 c0.5-2.63-0.75-5.28-3.13-6.58c-0.45-0.24-0.93-0.43-1.43-0.57L9.26,2.19z M10,15c-2.21,0-4-1.79-4-4s1.79-4,4-4s4,1.79,4,4 S12.21,15,10,15z" clip-rule="evenodd" />
                            </svg>
                            <span class="text-4xl font-bold text-orange-600">{{ $currentUserParticipant->streak ?? 0 }}</span>
                        </div>
                    </div>

                    {{-- Calendar Header --}}
                    <div class="flex items-center justify-between mb-4">
                        <button wire:click="previousMonth" aria-label="Tháng trước" class="p-2 rounded-full hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-teal-500">
                            <svg class="w-5 h-5 text-gray-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                        </button>
                        <span class="font-semibold text-lg capitalize">{{ $this->monthName }}</span>
                        <button wire:click="nextMonth" aria-label="Tháng sau" class="p-2 rounded-full hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-teal-500">
                            <svg class="w-5 h-5 text-gray-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                        </button>
                    </div>

                    {{-- Calendar Grid --}}
                    <div class="grid grid-cols-7 gap-1 text-center text-sm">
                        {{-- Day names --}}
                        @foreach(['CN', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7'] as $dayName)
                            <div class="font-semibold text-gray-500 py-2">{{ $dayName }}</div>
                        @endforeach

                        @foreach ($this->calendarGrid as $day)
                            @if ($day)
                                @php
                                $isDone = $day['status'] === 'done';
                                // Một ngày có thể tương tác chỉ khi đó là ngày hôm nay và người dùng là thành viên.
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
                                } else { // Not done
                                    if ($isInteractive) {
                                        $colorClasses = 'bg-gray-100 hover:bg-teal-200';
                                    } else {
                                        $colorClasses = 'text-gray-400';
                                    }
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
                                <div></div> {{-- Empty cell --}}
                            @endif
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <p class="text-gray-600">Bạn cần tham gia thói quen này để xem và ghi nhận tiến độ.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

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
</div>