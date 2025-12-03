<x-app-layout>
    <section class="space-y-2 mb-10">
        <p class="text-sm uppercase tracking-wide text-teal-600 font-semibold">Liên hệ</p>
        <h1 class="text-3xl font-bold text-gray-900">Kết nối với đội ngũ LifeQuest</h1>
        <p class="text-gray-500">Chúng tôi sẵn sàng lắng nghe mọi phản hồi để cải thiện trải nghiệm của bạn.</p>
    </section>

    <section class="grid lg:grid-cols-3 gap-8">
        <article class="lg:col-span-2 bg-white border border-gray-100 rounded-2xl shadow-sm p-8 space-y-6">
            <div>
                <h2 class="text-xl font-semibold text-gray-900">Kênh hỗ trợ chính</h2>
                <p class="mt-2 text-gray-600">Ưu tiên gửi yêu cầu qua các kênh bên dưới để chúng tôi theo dõi và phản hồi nhanh nhất.</p>
                <dl class="mt-6 space-y-4 text-gray-700">
                    <div>
                        <dt class="font-medium text-gray-900">Email sản phẩm</dt>
                        <dd>support@lifequest.vn (08:00 – 21:00, tất cả các ngày)</dd>
                    </div>
                    <div>
                        <dt class="font-medium text-gray-900">Hotline cộng đồng</dt>
                        <dd>0901 234 567 (thứ 2 – thứ 6)</dd>
                    </div>
                    <div>
                        <dt class="font-medium text-gray-900">Đối tác & truyền thông</dt>
                        <dd>partnership@lifequest.vn</dd>
                    </div>
                </dl>
            </div>

            <div>
                <h3 class="text-lg font-semibold text-gray-900">Hướng dẫn gửi yêu cầu</h3>
                <ol class="mt-3 list-decimal list-inside text-gray-600 space-y-2">
                    <li>Nêu rõ tài khoản hoặc thử thách/thói quen liên quan.</li>
                    <li>Mô tả vấn đề, kèm ảnh màn hình hoặc log nếu có.</li>
                    <li>Chọn mức độ ưu tiên (cao, chuẩn, thấp) để đội ngũ xếp lịch xử lý.</li>
                </ol>
            </div>

            <div class="grid md:grid-cols-3 gap-4">
                <div class="rounded-2xl border border-teal-100 bg-teal-50/60 p-4">
                    <p class="text-3xl font-bold text-teal-600">&lt; 4h</p>
                    <p class="text-sm text-gray-600">Thời gian phản hồi trung bình</p>
                </div>
                <div class="rounded-2xl border border-indigo-100 bg-indigo-50/60 p-4">
                    <p class="text-3xl font-bold text-indigo-600">92%</p>
                    <p class="text-sm text-gray-600">Yêu cầu được xử lý trong ngày</p>
                </div>
                <div class="rounded-2xl border border-emerald-100 bg-emerald-50/60 p-4">
                    <p class="text-3xl font-bold text-emerald-600">24/7</p>
                    <p class="text-sm text-gray-600">Giám sát hệ thống</p>
                </div>
            </div>
        </article>

        <aside class="bg-white border border-gray-100 rounded-2xl shadow-sm p-8 space-y-6">
            <div>
                <h3 class="text-lg font-semibold text-gray-900">Trung tâm hỗ trợ</h3>
                <p class="mt-2 text-gray-600">Theo dõi trạng thái ticket hoặc cập nhật hệ thống.</p>
                <a href="https://status.lifequest.vn" target="_blank" class="mt-3 inline-flex items-center text-teal-600 font-semibold hover:underline">status.lifequest.vn</a>
            </div>

            <div>
                <h3 class="text-lg font-semibold text-gray-900">Liên kết hữu ích</h3>
                <ul class="mt-3 space-y-2 text-teal-700">
                    <li><a class="hover:underline" href="{{ route('pages.introduction') }}" wire:navigate>Giới thiệu nền tảng</a></li>
                    <li><a class="hover:underline" href="{{ route('pages.ranking-rules') }}" wire:navigate>Quy tắc xếp hạng</a></li>
                    <li><a class="hover:underline" href="{{ route('pages.privacy-policy') }}" wire:navigate>Chính sách bảo mật</a></li>
                </ul>
            </div>

            <div>
                <h3 class="text-lg font-semibold text-gray-900">Văn phòng</h3>
                <p class="mt-2 text-gray-600">02, Nguyễn Đình Chiểu, Phường Bắc Nha Trang, Khánh Hòa.</p>
                <p class="mt-2 text-sm text-gray-500">Vui lòng đặt lịch trước khi ghé thăm để chúng tôi chuẩn bị tốt nhất.</p>
            </div>
        </aside>
    </section>
</x-app-layout>
