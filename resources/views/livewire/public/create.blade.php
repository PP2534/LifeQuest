<div class="container mx-auto p-6">

    <h1 class="text-3xl font-bold text-teal-600 mb-8">Tất cả thử thách & thói quen</h1>

    {{-- Filters --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-4">
        <input type="text" wire:model.defer="search"
               placeholder="Tìm kiếm thử thách, thói quen..."
               class="border rounded p-2 w-full md:w-1/4">

        <select wire:model.defer="category" class="border rounded p-2 w-full md:w-1/4">
            <option value="all">Tất cả</option>
            <option value="challenge">Thử thách</option>
            <option value="habit">Thói quen</option>
        </select>

        <select wire:model.defer="province_id" class="border rounded p-2 w-full md:w-1/4">
            <option value="">-- Chọn Tỉnh/Thành phố --</option>
            @foreach($provinces as $province)
                <option value="{{ $province->id }}">{{ $province->name }}</option>
            @endforeach
        </select>

        <select wire:model.defer="ward_id" class="border rounded p-2 w-full md:w-1/4">
            <option value="">-- Chọn Phường/Xã --</option>
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

    {{-- Thông báo nếu tìm kiếm nhưng không có kết quả --}}
    @if($searched && $challenges->isEmpty() && $habits->isEmpty())
        <p class="text-red-500 text-lg font-semibold">
            Không tìm thấy thử thách hoặc thói quen nào phù hợp với tìm kiếm.
        </p>
    @endif

    {{-- Challenges --}}
    @if(!$challenges->isEmpty())
        <h2 class="text-2xl font-semibold mb-3">Challenges</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-4">
            @foreach($challenges as $challenge)
                <div class="p-4 border rounded shadow">
                    <h3 class="text-xl font-bold">{{ $challenge->name }}</h3>
                    <p>{{ $challenge->description }}</p>
                    <p class="text-sm text-gray-500">
                        {{ $challenge->province?->name ?? '' }} - {{ $challenge->ward?->name ?? '' }}
                    </p>
                </div>
            @endforeach
        </div>
        {{ $challenges->links() }}
    @endif

    {{-- Habits --}}
    @if(!$habits->isEmpty())
        <h2 class="text-2xl font-semibold mb-3">Habits</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-4">
            @foreach($habits as $habit)
                <div class="p-4 border rounded shadow">
                    <h3 class="text-xl font-bold">{{ $habit->name }}</h3>
                    <p>{{ $habit->description }}</p>
                    <p class="text-sm text-gray-500">
                        {{ $habit->province?->name ?? '' }} - {{ $habit->ward?->name ?? '' }}
                    </p>
                </div>
            @endforeach
        </div>
        {{ $habits->links() }}
    @endif

</div>
