<div class="container mx-auto p-4">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold">Danh sách thói quen</h1>
        <a href="{{ route('habits.create') }}" wire:navigate class="bg-teal-600 text-white px-4 py-2 rounded-lg hover:bg-teal-700">Tạo thói quen mới</a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        @foreach($habits as $habit)
            <a href="{{ route('habits.show', $habit) }}" wire:navigate class="p-4 border rounded shadow hover:bg-gray-50 block">
                <img src="{{ asset('storage/' . $habit->image) }}" alt="{{ $habit->title }}" class="w-full h-40 object-cover rounded">
                <h2 class="font-semibold mt-2">{{ $habit->title }}</h2>
                <p class="text-sm text-gray-600">Ngày bắt đầu: {{ $habit->start_date }}</p>
                <p class="text-sm text-gray-600">Ngày kết thúc : {{ $habit->end_date }}</p>
                @if($habit->participants->isNotEmpty() && !is_null($habit->participants->first()->streak))
                    <p class="text-sm text-gray-600">Streak: {{ $habit->participants->first()->streak }}</p>
                @endif
            </a>
        @endforeach
        
    </div>
</div>