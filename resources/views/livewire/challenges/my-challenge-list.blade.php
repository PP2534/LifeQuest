<div> 
    <main class="container mx-auto px-4 py-12">
        <h1 class="text-3xl font-bold text-teal-600 mb-8">Quản lý Thử Thách của Tôi</h1>

        @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg mb-6" role="alert">
                <p>{{ session('success') }}</p>
            </div>
        @endif
        @if (session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg mb-6" role="alert">
                <p>{{ session('error') }}</p>
            </div>
        @endif

        <section class="grid md:grid-cols-2 gap-6">
            
            @forelse ($challenges as $challenge)
                <article class="bg-white shadow rounded-lg p-6">
                    <h3 class="text-lg font-semibold mb-2">{{ $challenge->title }}</h3>
                    
                    <p class="text-sm text-gray-600 mb-4">Trạng thái: {{ $challenge->status }}</p>
                    
                    <div class="flex space-x-2">
                        <a href="{{ route('challenges.show', $challenge) }}" wire:navigate
                           class="bg-teal-600 text-white px-3 py-1 rounded hover:bg-teal-700">Xem</a>
                        
                        <a href="{{ route('challenges.edit', $challenge) }}" wire:navigate
                           class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600">Sửa</a>
                        
                        <button 
                            wire:click="deleteChallenge({{ $challenge->id }})"
                            wire:confirm="Bạn có chắc chắn muốn xóa thử thách '{{ $challenge->title }}'? Hành động này không thể hoàn tác."
                            class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700">
                            Xóa
                        </button>
                    </div>
                </article>
            @empty
                <div class="md:col-span-2 bg-white shadow rounded-lg p-8 text-center">
                    <p class="text-gray-600">Bạn chưa tạo thử thách nào.</p>
                    <a href="{{ route('challenges.create') }}" wire:navigate
                       class="mt-4 inline-block bg-teal-600 hover:bg-teal-700 text-white font-semibold px-4 py-2 rounded-lg shadow">
                        + Tạo Thử Thách Ngay
                    </a>
                    <br>
                    <a href="{{ route('challenges.by-location') }}"
                        class="mt-4 inline-block bg-teal-600 hover:bg-teal-700 text-white font-semibold px-4 py-2 rounded-lg shadow">
                        Lọc thử thách theo vị trí
                    </a>
                </div>
            @endforelse
            </section>

        <div class="mt-8">
            {{ $challenges->links() }}
        </div>
    </main>
</div>