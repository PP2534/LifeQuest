<div class="container mx-auto p-4">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Danh sách thói quen</h1>
        <a href="{{ route('habits.create') }}" wire:navigate class="bg-teal-600 text-white px-4 py-2 rounded-lg hover:bg-teal-700">Tạo thói quen mới</a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($habits as $habit)
            <div class="bg-white rounded-lg shadow-md overflow-hidden flex flex-col">
                <a href="{{ route('habits.show', $habit) }}" wire:navigate>
                    <img src="{{ $habit->image ? asset('storage/' . $habit->image) : 'https://ui-avatars.com/api/?name='.urlencode($habit->title).'&background=random&color=fff' }}" alt="{{ $habit->title }}" class="w-full h-40 object-cover">
                </a>
                <div class="p-4 flex flex-col flex-grow">
                    <div class="flex justify-between items-center mb-2">
                        <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $habit->type === 'group' ? 'bg-indigo-100 text-indigo-800' : 'bg-green-100 text-green-800' }}">
                            {{ $habit->type === 'group' ? 'Nhóm' : 'Cá nhân' }}
                        </span>
                        @if($habit->participants->isNotEmpty())
                        <span class="text-sm font-semibold text-orange-500 flex items-center">
                            🔥 {{ $habit->participants->first()->streak }}
                        </span>
                        @endif
                    </div>
                    <a href="{{ route('habits.show', $habit) }}" wire:navigate class="flex-grow">
                        <h2 class="font-bold text-lg text-gray-800 hover:text-teal-600 mb-2">{{ $habit->title }}</h2>
                    </a>
                    <div class="text-sm text-gray-600 mt-auto space-y-1 pt-2 border-t border-gray-200">
                        <p><strong>Ngày bắt đầu:</strong> {{ $habit->start_date }}</p>
                        <p>
                            <strong>Ngày kết thúc:</strong> 
                            @if($habit->end_date)
                                {{ $habit->end_date }}
                            @else
                                <span class="font-semibold">Vĩnh viễn</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-1 md:col-span-2 lg:col-span-3 text-center py-12 bg-white rounded-lg shadow-md">
                <p class="text-gray-500 text-lg">Bạn chưa tham gia thói quen nào.</p>
                <a href="{{ route('habits.create') }}" wire:navigate class="mt-4 inline-block bg-teal-600 text-white px-6 py-2 rounded-lg hover:bg-teal-700">Tạo thói quen đầu tiên</a>
            </div>
        @endforelse
    </div>
</div>