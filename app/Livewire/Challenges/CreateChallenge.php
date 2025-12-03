<?php

namespace App\Livewire\Challenges;

use App\Models\Category;
use App\Models\Challenge;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads; //  để xử lý upload file
use Carbon\Carbon;
use App\Models\Province;
use App\Models\Ward;
use App\Models\ChallengeParticipant;
use App\Notifications\NewChallengePosted;
use Illuminate\Support\Facades\Notification;

class CreateChallenge extends Component
{
    use WithFileUploads; // Kích hoạt 

    // Các thuộc tính liên kết với form
    public string $title = '';
    public string $description = '';
    public $image; // Đây sẽ là file upload tạm thời
    public int $duration_days = 7; // Giá trị mặc định
    public string $type = 'public'; // Giá trị mặc định
    
    // Các trường bổ sung dựa trên CSDL 
    public string $time_mode = 'fixed';
    public string $streak_mode = 'continuous';

    // Thuộc tính cho ngày bắt đầu (chỉ dùng khi mode = fixed)
    public $custom_start_date;

    // Dùng để lưu các danh mục được chọn
    public array $selectedCategories = [];
    public $selectedProvinceId = null; // ID Tỉnh được chọn
    public $ward_id = null;// ID xã 
    
    // Dùng để hiển thị danh sách category
    public Collection $allCategories;
    public Collection $provinces;
    public Collection $wards;

    // mặc định là ko yêu cầu ảnh
    public bool $need_proof = false;

    public bool $allow_member_invite = true;

   

    /**
     * Hàm này chạy khi component được tải lần đầu
     */
    public function mount()
    {
        // Tải tất cả danh mục từ CSDL để hiển thị cho người dùng chọn
        $this->allCategories = Category::orderBy('name')->get();

        // Tải danh sách tất cả tỉnh thành
        $this->provinces = Province::orderBy('name')->get();
        
        // Khởi tạo danh sách xã rỗng
        $this->wards = collect();
    }

    /**
     * Định nghĩa các quy tắc xác thực (validation)
     */
    protected function rules(): array
    {
        return [
            'title' => 'required|string|min:5|max:255',
            'description' => 'required|string|min:20',
            'duration_days' => 'required|integer|min:1',
            'type' => 'required|in:public,private',
            'time_mode' => 'required|in:fixed,rolling',
            'streak_mode' => 'required|in:continuous,cumulative',
            'image' => 'nullable|image|max:2048', // cho phép null, 2MB max
            'selectedCategories' => 'required|array|min:1', // Yêu cầu ít nhất 1 danh mục
            'need_proof' => 'boolean',
            'allow_member_invite' => 'boolean',
           'selectedProvinceId' => 'nullable', 
            'ward_id' => 'nullable|exists:wards,id',
            // Validate custom_start_date nếu là fixed
            'custom_start_date' => 'required_if:time_mode,fixed|nullable|date|after_or_equal:today',
        ];
    }
    protected function messages()
    {
        return [
            'title.required' => 'Vui lòng nhập tiêu đề thử thách.',
            'title.min' => 'Tiêu đề phải có ít nhất 5 ký tự.',
            'description.required' => 'Vui lòng nhập mô tả chi tiết.',
            'description.min' => 'Mô tả phải có ít nhất 20 ký tự.',
            'duration_days.required' => 'Vui lòng nhập thời lượng.',
            'duration_days.min' => 'Thời lượng tối thiểu là 1 ngày.',
            'selectedCategories.required' => 'Bạn phải chọn ít nhất một danh mục.',
            'selectedProvinceId.required' => 'Vui lòng chọn Tỉnh/Thành phố.',
            'ward_id.required' => 'Vui lòng chọn Phường/Xã.',
            'image.max' => 'Kích thước ảnh không được vượt quá 2MB.',
            'image.image' => 'File tải lên phải là định dạng ảnh.',
            'custom_start_date.required_if' => 'Vui lòng chọn ngày bắt đầu cho chế độ Cố định.',
            'custom_start_date.after_or_equal' => 'Ngày bắt đầu không được ở quá khứ.',
        ];
    }
/**
     * Hàm Lifecycle Hook của Livewire:
     * Tự động chạy khi $selectedProvinceId thay đổi
     */
    public function updatedSelectedProvinceId($value)
    {
        // Khi chọn tỉnh mới, reset xã đã chọn
        $this->ward_id = null;
        
        // Lọc danh sách xã theo tỉnh mới chọn
        if ($value) {
            $this->wards = Ward::where('province_id', $value)->orderBy('name')->get();
        } else {
            $this->wards = collect();
        }
    }
    /**
     * Hàm này được gọi khi form được submit (wire:submit="save")
     */
    public function save()
    {
        $validatedData = $this->validate();

        $imagePath = null;
        if ($this->image) {
            $imagePath = $this->image->store('challenges', 'public');
            $validatedData['image'] = $imagePath;
        }

        // Tách categories ra
        $categories = $validatedData['selectedCategories'];
        unset($validatedData['selectedCategories']);

        // Loại bỏ selectedProvinceId vì không lưu vào bảng challenges
        unset($validatedData['selectedProvinceId']);

        unset($validatedData['custom_start_date']);

        $duration = (int) $validatedData['duration_days']; // Lấy số ngày

        
        if ($this->time_mode === 'fixed') {
            // Fixed: Lấy ngày người dùng chọn
            $startDate = Carbon::parse($this->custom_start_date);
        } else {
            // Rolling: Lấy ngày hiện tại (ngay lúc tạo)
            $startDate = Carbon::now();
        }
        
        // Thêm start_date và end_date vào mảng data
        $validatedData['start_date'] = $startDate;
        $validatedData['end_date'] = $startDate->clone()->addDays($duration);

        // Thêm thông tin người tạo
        $validatedData['creator_id'] = Auth::id();
        $validatedData['ward_id'] = $this->ward_id ?: Auth::user()->ward_id;
        
        // Tạo Challenge (giờ đã có đủ ngày tháng)
        $challenge = Challenge::create($validatedData);
        $challenge->load('creator'); // Eager load the creator
        
        // Đính kèm categories
        $challenge->categories()->attach($categories);

        // Gửi thông báo cho followers nếu challenge là public
        if ($challenge->type === 'public') {
            $creator = Auth::user();
            $followers = $creator->followersUsers; 
            
            if ($followers->isNotEmpty()) {
                Notification::send($followers, new NewChallengePosted($challenge));
            }
        }
        
        // Thêm người tạo vào danh sách tham gia với vai trò 'creator'
        ChallengeParticipant::create([
            'challenge_id' => $challenge->id,
            'user_id' => Auth::id(),
            'role' => 'creator', 
            'status' => 'active',
            'progress_percent' => 0,
            'streak' => 0,
            'personal_start_date' => $startDate,
        ]);

        session()->flash('success', 'Đã tạo thử thách thành công!');
        return $this->redirect(route('challenges.show', $challenge), navigate: true);
    }
    public function render()
    {
        return view('livewire.challenges.create-challenge')->layout('layouts.app');
    }
}