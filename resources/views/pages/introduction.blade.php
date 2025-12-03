<x-app-layout>
    <section class="space-y-2 mb-10">
        <p class="text-sm uppercase tracking-wide text-teal-600 font-semibold">Giới thiệu</p>
        <h1 class="text-3xl font-bold text-gray-900">LifeQuest là gì?</h1>
        <p class="text-gray-500 max-w-3xl">Hành trình phát triển bản thân có thể được đo đếm, khích lệ và chia sẻ cùng cộng đồng.</p>
    </section>

    <section class="space-y-12">
        <article class="bg-white shadow-sm rounded-2xl border border-gray-100 p-8">
            <h2 class="text-2xl font-semibold text-gray-900">Sứ mệnh</h2>
            <p class="mt-4 text-gray-600 leading-relaxed">
                LifeQuest giúp bạn xây dựng thói quen bền vững và hoàn thành thử thách bằng cách kết hợp kế hoạch cá nhân, hệ thống điểm XP,
                bảng xếp hạng linh hoạt và cộng đồng truyền cảm hứng. Nền tảng được thiết kế để người dùng Việt dễ dàng tiếp cận nhưng vẫn đủ sâu cho người yêu thích dữ liệu.
            </p>
        </article>

        <article class="grid md:grid-cols-2 gap-8">
            <div class="bg-white border border-gray-100 rounded-2xl p-8 shadow-sm">
                <h3 class="text-xl font-semibold text-gray-900">Điểm nổi bật</h3>
                <ul class="mt-4 space-y-3 text-gray-600 list-disc list-inside">
                    <li>Theo dõi thử thách cá nhân, nhóm hoặc theo khu vực với biểu đồ tiến độ cập nhật theo thời gian thực.</li>
                    <li>Hệ thống XP minh bạch, thưởng cho cả người tham gia và người tạo thử thách có ảnh hưởng.</li>
                    <li>Cộng đồng tích cực với bảng tin hoạt động, theo dõi bạn bè và thông báo thông minh.</li>
                    <li>Cơ chế bảo vệ tài khoản, chỉ cho phép người dùng đang hoạt động tham gia bảng xếp hạng.</li>
                </ul>
            </div>
            <div class="bg-white border border-gray-100 rounded-2xl p-8 shadow-sm">
                <h3 class="text-xl font-semibold text-gray-900">Cam kết sản phẩm</h3>
                <dl class="mt-4 space-y-4 text-gray-600">
                    <div>
                        <dt class="font-medium text-gray-900">Trải nghiệm liền mạch</dt>
                        <dd>Giao diện Livewire tương tác nhanh, đồng bộ dữ liệu tức thì giữa web app và các widget cộng đồng.</dd>
                    </div>
                    <div>
                        <dt class="font-medium text-gray-900">Dữ liệu minh bạch</dt>
                        <dd>Mọi điểm XP đều được ghi log tại bảng <code>user_xp_logs</code>, giúp bạn truy vết lịch sử dễ dàng.</dd>
                    </div>
                    <div>
                        <dt class="font-medium text-gray-900">An toàn & riêng tư</dt>
                        <dd>Ẩn thông tin nhạy cảm theo mặc định, đồng thời cho phép bạn kiểm soát phạm vi hiển thị.</dd>
                    </div>
                </dl>
            </div>
        </article>

        <article class="bg-gradient-to-br from-teal-600 to-emerald-500 text-white rounded-3xl p-8">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-8">
                <div>
                    <h3 class="text-2xl font-semibold">Cùng LifeQuest bứt phá mỗi ngày</h3>
                    <p class="mt-3 text-teal-50 max-w-3xl">
                        Dù bạn đang luyện tập thể thao, phát triển kỹ năng hay xây dựng lối sống lành mạnh, LifeQuest cung cấp bộ công cụ cần thiết để theo dõi,
                        đo lường và ăn mừng mọi cột mốc. Hãy bắt đầu bằng cách tạo thử thách đầu tiên hoặc tham gia một cộng đồng phù hợp với mục tiêu của bạn.
                    </p>
                </div>
                <div class="bg-white/10 rounded-2xl p-6 backdrop-blur">
                    <p class="text-sm uppercase tracking-wider text-teal-50">Con số nổi bật</p>
                    <ul class="mt-4 space-y-2 text-lg">
                        <li><span class="font-semibold">+120</span> thử thách đang diễn ra</li>
                        <li><span class="font-semibold">+8.500</span> thói quen được tạo</li>
                        <li><span class="font-semibold">97%</span> người dùng quay lại hằng tuần</li>
                    </ul>
                </div>
            </div>
        </article>
    </section>
</x-app-layout>
