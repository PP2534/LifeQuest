<div> <main class="container mx-auto px-4 py-12">
        <h1 class="text-3xl font-bold text-teal-600 mb-8">Thử Thách Của Tôi</h1>

        <section class="grid gap-8 md:grid-cols-3">
            
            @forelse ($challenges as $challenge)
            <article class="bg-white rounded-lg shadow hover:shadow-lg transition-shadow focus-within:ring-2 focus-within:ring-teal-600 flex flex-col" tabindex="0">
                <!-- <a href="{{ route('challenges.show', $challenge->id) }}" wire:navigate>
                    <img src="{{ asset('storage/' . $challenge->image) }}" alt="Challenge Image" class="rounded-t-lg w-full object-cover h-48" />
                </a> -->
                <div class="p-4 flex flex-col flex-grow">
                    <h3 class="text-lg font-semibold text-gray-900 mb-1"><a href="{{ route('challenges.show', $challenge->id) }}" wire:navigate>{{$challenge->title}}</a></h3>
                    @if($challenge->categories->isNotEmpty())
                        <p class="text-sm text-teal-600 mb-2">{{ $challenge->categories->first()->name }}</p>
                    @else
                        <p class="text-sm text-teal-600 mb-2">Trạm Năng Lượng</p>
                    @endif
                    
                    @if($challenge->creator)
                    <div class="flex items-center my-3">
                        <a href="{{ route('profile.show', ['id' => $challenge->creator->id]) }}" wire:navigate>
                            <img class="w-8 h-8 rounded-full object-cover mr-3" src="{{ $challenge->creator->avatar ? asset('storage/users/'. $challenge->creator->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode($challenge->creator->name) . '&color=0d9488&background=94ffd8' }}" alt="{{ $challenge->creator->name }}">
                        </a>
                        <a href="{{ route('profile.show', ['id' => $challenge->creator->id]) }}" wire:navigate class="ml-2 text-sm font-semibold text-gray-700 hover:text-gray-900">
                            {{ $challenge->creator->name }}
                        </a>
                    </div>
                    @endif
            
                    <p class="text-sm text-gray-600 mb-2">Thời gian: {{$challenge->duration_days}} ngày</p>
                    <p class="text-sm text-gray-600 mb-4">Trạng thái: {{ $challenge->status }}</p>
            
                    <div class="mt-auto">
                        <div class="flex justify-center mb-2">
                            <a href="{{ route('challenges.show', $challenge->id) }}" wire:navigate
                            class="w-11/12 bg-teal-600 hover:bg-teal-700 text-white font-semibold py-2 rounded focus:outline-none focus:ring-2 focus:ring-teal-400 text-center">
                                Xem Chi Tiết
                            </a>
                        </div>
            
                        @if ($challenge->creator_id == Auth::id())
                            <div class="flex justify-center space-x-2">
                                <a href="{{ route('challenges.edit', $challenge->id) }}" wire:navigate
                                    class="flex-1 bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600 text-center text-sm">Sửa</a>
                                
                                <button 
                                    wire:click="deleteChallenge({{ $challenge->id }})"
                                    wire:confirm="Bạn có chắc chắn muốn xóa thử thách '{{ $challenge->title }}'? Hành động này không thể hoàn tác."
                                    class="flex-1 bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700 text-sm">
                                    Xóa
                                </button>
                            </div>
                        @else
                            <div class="flex justify-center">
                                <span class="inline-flex items-center bg-gray-100 text-gray-700 px-3 py-1 rounded-md text-sm font-medium">
                                    Đã tham gia
                                </span>
                            </div>
                        @endif
                    </div>
                </div>
            </article>
            @empty
                <div class="md:col-span-3 bg-white shadow rounded-lg p-8 text-center">
                    <p class="text-gray-600">Bạn chưa tạo hoặc tham gia thử thách nào.</p>
                    <a href="{{ route('challenges.index') }}" wire:navigate
                       class="mt-4 inline-block bg-teal-600 hover:bg-teal-700 text-white font-semibold px-4 py-2 rounded-lg shadow">
                        Khám phá Thử thách
                    </a>
                    <br>
                    <a href="{{ route('challenges.by-location') }}" wire:navigate
                        class="mt-4 inline-block bg-teal-600 hover:bg-teal-700 text-white font-semibold px-4 py-2 rounded-lg shadow">
                        Lọc thử thách theo vị trí
                    </a>
                </div>
            @endforelse

        </section>

       
        <!-- Pagination -->
        {{ $challenges->links('components.pagination-teal') }}
    
    </main>
</div>