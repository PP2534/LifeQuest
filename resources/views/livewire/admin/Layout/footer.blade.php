  <footer class="bg-white border-t border-gray-200">
    <div class="container mx-auto px-4 py-6 flex flex-col md:flex-row justify-between items-center text-sm text-gray-600">
      <p>© 2025 LifeQuest. Tất cả quyền được bảo lưu.</p>
      <nav aria-label="Footer navigation" class="mt-3 md:mt-0 space-x-4">
        <a href="{{ route('pages.introduction') }}" wire:navigate class="hover:text-teal-600 focus:outline-none focus:text-teal-600">Giới thiệu</a>
        <a href="{{ route('pages.contact') }}" wire:navigate class="hover:text-teal-600 focus:outline-none focus:text-teal-600">Liên hệ</a>
        <a href="{{ route('pages.privacy-policy') }}" wire:navigate class="hover:text-teal-600 focus:outline-none focus:text-teal-600">Chính sách bảo mật</a>
        <a href="{{ route('pages.ranking-rules') }}" wire:navigate class="hover:text-teal-600 focus:outline-none focus:text-teal-600">Quy tắc xếp hạng</a>
      </nav>
    </div>
  </footer>