<section aria-label="Featured Challenges" class="mt-20 text-center">
    <h2 class="text-2xl font-bold text-gray-900 mb-6">Thử Thách Nổi Bật</h2>
    <div class="grid gap-8 md:grid-cols-3">
      @forelse ($challenges as $challenge)
        <article class="bg-white rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300 flex flex-col overflow-hidden">
            <div class="relative">
                <a href="{{ route('challenges.show', $challenge->id) }}" wire:navigate>
                    @php
                        $imageUrl = asset('no_image.png');
                        if ($challenge->image && file_exists(public_path('storage/' . $challenge->image))) {
                            $imageUrl = asset('storage/' . $challenge->image);
                        } elseif ($challenge->categories->isNotEmpty() && $challenge->categories->first()->icon && file_exists(public_path('storage/' . $challenge->categories->first()->icon))) {
                            $imageUrl = asset('storage/' . $challenge->categories->first()->icon);
                        }
                    @endphp
                    <img src="{{ $imageUrl }}" alt="Challenge Image" class="w-full object-cover h-48">
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
                 <div class="flex justify-center mt-4">
                  <a href="{{ route('challenges.show', $challenge->id) }}" wire:navigate
                    class="w-full bg-teal-600 hover:bg-teal-700 text-white font-semibold py-2 rounded focus:outline-none focus:ring-2 focus:ring-teal-400 text-center">
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