<div> <main class="container mx-auto px-4 py-12 max-w-3xl">
        
        <h1 class="text-3xl font-bold text-teal-600 mb-8">Cập nhật Thử thách</h1>
        
        <form wire:submit="update" class="bg-white shadow rounded-lg p-6 space-y-6">
            
            <div>
                <label for="title" class="block text-sm font-medium">Tiêu đề</label>
                <input type="text" id="title" wire:model="title" class="w-full border rounded px-3 py-2 mt-1">
                @error('title') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="description" class="block text-sm font-medium">Mô tả</label>
                <textarea id="description" rows="5" wire:model="description" class="w-full border rounded px-3 py-2 mt-1"></textarea>
                @error('description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="image" class="block text-sm font-medium">Ảnh minh họa (Để trống nếu không muốn thay đổi)</label>
                <input type="file" id="image" wire:model="image" class="w-full border rounded px-3 py-2 mt-1">
                
                <div wire:loading wire:target="image" class="text-sm text-gray-500 mt-1">Đang tải ảnh lên...</div>

                @if ($image)
                    <div class="mt-2">
                        <span class="block text-sm font-medium text-gray-700">Xem trước ảnh mới:</span>
                        <img src="{{ $image->temporaryUrl() }}" class="w-full h-48 object-cover rounded-lg mt-1">
                    </div>
                @elseif ($existingImageUrl)
                    <div class="mt-2">
                        <span class="block text-sm font-medium text-gray-700">Ảnh hiện tại:</span>
                        <img src="{{ $existingImageUrl }}" class="w-full h-48 object-cover rounded-lg mt-1">
                    </div>
                @endif
                
                @error('image') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium">Danh mục (Chọn ít nhất 1)</label>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-2 mt-2">
                    @foreach($allCategories as $category)
                        <label class="flex items-center space-x-2 p-2 border rounded-lg hover:bg-gray-50">
                            <input type="checkbox" wire:model="selectedCategories" value="{{ $category->id }}" class="rounded text-teal-600 focus:ring-teal-500">
                            <span class="text-sm">{{ $category->name }}</span>
                        </label>
                    @endforeach
                </div>
                @error('selectedCategories') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label for="duration_days" class="block text-sm font-medium">Thời lượng (ngày)</label>
                    <input type="number" id="duration_days" wire:model="duration_days" class="w-full border rounded px-3 py-2 mt-1" min="1">
                </div>
                <div>
                    <label for="time_mode" class="block text-sm font-medium">Chế độ thời gian</label>
                    <select id="time_mode" wire:model="time_mode" class="w-full border rounded px-3 py-2 mt-1">
                        <option value="fixed">Cố định (Fixed)</option>
                        <option value="rolling">Linh hoạt (Rolling)</option>
                    </select>
                </div>
                <div>
                    <label for="streak_mode" class="block text-sm font-medium">Chế độ chuỗi</label>
                    <select id="streak_mode" wire:model="streak_mode" class="w-full border rounded px-3 py-2 mt-1">
                        <option value="continuous">Liên tục (Continuous)</option>
                        <option value="cumulative">Tích lũy (Cumulative)</option>
                    </select>
                </div>
            </div>

            <div>
                <label for="type" class="block text-sm font-medium">Loại</label>
                <select id="type" wire:model="type" class="w-full border rounded px-3 py-2 mt-1">
                    <option value="public">Công khai (Public)</option>
                    <option value="private">Riêng tư (Private)</option>
                </select>
            </div>
            
            <div class="text-right">
                <button type="submit" class="bg-teal-600 text-white px-6 py-2 rounded-lg hover:bg-teal-700 font-semibold">
                    <span wire:loading.remove wire:target="update">
                        Cập nhật
                    </span>
                    <span wire:loading wire:target="update">
                        Đang lưu...
                    </span>
                </button>
            </div>
        </form>
    </main>
</div>