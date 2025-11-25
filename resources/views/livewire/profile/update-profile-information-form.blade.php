<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;
use Livewire\WithFileUploads;

new class extends Component
{
    use WithFileUploads;

    public string $name = '';
    public string $email = '';
    public ?string $bio = null;
    public ?string $interests = '';
    public ?int $ward_id = null;

    public array $provinces = [];
    public array $wards = [];
    public ?int $province_id = null;

    public $avatar = null;

    /**
     * Mount the component.
     */
    public function mount(): void
    {
        $user = Auth::user();
        $this->name = $user->name;
        $this->email = $user->email;
        $this->bio = $user->bio;
        $this->interests = $user->interests;
        $this->ward_id = $user->ward_id;

        $this->provinces = \App\Models\Province::select('id', 'full_name')
        ->orderBy('full_name')
        ->get()
        ->toArray();

        //nếu user đã có ward=>load province + wards
        if($user->ward_id){
            $ward = \App\Models\Ward::find($user->ward_id);
            if ($ward){
                $this->province_id = $ward->province_id;
            }
        }

        if(!$this->province_id && count($this->provinces) > 0){
        $this->province_id = $this->provinces[0]['id'];
        }
        // Load wards dựa theo province_id
        $this->loadWards();
        }

        public function updatedProvinceId($value): void{
            $this->ward_id = null; // reset ward
            $this->loadWards();
        }

        private function loadWards(): void{
            if($this->province_id){
                $this->wards = \App\Models\Ward::where('province_id', $this->province_id)
                ->select('id', 'name_with_type')
                ->orderBy('name_with_type')
                ->get()
                ->toArray();
            }else{
                $this->wards = [];
        }
}


    /**
     * Update the profile information for the currently authenticated user.
     */
    public function updateProfileInformation(): void
    {
        $user = Auth::user();

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($user->id)],
            'bio' => ['nullable', 'string', 'max:1000'],
            'interests' => ['nullable', 'string', 'max:255'],
            'ward_id' => ['nullable', 'integer', 'exists:wards,id'],
            'avatar' => ['nullable', 'image', 'max:1024'], // 1MB Max
        ]);

        $user->ward_id = $this->ward_id;

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        if ($this->avatar) {
            // Xóa avatar cũ nếu có
            if ($user->avatar && \Illuminate\Support\Facades\Storage::disk('public')->exists('users/' . $user->avatar)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete('users/' . $user->avatar);
            }
            // Lưu avatar mới
            $path = $this->avatar->store('users', 'public');
            $user->avatar = basename($path);
        }

        // Loại bỏ avatar khỏi mảng validated để không ghi đè giá trị null
        unset($validated['avatar']);
        $user->fill($validated);

        $user->save();

        // Reset a file input
        $this->avatar = null;

        $this->dispatch('profile-updated', name: $user->name);
        // Dispatch event để cập nhật avatar ở header nếu có
        $this->dispatch('avatar-updated');

        // Reload the page to show new avatar everywhere
        $this->redirect(request()->header('Referer'), navigate: true);
    }

    /**
     * Send an email verification notification to the current user.
     */
    public function sendVerification(): void
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false));

            return;
        }

        $user->sendEmailVerificationNotification();

        Session::flash('status', 'verification-link-sent');
    }

    /**
     * Delete the user's profile avatar.
     */
    public function deleteAvatar(): void
    {
        $user = Auth::user();

        if ($user->avatar && \Illuminate\Support\Facades\Storage::disk('public')->exists('users/' . $user->avatar)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete('users/' . $user->avatar);
        }

        $user->forceFill(['avatar' => null])->save();
        $this->dispatch('avatar-updated');
    }
}; ?>

