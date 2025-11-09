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

    <section aria-label="Featured Challenges" class="mt-20 text-center">
      <h2 class="text-2xl font-bold text-gray-900 mb-6">Thử Thách Nổi Bật</h2>
      <div class="grid gap-8 md:grid-cols-3">
        <article class="bg-white rounded-lg shadow hover:shadow-lg transition-shadow focus-within:ring-2 focus-within:ring-teal-600" tabindex="0">
          <img src="https://source.unsplash.com/400x250/?fitness" alt="Challenge Image" class="rounded-t-lg w-full object-cover h-48" />
          <div class="p-4">
            <h3 class="text-lg font-semibold text-gray-900 mb-1">30 ngày tập thể dục tại nhà</h3>
            <p class="text-sm text-teal-600 mb-2">Sức khỏe</p>
            <p class="text-sm text-gray-600 mb-4">Thời gian: 30 ngày</p>
            <button class="w-full bg-teal-600 hover:bg-teal-700 text-white font-semibold py-2 rounded focus:outline-none focus:ring-2 focus:ring-teal-400" aria-label="Tham gia thử thách này">
              Tham gia
            </button>
          </div>
        </article>

        <article class="bg-white rounded-lg shadow hover:shadow-lg transition-shadow focus-within:ring-2 focus-within:ring-teal-600" tabindex="0">
          <img src="https://source.unsplash.com/400x250/?reading" alt="Challenge Image" class="rounded-t-lg w-full object-cover h-48" />
          <div class="p-4">
            <h3 class="text-lg font-semibold text-gray-900 mb-1">Đọc sách mỗi ngày</h3>
            <p class="text-sm text-teal-600 mb-2">Phát triển bản thân</p>
            <p class="text-sm text-gray-600 mb-4">Thời gian: 15 ngày</p>
            <button class="w-full bg-teal-600 hover:bg-teal-700 text-white font-semibold py-2 rounded focus:outline-none focus:ring-2 focus:ring-teal-400" aria-label="Tham gia thử thách này">
              Tham gia
            </button>
          </div>
        </article>

        <article class="bg-white rounded-lg shadow hover:shadow-lg transition-shadow focus-within:ring-2 focus-within:ring-teal-600" tabindex="0">
          <img src="https://source.unsplash.com/400x250/?meditation" alt="Challenge Image" class="rounded-t-lg w-full object-cover h-48" />
          <div class="p-4">
            <h3 class="text-lg font-semibold text-gray-900 mb-1">Thiền 10 phút mỗi ngày</h3>
            <p class="text-sm text-teal-600 mb-2">Tâm trí</p>
            <p class="text-sm text-gray-600 mb-4">Thời gian: 21 ngày</p>
            <button class="w-full bg-teal-600 hover:bg-teal-700 text-white font-semibold py-2 rounded focus:outline-none focus:ring-2 focus:ring-teal-400" aria-label="Tham gia thử thách này">
              Tham gia
            </button>
          </div>
        </article>
      </div>
    </section>
</x-app-layout>