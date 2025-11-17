<x-app-layout>
    <h1 class="text-3xl font-bold text-teal-600 mb-8">Tìm bạn bè</h1>   

    <div class="container mx-auto p-6">
        @if(!empty($errorMessage))
        <div class="mb-4 p-3 bg-red-100 text-red-700 rounded"> {{ $errorMessage }} </div>
    @endif

    @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-700 rounded"> {{ session('success') }} </div>
    @endif

    {{--form tìm kiếm--}}
    <form method="GET" action="{{ route('users.index') }}" class="flex flex-wrap md:flex-nowrap gap-3 mb-6">
        <input type="search" name="search" placeholder="Tìm bạn theo tên..." value="{{ request('search') }}" 
        class="w-full md:w-auto border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-teal-500">

        <select name="interest" class="w-full md:w-auto border border-gray-300 rounded-md px-4 py-2 pr-8 focus:outline-none focus:ring-2 focus:ring-teal-500">
            <option value="">Tất cả sở thích</option>
            <option value="thể thao" {{ request('interest') == 'thể thao' ? 'selected' : '' }}>Thể thao</option>
            <option value="đọc sách" {{ request('interest') == 'đọc sách' ? 'selected' : '' }}>Đọc sách</option>
            <option value="âm nhạc" {{ request('interest') == 'âm nhạc' ? 'selected' : '' }}>Âm nhạc</option>
            <option value="thiền" {{ request('interest') == 'thiền' ? 'selected' : '' }}>Thiền</option>
        </select>

        <select name="province_id" class="w-full md:w-auto border border-gray-300 rounded-md px-4 py-2 pr-8 focus:outline-none focus:ring-2 focus:ring-teal-500">
            <option value="">Tất cả địa điểm</option>
            @foreach(App\Models\Province::all() as $province)
                <option value="{{ $province->id }}" {{ request('province_id') == $province->id ? 'selected' : '' }}>
                    {{ $province->name }}
                </option>
            @endforeach
        </select>

        

        <button type="submit" class="w-full md:w-auto bg-amber-500 hover:bg-amber-600 text-white rounded-md px-6 py-2 font-semibold focus:outline-none focus:ring-2 focus:ring-amber-400">
            Tìm kiếm
        </button>
    </form>

    {{--ds người dùng--}}
    <section aria-label="Kết quả tìm kiếm bạn bè" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
        @if($users->isEmpty())
            <div class="col-span-1 md:col-span-3 text-center text-gray-500 py-6">
                Không tìm thấy bạn bè nào phù hợp.
            </div>
        @else
            @foreach($users as $user)
                <article class="bg-white shadow rounded-lg p-6 flex flex-col items-center text-center">
                    <img src="{{ $user->avatar_url ?? 'https://i.pravatar.cc/100?u=' . $user->id }}" alt="Avatar {{ $user->name }}" class="rounded-full w-24 h-24 mb-4 object-cover">
                    <h2 class="text-lg font-semibold text-teal-600 mb-1">{{ $user->name }}</h2>
                    <p class="text-sm text-gray-600 mb-1">Sở thích: {{ $user->interests ?? 'Chưa cập nhật' }}</p>
                    <p class="text-sm text-gray-500 mb-4">{{ $user->ward->province->name ?? 'Chưa rõ địa điểm' }}</p>

                    @php
                        $isFollowing = $user->followers->contains('follower_id', auth()->id());
                    @endphp

                    @if(auth()->id() !== $user->id)
                        <form action="{{ route('users.toggleFollow', $user->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="{{ $isFollowing ? 'bg-teal-500 hover:bg-teal-600' : 'bg-teal-600 hover:bg-teal-700' }} 
                            text-white rounded-md px-4 py-2 font-semibold focus:outline-none focus:ring-2 focus:ring-teal-400"> {{ $isFollowing ? 'Bỏ theo dõi' : 'Theo dõi' }}
                            </button>
                        </form>
                    @endif
                </article>
            @endforeach
        @endif
    </section>

    {{--phân trang--}}
    <div class="mt-10 flex justify-center"> {{ $users->links('pagination.teal') }}</div>
</x-app-layout>