<div class="container mx-auto p-6 bg-white rounded-lg shadow-md">
    <h2 class="text-2xl font-semibold mb-6 text-indigo-600">Chỉnh sửa thói quen</h2>

    <form wire:submit.prevent="update" class="space-y-6">
        {{-- Tên thói quen --}}
        <div>
            <label for="title" class="block text-sm font-medium text-gray-700">Tên thói quen</label>
            <input wire:model="title" id="title" type="text" class="mt-1 w-full border rounded-md px-3 py-2 focus:ring-teal-500 focus:border-teal-500 @error('title') border-red-500 @enderror" />
            @error('title') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        {{-- Mô tả --}}
        <div>
            <label for="description" class="block text-sm font-medium text-gray-700">Mô tả</label>
            <textarea wire:model="description" id="description" rows="3" class="mt-1 w-full border rounded-md px-3 py-2 focus:ring-teal-500 focus:border-teal-500 @error('description') border-red-500 @enderror"></textarea>
            @error('description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        {{-- Ảnh bìa --}}
        <div>
            <label for="image" class="block text-sm font-medium text-gray-700">Thay đổi ảnh bìa (tùy chọn)</label>
            <input wire:model="image" id="image" type="file" class="mt-1 w-full border rounded-md px-3 py-2 @error('image') border-red-500 @enderror">
            
            <div class="mt-2">
                @if ($image)
                    <p>Xem trước ảnh mới:</p>
                    <img src="{{ $image->temporaryUrl() }}" class="w-48 h-auto rounded-md">
                @elseif ($existingImage)
                    <p>Ảnh hiện tại:</p>
                    <img src="{{ asset('storage/' . $existingImage) }}" class="w-48 h-auto rounded-md">
                @endif
            </div>
            @error('image') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        {{-- Ngày bắt đầu và kết thúc --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="start_date" class="block text-sm font-medium text-gray-700">Ngày bắt đầu</label>
                <input wire:model="start_date" id="start_date" type="date" class="mt-1 w-full border rounded-md px-3 py-2 focus:ring-teal-500 focus:border-teal-500 @error('start_date') border-red-500 @enderror" />
                @error('start_date') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
            <div>
                <label for="end_date" class="block text-sm font-medium text-gray-700">Ngày kết thúc</label>
                <input wire:model="end_date" id="end_date" type="date" class="mt-1 w-full border rounded-md px-3 py-2 focus:ring-teal-500 focus:border-teal-500 @error('end_date') border-red-500 @enderror" />
                @error('end_date') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
        </div>

        {{-- Loại thói quen --}}
        <div>
            <label for="type" class="block text-sm font-medium text-gray-700">Loại</label>
            <select wire:model.live="type" id="type" class="mt-1 w-full border rounded-md px-3 py-2 focus:ring-teal-500 focus:border-teal-500 @error('type') border-red-500 @enderror">
                <option value="personal">Cá nhân</option>
                <option value="group">Nhóm</option>
            </select>
            @error('type') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        {{-- Cài đặt cho nhóm --}}
        @if ($type === 'group')
            <div class="space-y-4 border-t pt-4 mt-4">
                <h3 class="text-lg font-medium text-gray-900">Cài đặt nhóm</h3>
                <div class="flex items-start">
                    <div class="flex items-center h-5">
                        <input wire:model="allow_request_join" id="allow_request_join" type="checkbox" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                    </div>
                    <div class="ml-3 text-sm">
                        <label for="allow_request_join" class="font-medium text-gray-700">Cho phép người khác yêu cầu tham gia</label>
                    </div>
                </div>
                <div class="flex items-start">
                    <div class="flex items-center h-5">
                        <input wire:model="allow_member_invite" id="allow_member_invite" type="checkbox" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                    </div>
                    <div class="ml-3 text-sm">
                        <label for="allow_member_invite" class="font-medium text-gray-700">Cho phép thành viên mời người khác</label>
                    </div>
                </div>
            </div>
        @endif

        {{-- Nút Cập nhật --}}
        <div class="text-right">
            <button type="submit" class="bg-teal-600 text-white px-6 py-2 rounded-lg hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500">
                Cập nhật
            </button>
        </div>
    </form>
</div>