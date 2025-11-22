<div class="container mx-auto p-6">
    <h1 class="text-3xl font-bold text-teal-600 mb-8">Tất cả thử thách & thói quen</h1>

    {{-- Filters --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-4">
        <input type="text" wire:model="searchAll"
               placeholder="Tìm kiếm thử thách, thói quen..."
               class="border rounded p-2 w-full md:w-1/4">

        <select wire:model="category" class="border rounded p-2 w-full md:w-1/4">
            <option value="all">Tất cả</option>
            <option value="challenge">Thử thách</option>
            <option value="habit">Thói quen</option>
        </select>

        <select wire:model="province_id" class="border rounded p-2 w-full md:w-1/4 pr-8 focus:outline-none focus:ring-2 focus:ring-teal-500">
            <option value="">Chọn Tỉnh/Thành phố</option>
            @foreach($provinces as $province)
                <option value="{{ $province->id }}">{{ $province->name }}</option>
            @endforeach
        </select>

        <select wire:model="ward_id" class="border rounded p-2 w-full md:w-1/4 pr-8 focus:outline-none focus:ring-2 focus:ring-teal-500">
            <option value="">Chọn Phường/Xã</option>
            @foreach($wards as $ward)
                <option value="{{ $ward->id }}">{{ $ward->name }}</option>
            @endforeach
        </select>

        <button type="button" wire:click="search"
                class="border rounded p-2 w-full md:w-32 bg-amber-500 hover:bg-amber-600
                       text-white font-semibold focus:outline-none focus:ring-2 focus:ring-amber-400">
            Tìm kiếm
        </button>
    </div>

    {{-- THÔNG BÁO KHI KHÔNG CÓ KẾT QUẢ --}}
    @if($searched && $challenges->isEmpty() && $habits->isEmpty())
        <p class="text-red-500 text-lg font-semibold">
            Không tìm thấy thử thách hoặc thói quen nào phù hợp với tìm kiếm.
        </p>
    @endif

   {{-- CHALLENGES --}}
@if(!$challenges->isEmpty())
    <h2 class="text-2xl font-semibold mb-3">Thử thách</h2>

    <section aria-label="Challenges list">
        <div class="grid gap-8 md:grid-cols-3">

            @foreach($challenges as $challenge)
                <article
                    class="bg-white rounded-lg shadow hover:shadow-lg transition-shadow focus-within:ring-2 focus-within:ring-teal-600"
                    tabindex="0">

                    <img src="https://source.unsplash.com/400x250/?challenge,activity,{{ $challenge->name }}"
                        alt="Challenge Image"
                        class="rounded-t-lg w-full object-cover h-48">

                    <div class="p-4">
                        <h3 class="text-lg font-semibold text-gray-900 mb-1">{{ $challenge->title }}</h3>

                        <p class="text-sm text-teal-600 mb-2">
                            {{ $challenge->category ?? 'Thử thách' }}
                        </p>

                        <p class="text-sm text-gray-600 mb-4">
                        Thời gian: {{ $challenge->duration_days ?? 'Không rõ' }} ngày
                        </p>

                        <button
                            class="w-full bg-teal-600 hover:bg-teal-700 text-white font-semibold py-2 rounded focus:outline-none focus:ring-2 focus:ring-teal-400">
                            Tham gia
                        </button>
                    </div>

                </article>
            @endforeach

        </div>
    </section>

    <div class="mt-4">
        {{ $challenges->links() }}
    </div>
@endif



    {{-- HABITS --}}
    @if(!$habits->isEmpty())
    <h2 class="text-2xl font-semibold mb-3 mt-10">Thói quen</h2>

    <section aria-label="Habits list">
        <div class="grid gap-8 md:grid-cols-3">

            @foreach($habits as $habit)
                <article class="bg-white rounded-lg shadow hover:shadow-lg transition-shadow focus-within:ring-2 focus-within:ring-teal-600" tabindex="0">
                    <img src="https://source.unsplash.com/400x250/?habit,lifestyle,{{ $habit->name }}"
                         alt="Habit Image"
                         class="rounded-t-lg w-full object-cover h-48">

                    <div class="p-4">
                        <h3 class="text-lg font-semibold text-gray-900 mb-1">{{ $habit->title }}</h3>

                        <p class="text-sm text-teal-600 mb-2">
                            {{ $habit->category ?? 'Thói quen' }}
                        </p>


                        <button
                            class="w-full bg-teal-600 hover:bg-teal-700 text-white font-semibold py-2 rounded focus:outline-none focus:ring-2 focus:ring-teal-400">
                            Tham gia
                        </button>
                    </div>
                </article>
            @endforeach

        </div>
    </section>

    <div class="mt-4">
        {{ $habits->links() }}
    </div>
@endif


    {{-- TRƯỜNG HỢP KHÔNG CÓ DỮ LIỆU & CHƯA TÌM KIẾM --}}
    @if(!$searched && $challenges->isEmpty() && $habits->isEmpty())
        <p class="text-gray-600 text-lg">
            Hiện chưa có thử thách hoặc thói quen công khai nào.
        </p>
    @endif

</div>
