<section aria-label="Featured Challenges" class="mt-20 text-center">
    <h2 class="text-2xl font-bold text-gray-900 mb-6">Thử Thách Nổi Bật</h2>
    <div class="grid gap-8 md:grid-cols-3">
      @forelse ($challenges as $challenge)
        <article class="bg-white rounded-lg shadow hover:shadow-lg transition-shadow focus-within:ring-2 focus-within:ring-teal-600 flex flex-col" tabindex="0">
            {{-- TODO: Link to challenge details page --}}
            <img src="{{ $challenge->image ? asset('storage/' . $challenge->image) : 'https://source.unsplash.com/400x250/?' . data_get($challenge, 'categories.0.slug', 'challenge') }}" alt="Challenge Image" class="rounded-t-lg w-full object-cover h-48" />
            <div class="p-4 flex flex-col flex-grow">
                <h3 class="text-lg font-semibold text-gray-900 mb-1">{{ $challenge->title }}</h3>
                @if($challenge->categories->isNotEmpty())
                    <p class="text-sm text-teal-600 mb-2">{{ $challenge->categories->pluck('name')->join(', ') }}</p>
                @else
                    <p class="text-sm text-transparent mb-2 invisible">No category</p>
                @endif
                <p class="text-sm text-gray-600 mb-4 mt-auto">Thời gian: {{ $challenge->duration_days }} ngày</p>
                {{-- TODO: Link to join challenge action --}}
                <div class="flex justify-center">
                  <a href="{{ route('challenges.show', $challenge->id) }}" wire:navigate
                    class="w-11/12 bg-teal-600 hover:bg-teal-700 text-white font-semibold py-2 rounded focus:outline-none focus:ring-2 focus:ring-teal-400 text-center">
                      Xem Chi Tiết
                  </a>
                </div>
            </div>
        </article>
      @empty
        <p class="md:col-span-3 text-gray-500">Hiện tại chưa có thử thách nổi bật nào.</p>
      @endforelse
    </div>
</section>