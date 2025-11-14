<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="text-3xl font-bold text-teal-600">Bạn bè & Hoạt động của bạn bè</h2>
        </div>
    </x-slot>

    <div class="max-w-6xl mx-auto py-10 flex gap-8">
        <!-- Cột Bạn bè -->
        <div class="w-1/2 h-[80vh] overflow-y-auto border-r border-gray-200 pr-4">
            @livewire('community.following-followers-list')
        </div>

        <!-- Cột Hoạt động theo dõi -->
        <div class="w-1/2 h-[80vh] overflow-y-auto pl-4">
            @livewire('community.following-activities')
        </div>
    </div>
</x-app-layout>
