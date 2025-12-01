<?php

namespace App\Livewire\Challenges;

use App\Models\Category;
use App\Models\Province;
use App\Models\Ward;
use App\Models\Activity;
use App\Notifications\NewChallengePosted;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Livewire\Component;
use Livewire\WithFileUploads;

class CreateChallenge extends Component
{
    use WithFileUploads;

    public $title, $description, $image, $selectedProvinceId, $ward_id;
    public $selectedCategories = [];
    public $duration_days = 30;
    public $time_mode = 'fixed';
    public $streak_mode = 'continuous';
    public $type = 'public';
    public $need_proof = false;
    public $allow_member_invite = true;

    public $provinces = [];
    public $wards = [];
    public $allCategories = [];

    protected $rules = [
        'title' => 'required|string|max:255',
        'description' => 'required|string',
        'image' => 'nullable|image|max:2048',
        'selectedProvinceId' => 'required|exists:provinces,id',
        'ward_id' => 'required|exists:wards,id',
        'selectedCategories' => 'required|array|min:1',
        'selectedCategories.*' => 'exists:categories,id',
        'duration_days' => 'required|integer|min:1',
        'time_mode' => 'required|in:fixed,rolling',
        'streak_mode' => 'required|in:continuous,cumulative',
        'type' => 'required|in:public,private',
        'need_proof' => 'boolean',
        'allow_member_invite' => 'boolean',
    ];

    public function mount()
    {
        $this->provinces = Province::all();
        $this->allCategories = Category::all();
        $this->wards = collect();
    }

    public function updatedSelectedProvinceId($provinceId)
    {
        if ($provinceId) {
            $this->wards = Ward::where('province_id', $provinceId)->get();
        } else {
            $this->wards = collect();
        }
        $this->ward_id = null;
    }

    public function save()
    {
        $this->validate();

        $user = Auth::user();

        $imagePath = $this->image ? $this->image->store('challenges', 'public') : null;

        $challenge = $user->challenges()->create([
            'title' => $this->title,
            'description' => $this->description,
            'image' => $imagePath,
            'ward_id' => $this->ward_id,
            'duration_days' => $this->duration_days,
            'time_mode' => $this->time_mode,
            'streak_mode' => $this->streak_mode,
            'type' => $this->type,
            'need_proof' => $this->need_proof,
            'allow_member_invite' => $this->allow_member_invite,
        ]);

        $challenge->categories()->sync($this->selectedCategories);

        // Ghi lại hoạt động
        Activity::create([
            'user_id' => $user->id,
            'type' => 'create_challenge',
            'details' => $challenge->id,
        ]);

        // Lấy danh sách người theo dõi của người dùng hiện tại
        $followers = $user->followersUsers;

        // Gửi thông báo đến những người theo dõi
        if ($followers->isNotEmpty()) {
            Notification::send($followers, new NewChallengePosted($challenge, $user));
        }

        // Gửi event để cập nhật feed hoạt động
        $this->dispatch('activityAdded');

        return redirect()->route('challenges.show', $challenge->id)->with('success', 'Thử thách đã được tạo thành công!');
    }

    public function render()
    {
        return view('livewire.challenges.create-challenge');
    }
}