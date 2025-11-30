<?php

namespace App\Livewire\Challenges;

use App\Models\Category;
use App\Models\Challenge;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use App\Models\Province;
use App\Models\Ward;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class EditChallenge extends Component
{
    use WithFileUploads;

    public Challenge $challenge; // Biến này sẽ lưu thử thách cần sửa

    // Các thuộc tính liên kết với form
    public string $title = '';
    public string $description = '';
    public $image; // File upload tạm thời
    public $existingImageUrl = null; // Link ảnh cũ (nếu có)
    public int $duration_days = 7;
    public string $type = 'public';
    public string $time_mode = 'fixed';
    public string $streak_mode = 'continuous';
    public array $selectedCategories = [];
   
    public bool $need_proof = false;
    public bool $allow_member_invite = true;

    public $selectedProvinceId = null;
    public $ward_id = null;

    public Collection $allCategories;
    public Collection $provinces;
    public Collection $wards;

    /**
     * Hàm Mount sẽ TẢI DỮ LIỆU CŨ của thử thách vào form
     */
    public function mount(Challenge $challenge)
    {
        // Kiểm tra quyền (chỉ creator mới được sửa)
        if ((int) $challenge->creator_id !== Auth::id()) {
            abort(403, 'Bạn không có quyền sửa thử thách này.');
        }

        $this->challenge = $challenge;
        $this->allCategories = Category::orderBy('name')->get();

        $this->provinces = Province::orderBy('name')->get();

        // Tải dữ liệu cũ vào các thuộc tính
        $this->title = $challenge->title;
        $this->description = $challenge->description;
        $this->duration_days = $challenge->duration_days;
        $this->type = $challenge->type;
        $this->time_mode = $challenge->time_mode;
        $this->streak_mode = $challenge->streak_mode;
        $this->need_proof = $challenge->need_proof;
        $this->allow_member_invite = $challenge->allow_member_invite;
        $this->selectedCategories = $challenge->categories->pluck('id')->toArray();

        
        if ($challenge->image) {
            $this->existingImageUrl = Storage::url($challenge->image);
        }

        $this->ward_id = $challenge->ward_id;
        
        if ($this->ward_id) {
            // Tìm xã cũ để suy ra tỉnh cũ
            $currentWard = Ward::find($this->ward_id);
            if ($currentWard) {
                $this->selectedProvinceId = $currentWard->province_id;
                // Load danh sách xã của tỉnh đó
                $this->wards = Ward::where('province_id', $this->selectedProvinceId)->orderBy('name')->get();
            } else {
                $this->wards = collect();
            }
        } else {
            $this->wards = collect();
        }
    }
    public function updatedSelectedProvinceId($value)
    {
        $this->ward_id = null; // Reset xã khi đổi tỉnh
        if ($value) {
            $this->wards = Ward::where('province_id', $value)->orderBy('name')->get();
        } else {
            $this->wards = collect();
        }
    }
    protected function rules(): array
    {
        return [
            'title' => 'required|string|min:5|max:255',
            'description' => 'required|string|min:20',
            'duration_days' => 'required|integer|min:1',
            'type' => 'required|in:public,private',
            'time_mode' => 'required|in:fixed,rolling',
            'streak_mode' => 'required|in:continuous,cumulative',
            'image' => 'nullable|image|max:2048',
            'selectedCategories' => 'required|array|min:1',

            'need_proof' => 'boolean',
            'allow_member_invite' => 'boolean',
            'selectedProvinceId' => 'required',
            'ward_id' => 'required|exists:wards,id',
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
        ];
    }
    /**
     * Hàm này chỉ xử lý CẬP NHẬT
     */
    public function update()
    {
        $validatedData = $this->validate();

        //  Tách danh mục (categories) ra khỏi dữ liệu
        $categories = $validatedData['selectedCategories'];
        unset($validatedData['selectedCategories']);
        
        // Tách file ảnh (image) ra khỏi dữ liệu
        // $newImage có thể là null (nếu không upload) hoặc là 1 file
        $newImage = $validatedData['image'];
        unset($validatedData['image']); // Xóa key 'image' khỏi mảng data chính

        unset($validatedData['selectedProvinceId']);

        // Cập nhật dữ liệu TEXT (title, description,...)
        // Bằng cách này, cột 'image' trong CSDL sẽ KHÔNG bị ghi đè là null
        $this->challenge->update($validatedData);

        // CHỈ xử lý ảnh NẾU có file MỚI được upload
        if ($newImage) {
            // Xóa ảnh cũ (nếu có)
            if ($this->challenge->image) {
                Storage::disk('public')->delete($this->challenge->image);
            }
            
            // Lưu ảnh mới và cập nhật đường dẫn vào CSDL
            $imagePath = $newImage->store('challenges', 'public');
            $this->challenge->image = $imagePath; // Cập nhật thuộc tính image
            $this->challenge->save(); // Lưu lại thay đổi này
        }

        //  Đồng bộ hóa (sync) các danh mục
        $this->challenge->categories()->sync($categories); 
        
        session()->flash('success', 'Đã cập nhật thử thách thành công!');
        return $this->redirect(route('challenges.show', $this->challenge), navigate: true);
    }

    public function render()
    {
        // Trỏ đến view 'edit-challenge'
        return view('livewire.challenges.edit-challenge')->layout('layouts.app');
    }
}