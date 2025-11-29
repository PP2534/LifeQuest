<div>
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-3xl font-bold text-teal-600">Danh sách Thử Thách</h1>
      <div class="space-x-3">      
        @auth
            <a href="{{ route('challenges.create') }}" wire:navigate
               class="inline-block bg-teal-600 hover:bg-teal-700 text-white font-semibold px-4 py-2 rounded-lg shadow">
                + Tạo Thử Thách
            </a>          
            <a href="{{route('my-challenges')}}" wire:navigate
               class="inline-block bg-yellow-500 hover:bg-indigo-700 text-white font-semibold px-4 py-2 rounded-lg shadow">
                Quản lý Thử Thách
            </a>
        @endauth
        </div>
    </div>
    <section aria-label="Challenges list">
      <div class="grid gap-8 md:grid-cols-3">
        @foreach($challenges as $challenge)
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
        @endforeach
      </div>
    </section>

    <!-- Pagination -->
   {{ $challenges->links('components.pagination-teal') }}
</div>