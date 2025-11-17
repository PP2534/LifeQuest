<div class="container mx-auto p-8 bg-white rounded-2xl shadow-xl">
<h2 class="text-2xl font-semibold mb-4 text-indigo-600">Tạo thói quen mới</h2>

    @if (session()->has('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg relative mb-6" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    <form wire:submit.prevent="save" class="space-y-6">
        <div>
            <label for="title" class="block text-sm font-medium text-gray-700">Tên thói quen</label>
            <input wire:model="title" id="title" type="text" placeholder="Ví dụ: Đọc sách 30 phút mỗi ngày"
                   class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md text-sm shadow-sm placeholder-gray-400
                          focus:outline-none focus:border-teal-500 focus:ring-1 focus:ring-teal-500
                          @error('title') border-red-500 ring-red-500 @enderror" />
            @error('title') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div>
            <label for="description" class="block text-sm font-medium text-gray-700">Mô tả</label>
            <textarea wire:model="description" id="description" rows="3" placeholder="Mô tả ngắn về mục tiêu và lợi ích của thói quen..."
                      class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md text-sm shadow-sm placeholder-gray-400
                             focus:outline-none focus:border-teal-500 focus:ring-1 focus:ring-teal-500
                             @error('description') border-red-500 ring-red-500 @enderror"></textarea>
            @error('description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div>
            <label for="image" class="block text-sm font-medium text-gray-700">Ảnh bìa</label>
            <input wire:model="image" id="image" type="file" accept="image/*"
                   class="mt-1 block w-full text-sm text-gray-500
                          file:mr-4 file:py-2 file:px-4
                          file:rounded-full file:border-0
                          file:text-sm file:font-semibold
                          file:bg-teal-50 file:text-teal-700
                          hover:file:bg-teal-100">
            @if ($image)
                <div class="mt-2">
                    <p>Xem trước:</p>
                    <img src="{{ $image->temporaryUrl() }}" class="w-48 h-auto rounded-md">
                </div>
            @endif
            @error('image') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>


        <div>
            <label for="type" class="block text-sm font-medium text-gray-700">Loại</label>
            <select wire:model.live="type" id="type"
                    class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md text-sm shadow-sm
                           focus:outline-none focus:border-teal-500 focus:ring-1 focus:ring-teal-500
                           @error('type') border-red-500 ring-red-500 @enderror">
                <option value="">Chọn loại</option>
                <option value="personal">Cá nhân</option>
                <option value="group">Nhóm</option>
            </select>
            @error('type') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        {{-- Yêu cầu bằng chứng --}}
        <div class="flex items-start pt-4 border-t">
            <div class="flex items-center h-5">
                <input wire:model="need_proof" id="need_proof" type="checkbox" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
            </div>
            <div class="ml-3 text-sm">
                <label for="need_proof" class="font-medium text-gray-700">Yêu cầu hình ảnh bằng chứng</label>
                <p class="text-gray-500">Nếu được chọn, người tham gia sẽ cần tải lên hình ảnh để đánh dấu là đã hoàn thành.</p>
            </div>
        </div>

        {{-- Cài đặt cho nhóm --}}
        @if ($type === 'group')
            <div class="space-y-4 border-t pt-4">
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
            <button type="submit" class="inline-flex justify-center py-2 px-6 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-teal-600 hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500">
                Lưu
            </button>
        </div>
    </form>
</div>
