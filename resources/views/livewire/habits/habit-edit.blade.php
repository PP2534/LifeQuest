<div class="container mx-auto p-8 bg-white rounded-2xl shadow-xl">
<h2 class="text-2xl font-semibold mb-4 text-teal-600">Chỉnh sửa thói quen</h2>

    <form wire:submit.prevent="update" class="space-y-6">
        {{-- Tên thói quen --}}
        <div>
            <label for="title" class="block text-sm font-medium text-gray-700">Tên thói quen <span class="text-red-500" aria-hidden="true">*</span><span class="sr-only">Bắt buộc</span></label>
            <input wire:model="title" id="title" type="text"
                   class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md text-sm shadow-sm placeholder-gray-400
                          focus:outline-none focus:border-teal-500 focus:ring-1 focus:ring-teal-500
                          @error('title') border-red-500 ring-red-500 @enderror" />
            @error('title') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        {{-- Mô tả --}}
        <div>
            <label for="description" class="block text-sm font-medium text-gray-700">Mô tả</label>
            <textarea wire:model="description" id="description" rows="3"
                      class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md text-sm shadow-sm placeholder-gray-400
                             focus:outline-none focus:border-teal-500 focus:ring-1 focus:ring-teal-500
                             @error('description') border-red-500 ring-red-500 @enderror"></textarea>
            @error('description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        {{-- Ảnh bìa --}}
        <div>
            <label for="image" class="block text-sm font-medium text-gray-700">Thay đổi ảnh bìa (tùy chọn)</label>
            <input wire:model="image" id="image" type="file" accept="image/*"
                   class="mt-1 block w-full text-sm text-gray-500
                          file:mr-4 file:py-2 file:px-4
                          file:rounded-full file:border-0
                          file:text-sm file:font-semibold
                          file:bg-teal-50 file:text-teal-700
                          hover:file:bg-teal-100">
            
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

       

        {{-- Loại thói quen --}}
        <div>
            <label for="type" class="block text-sm font-medium text-gray-700">Loại <span class="text-red-500" aria-hidden="true">*</span><span class="sr-only">Bắt buộc</span></label>
            <select wire:model.live="type" id="type"
                    class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md text-sm shadow-sm
                           focus:outline-none focus:border-teal-500 focus:ring-1 focus:ring-teal-500
                           @error('type') border-red-500 ring-red-500 @enderror">
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
            <div class="space-y-4 border-t border-gray-200 pt-6 mt-6 bg-gray-50 p-4 rounded-lg">
                <h3 class="text-lg font-medium text-gray-900">Cài đặt nhóm</h3>
                <div class="flex items-start">
                    <div class="flex items-center h-5">
                        <input wire:model="allow_request_join" id="allow_request_join" type="checkbox" class="focus:ring-teal-500 h-4 w-4 text-teal-600 border-gray-300 rounded">
                    </div>
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

        {{-- Nút Cập nhật & Hủy --}}
        <div class="flex justify-end items-center space-x-4">
            <a href="{{ route('habits.show', $habit->id) }}" wire:navigate class="inline-flex justify-center py-2 px-6 border border-teal-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Hủy
            </a>
            <button type="submit" wire:loading.attr="disabled" wire:target="update" class="inline-flex justify-center py-2 px-6 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-teal-600 hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500">
                <span wire:loading wire:target="update">Đang lưu...</span>
                <span wire:loading.remove wire:target="update">Cập nhật</span>
            </button>
        </div>
    </form>
</div>