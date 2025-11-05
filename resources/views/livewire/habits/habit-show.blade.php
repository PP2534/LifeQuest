<div class="container mx-auto p-6">
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        @if($habit->image)
            <img src="{{ asset('storage/' . $habit->image) }}" alt="{{ $habit->title }}" class="w-full h-64 object-cover">
        @endif
        <div class="p-6">
            @if (session('status'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('status') }}</span>
                </div>
            @endif

            <div class="flex justify-between items-start mb-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800 mb-2">{{ $habit->title }}</h1>
                    <p class="text-gray-600">{{ $habit->description }}</p>
                </div>

                <div class="flex-shrink-0 ml-4 flex items-center space-x-2">
                    @if($isCreator)
                        <a href="{{ route('habits.edit', $habit) }}" wire:navigate class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Sửa</a>
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

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-600 mb-4">
                <p><strong>Ngày bắt đầu:</strong> {{ $habit->start_date }}</p>
                <p><strong>Ngày kết thúc:</strong> {{ $habit->end_date }}</p>
                @if($isParticipant)
                    <p><strong>Streak của bạn:</strong> {{ $currentUserParticipant->streak }}</p>
                @endif
            </div>  

            @if ($habit->type === 'group')
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