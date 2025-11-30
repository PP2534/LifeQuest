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
                   class="inline-block bg-yellow-500 hover:bg-indigo-700 text-white font-semibold px-4 py-2 rounded-lg shadow whitespace-nowrap">
                    Quản lý Thử Thách
                </a>
            </div>
        @endauth
    </div>

    <section aria-label="Challenges list">
      <div class="grid gap-8 md:grid-cols-3">
        @forelse($challenges as $challenge)
          <article class="bg-white rounded-lg shadow hover:shadow-lg transition-shadow focus-within:ring-2 focus-within:ring-teal-600" tabindex="0">
            <a href="{{ route('challenges.show', $challenge->id) }}">
            <img src="{{ asset('storage/' . $challenge->image) }}" alt="Challenge Image" class="rounded-t-lg w-full object-cover h-48" />
            </a>
            <div class="p-4">
                <h3 class="text-lg font-semibold text-gray-900 mb-1">{{$challenge->title}}</h3>
                @if($challenge->categories->isNotEmpty())
                    <p class="text-sm text-teal-600 mb-2">{{ $challenge->categories->first()->name }}</p>
                @else
                    <p class="text-sm text-teal-600 mb-2">Trạm Năng Lượng</p>
                @endif
                <p class="text-sm text-gray-600 mb-4">Thời gian: {{$challenge->duration_days}} ngày</p>
                <div class="flex justify-center">
                  <a href="{{ route('challenges.show', $challenge->id) }}" wire:navigate
                    class="w-11/12 bg-teal-600 hover:bg-teal-700 text-white font-semibold py-2 rounded focus:outline-none focus:ring-2 focus:ring-teal-400 text-center">
                      Xem Chi Tiết
                  </a>
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