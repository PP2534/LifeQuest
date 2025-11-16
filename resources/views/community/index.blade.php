<x-app-layout>
    <div class="flex justify-between items-center">
        <h2 class="text-3xl font-bold text-teal-600">Bạn bè & Hoạt động của bạn bè</h2>

        <a href="{{ route('users.index') }}" class="px-4 py-2 rounded-md bg-teal-600 text-white font-semibold hover:bg-teal-600 hover:text-white focus:outline-none focus:ring-2 focus:ring-teal-400">
            Tìm bạn bè
        </a>
    </div>

    <div class="max-w-6xl mx-auto py-10 flex gap-8">
        <!--cột bạn bè-->
        <div class="w-1/2 h-[80vh] overflow-y-auto border-r border-gray-200 pr-4">
            @livewire('community.following-followers-list')
        </div>

        <!--cột hđ theo dõi-->
        <div class="w-1/2 h-[80vh] overflow-y-auto pl-4">
            @livewire('community.following-activities')
        </div>

    </div>
</x-app-layout>
