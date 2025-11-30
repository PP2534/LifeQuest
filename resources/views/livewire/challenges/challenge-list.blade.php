<div>
    <!-- Dòng tiêu đề -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-teal-600 inline-block">Danh Sách Thử Thách</h1>
    </div>

    <!-- Dòng tìm kiếm, bộ lọc và các nút hành động -->
    <div class="flex justify-between items-center mb-6">
        <!-- Nhóm tìm kiếm và bộ lọc -->
        <div class="flex items-center space-x-2">
            <!-- Ô tìm kiếm -->
            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                    <svg class="w-5 h-5 text-gray-400" viewBox="0 0 24 24" fill="none">
                        <path d="M21 21L15 15M17 10C17 13.866 13.866 17 10 17C6.13401 17 3 13.866 3 10C3 6.13401 6.13401 3 10 3C13.866 3 17 6.13401 17 10Z"
                              stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                </span>
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Tìm kiếm thử thách..."
                       class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition">
            </div>
            <!-- Bộ lọc Tỉnh/Thành -->
            <select wire:model.live="selectedProvince"
                    class="py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition">
                <option value="">Tất cả tỉnh thành</option>
                @foreach($provinces as $province)
                    <option value="{{ $province->id }}">{{ $province->name }}</option>
                @endforeach
            </select>
            <!-- Bộ lọc Phường/Xã -->
            <select wire:model.live="selectedWard"
                    class="py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition"
                    @if($wards->isEmpty()) disabled @endif>
                <option value="">Tất cả phường/xã</option>
                @if($wards->isNotEmpty())
                    @foreach($wards as $ward)
                        <option value="{{ $ward->id }}">{{ $ward->name }}</option>
                    @endforeach
                @endif
            </select>
        </div>

        <!-- Nhóm các nút hành động -->
        @auth
            <div class="flex items-center space-x-2">
                <a href="{{ route('challenges.create') }}" wire:navigate
                   class="inline-block bg-teal-600 hover:bg-teal-700 text-white font-semibold px-4 py-2 rounded-lg shadow whitespace-nowrap">
                    + Tạo Thử Thách
                </a>
                <a href="{{route('my-challenges')}}" wire:navigate
                   class="inline-block bg-amber-500 hover:bg-amber-600 text-white font-semibold px-4 py-2 rounded-lg shadow-md focus:outline-none focus:ring-4 focus:ring-amber-300">
                    Quản lý Thử Thách
                </a>
            </div>
        @endauth
    </div>

    <section aria-label="Challenges list">
      <div class="grid gap-8 md:grid-cols-3">
        @foreach($challenges as $challenge)
          <article class="bg-white rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300 flex flex-col overflow-hidden">
            <div class="relative">
                <a href="{{ route('challenges.show', $challenge->id) }}" wire:navigate>
                    <img src="{{ asset('storage/' . $challenge->image) }}" alt="Challenge Image" class="w-full object-cover h-48">
                </a>
                @if($challenge->categories->isNotEmpty())
                    <span class="absolute top-3 left-3 bg-teal-500 text-white text-xs font-semibold px-3 py-1 rounded-full">{{ $challenge->categories->first()->name }}</span>
                @else
                    <span class="absolute top-3 left-3 bg-gray-500 text-white text-xs font-semibold px-3 py-1 rounded-full">Chung</span>
                @endif
            </div>

            <div class="p-5 flex flex-col flex-grow">
                <h3 class="text-xl font-bold text-gray-800 mb-3 hover:text-teal-600 transition-colors">
                    <a href="{{ route('challenges.show', $challenge->id) }}" wire:navigate>{{$challenge->title}}</a>
                </h3>

                @if($challenge->creator)
                <div class="flex items-center mb-4 text-sm">
                    <a href="{{ route('profile.show', ['id' => $challenge->creator->id]) }}" wire:navigate class="flex items-center text-gray-600 hover:text-gray-900">
                        <img class="w-8 h-8 rounded-full object-cover mr-3" src="{{ $challenge->creator->avatar ? asset('storage/users/'. $challenge->creator->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode($challenge->creator->name) . '&color=0d9488&background=94ffd8' }}" alt="{{ $challenge->creator->name }}">
                        <span class="font-medium">{{ $challenge->creator->name }}</span>
                    </a>
                </div>
                @endif
                
                <div class="mt-auto pt-4 border-t border-gray-200 flex justify-between items-center text-sm text-gray-600">
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1.5 text-teal-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>{{$challenge->duration_days}} ngày</span>
                    </div>
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1.5 text-teal-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.653-.124-1.28-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.653.124-1.28.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        <span>{{ $challenge->participants_count }} người</span>
                    </div>
                </div>
            </div>
          </article>
            <!-- <a href="{{ route('challenges.show', $challenge->id) }}" class="p-4 border rounded shadow hover:bg-gray-50">
                <img src="{{ $challenge->image }}" class="w-full h-40 object-cover rounded">
                <h2 class="font-semibold mt-2">{{ $challenge->title }}</h2>
                <p class="text-sm text-gray-600">{{ Str::limit($challenge->description, 100) }}</p>
            </a> -->
        @empty
            <div class="md:col-span-3 text-center text-gray-500 py-8">
                <p>Chưa có thử thách nào được tạo.</p>
            </div>
        @endforelse
      </div>
    </section>

    @if ($challenges->hasPages())
        <div class="mt-12">
            {{ $challenges->links('vendor.pagination.tailwind') }}
        </div>
    @endif
</div>