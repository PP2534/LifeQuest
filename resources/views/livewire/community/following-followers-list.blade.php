<div>
    <nav role="tablist" class="flex space-x-4 border-b border-gray-300 mb-8">
        <button wire:click="$set('activeTab', 'following')" id="tab-following" class="pb-2 border-b-2 font-semibold focus:outline-none {{ $activeTab == 'following' ? 'border-teal-600 text-teal-600' : 'border-transparent text-gray-600 hover:text-teal-600' }}">
            Đang theo dõi
        </button>

        <button wire:click="$set('activeTab', 'followers')" id="tab-followers" class="pb-2 border-b-2 font-semibold focus:outline-none {{ $activeTab == 'followers' ? 'border-teal-600 text-teal-600' : 'border-transparent text-gray-600 hover:text-teal-600' }}">
            Người theo dõi
        </button>
    </nav>

    <!--đang theo dõi -->
    <section id="following" role="tabpanel" aria-labelledby="tab-following" class="{{ $activeTab != 'following' ? 'hidden' : '' }}">
        <ul class="space-y-6">
            @forelse($followings as $user)
                <li class="bg-white p-4 rounded-lg shadow flex items-center space-x-4">
                    <a href="{{ route('profile.show', ['id' => $user->id]) }}" wire:navigate class="flex-shrink-0"> 
                        <img class="h-10 w-10 rounded-full object-cover" src="{{ $user->avatar ? asset('storage/users/' . $user->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&color=0d9488&background=94ffd8'}}" 
                        alt="{{ $user->name }}">
                    </a>
                    <div class="flex-grow">
                        <a href="{{ route('profile.show', ['id' => $user->id]) }}" wire:navigate class="text-lg font-semibold text-teal-600 hover:underline">
                            {{ $user->name }}
                        </a>
                        <p class="text-gray-600 text-sm truncate max-w-xl">{{ $user->bio ?? 'Chưa có giới thiệu.' }}</p>
                    </div>
                    
                    <button wire:click="unfollow({{ $user->id }})" class="px-4 py-2 rounded-md border border-teal-600 text-teal-600 font-semibold hover:bg-teal-600 hover:text-white focus:outline-none focus:ring-2 focus:ring-teal-400" aria-label="Bỏ theo dõi {{ $user->name }}">
                        Bỏ theo dõi
                    </button>
                </li>
            @empty
                <p class="text-center text-gray-500 py-6">Bạn chưa theo dõi ai.</p>
            @endforelse
        </ul>
    </section>

    <!--người theo dõi-->
    <section id="followers" role="tabpanel" aria-labelledby="tab-followers" class="{{ $activeTab != 'followers' ? 'hidden' : '' }}">
        <ul class="space-y-6">
            @forelse($followers as $user)
                <li class="bg-white p-4 rounded-lg shadow flex items-center space-x-4">
                    <a href="{{ route('profile.show', ['id' => $user->id]) }}" wire:navigate class="flex-shrink-0">
                        <img class="h-10 w-10 rounded-full object-cover" src="{{ $user->avatar ? asset('storage/users/' . $user->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&color=0d9488&background=94ffd8'}}" 
                        alt="{{ $user->name }}">
                    </a>
                    <div class="flex-grow">
                        <a href="{{ route('profile.show', ['id' => $user->id]) }}" wire:navigate class="text-lg font-semibold text-teal-600 hover:underline">
                            {{ $user->name }}
                        </a>
                        <p class="text-gray-600 text-sm truncate max-w-xl">{{ $user->bio ?? 'Chưa có giới thiệu.' }}</p>
                    </div>

                    @if(!Auth::user()->followingsUsers->contains($user->id))
                        <button wire:click="follow({{ $user->id }})" class="px-4 py-2 rounded-md bg-teal-600 text-white font-semibold hover:bg-teal-600 hover:text-white focus:outline-none focus:ring-2 focus:ring-teal-400" aria-label="Theo dõi {{ $user->name }}">
                            Theo dõi
                        </button>
                    @endif
                </li>
            @empty
                <p class="text-center text-gray-500 py-6">Chưa có ai theo dõi bạn.</p>
            @endforelse
        </ul>
    </section>
</div>
