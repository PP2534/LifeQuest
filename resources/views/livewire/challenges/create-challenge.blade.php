<div> <main class="container mx-auto px-4 py-12 max-w-3xl">
        <h1 class="text-3xl font-bold text-teal-600 mb-8">Tạo Thử Thách Mới</h1>
        
        <form wire:submit="save" class="bg-white shadow rounded-lg p-6 space-y-6">
            
            <div>
                <label for="title" class="block text-sm font-medium">Tiêu đề</label>
                <input type="text" id="title" wire:model="title" class="w-full border rounded px-3 py-2 mt-1" placeholder="Ví dụ: 30 ngày tập thể dục">
                @error('title') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="description" class="block text-sm font-medium">Mô tả</label>
                <textarea id="description" rows="5" wire:model="description" class="w-full border rounded px-3 py-2 mt-1" placeholder="Mô tả chi tiết về thử thách..."></textarea>
                @error('description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="image" class="block text-sm font-medium">Ảnh minh họa</label>
                <input type="file" id="image" wire:model="image" class="w-full border rounded px-3 py-2 mt-1">
                
                <div wire:loading wire:target="image" class="text-sm text-gray-500 mt-1">Đang tải ảnh lên...</div>

                @if ($image)
                    <div class="mt-2">
                        <span class="block text-sm font-medium text-gray-700">Xem trước:</span>
                        <img src="{{ $image->temporaryUrl() }}" class="w-full h-48 object-cover rounded-lg mt-1">
                    </div>
                @endif
                
                @error('image') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="province" class="block text-sm font-medium text-gray-700">Tỉnh / Thành phố</label>
                    <select id="province" 
                            wire:model.live="selectedProvinceId" 
                            class="w-full border-gray-300 rounded-lg px-3 py-2 mt-1 focus:ring-teal-500 focus:border-teal-500">
                        <option value="">-- Chọn Tỉnh/Thành --</option>
                        @foreach($provinces as $province)
                            <option value="{{ $province->id }}">{{ $province->name }}</option>
                        @endforeach
                    </select>
                    @error('selectedProvinceId') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="ward" class="block text-sm font-medium text-gray-700">Phường / Xã</label>
                    <select id="ward" 
                            wire:model="ward_id" 
                            class="w-full border-gray-300 rounded-lg px-3 py-2 mt-1 focus:ring-teal-500 focus:border-teal-500"
                            @if($wards->isEmpty()) disabled @endif> 
                            <option value="">-- Chọn Phường/Xã --</option>
                        @foreach($wards as $ward)
                            <option value="{{ $ward->id }}">{{ $ward->name }}</option>
                        @endforeach
                    </select>
                    
                    <div wire:loading wire:target="selectedProvinceId" class="text-xs text-teal-600 mt-1">
                        Đang tải danh sách xã...
                    </div>
                    
                    @error('ward_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
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
                    @error('duration_days') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
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
                @error('type') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
            <div class="flex items-start space-x-3 p-4 border border-gray-200 rounded-lg bg-gray-50">
                <div class="flex items-center h-5">
                    <input id="need_proof" 
                           wire:model="need_proof" 
                           type="checkbox" 
                           class="focus:ring-teal-500 h-5 w-5 text-teal-600 border-gray-300 rounded">
                </div>
                <div class="ml-3 text-sm">
                    <label for="need_proof" class="font-medium text-gray-700">Yêu cầu ảnh minh chứng (Proof)</label>
                    <p class="text-gray-500">Nếu chọn, người tham gia bắt buộc phải tải ảnh lên mỗi khi điểm danh hàng ngày.</p>
                </div>                       
            </div>
            <div class="flex items-start space-x-3 p-4 border border-gray-200 rounded-lg bg-gray-50">
                <div class="flex items-start space-x-3">
                    <div class="flex items-center h-5">
                        <input id="allow_member_invite" wire:model="allow_member_invite" type="checkbox" class="focus:ring-teal-500 h-5 w-5 text-teal-600 border-gray-300 rounded">
                    </div>
                    <div class="ml-3 text-sm">
                        <label for="allow_member_invite" class="font-medium text-gray-700">Cho phép thành viên mời</label>
                        <p class="text-gray-500 text-xs">Nếu tắt, chỉ có Người tạo (Bạn) mới được quyền mời người khác.</p>
                    </div>
                </div>
            </div>
            <div class="text-right">
                <button type="submit" class="bg-teal-600 text-white px-6 py-2 rounded-lg hover:bg-teal-700 font-semibold">
                    <span wire:loading.remove wire:target="save">
                        Lưu Thử Thách
                    </span>
                    <span wire:loading wire:target="save">
                        Đang lưu...
                    </span>
                </button>
            </div>
        </form>
    </main>

    </div>