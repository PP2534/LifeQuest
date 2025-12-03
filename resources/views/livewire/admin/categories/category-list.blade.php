<div>
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-teal-600">Quản lý Danh mục</h1>
        <!-- <a href="{{ route('admin.dashboard') }}" wire:navigate class="inline-block bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold px-4 py-2 rounded-lg shadow">
            Quay lại Dashboard
        </a> -->
    </div>

    @if (session()->has('message'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('message') }}</span>
            <span class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="this.parentElement.style.display='none';">
                <svg class="fill-current h-6 w-6 text-green-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><title>Close</title><path d="M14.348 14.849a1.2 1.2 0 01-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 11-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 111.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 111.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 010 1.698z"/></svg>
            </span>
        </div>
    @endif

    <div class="bg-white shadow-md rounded-lg p-6 mb-8">
        <h2 class="text-2xl font-semibold text-gray-800 mb-4">Thêm Danh mục Mới</h2>
        <form wire:submit.prevent="addCategory" class="space-y-4">
            <div>
                <label for="newCategoryName" class="block text-sm font-medium text-gray-700">Tên Danh mục</label>
                <input type="text" id="newCategoryName" wire:model.live="newCategoryName" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500">
                @error('newCategoryName') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="newCategorySlug" class="block text-sm font-medium text-gray-700">Slug</label>
                <input type="text" id="newCategorySlug" wire:model="newCategorySlug" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500">
                @error('newCategorySlug') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="newCategoryIcon" class="block text-sm font-medium text-gray-700">Icon (Tùy chọn)</label>
                <input type="file" id="newCategoryIcon" wire:model="newCategoryIcon" class="mt-1 block w-full text-gray-700 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100">
                @error('newCategoryIcon') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                @if ($newCategoryIcon)
                    <p class="mt-2 text-sm text-gray-500">Xem trước Icon:</p>
                    <img src="{{ $newCategoryIcon->temporaryUrl() }}" class="mt-2 h-20 w-20 object-cover rounded-full">
                @endif
            </div>

            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-teal-600 hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500">
                Thêm Danh mục
            </button>
        </form>
    </div>

    <div class="bg-white shadow-md rounded-lg p-6">
        <h2 class="text-2xl font-semibold text-gray-800 mb-4">Danh sách Danh mục</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tên</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Slug</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Icon</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hành động</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($categories as $category)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $category->id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $category->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $category->slug }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if ($category->icon)
                                    <img src="{{ asset('storage/' . $category->icon) }}" alt="{{ $category->name }} icon" class="h-10 w-10 object-cover rounded-full">
                                @else
                                    Không có Icon
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="{{ route('admin.categories.edit', $category->id) }}" wire:navigate class="text-teal-600 hover:text-teal-900 mr-3">Sửa</a>
                                <button wire:click="deleteCategory({{ $category->id }})" wire:confirm="Bạn có chắc chắn muốn xóa danh mục này không?" class="text-red-600 hover:text-red-900">Xóa</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-center" colspan="5">Không có danh mục nào.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
