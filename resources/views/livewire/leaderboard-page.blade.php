<div class="mx-auto">
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <header class="p-6 border-b border-gray-200">
            <h1 class="text-2xl font-bold text-gray-800 text-center">Bảng Xếp Hạng Toàn Cầu</h1>
            <p class="text-center text-gray-500 mt-1">Vinh danh những thành viên xuất sắc nhất của LifeQuest!</p>
        </header>

        {{-- Filters --}}
        <div class="p-4 bg-gray-50 border-b border-gray-200">
            <div class="max-w-md mx-auto flex items-center bg-gray-200 p-1 rounded-lg">
                <button wire:click="setPeriod('all')" class="{{ $period === 'all' ? 'bg-teal-600 text-white shadow' : 'text-gray-600 hover:bg-teal-100' }} px-3 py-1.5 rounded-md font-semibold text-sm transition-all duration-200 flex-1">Tất cả</button>
                <button wire:click="setPeriod('week')" class="{{ $period === 'week' ? 'bg-teal-600 text-white shadow' : 'text-gray-600 hover:bg-teal-100' }} px-3 py-1.5 rounded-md font-semibold text-sm transition-all duration-200 flex-1 ml-1">Tuần</button>
                <button wire:click="setPeriod('month')" class="{{ $period === 'month' ? 'bg-teal-600 text-white shadow' : 'text-gray-600 hover:bg-teal-100' }} px-3 py-1.5 rounded-md font-semibold text-sm transition-all duration-200 flex-1 ml-1">Tháng</button>
                <button wire:click="setPeriod('year')" class="{{ $period === 'year' ? 'bg-teal-600 text-white shadow' : 'text-gray-600 hover:bg-teal-100' }} px-3 py-1.5 rounded-md font-semibold text-sm transition-all duration-200 flex-1 ml-1">Năm</button>
            </div>
        </div>

        {{-- Leaderboard List --}}
        <div class="overflow-x-auto relative">
            {{-- Loading Overlay --}}
            <div wire:loading.flex wire:target="setPeriod, nextPage, previousPage" class="absolute inset-0 bg-white bg-opacity-75 flex items-center justify-center z-10 transition-opacity">
                <div role="status">
                    <svg aria-hidden="true" class="w-10 h-10 text-gray-200 animate-spin fill-teal-600" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/><path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/></svg>
                    <span class="sr-only">Đang tải...</span>
                </div>
            </div>

            <ul class="divide-y divide-gray-200">
                @forelse ($users as $index => $user)
                    @php
                        $rank = $users->firstItem() + $index;
                        $isCurrentUser = auth()->id() == $user->id;

                        $medalColor = '';
                        if ($rank == 1) $medalColor = 'text-yellow-400';
                        if ($rank == 2) $medalColor = 'text-gray-400';
                        if ($rank == 3) $medalColor = 'text-orange-400';

                        $bgColor = $isCurrentUser ? 'bg-teal-50' : 'hover:bg-gray-50';
                    @endphp
                    <li class="{{ $bgColor }} transition-colors duration-300">
                        <a href="{{ route('profile.show', ['id' => $user->id]) }}" wire:navigate class="px-4 sm:px-6 py-4 flex items-center">
                            {{-- Rank --}}
                            <div class="w-12 text-center flex-shrink-0">
                                @if($rank <= 3)
                                    <x-lucide-award class="w-8 h-8 mx-auto {{ $medalColor }}" />
                                @else
                                    <span class="text-base font-bold text-gray-500">#{{ $rank }}</span>
                                @endif
                            </div>

                            {{-- User Info --}}
                            <div class="min-w-0 flex-1 flex items-center ml-4">
                                <div class="flex-shrink-0">
                                    <img class="h-12 w-12 rounded-full object-cover"
                                         src="{{ $user->avatar ? asset('storage/users/' . $user->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&color=0d9488&background=94ffd8' }}"
                                         alt="{{ $user->name }}">
                                </div>
                                <div class="min-w-0 flex-1 ml-4">
                                    <p class="text-md font-semibold text-gray-800 truncate">{{ $user->name }}</p>
                                    @if($isCurrentUser)
                                        <span class="text-xs text-teal-600 font-medium px-2 py-0.5 bg-teal-100 rounded-full">Bạn</span>
                                    @endif
                                </div>
                            </div>

                            {{-- XP --}}
                            <div class="text-right flex-shrink-0 ml-4">
                                <span class="font-bold text-lg text-teal-600">{{ number_format($user->total_xp ?? 0) }}</span>
                                <span class="text-sm text-gray-500">XP</span>
                            </div>
                        </a>
                    </li>
                @empty
                    <li class="text-center py-12 px-4 text-gray-500">
                        <p class="font-semibold">Chưa có dữ liệu cho mục này.</p>
                        <p class="text-sm mt-1">Hãy là người đầu tiên ghi danh trên bảng vàng!</p>
                    </li>
                @endforelse
            </ul>
        </div>

        {{-- Pagination --}}
        @if ($users->hasPages())
            <div class="p-4 border-t border-gray-200">
                {{ $users->links() }}
            </div>
        @endif

        {{-- Current User (if not in current page) --}}
        @if ($currentUserData)
            <div class="sticky bottom-0">
                <div class="px-4 sm:px-6 py-3 flex items-center bg-white border-t-2 border-teal-500 shadow-lg-top">
                    {{-- Rank --}}
                    <div class="w-12 text-center flex-shrink-0">
                        <span class="text-base font-bold text-gray-600">#{{ $currentUserData->rank }}</span>
                    </div>

                    {{-- User Info --}}
                    <div class="min-w-0 flex-1 flex items-center ml-4">
                        <div class="flex-shrink-0">
                            <img class="h-12 w-12 rounded-full object-cover ring-2 ring-teal-500" src="{{ $currentUserData->avatar ? asset('storage/users/' . $currentUserData->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($currentUserData->name).'&color=0d9488&background=94ffd8' }}" alt="{{ $currentUserData->name }}">
                        </div>
                        <div class="min-w-0 flex-1 ml-4">
                            <p class="text-md font-semibold text-gray-800 truncate">{{ $currentUserData->name }}</p>
                            <span class="text-sm text-teal-600 font-medium">Vị trí của bạn</span>
                        </div>
                    </div>

                    {{-- XP --}}
                    <div class="text-right flex-shrink-0 ml-4">
                        <span class="font-bold text-lg text-teal-600">{{ number_format($currentUserData->total_xp ?? 0) }}</span>
                        <span class="text-sm text-gray-500">XP</span>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <style>
        .shadow-lg-top {
            box-shadow: 0 -4px 6px -1px rgb(0 0 0 / 0.1), 0 -2px 4px -2px rgb(0 0 0 / 0.1);
        }
    </style>
</div>