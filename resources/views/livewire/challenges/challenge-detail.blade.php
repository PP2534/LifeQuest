<div>
  <main role="main" class="container m-auto px-4 py-12 max-w-4xl">
    <article aria-label="Challenge detail" class="bg-white rounded-lg shadow p-8 text-left">
      <header class="mb-6">
        <h1 class="text-3xl font-bold text-teal-600 mb-2">30 ngày tập thể dục tại nhà</h1>
        <p class="text-sm text-teal-700 font-semibold mb-1">Sức khỏe</p>
        <p class="text-sm text-gray-600">Thời gian thử thách: 30 ngày</p>
      </header>

      <section aria-label="Challenge description" class="mb-8">
        <h2 class="text-xl font-semibold mb-2">Mô tả thử thách</h2>
        <p class="text-gray-700 leading-relaxed">
          Tham gia thử thách tập thể dục mỗi ngày tại nhà để cải thiện sức khỏe và tăng cường thể lực. Các bài tập đơn giản, dễ theo dõi.
        </p>
      </section>

      <section aria-label="Join challenge" class="mb-8">
        <button id="join-challenge-btn" class="bg-teal-600 hover:bg-teal-700 text-white font-semibold px-6 py-3 rounded-lg focus:outline-none focus:ring-4 focus:ring-teal-400" aria-pressed="false">
          Tham gia thử thách
        </button>
      </section>

      <section aria-label="Progress bar" class="mb-8">
        <h2 class="text-xl font-semibold mb-4">Tiến trình của bạn</h2>
        <div class="w-full bg-gray-200 rounded-full h-6 relative overflow-hidden" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="45" aria-label="Tiến trình phần trăm">
          <div id="progress-fill" class="bg-teal-600 h-6 rounded-full w-0 transition-width duration-1000 ease-in-out"></div>
          <span class="absolute right-3 top-0 text-white font-semibold text-sm leading-6">45%</span>
        </div>
        <div class="inline-block mt-2 bg-amber-400 text-amber-900 text-xs font-semibold px-3 py-1 rounded-full select-none" aria-label="Chuỗi ngày liên tiếp">
          Chuỗi 7 ngày
        </div>
      </section>

      <section aria-label="Leaderboard" class="mb-8">
        <h2 class="text-xl font-semibold mb-4">Bảng xếp hạng</h2>
        <div class="bg-white border border-gray-300 rounded shadow max-h-64 overflow-y-auto">
          <table class="w-full text-left text-gray-700 text-sm">
            <thead class="bg-gray-100 sticky top-0">
              <tr>
                <th class="px-4 py-2">Hạng</th>
                <th class="px-4 py-2">Tên người dùng</th>
                <th class="px-4 py-2">Tiến trình</th>
              </tr>
            </thead>
            <tbody>
              <!-- TODO: Replace with dynamic leaderboard rows -->
              <tr class="border-t border-gray-200 hover:bg-teal-50">
                <td class="px-4 py-2 font-semibold">1</td>
                <td class="px-4 py-2">Nguyễn Văn A</td>
                <td class="px-4 py-2">90%</td>
              </tr>
              <tr class="border-t border-gray-200 hover:bg-teal-50">
                <td class="px-4 py-2 font-semibold">2</td>
                <td class="px-4 py-2">Trần Thị B</td>
                <td class="px-4 py-2">85%</td>
              </tr>
              <tr class="border-t border-gray-200 hover:bg-teal-50">
                <td class="px-4 py-2 font-semibold">3</td>
                <td class="px-4 py-2">Lê Văn C</td>
                <td class="px-4 py-2">80%</td>
              </tr>
              <tr class="border-t border-gray-200 hover:bg-teal-50">
                <td class="px-4 py-2 font-semibold">4</td>
                <td class="px-4 py-2">Phạm Thị D</td>
                <td class="px-4 py-2">75%</td>
              </tr>
              <tr class="border-t border-gray-200 hover:bg-teal-50">
                <td class="px-4 py-2 font-semibold">5</td>
                <td class="px-4 py-2">Hoàng Văn E</td>
                <td class="px-4 py-2">70%</td>
              </tr>
            </tbody>
          </table>
        </div>
      </section>

            <!-- Comment Section -->
      <section aria-label="Comments" class="mb-8">
        <h2 class="text-xl font-semibold mb-4">Bình luận</h2>

        <!-- New comment input -->
        <div class="flex items-start mb-6">
          <img src="https://i.pravatar.cc/40?u=me" alt="Avatar" class="w-10 h-10 rounded-full mr-3" />
          <div class="flex-1">
            <textarea placeholder="Viết bình luận..." rows="2"
              class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-teal-500 focus:border-teal-500"></textarea>
            <div class="text-right mt-2">
              <button class="bg-teal-600 hover:bg-teal-700 text-white text-sm font-semibold px-4 py-2 rounded-lg">
                Gửi
              </button>
            </div>
          </div>
        </div>

        <!-- Comment list -->
        <ul class="space-y-4">
          <li class="flex items-start">
            <img src="https://i.pravatar.cc/40?u=a" alt="Avatar" class="w-10 h-10 rounded-full mr-3" />
            <div class="flex-1 bg-gray-100 rounded-lg px-4 py-3">
              <div class="flex justify-between items-center mb-1">
                <span class="font-semibold text-sm">Nguyễn Văn A</span>
                <span class="text-xs text-gray-500">2 giờ trước</span>
              </div>
              <p class="text-sm text-gray-700">Mình mới tham gia, cố gắng cùng nhau nhé! 💪</p>
            </div>
          </li>
          <li class="flex items-start">
            <img src="https://i.pravatar.cc/40?u=b" alt="Avatar" class="w-10 h-10 rounded-full mr-3" />
            <div class="flex-1 bg-gray-100 rounded-lg px-4 py-3">
              <div class="flex justify-between items-center mb-1">
                <span class="font-semibold text-sm">Trần Thị B</span>
                <span class="text-xs text-gray-500">1 ngày trước</span>
              </div>
              <p class="text-sm text-gray-700">Hôm nay mình tập xong rồi, cảm thấy rất khỏe 👍</p>
            </div>
          </li>
        </ul>
      </section>

      <!-- Reaction Section -->
      <section aria-label="Reactions" class="mb-8">
        <h2 class="text-xl font-semibold mb-4">Cảm xúc</h2>
        <div class="flex space-x-4">
          <button class="flex items-center space-x-1 px-3 py-2 border rounded-full hover:bg-gray-100">
            <span>👍</span><span class="text-sm">12</span>
          </button>
          <button class="flex items-center space-x-1 px-3 py-2 border rounded-full hover:bg-gray-100">
            <span>❤️</span><span class="text-sm">8</span>
          </button>
          <button class="flex items-center space-x-1 px-3 py-2 border rounded-full hover:bg-gray-100">
            <span>🔥</span><span class="text-sm">5</span>
          </button>
          <button class="flex items-center space-x-1 px-3 py-2 border rounded-full hover:bg-gray-100">
            <span>🎉</span><span class="text-sm">3</span>
          </button>
          <button class="flex items-center space-x-1 px-3 py-2 border rounded-full hover:bg-gray-100">
            <span>🙌</span><span class="text-sm">7</span>
          </button>
        </div>
      </section>

    </article>
  </main>
    <script>
    // Mobile nav toggle
    const navToggle = document.getElementById('nav-toggle');
    const primaryMenu = document.getElementById('primary-menu');
    navToggle.addEventListener('click', () => {
      const expanded = navToggle.getAttribute('aria-expanded') === 'true' || false;
      navToggle.setAttribute('aria-expanded', !expanded);
      primaryMenu.classList.toggle('hidden');
    });

    // User menu dropdown toggle
    const userMenuButton = document.getElementById('user-menu-button');
    const userMenu = document.getElementById('user-menu');
    userMenuButton.addEventListener('click', () => {
      const expanded = userMenuButton.getAttribute('aria-expanded') === 'true' || false;
      userMenuButton.setAttribute('aria-expanded', !expanded);
      userMenu.classList.toggle('hidden');
    });

    // Animate progress bar fill on page load
    window.addEventListener('DOMContentLoaded', () => {
      const progressFill = document.getElementById('progress-fill');
      // Animate to 45% width
      setTimeout(() => {
        progressFill.style.width = '45%';
      }, 100);
    });

    // Close menus on outside click
    document.addEventListener('click', (e) => {
      if (!userMenuButton.contains(e.target) && !userMenu.contains(e.target)) {
        userMenu.classList.add('hidden');
        userMenuButton.setAttribute('aria-expanded', false);
      }
      if (!navToggle.contains(e.target) && !primaryMenu.contains(e.target)) {
        primaryMenu.classList.add('hidden');
        navToggle.setAttribute('aria-expanded', false);
      }
    });
  </script>
  </div>