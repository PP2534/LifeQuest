<div class="container mx-auto p-6">
    <h1 class="text-3xl font-bold text-teal-600 mb-6">Tất cả thử thách & thói quen</h1>

    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-4">
        <input type="text" wire:model="search" placeholder="Tìm thử thách, thói quen..."
        class="border rounded p-2 w-full md:w-1/4 focus:outline-none focus:ring-2 focus:ring-teal-500">

        <select wire:model="category" class="border rounded p-2 w-full md:w-1/4 focus:outline-none focus:ring-2 focus:ring-teal-500">
            <option value="all">Tất cả</option>
            <option value="challenge">Thử thách</option>
            <option value="habit">Thói quen</option>
        </select>

        <select wire:model="province_id" class="border rounded p-2 w-full md:w-1/4 pr-8 focus:outline-none focus:ring-2 focus:ring-teal-500">
            <option value="">Chọn tỉnh/thành phố</option>
            @foreach($provinces as $p)
                <option value="{{ $p->id }}">{{ $p->name }}</option>
            @endforeach
        </select>

        <select wire:model="ward_id" class="border rounded p-2 w-full md:w-1/4 pr-8 focus:outline-none focus:ring-2 focus:ring-teal-500">
            <option value="">Chọn phường/xã</option>
            @foreach($wards as $w)
                <option value="{{ $w->id }}">{{ $w->name }}</option>
            @endforeach
        </select>

        {{-- Button --}}
        <button wire:click="searchAction" class="border rounded p-2 w-full md:w-32 bg-amber-500 hover:bg-amber-600
                       text-white font-semibold focus:outline-none focus:ring-2 focus:ring-amber-400">
            Tìm kiếm
        </button>
    </div>

    @if (session()->has('error'))
    <div class="mb-4 p-3 bg-yellow-100 text-yellow-700 rounded">
        {{ session('error') }}
    </div>
@endif

    @if ($items->count() === 0 && $searched)
        <div class="text-center text-gray-600 text-lg py-10">
            Không tìm thấy thử thách hoặc thói quen nào phù hợp với tìm kiếm.
        </div>
    @endif 
@if($items->where('type', 'challenge')->count() > 0)
    <h2 class="text-2xl font-semibold mb-3">Thử thách</h2>
    <div class="grid gap-8 md:grid-cols-3">
        @foreach($items->where('type', 'challenge') as $item)
            <article class="bg-white rounded-lg shadow hover:shadow-lg transition-shadow focus-within:ring-2 focus-within:ring-teal-600" tabindex="0">
                <img src="https://source.unsplash.com/400x250/?challenge,{{ $item->name }}"
                    alt="Challenge Image" class="rounded-t-lg w-full object-cover h-48">

                <div class="p-4">
                    <h3 class="text-lg font-semibold text-gray-900 mb-1">{{ $item->title }}</h3>
                    <p class="text-sm text-teal-600 mb-2">Thử thách</p>
                    <p class="text-sm text-gray-600 mb-4">Thời gian: {{ $item->duration_days }} ngày</p>
                    

 @if(in_array($item->id, $joinedChallenges))
        <p class="text-sm text-green-600 font-semibold mb-2">Bạn đã tham gia</p>
    @endif
    <button wire:click="toggleJoinItem('challenge', {{ $item->id }})" 
            class="w-full bg-teal-600 hover:bg-teal-700 text-white font-semibold py-2 rounded focus:outline-none focus:ring-2 focus:ring-teal-400">
        {{ in_array($item->id, $joinedChallenges) ? 'Đã tham gia' : 'Tham gia' }}
    </button>
                </div>
            </article>
        @endforeach
    </div>
@endif
<br>

{{--thói quen--}}
@if($items->where('type', 'habit')->count() > 0)
    <h2 class="text-2xl font-semibold mb-3">Thói quen</h2>
    <div class="grid gap-8 md:grid-cols-3">
        @foreach($items->where('type', 'habit') as $item)
            <article class="bg-white rounded-lg shadow hover:shadow-lg transition-shadow focus-within:ring-2 focus-within:ring-teal-600" tabindex="0">
                <img src="https://source.unsplash.com/400x250/?habit,{{ $item->name }}"
                    alt="Habit Image" class="rounded-t-lg w-full object-cover h-48">

                <div class="p-4">
                    <h3 class="text-lg font-semibold text-gray-900 mb-1">{{ $item->title }}</h3>
                    <p class="text-sm text-teal-600 mb-2">Thói quen</p>

                    
                    @if(in_array($item->id, $joinedHabits))
        <p class="text-sm text-green-600 font-semibold mb-2">Bạn đã tham gia</p>
        @endif
        <button wire:click="toggleJoinItem('habit', {{ $item->id }})" 
                class="w-full bg-teal-600 hover:bg-teal-700 text-white font-semibold py-2 rounded focus:outline-none focus:ring-2 focus:ring-teal-400">
            {{ in_array($item->id, $joinedHabits) ? 'Đã tham gia' : 'Tham gia' }}
        </button>
        </div>
        </article>
        @endforeach
    </div>
@endif

{{-- PHÂN TRANG CHUNG --}}
<div class="mt-10 flex justify-center">
    {{ $items->links('pagination.teal') }}
</div>


</div>

