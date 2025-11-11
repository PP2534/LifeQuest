<?php

namespace App\Livewire\Challenges;

use App\Models\Category;
use App\Models\Challenge;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads; //  để xử lý upload file
use Carbon\Carbon;

class CreateChallenge extends Component
{
    use WithFileUploads; // Kích hoạt 

    // Các thuộc tính liên kết với form
    public string $title = '';
    public string $description = '';
    public $image; // Đây sẽ là file upload tạm thời
    public int $duration_days = 7; // Giá trị mặc định
    public string $type = 'public'; // Giá trị mặc định
    
    // Các trường bổ sung dựa trên CSDL của bạn
    public string $time_mode = 'fixed';
    public string $streak_mode = 'continuous';

    // Dùng để lưu các danh mục được chọn
    public array $selectedCategories = [];
    
    // Dùng để hiển thị danh sách category
    public Collection $allCategories;

    /**
     * Hàm này chạy khi component được tải lần đầu
     */
    public function mount()
    {
        // Tải tất cả danh mục từ CSDL để hiển thị cho người dùng chọn
        $this->allCategories = Category::orderBy('name')->get();
    }

    /**
     * Định nghĩa các quy tắc xác thực (validation)
     */
    protected function rules(): array
    {
        return [
            'title' => 'required|string|min:10|max:255',
            'description' => 'required|string|min:20',
            'duration_days' => 'required|integer|min:1',
            'type' => 'required|in:public,private',
            'time_mode' => 'required|in:fixed,rolling',
            'streak_mode' => 'required|in:continuous,cumulative',
            'image' => 'nullable|image|max:2048', // cho phép null, 2MB max
            'selectedCategories' => 'required|array|min:1', // Yêu cầu ít nhất 1 danh mục
        ];
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

        $startDate = Carbon::now(); // Lấy ngày giờ hiện tại
        $duration = (int) $validatedData['duration_days']; // Lấy số ngày
        
        // Thêm start_date và end_date vào mảng data
        $validatedData['start_date'] = $startDate;
        $validatedData['end_date'] = $startDate->clone()->addDays($duration);

        // Thêm thông tin người tạo
        $validatedData['creator_id'] = Auth::id();
        $validatedData['ward_id'] = Auth::user()->ward_id;
        
        // Tạo Challenge (giờ đã có đủ ngày tháng)
        $challenge = Challenge::create($validatedData);
        
        // Đính kèm categories
        $challenge->categories()->attach($categories);

        session()->flash('success', 'Đã tạo thử thách thành công!');
        return $this->redirect(route('challenges.show', $challenge), navigate: true);
    }
    public function render()
    {
        return view('livewire.challenges.create-challenge')->layout('layouts.app');
    }
}