<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            Thông tin hồ sơ
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            Cập nhật thông tin hồ sơ và địa chỉ email của bạn.
        </p>
    </header>

    <form wire:submit="updateProfileInformation" class="mt-6 space-y-6">
        <!-- Avatar -->
        <div>
            <x-input-label for="avatar" value="Ảnh đại diện" />
            <div class="mt-2 flex items-center gap-x-4">
                @if ($avatar)
                    <img src="{{ $avatar->temporaryUrl() }}" alt="Avatar Preview" class="h-20 w-20 rounded-full object-cover">
                @else
                    <img src="{{ auth()->user()->avatar ? asset('storage/users/' . auth()->user()->avatar) : 'https://ui-avatars.com/api/?name='.urlencode(auth()->user()->name).'&color=0d9488&background=94ffd8' }}" alt="{{ auth()->user()->name }}" class="h-20 w-20 rounded-full object-cover">
                @endif

                <div class="flex flex-col gap-y-2">
                     <label for="avatar" class="cursor-pointer rounded-md bg-white px-2.5 py-1.5 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                        <span>Thay đổi</span>
                        <input id="avatar" name="avatar" type="file" class="sr-only" wire:model="avatar">
                    </label>
                    @if (auth()->user()->avatar)
                        <button type="button" wire:click="deleteAvatar" class="rounded-md bg-transparent px-2.5 py-1.5 text-sm font-semibold text-red-600 hover:text-red-800">
                            Xóa ảnh
                        </button>
                    @endif
                </div>
                 <div wire:loading wire:target="avatar" class="text-sm text-gray-500">Đang tải lên...</div>
            </div>
            <x-input-error class="mt-2" :messages="$errors->get('avatar')" />
        </div>

        <div>
            <x-input-label for="name" value="Họ tên" />
            <x-text-input wire:model="name" id="name" name="name" type="text" class="mt-1 block w-full focus:ring-teal-500 focus:border-teal-500" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" value="Email" />
            <x-text-input wire:model="email" id="email" name="email" type="email" class="mt-1 block w-full focus:ring-teal-500 focus:border-teal-500" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if (auth()->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! auth()->user()->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800">
                        Địa chỉ email của bạn chưa được xác minh.

                        <button wire:click.prevent="sendVerification" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500">
                            Nhấn vào đây để gửi lại email xác minh.
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            Một liên kết xác minh mới đã được gửi đến địa chỉ email của bạn.
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div>
            <x-input-label for="bio" value="Tiểu sử" />
            <textarea wire:model="bio" id="bio" name="bio" rows="3" class="mt-1 block w-full border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-md shadow-sm"></textarea>
            <x-input-error class="mt-2" :messages="$errors->get('bio')" />
        </div>

        <div>
            <x-input-label for="interests" value="Sở thích" />
            <x-text-input wire:model="interests" id="interests" name="interests" type="text" class="mt-1 block w-full focus:ring-teal-500 focus:border-teal-500" placeholder="Ví dụ: đọc sách, du lịch, code" />
            <x-input-error class="mt-2" :messages="$errors->get('interests')" />
        </div>


        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
            <x-input-label value="Tỉnh/Thành phố" />
            <select wire:model="province_id" class="mt-1 block border-gray-300 rounded p-2 w-full pr-8 focus:outline-none focus:ring-2 focus:ring-teal-500">
            <option value="">-- Chọn tỉnh --</option>
            @foreach($provinces as $province)
            <option value="{{ $province['id'] }}">{{ $province['full_name'] }}</option>
            @endforeach
            </select>
        </div>

        <div>
            <x-input-label value="Phường / Xã" />
            <select wire:model="ward_id" class="mt-1 block w-full border-gray-300 rounded p-2 w-full pr-8 focus:outline-none focus:ring-2 focus:ring-teal-500">
            <option value="">-- Chọn phường / xã --</option>
            @foreach($wards as $ward)
            <option value="{{ $ward['id'] }}">{{ $ward['name_with_type'] }}</option>
            @endforeach
            </select>
        </div>

        {{-- TODO: Implement a location selector component for ward_id --}}
        {{-- For now, we'll just show the current ward_id if it exists --}}

        <div class="flex items-center gap-4">
            <x-primary-button class="bg-teal-600 hover:bg-teal-700 focus:ring-teal-400">
                <span wire:loading.remove>
                    Lưu
                </span>

                <span wire:loading.flex class="items-center justify-center">
                    <svg class="animate-spin h-5 w-5" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke-width="4"></circle>
                        <path class="opacity-75" d="M4 12a8 8 0 018-8"></path>
                    </svg>
                </span>
            </x-primary-button>

            <x-action-message class="me-3" on="profile-updated">
                Đã lưu.
            </x-action-message>
        </div>
    </form>
</section>
