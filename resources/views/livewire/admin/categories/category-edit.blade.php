<div>
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-teal-600">Sửa Danh mục: {{ $name }}</h1>
        <a href="{{ route('admin.categories.list') }}" wire:navigate class="inline-block bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold px-4 py-2 rounded-lg shadow">
            Quay lại Danh sách Danh mục
        </a>
    </div>

    @if (session()->has('message'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('message') }}</span>
            <span class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="this.parentElement.style.display='none';">
                <svg class="fill-current h-6 w-6 text-green-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><title>Close</title><path d="M14.348 14.849a1.2 1.2 0 01-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 11-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 111.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 111.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 010 1.698z"/></svg>
            </span>
        </div>
    @endif

    <div class="bg-white shadow-md rounded-lg p-6">
        <form wire:submit.prevent="updateCategory" class="space-y-4">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Tên Danh mục</label>
                <input type="text" id="name" wire:model.live="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500">
                @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="slug" class="block text-sm font-medium text-gray-700">Slug</label>
                <input type="text" id="slug" wire:model="slug" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500">
                @error('slug') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="newIcon" class="block text-sm font-medium text-gray-700">Icon Mới (Tùy chọn)</label>
                <input type="file" id="newIcon" wire:model="newIcon" class="mt-1 block w-full text-gray-700 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100">
                @error('newIcon') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                @if ($newIcon)
                    <p class="mt-2 text-sm text-gray-500">Xem trước Icon Mới:</p>
                    <img src="{{ $newIcon->temporaryUrl() }}" class="mt-2 h-20 w-20 object-cover rounded-full">
                @elseif ($currentIcon)
                    <p class="mt-2 text-sm font-medium text-gray-700">Icon Hiện tại:</p>
                    <img src="{{ asset('storage/' . $currentIcon) }}" alt="Current category icon" class="mt-2 h-20 w-20 object-cover rounded-full">
                @else
                    <p class="mt-2 text-sm text-gray-500">Không có Icon hiện tại.</p>
                @endif
            </div>

            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-teal-600 hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500">
                Cập nhật Danh mục
            </button>
        </form>
    </div>
</div>
