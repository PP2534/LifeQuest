<x-app-layout>


    <div class="max-w-4xl mx-auto">
        <h2 class="font-semibold text-2xl mb-4 text-gray-800 leading-tight">
            Hồ sơ
        </h2>
        <a href="{{ route('community') }}" class="px-4 py-2 bg-teal-600 text-white rounded-lg">
    Cộng đồng
</a>
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <!-- Update Profile Information -->
            <div class="p-6 sm:p-8 border-b border-gray-200">
                <livewire:profile.update-profile-information-form />
            </div>

            <!-- Update Password -->
            <div class="p-6 sm:p-8 border-b border-gray-200">
                <livewire:profile.update-password-form />
            </div>

            <!-- Delete User -->
            <div class="p-6 sm:p-8">
                <livewire:profile.delete-user-form />
            </div>
        </div>
    </div>
</x-app-layout>
