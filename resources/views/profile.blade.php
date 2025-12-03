<x-app-layout>
    <div class="mx-auto py-8">
        <!-- Profile Header -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden mb-8">
            <div class="p-6 sm:p-8">
                <div class="flex items-center gap-6">
                    {{-- Giả sử user có avatar, nếu không có bạn có thể thay bằng ảnh mặc định --}}
                    <img class="h-20 w-20 rounded-full object-cover mr-6" src="{{ $user->avatar ? asset('storage/users/' . $user->avatar): 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&color=0d9488&background=94ffd8' }}" alt="{{ $user->name }}">
                    <div class="flex-1">
                        <h1 class="text-3xl font-bold text-gray-900">{{ $user->name }}</h1>
                        @if($user->ward_id)
                            <p class="text-md text-gray-600 mt-1"><strong>Vị trí:</strong> {{ $user->ward->name }}, {{ $user->ward->province->name }}</p>
                        @endif
                        @if($user->interests)
                            <p class="text-md text-gray-600 mt-1"><strong>Sở thích:</strong> {{ $user->interests }}</p>
                        @endif
                        <p class="text-md text-gray-600 mt-1">
                            @if($rank)
                                <span class="font-semibold text-teal-600">#{{ $rank}}</span> trên BXH
                            @else
                                Chưa xếp hạng
                            @endif
                        </p>
                    </div>
                    <div class="ml-auto flex items-center gap-3 self-start">
                        @livewire('user-profile.follow-toggle', ['profileUser' => $user], key('profile-follow-'.$user->id))
                    </div>
                </div>
                <div class="mt-8">
                    @if($user->bio)
                        <p class="text-md text-gray-600 mt-1 text-center">{{$user->bio}}</p>
                    @endif
                    <div class="mt-4">
                        @livewire('user-profile.follower-stats', ['profileUser' => $user], key('profile-follower-stats-'.$user->id))
                    </div>
                </div>
            </div>
        </div>

        <!-- Participated Challenges -->
        <div class="mb-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">Thử thách đã tham gia</h2>
            @if($participatedChallenges->count() > 0)
                <div class="grid gap-8 md:grid-cols-2">
                    @foreach($participatedChallenges as $challenge)
                        <article class="bg-white rounded-lg shadow hover:shadow-lg transition-shadow">
                            <a href="{{ route('challenges.show', $challenge->id) }}" class="block">
                                                                @php
                                    $imageUrl = asset('no_image.png');
                                    if ($challenge->image && file_exists(public_path('storage/' . $challenge->image))) {
                                        $imageUrl = asset('storage/' . $challenge->image);
                                    } elseif ($challenge->categories->isNotEmpty() && $challenge->categories->first()->icon && file_exists(public_path('storage/' . $challenge->categories->first()->icon))) {
                                        $imageUrl = asset('storage/' . $challenge->categories->first()->icon);
                                    }
                                @endphp
                                <img src="{{ $imageUrl }}" alt="Challenge Image" class="rounded-t w-full object-cover h-48">
                                <div class="p-4">
                                    <h3 class="text-lg font-semibold text-gray-900 mb-1">{{ $challenge->title }}</h3>
                                    <p class="text-sm text-gray-600">Thời gian: {{ $challenge->duration_days }} ngày</p>
                                </div>
                            </a>
                        </article>
                    @endforeach
                </div>
            @else
                <div class="bg-white rounded-lg shadow-md p-6 text-center text-gray-500">
                    <p>Người dùng này chưa tham gia thử thách công khai nào.</p>
                </div>
            @endif
        </div>

        <!-- Created Challenges -->
        <div>
            <h2 class="text-2xl font-bold text-gray-800 mb-4">Thử thách đã tạo</h2>
            @if($createdChallenges->count() > 0)
                <div class="grid gap-8 md:grid-cols-2">
                    @foreach($createdChallenges as $challenge)
                        <article class="bg-white rounded-lg shadow hover:shadow-lg transition-shadow">
                            <a href="{{ route('challenges.show', $challenge->id) }}" class="block">
                                @php
                                    $imageUrl = asset('no_image.png');
                                    if ($challenge->image && file_exists(public_path('storage/' . $challenge->image))) {
                                        $imageUrl = asset('storage/' . $challenge->image);
                                    } elseif ($challenge->categories->isNotEmpty() && $challenge->categories->first()->icon && file_exists(public_path('storage/' . $challenge->categories->first()->icon))) {
                                        $imageUrl = asset('storage/' . $challenge->categories->first()->icon);
                                    }
                                @endphp
                                <img src="{{ $imageUrl }}" alt="Challenge Image" class=" rounded-t w-full object-cover h-48">
                                <div class="p-4">
                                    <h3 class="text-lg font-semibold text-gray-900 mb-1">{{ $challenge->title }}</h3>
                                    <p class="text-sm text-gray-600">Thời gian: {{ $challenge->duration_days }} ngày</p>
                                </div>
                            </a>
                        </article>
                    @endforeach
                </div>
            @else
                <div class="bg-white rounded-lg shadow-md p-6 text-center text-gray-500">
                    <p>Người dùng này chưa tạo thử thách công khai nào.</p>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
