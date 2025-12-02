<div class="container mx-auto p-6">
    <h1 class="text-3xl font-bold text-teal-600 mb-8">Tìm bạn bè</h1>

    {{-- Form lọc --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-3">
        <input type="text" wire:model="search" placeholder="Tìm bạn theo tên..."
            class="border rounded p-2 w-full md:w-1/4 focus:outline-none focus:ring-2 focus:ring-teal-500">

        <select wire:model="interest" class="border rounded p-2 w-full md:w-1/4 focus:outline-none focus:ring-2 focus:ring-teal-500">
            <option value="">Tất cả sở thích</option>
            <option value="thể thao">Thể thao</option>
            <option value="đọc sách">Đọc sách</option>
            <option value="âm nhạc">Âm nhạc</option>
            <option value="thiền">Thiền</option>
        </select>

        <select wire:model.live="province_id" class="border rounded p-2 w-full md:w-1/4 pr-8 focus:outline-none focus:ring-2 focus:ring-teal-500">
            <option value="">Chọn tỉnh/thành phố</option>
            @foreach($provinces as $p)
                <option value="{{ $p->id }}">{{ $p->name }}</option>
            @endforeach
        </select>

        <select wire:model="ward_id" class="border rounded p-2 w-full md:w-1/4 focus:outline-none focus:ring-2 focus:ring-teal-500">
            <option value="">Chọn phường/xã</option>
            @foreach($wards as $w)
                <option value="{{ $w->id }}">{{ $w->name }}</option>
            @endforeach
        </select>

        <button wire:click="searchAction" class="border rounded p-2 w-full md:w-32 bg-amber-500 hover:bg-amber-600
                       text-white font-semibold focus:outline-none focus:ring-2 focus:ring-amber-400">
                       Tìm kiếm
        </button>
    </div>

    @if (session()->has('warning'))
    <div class="mb-4 p-3 bg-yellow-100 text-yellow-700 rounded">
        {{ session('warning') }}
    </div>
    @endif

    {{-- Thông báo lỗi --}}
    @if($errorMessage)
        <div class="mb-4 p-3 bg-red-100 text-red-700 rounded">{{ $errorMessage }}</div>
    @endif

    {{-- Danh sách người dùng --}}
    <section class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
        @forelse($users as $user)
            <article class="bg-white shadow rounded-lg p-6 flex flex-col items-center text-center">
                <img class="h-10 w-10 rounded-full object-cover" src="{{ $user->avatar ? asset('storage/users/' . $user->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&color=0d9488&background=94ffd8'}}" 
                alt="{{ $user->name }}" >
                {{--<img src="{{ $user->avatar_url ?? 'https://i.pravatar.cc/100?u=' . $user->id }}" 
                     alt="Avatar {{ $user->name }}" class="rounded-full w-24 h-24 mb-4 object-cover">--}}
                <h2 class="text-lg font-semibold text-teal-600 mb-1">{{ $user->name }}</h2>
                <p class="text-sm text-gray-600 mb-1">Sở thích: {{ $user->interests ?? 'Chưa cập nhật' }}</p>
                <p class="text-sm text-gray-500 mb-4">{{ $user->ward->province->name ?? 'Chưa rõ địa điểm' }}</p>

                @php
                    $isFollowing = $user->followers->contains('follower_id', auth()->id());
                @endphp

                @if(auth()->id() !== $user->id)
                    <button wire:click="toggleFollow({{ $user->id }})" 
                    class="{{ $isFollowing ? 'bg-teal-500 hover:bg-teal-600' : 'bg-teal-600 hover:bg-teal-700' }} 
                               text-white rounded-md px-4 py-2 font-semibold focus:outline-none focus:ring-2 focus:ring-teal-400">
                        {{ $isFollowing ? 'Bỏ theo dõi' : 'Theo dõi' }}
                    </button>
                @endif
            </article>
        @empty
            <div class="col-span-1 md:col-span-3 text-center text-gray-600 text-lg py-10">
                Không tìm thấy bạn bè nào phù hợp.
            </div>
        @endforelse
    </section>

    {{-- Phân trang --}}
    <div class="mt-10 flex justify-center">
        {{ $users->links('pagination.teal') }}
    </div>
</div>
