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
                        @if ($isParticipant)
                            <button wire:click="leaveHabit" wire:confirm="Bạn có chắc chắn muốn rời khỏi thói quen này?" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 disabled:opacity-50 {{ $isCreator ? 'cursor-not-allowed' : '' }}" {{ $isCreator ? 'disabled' : '' }} title="{{ $isCreator ? 'Người tạo không thể rời khỏi thói quen' : '' }}">
                                Rời khỏi
                            </button>
                        @else
                            <button wire:click="joinHabit" class="bg-teal-600 text-white px-4 py-2 rounded-lg hover:bg-teal-700">Tham gia</button>
                        @endif
                    @endif
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-600 mb-4">
                <p><strong>Ngày bắt đầu:</strong> {{ $habit->start_date }}</p>
                <p><strong>Ngày kết thúc:</strong> {{ $habit->end_date }}</p>
                @if($habit->participants->isNotEmpty() && !is_null($habit->participants->first()->streak))
                    <p><strong>Streak:</strong> {{ $habit->participants->first()->streak }}</p>
                @endif
            </div>  

            @if ($habit->type === 'group')
                <div class="mt-6 border-t pt-6">
                    <h2 class="text-2xl font-semibold text-gray-800 mb-4">Thành viên</h2>
                    <ul class="space-y-3">
                        @forelse ($habit->participants as $participant)
                            <li class="flex items-center justify-between p-3 bg-gray-50 rounded-lg shadow-sm">
                                <div class="flex items-center">
                                    <img src="{{ $participant->user->avatar ?? 'https://ui-avatars.com/api/?name='.urlencode($participant->user->name) }}" alt="{{ $participant->user->name }}" class="w-10 h-10 rounded-full mr-4">
                                    <span class="font-medium text-gray-700">{{ $participant->user->name }}</span>
                                </div>
                                <span class="text-sm font-semibold px-3 py-1 rounded-full {{ $participant->role === 'creator' ? 'bg-indigo-100 text-indigo-800' : 'bg-green-100 text-green-800' }}">
                                    {{ $participant->role === 'creator' ? 'Người tạo' : 'Thành viên' }}
                                </span>
                            </li>
                        @empty
                            <p class="text-gray-500">Chưa có thành viên nào tham gia.</p>
                        @endforelse
                    </ul>
                </div>
            @endif
        </div>
    </div>
</div>