<div class="p-6">
    <h2 class="text-2xl font-semibold mb-4">Chỉnh sửa người dùng: {{ $user->name }}</h2>

    <form wire:submit.prevent="save">
        <div class="space-y-4">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Tên</label>
                <input type="text" wire:model.defer="name" id="name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500">
                @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="province_id" class="block text-sm font-medium text-gray-700">Tỉnh/Thành phố</label>
                    <select wire:model="province_id" id="province_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500">
                        <option value="">-- Chọn tỉnh --</option>
                        @foreach($provinces as $p)
                        <option value="{{ $p -> id }}">{{ $p ->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="ward_id" class="block text-sm font-medium text-gray-700">Phường/Xã</label>
                    <select wire:model="ward_id" id="ward_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500" >
                        <option value="">-- Chọn phường/xã --</option>
                        @foreach($wards as $w) 
                        <option value="{{ $w->id }}">{{ $w->name }}</option>
                        @endforeach
                    </select>
                    @error('ward_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <div class="mt-6 flex items-center justify-end gap-x-4">
             <a href="{{ route('admin.users.list') }}" wire:navigate class="text-sm font-semibold leading-6 text-gray-900">Hủy</a>
            <button type="submit" class="rounded-md bg-teal-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-teal-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-teal-600">Lưu</button>
        </div>
    </form>
</div>
