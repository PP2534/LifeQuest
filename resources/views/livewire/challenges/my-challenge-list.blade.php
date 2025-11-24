<div> <main class="container mx-auto px-4 py-12">
        <h1 class="text-3xl font-bold text-teal-600 mb-8">Thử Thách Của Tôi (Đã tạo & Tham gia)</h1>

        <section class="grid md:grid-cols-2 gap-6">
            
            @forelse ($challenges as $challenge)
                <article class="bg-white shadow rounded-lg p-6">
                    <h3 class="text-lg font-semibold mb-2">{{ $challenge->title }}</h3>
                    
                    <p class="text-sm text-gray-600 mb-4">Trạng thái: {{ $challenge->status }}</p>
                    
                    <div class="flex space-x-2">
                        <a href="{{ route('challenges.show', $challenge) }}" wire:navigate
                           class="bg-teal-600 text-white px-3 py-1 rounded hover:bg-teal-700">Xem</a>
                        
                        @if ($challenge->creator_id == Auth::id())
                            <a href="{{ route('challenges.edit', $challenge) }}" wire:navigate
                               class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600">Sửa</a>
                            
                            <button 
                                wire:click="deleteChallenge({{ $challenge->id }})"
                                wire:confirm="Bạn có chắc chắn muốn xóa thử thách '{{ $challenge->title }}'? Hành động này không thể hoàn tác."
                                class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700">
                                Xóa
                            </button>
                        @else
                            <span class="inline-flex items-center bg-gray-100 text-gray-700 px-3 py-1 rounded-md text-sm font-medium">
                                Đã tham gia
                            </span>
                        @endif
                        </div>
                </article>
            @empty
                <div class="md:col-span-2 bg-white shadow rounded-lg p-8 text-center">
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