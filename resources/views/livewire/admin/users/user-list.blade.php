<div class="p-6">
    <h2 class="text-2xl font-semibold mb-4">Danh sách người dùng</h2>

    @if (session()->has('message'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('message') }}</span>
        </div>
    @endif

    <table class="table-auto w-full border-collapse border">
        <thead class="bg-gray-100">
            <tr>
                <th class="border p-2">STT</th>
                <th class="border p-2">Tên</th>
                <th class="border p-2">Email</th>
                <th class="border p-2">Địa điểm</th>
                <th class="border p-2">Trạng thái</th>
                <th class="border p-2">Quyền</th>
                <th class="border p-2">Hành động</th>
            </tr>
        </thead>

        <tbody>
            @forelse($users as $index => $user)
            <tr class="{{ auth()->id() === $user->id ? 'bg-teal-50' : '' }}">
                <td class="border p-2">{{ $users->firstItem() + $index }}</td>
                <td class="border p-2">{{ $user->name }}</td>
                <td class="border p-2">{{ $user->email }}</td>
                <td class="border p-2">
                    @if ($user->ward)
                        {{ $user->ward->name_with_type }}, {{ $user->ward->province->full_name }}
                    @else
                        Chưa cập nhật
                    @endif
                </td>
                <td class="border p-2">
                    <select wire:change="updateUserStatus({{ $user->id }}, $event.target.value)"
                        class="border-gray-300 rounded focus:border-teal-500 focus:ring-teal-500 {{ $user->status === 'banned' ? 'bg-red-200 text-red-800' : 'bg-green-200 text-green-800' }}">
                        <option value="active" @if($user->status == 'active') selected @endif>Hoạt động</option>
                        <option value="banned" @if($user->status == 'banned') selected @endif>Khóa</option>
                    </select>
                </td>
                <td class="border p-2">
                    <select wire:change="updateUserRole({{ $user->id }}, $event.target.value)"
                        class="border-gray-300 rounded focus:border-indigo-500 focus:ring-indigo-500 {{ $user->role === 'admin' ? 'bg-indigo-100 text-indigo-800' : 'bg-gray-100 text-gray-800' }}"
                        @disabled(auth()->id() === $user->id)
                    >
                        <option value="user" @selected($user->role === 'user')>Người dùng</option>
                        <option value="admin" @selected($user->role === 'admin')>Quản trị</option>
                    </select>
                </td>
                <td class="border p-2">
                    <a href="{{ route('admin.users.edit', $user) }}" class="text-blue-600 hover:text-blue-900" wire:navigate>Sửa</a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center p-4 text-gray-500">Không có người dùng</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="mt-4">
        {{ $users->links() }}
    </div>
</div>
