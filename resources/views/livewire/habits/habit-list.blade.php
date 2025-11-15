<div class="container mx-auto p-4">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold">Danh sách thói quen</h1>
        <a href="{{ route('habits.create') }}" wire:navigate class="bg-teal-600 text-white px-4 py-2 rounded-lg hover:bg-teal-700">Tạo thói quen mới</a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        @forelse($habits as $habit)
            <div class="bg-white rounded-lg shadow-md overflow-hidden transition-transform transform hover:-translate-y-1">
                <a href="{{ route('habits.show', $habit) }}" wire:navigate class="block">
                    <img src="{{ $habit->image ? asset('storage/' . $habit->image) : 'https://placehold.co/600x400/e2e8f0/4a5568?text=' . urlencode($habit->title) }}" alt="{{ $habit->title }}" class="w-full h-40 object-cover">
                    <div class="p-4">
                        <div class="flex justify-between items-start mb-2">
                            <h2 class="font-bold text-lg text-gray-800 truncate pr-2" title="{{ $habit->title }}">{{ $habit->title }}</h2>
                            <span class="flex-shrink-0 text-xs font-semibold px-2 py-1 rounded-full {{ $habit->type === 'group' ? 'bg-indigo-100 text-indigo-800' : 'bg-green-100 text-green-800' }}">
                                {{ $habit->type === 'group' ? 'Nhóm' : 'Cá nhân' }}
                            </span>
                        </div>
                        <div class="flex items-center text-gray-600">
                            <svg class="w-5 h-5 mr-1 text-orange-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M9.26,2.19C8.94,1.5,8.08,1.23,7.4,1.55S6.18,2.47,6.5,3.15l0.82,1.72 c-1.29,0.59-2.39,1.5-3.13,2.63c-1.48,2.24-1.58,5.13-0.28,7.51c1.3,2.38,3.95,3.63,6.58,3.13c2.63-0.5,4.68-2.55,5.18-5.18 c0.5-2.63-0.75-5.28-3.13-6.58c-0.45-0.24-0.93-0.43-1.43-0.57L9.26,2.19z M10,15c-2.21,0-4-1.79-4-4s1.79-4,4-4s4,1.79,4,4 S12.21,15,10,15z" clip-rule="evenodd" />
                            </svg>
                            <span class="font-semibold text-sm">Streak:</span>
                            <span class="ml-1 font-bold text-orange-600">{{ $habit->participants->first()->streak ?? 0 }}</span>
                        </div>
                    </div>
                </a>
            </div>
        @empty
            <div class="md:col-span-3 text-center py-12">
                <p class="text-gray-500 text-lg">Bạn chưa tham gia thói quen nào.</p>
                <p class="text-gray-400 mt-2">Hãy tạo một thói quen mới để bắt đầu hành trình của bạn!</p>
            </div>
        @endforelse
    </div>
</div>