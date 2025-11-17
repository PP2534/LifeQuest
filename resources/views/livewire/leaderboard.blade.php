<div wire:poll.30s>
    <div class="w-full bg-white shadow-lg rounded-xl border border-gray-200 min-h-[70dvh]">
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
        <div class="overflow-hidden">
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
                                    <img class="h-10 w-10 rounded-full object-cover" src="{{ $user->avatar ? asset('storage/' . $user->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&color=7F9CF5&background=EBF4FF' }}" alt="{{ $user->name }}">
                                </div>
                                <div class="min-w-0 flex-1 ml-3">
                                    <p class="text-sm font-semibold text-gray-800truncate">{{ $user->name }}</p>
                                    @if($isCurrentUser)
                                        <span class="text-xs text-indigo-600 font-medium">Bạn</span>
                                    @endif
                                </div>
                            </div>

                            {{-- XP --}}
                            <div class="text-right flex-shrink-0 ml-2">
                                <span class="font-bold text-base text-indigo-600">{{ number_format($user->total_xp ?? 0) }}</span>
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
                            <img class="h-10 w-10 rounded-full object-cover ring-2 ring-indigo-500" src="{{ $currentUserData->avatar ? asset('storage/' . $currentUserData->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode($currentUserData->name) . '&color=7F9CF5&background=EBF4FF' }}" alt="{{ $currentUserData->name }}">
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
