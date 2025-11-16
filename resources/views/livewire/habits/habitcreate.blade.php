<div class="container mx-auto p-6 bg-white rounded-lg shadow-md">
<h2 class="text-2xl font-bold mb-6 text-teal-600">Tạo thói quen mới</h2>

    @if (session()->has('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    <form wire:submit.prevent="save" class="space-y-6">
        <div>
            <label for="title" class="block text-sm font-medium text-gray-700">Tên thói quen</label>
            <input wire:model="title" id="title" type="text" placeholder="Ví dụ: Đọc sách 30 phút" class="mt-1 w-full border rounded-md px-3 py-2 focus:ring-teal-500 focus:border-teal-500 @error('title') border-red-500 @enderror" />
            @error('title') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div>
            <label for="description" class="block text-sm font-medium text-gray-700">Mô tả</label>
            <textarea wire:model="description" id="description" rows="3" placeholder="Mô tả ngắn về thói quen của bạn..." class="mt-1 w-full border rounded-md px-3 py-2 focus:ring-teal-500 focus:border-teal-500 @error('description') border-red-500 @enderror"></textarea>
            @error('description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div>
            <label for="image" class="block text-sm font-medium text-gray-700">Ảnh bìa</label>
            <input wire:model="image" id="image" type="file" class="mt-1 w-full border rounded-md px-3 py-2 @error('image') border-red-500 @enderror">
            @if ($image)
                <div class="mt-2">
                    <p>Xem trước:</p>
                    <img src="{{ $image->temporaryUrl() }}" class="w-48 h-auto rounded-md">
                </div>
            @endif
            @error('image') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

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

        <div>
            <label for="type" class="block text-sm font-medium text-gray-700">Loại</label>
            <select wire:model.live="type" id="type" class="mt-1 w-full border rounded-md px-3 py-2 focus:ring-teal-500 focus:border-teal-500 @error('type') border-red-500 @enderror">
                <option value="">Chọn loại</option>
                <option value="personal">Cá nhân</option>
                <option value="group">Nhóm</option>
            </select>
            @error('type') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        @if ($type === 'group')
        <div class="space-y-4 border-t border-gray-200 pt-6 mt-6 bg-gray-50 p-4 rounded-lg">
                <h3 class="text-lg font-medium text-gray-900">Cài đặt nhóm</h3>
                <div class="flex items-start">
                    <div class="flex items-center h-5">
                        <input wire:model="allow_request_join" id="allow_request_join" type="checkbox" class="focus:ring-teal-500 h-4 w-4 text-teal-600 border-gray-300 rounded">                    </div>
                    <div class="ml-3 text-sm">
                        <label for="allow_request_join" class="font-medium text-gray-700">Cho phép người khác yêu cầu tham gia</label>
                    </div>
                </div>
                <div class="flex items-start">
                    <div class="flex items-center h-5">
                        <input wire:model="allow_member_invite" id="allow_member_invite" type="checkbox" class="focus:ring-teal-500 h-4 w-4 text-teal-600 border-gray-300 rounded">
                    </div>
                    <div class="ml-3 text-sm">
                        <label for="allow_member_invite" class="font-medium text-gray-700">Cho phép thành viên mời người khác</label>
                    </div>
                </div>
            </div>
        @endif

        <div class="text-right">
            <button type="submit" class="bg-teal-600 text-white px-6 py-2 rounded-lg hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500">
                Lưu
            </button>
        </div>
    </form>
</div>
