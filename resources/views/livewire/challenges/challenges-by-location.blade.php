<main class="container mx-auto px-4 py-12">
    <h1 class="text-3xl font-bold text-teal-600 mb-6">Thử thách theo vị trí của bạn</h1>
    <form wire:submit.prevent="search" class="mb-6 flex flex-col sm:flex-row sm:items-center gap-4">
        <label for="province-select" class="sr-only">Chọn địa điểm</label>
        <select id="province-select" wire:model="selectedProvince"
            class="border border-gray-300 rounded-md px-4 py-2 pr-8 focus:outline-none focus:ring-2 focus:ring-teal-500">
            <option value="">-- Chọn tỉnh/thành --</option>
            @foreach($provinces as $province)
            <option value="{{ $province->id }}">{{ $province->name }}</option>
            @endforeach
        </select>

        <nav role="tablist" aria-label="Bộ lọc thời gian" class="flex space-x-2">
            <button type="button" role="tab" aria-selected="true" aria-controls="weekly" id="tab-weekly" 
            class="px-4 py-2 rounded-md bg-gray-200 text-gray-700 hover:bg-teal-100 focus:outline-none focus:ring-2 focus:ring-teal-400">
                Hàng tuần
            </button>

            <button type="button" role="tab" aria-selected="false" aria-controls="monthly" id="tab-monthly" 
            class="px-4 py-2 rounded-md bg-gray-200 text-gray-700 hover:bg-teal-100 focus:outline-none focus:ring-2 focus:ring-teal-400">
                Hàng tháng
            </button>
        </nav>

        <button type="submit" class="w-full md:w-auto bg-amber-500 hover:bg-amber-600 text-white rounded-md px-6 py-2 font-semibold focus:outline-none focus:ring-2 focus:ring-amber-400">
                Tìm kiếm
        </button>
    </form>

    @if($challenges->isNotEmpty())
        <section aria-label="Featured Challenges" class="mt-6">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Các thử thách</h2>
            <div class="grid gap-8 md:grid-cols-3">
                @foreach($challenges as $challenge)
                <article class="bg-white rounded-lg shadow hover:shadow-lg transition-shadow focus-within:ring-2 focus-within:ring-teal-600" tabindex="0">
                    <img src="{{ asset('storage/' . $challenge->image) }}" alt="{{ $challenge->title }}" class="rounded-t-lg w-full object-cover h-48">
                    <div class="p-4">
                        <h3 class="text-lg font-semibold text-gray-900 mb-1">{{ $challenge->title }}</h3>
                        <p class="text-sm text-teal-600 mb-2">{{ $challenge->categories->first()->name ?? 'Trạm Năng Lượng' }}</p>
                        <p class="text-sm text-gray-600 mb-4">Thời gian: {{ $challenge->duration_days }} ngày</p>
                        <button class="w-full bg-teal-600 hover:bg-teal-700 text-white font-semibold py-2 rounded focus:outline-none focus:ring-2 focus:ring-teal-400" aria-label="Tham gia thử thách này">
                            Tham gia
                        </button>
                    </div>
                </article>
                @endforeach
            </div>
        </section>

        @elseif($selectedProvince)
        <p class="text-gray-500 text-center">Không có thử thách nào tại vị trí này.</p>
    @endif
</main>
