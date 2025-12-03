<div>
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-teal-600">Quản lý Thử thách</h1>
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

    <div class="bg-white shadow-md rounded-lg p-6">
        <div class="flex justify-between mb-4">
            <input type="text" wire:model.live="search" placeholder="Tìm kiếm thử thách hoặc người tạo..." class="w-1/3 rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500">
            <select wire:model.live="perPage" class="rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500">
                <option value="10">10 mỗi trang</option>
                <option value="20">20 mỗi trang</option>
                <option value="50">50 mỗi trang</option>
            </select>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer" wire:click="sortBy('id')">
                            ID
                            @if ($sortField == 'id')
                                <span class="ml-1">{{ $sortAsc ? '&uarr;' : '&darr;' }}</span>
                            @endif
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer" wire:click="sortBy('title')">
                            Tiêu đề
                            @if ($sortField == 'title')
                                <span class="ml-1">{{ $sortAsc ? '&uarr;' : '&darr;' }}</span>
                            @endif
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Người tạo</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Danh mục</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer" wire:click="sortBy('status')">
                            Trạng thái
                            @if ($sortField == 'status')
                                <span class="ml-1">{{ $sortAsc ? '&uarr;' : '&darr;' }}</span>
                            @endif
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hành động</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($challenges as $challenge)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $challenge->id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <a href="{{ route('challenges.show', $challenge->id) }}" target="_blank" class="text-teal-600 hover:text-teal-900">
                                    {{ $challenge->title }}
                                </a>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $challenge->creator->name ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @foreach ($challenge->categories as $category)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-teal-100 text-teal-800">
                                        {{ $category->name }}
                                    </span>
                                @endforeach
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <select wire:change="updateChallengeStatus({{ $challenge->id }}, $event.target.value)" class="rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500">
                                    <!-- <option value="pending" {{ $challenge->status == 'pending' ? 'selected' : '' }}>Đang chờ duyệt</option> -->
                                    <option value="active" {{ $challenge->status == 'active' ? 'selected' : '' }}>Hoạt động</option>
                                    <option value="block" {{ $challenge->status == 'block' ? 'selected' : '' }}>Khóa</option>
                                    <!-- <option value="completed" {{ $challenge->status == 'completed' ? 'selected' : '' }}>Hoàn thành</option> -->
                                </select>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="{{ route('challenges.show', $challenge->id) }}" target="_blank" class="text-blue-600 hover:text-blue-900 mr-3">Xem</a>
                                {{-- <a href="{{ route('admin.challenges.edit', $challenge->id) }}" class="text-teal-600 hover:text-teal-900">Sửa</a> --}}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-center" colspan="6">Không có thử thách nào.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $challenges->links() }}
        </div>
    </div>
</div>
