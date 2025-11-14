<x-app-layout>
    <section aria-label="Hero section" class="text-center">
      <h1 class="text-4xl md:text-5xl font-extrabold text-teal-600 mb-6 leading-tight">
        Hành trình phát triển bản thân bắt đầu từ đây
      </h1>
      <p class="text-lg md:text-xl max-w-3xl mx-auto mb-10 text-gray-700">
        Tham gia thử thách và theo dõi thói quen để tạo nên phiên bản tốt nhất của chính bạn mỗi ngày.
      </p>
      <a href="challenges.html" role="button" class="inline-block bg-amber-500 hover:bg-amber-600 text-white font-semibold px-8 py-4 rounded-lg shadow-md focus:outline-none focus:ring-4 focus:ring-amber-300" aria-label="Tham gia thử thách">
        Tham gia thử thách
      </a>
    </section>

    <livewire:challenges.featured-challenges />
</x-app-layout>