<div wire:poll.30s>
    <div class="w-full bg-white shadow-lg rounded-xl border border-gray-200">
        {{-- Header --}}
        <div class="p-4 border-b border-gray-200">
            <h2 class="text-lg font-bold text-gray-800 text-center">Bảng Xếp Hạng</h2>
        </div>

        {{-- Filters --}}
        <div class="p-2">
            <div class="flex items-center bg-gray-100  p-1 rounded-lg">
                <button wire:click="setPeriod('all')" class="{{ $period === 'all' ? 'bg-teal-600 text-white shadow' : 'text-gray-500  hover:bg-teal-100' }} px-3 py-1 rounded-md font-semibold text-xs transition-all duration-200 flex-1">Tất cả</button>
                <button wire:click="setPeriod('week')" class="{{ $period === 'week' ? 'bg-teal-600 text-white shadow' : 'text-gray-500  hover:bg-teal-100' }} px-3 py-1 rounded-md font-semibold text-xs transition-all duration-200 flex-1 ml-1">Tuần</button>
                <button wire:click="setPeriod('month')" class="{{ $period === 'month' ? 'bg-teal-600 text-white shadow' : 'text-gray-500  hover:bg-teal-100' }} px-3 py-1 rounded-md font-semibold text-xs transition-all duration-200 flex-1 ml-1">Tháng</button>
                <button wire:click="setPeriod('year')" class="{{ $period === 'year' ? 'bg-teal-600 text-white shadow' : 'text-gray-500  hover:bg-teal-100' }} px-3 py-1 rounded-md font-semibold text-xs transition-all duration-200 flex-1 ml-1">Năm</button>
            </div>
        </div>

        {{-- Leaderboard List --}}
        <div class="overflow-hidden relative min-h-[60dvh]">
            {{-- Loading Overlay --}}
            <div wire:loading.flex wire:target="setPeriod" class="absolute inset-0 bg-white bg-opacity-75 items-center justify-center z-10 transition-opacity">
                <div role="status">
                    <svg aria-hidden="true" class="w-8 h-8 text-gray-200 animate-spin dark:text-gray-600 fill-teal-600" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/><path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/></svg>
                    <span class="sr-only">Đang tải...</span>
                </div>
            </div>

            <ul class="divide-y divide-gray-200">
                @forelse ($users as $index => $user)
                    @php
                        $rank = $index + 1;
                        $isCurrentUser = auth()->id() == $user->id;

                        $medalColor = '';
                        if ($rank == 1) $medalColor = 'text-yellow-400';
                        if ($rank == 2) $medalColor = 'text-gray-400';
                        if ($rank == 3) $medalColor = 'text-orange-400';

                        $bgColor = $isCurrentUser ? 'bg-indigo-50' : '';
                    @endphp
                    <li class="{{ $bgColor }} transition-colors duration-300">
                        <div class="px-4 py-3 flex items-center">
                            {{-- Rank --}}
                            <div class="w-10 text-center flex-shrink-0">
                                @if($rank <= 3)
                                    <svg class="w-7 h-7 mx-auto {{ $medalColor }}" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" d="M10 2a.75.75 0 01.75.75v3.522l1.547-.86a.75.75 0 01.686.05l3.25 1.8a.75.75 0 010 1.328l-3.25 1.8a.75.75 0 01-.686.05l-1.547-.86V14.5a.75.75 0 01-1.5 0V9.478l-1.547.86a.75.75 0 01-.686-.05l-3.25-1.8a.75.75 0 010-1.328l3.25-1.8a.75.75 0 01.686-.05l1.547.86V2.75A.75.75 0 0110 2zM3.5 13.25a.75.75 0 01.75-.75h11.5a.75.75 0 010 1.5H4.25a.75.75 0 01-.75-.75zM3 16.25a.75.75 0 01.75-.75h12.5a.75.75 0 010 1.5H3.75a.75.75 0 01-.75-.75z" clip-rule="evenodd" />
                                    </svg>
                                @else
                                    <span class="text-base font-bold text-gray-500">#{{ $rank }}</span>
                                @endif
                            </div>

                            {{-- User Info --}}
                            <div class="min-w-0 flex-1 flex items-center ml-3">
                                <div class="flex-shrink-0">
                                    <img 
                                        class="h-10 w-10 rounded-full object-cover"
                                        src="{{ $user->avatar 
                                                ? asset('storage/users/' . $user->avatar) 
                                                : 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&color=0d9488&background=94ffd8' }}"
                                        alt="{{ $user->name }}"
                                    >
                                </div>
                                <div class="min-w-0 flex-1 ml-3">
                                    <p class="text-sm font-semibold text-gray-800truncate"><a href="{{ route('profile.show', ['id' => $user->id]) }}" wire:navigate>{{ $user->name }}</a></p>
                                    @if($isCurrentUser)
                                        <span class="text-xs text-primary font-medium">Bạn</span>
                                    @endif
                                </div>
                            </div>

                            {{-- XP --}}
                            <div class="text-right flex-shrink-0 ml-2">
                                <span class="font-bold text-base text-primary">{{ number_format($user->total_xp ?? 0) }}</span>
                                <span class="text-xs text-gray-500">XP</span>
                            </div>
                        </div>
                    </li>
                @empty
                    <li class="text-center py-8 px-4 text-sm text-gray-500">
                        Chưa có ai trên bảng xếp hạng. Hãy là người đầu tiên!
                    </li>
                @endforelse
            </ul>
        </div>

        {{-- Current User (if not in top 20) --}}
        @if ($currentUserData)
            <div class="border-t-2 border-dashed border-gray-300 mt-2">
                <div class="px-4 py-3 flex items-center bg-indigo-50 rounded-b-xl">
                    {{-- Rank --}}
                    <div class="w-10 text-center flex-shrink-0">
                        <span class="text-base font-bold text-gray-500">#{{ $currentUserData->rank }}</span>
                    </div>

                    {{-- User Info --}}
                    <div class="min-w-0 flex-1 flex items-center ml-3">
                        <div class="flex-shrink-0">
                            <img class="h-10 w-10 rounded-full object-cover ring-2 ring-indigo-500" src="{{ $currentUserData->avatar ? asset('storage/' . $currentUserData->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&color=0d9488&background=94ffd8' }}" alt="{{ $currentUserData->name }}">
                        </div>
                        <div class="min-w-0 flex-1 ml-3">
                            <p class="text-sm font-semibold text-gray-800 truncate">{{ $currentUserData->name }}</p>
                            <span class="text-xs text-indigo-600 font-medium">Vị trí của bạn</span>
                        </div>
                    </div>

                    {{-- XP --}}
                    <div class="text-right flex-shrink-0 ml-2">
                        <span class="font-bold text-base text-indigo-600">{{ number_format($currentUserData->total_xp ?? 0) }}</span>
                        <span class="text-xs text-gray-500">XP</span>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
