<x-app-layout>
    <section class="space-y-2 mb-10">
        <p class="text-sm uppercase tracking-wide text-teal-600 font-semibold">Bảng xếp hạng</p>
        <h1 class="text-3xl font-bold text-gray-900">Quy tắc tính điểm & xếp hạng LifeQuest</h1>
        <p class="text-gray-500">Cập nhật ngày 15/02/2025</p>
    </section>

    <section class="space-y-10">
        <article class="bg-white border border-gray-100 rounded-2xl shadow-sm p-8">
            <h2 class="text-2xl font-semibold text-gray-900">1. Cách chấm XP</h2>
            <div class="mt-4 overflow-x-auto">
                <table class="min-w-full text-left text-gray-700 border divide-y divide-gray-200">
                    <thead class="bg-gray-50 text-gray-500 uppercase text-xs">
                        <tr>
                            <th class="px-4 py-2">Hành động</th>
                            <th class="px-4 py-2">XP</th>
                            <th class="px-4 py-2">Ghi chú</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <tr>
                            <td class="px-4 py-3">Đăng nhập mỗi ngày</td>
                            <td class="px-4 py-3 font-semibold text-teal-600">+2 XP</td>
                            <td class="px-4 py-3">Chỉ nhận 1 lần/ngày, dựa trên log <code>daily_login</code>.</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-3">Hoàn thành bất kỳ hoạt động thói quen/thử thách</td>
                            <td class="px-4 py-3 font-semibold text-teal-600">+3 XP</td>
                            <td class="px-4 py-3">Log <code>daily_activity</code>, ghi nhận sau khi cập nhật tiến độ.</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-3">Đăng bình luận trong ngày</td>
                            <td class="px-4 py-3 font-semibold text-teal-600">+1 XP</td>
                            <td class="px-4 py-3">Giới hạn 1 lần/ngày để tránh spam.</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-3">Giữ streak thói quen</td>
                            <td class="px-4 py-3 font-semibold text-teal-600">+2 → +32 XP</td>
                            <td class="px-4 py-3">Thưởng tại các mốc 7/14/21/28/35 ngày, tăng gấp đôi mỗi mốc.</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-3">Hoàn thành thử thách</td>
                            <td class="px-4 py-3 font-semibold text-teal-600">Công thức</td>
                            <td class="px-4 py-3">XP = ceil((số ngày / 3) * 1.5 + (streak / 7) * 2). Áp dụng thử thách ≥ 3 ngày.</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-3">Thử thách nổi bật của người tạo</td>
                            <td class="px-4 py-3 font-semibold text-teal-600">+5 → +20 XP</td>
                            <td class="px-4 py-3">Mốc 20 người tham gia (+5), thử thách dài &gt; 7 ngày (+5), 10 người hoàn thành (+10), 20 người hoàn thành (+20).</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </article>

        <article class="bg-white border border-gray-100 rounded-2xl shadow-sm p-8">
            <h2 class="text-2xl font-semibold text-gray-900">2. Cách xếp hạng</h2>
            <ul class="mt-4 space-y-3 text-gray-600">
                <li>Bảng xếp hạng lấy tổng XP trong cơ sở dữ liệu, chỉ tính người dùng <span class="font-semibold">trạng thái active</span>.</li>
                <li>Bộ lọc thời gian: tất cả, tuần, tháng, năm. Chúng tôi dùng khoảng thời gian theo múi giờ hệ thống (Asia/Ho_Chi_Minh).</li>
                <li>Khi nhiều người cùng tổng XP, hệ thống dùng <code>RANK()</code> (SQL window function) để xác định thứ hạng và sắp xếp tên theo alphabet.</li>
                <li>Widget Leaderboard (sidebar) hiển thị top 20 và vị trí hiện tại của bạn nếu nằm ngoài top.</li>
                <li>Trang Leaderboard đầy đủ có phân trang 50 người/lần và đồng bộ với widget.</li>
            </ul>
        </article>

        <article class="bg-white border border-gray-100 rounded-2xl shadow-sm p-8">
            <h2 class="text-2xl font-semibold text-gray-900">3. Quy tắc công bằng</h2>
            <ul class="mt-4 space-y-3 text-gray-600">
                <li>Tài khoản bị tạm khóa hoặc vi phạm <a href="{{ route('pages.privacy-policy') }}" wire:navigate class="text-teal-600 font-semibold hover:underline">chính sách bảo mật</a> sẽ bị loại khỏi bảng xếp hạng.</li>
                <li>Chúng tôi hạn chế nhận XP trùng lặp bằng cách kiểm tra log theo ngày. Mọi hành vi tự động hóa, spam hoặc gian lận sẽ bị trừ XP và ghi log.</li>
                <li>Yêu cầu điều chỉnh XP cần gửi qua <a href="{{ route('pages.contact') }}" wire:navigate class="text-teal-600 font-semibold hover:underline">trang Liên hệ</a> trong vòng 7 ngày kể từ khi phát sinh.</li>
            </ul>
        </article>

        <article class="bg-white border border-gray-100 rounded-2xl shadow-sm p-8">
            <h2 class="text-2xl font-semibold text-gray-900">4. Câu hỏi thường gặp</h2>
            <dl class="mt-4 space-y-4 text-gray-600">
                <div>
                    <dt class="font-semibold text-gray-900">Tôi đổi tên, thứ hạng có bị ảnh hưởng không?</dt>
                    <dd>Không. XP dựa trên ID người dùng nên không bị ảnh hưởng bởi thay đổi hồ sơ.</dd>
                </div>
                <div>
                    <dt class="font-semibold text-gray-900">Tôi có thể ẩn XP không?</dt>
                    <dd>Bạn có thể bật chế độ hồ sơ riêng tư, khi đó người khác chỉ thấy tên trên bảng xếp hạng nhưng không xem chi tiết.</dd>
                </div>
                <div>
                    <dt class="font-semibold text-gray-900">Thứ hạng cập nhật khi nào?</dt>
                    <dd>Leaderboard cập nhật ngay khi có log XP mới, đồng thời chạy đồng bộ định kỳ mỗi 5 phút để đảm bảo tính nhất quán.</dd>
                </div>
            </dl>
        </article>
    </section>
</x-app-layout>
