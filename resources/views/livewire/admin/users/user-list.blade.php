<div class="p-6">
    <h2 class="text-2xl font-semibold mb-4">Danh sách người dùng</h2>

    <table class="table-auto w-full border-collapse border">
        <thead class="bg-gray-100">
            <tr>
                <th class="border p-2">STT</th>
                <th class="border p-2">Tên</th>
                <th class="border p-2">Email</th>
                <th class="border p-2">Sở thích</th>
                <th class="border p-2">Địa điểm</th>
            </tr>
        </thead>

        <tbody>
            @forelse($users as $index => $user)
            <tr>
                <td class="border p-2">{{ $users->firstItem() + $index }}</td>
                <td class="border p-2">{{ $user->name }}</td>
                <td class="border p-2">{{ $user->email }}</td>
                <td class="border p-2">{{ $user->interests ?? 'Chưa cập nhật' }}</td>
                <td class="border p-2">{{ $user->location ?? 'Chưa cập nhật' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center p-4 text-gray-500">Không có người dùng</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Phân trang -->
    <div class="mt-4">
        {{ $users->links() }}
    </div>
</div>
