<x-app-layout>
    <section class="space-y-2 mb-10">
        <p class="text-sm uppercase tracking-wide text-teal-600 font-semibold">Chính sách</p>
        <h1 class="text-3xl font-bold text-gray-900">Chính sách bảo mật & quyền riêng tư</h1>
        <p class="text-gray-500">Có hiệu lực từ ngày 15/02/2025. Phiên bản 1.4.</p>
    </section>

    <section class="space-y-8">
        <article class="bg-white border border-gray-100 rounded-2xl shadow-sm p-8">
            <h2 class="text-2xl font-semibold text-gray-900">1. Dữ liệu chúng tôi thu thập</h2>
            <p class="mt-3 text-gray-600">LifeQuest chỉ thu thập những thông tin cần thiết để vận hành thử thách, bảng xếp hạng và chức năng cộng đồng.</p>
            <ul class="mt-4 space-y-2 list-disc list-inside text-gray-600">
                <li>Thông tin tài khoản: họ tên, email, số điện thoại (nếu cung cấp), ảnh đại diện.</li>
                <li>Hoạt động ứng dụng: thói quen, thử thách bạn tạo/tham gia, điểm XP và log tương tác.</li>
                <li>Dữ liệu kỹ thuật: địa chỉ IP, thiết bị, trình duyệt nhằm bảo vệ tài khoản.</li>
            </ul>
        </article>

        <article class="bg-white border border-gray-100 rounded-2xl shadow-sm p-8">
            <h2 class="text-2xl font-semibold text-gray-900">2. Cách sử dụng & lưu trữ</h2>
            <p class="mt-3 text-gray-600">Dữ liệu được lưu trữ tại trung tâm dữ liệu đạt chuẩn ISO 27001, sao lưu mỗi 6 giờ và mã hóa khi truyền.</p>
            <ul class="mt-4 space-y-2 list-disc list-inside text-gray-600">
                <li>Chúng tôi dùng thông tin đăng nhập để xác thực và áp dụng middleware <code>active.user</code>.</li>
                <li>Điểm XP và log hoạt động phục vụ bảng xếp hạng. Quy tắc đầy đủ được trình bày trong <a href="{{ route('pages.ranking-rules') }}" class="text-teal-600 font-semibold hover:underline" wire:navigate>Quy tắc xếp hạng LifeQuest</a>.</li>
                <li>Dữ liệu giao tiếp (email, notification token) hỗ trợ gửi thông báo quan trọng.</li>
            </ul>
        </article>

        <article class="bg-white border border-gray-100 rounded-2xl shadow-sm p-8">
            <h2 class="text-2xl font-semibold text-gray-900">3. Quyền của người dùng</h2>
            <p class="mt-3 text-gray-600">Bạn có thể quản lý dữ liệu trực tiếp trong trang hồ sơ hoặc liên hệ đội ngũ LifeQuest.</p>
            <ul class="mt-4 space-y-2 list-decimal list-inside text-gray-600">
                <li>Yêu cầu xuất toàn bộ dữ liệu cá nhân dưới định dạng JSON/CSV.</li>
                <li>Khóa hoặc xóa tài khoản. Khi xóa, các log XP được ẩn khỏi bảng xếp hạng nhưng vẫn lưu trữ 30 ngày cho mục đích kiểm toán.</li>
                <li>Bật/tắt hiển thị hồ sơ công khai và giới hạn lời mời thử thách.</li>
            </ul>
        </article>

        <article class="bg-white border border-gray-100 rounded-2xl shadow-sm p-8">
            <h2 class="text-2xl font-semibold text-gray-900">4. Chia sẻ với bên thứ ba</h2>
            <p class="mt-3 text-gray-600">LifeQuest không bán dữ liệu cá nhân. Chúng tôi chỉ chia sẻ tối thiểu với các nhà cung cấp:</p>
            <ul class="mt-4 space-y-2 list-disc list-inside text-gray-600">
                <li>Nhà cung cấp hạ tầng (Viettel Cloud, AWS) để lưu trữ và phân phối nội dung.</li>
                <li>Dịch vụ gửi email (Amazon SES) để truyền thông chính thức.</li>
                <li>Các cơ quan quản lý khi có yêu cầu hợp pháp.</li>
            </ul>
        </article>

        <article class="bg-white border border-gray-100 rounded-2xl shadow-sm p-8">
            <h2 class="text-2xl font-semibold text-gray-900">5. Liên hệ & cập nhật</h2>
            <p class="mt-3 text-gray-600">Nếu có thắc mắc, vui lòng liên hệ <a href="mailto:privacy@lifequest.vn" class="text-teal-600 font-semibold">privacy@lifequest.vn</a> hoặc đọc thêm tại <a href="{{ route('pages.contact') }}" class="text-teal-600 font-semibold hover:underline" wire:navigate>trang Liên hệ</a>.</p>
            <p class="mt-3 text-gray-600">Chính sách có thể được cập nhật. Khi thay đổi đáng kể, chúng tôi sẽ thông báo qua email và banner trong ứng dụng.</p>
        </article>
    </section>
</x-app-layout>
