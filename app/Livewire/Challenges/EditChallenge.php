<?php

namespace App\Livewire\Challenges;

use App\Models\Category;
use App\Models\Challenge;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
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
    public Collection $allCategories;

    /**
     * Hàm Mount sẽ TẢI DỮ LIỆU CŨ của thử thách vào form
     */
    public function mount(Challenge $challenge)
    {
        // Kiểm tra quyền (chỉ creator mới được sửa)
        if ($challenge->creator_id !== Auth::id()) {
            abort(403, 'Bạn không có quyền sửa thử thách này.');
        }

        $this->challenge = $challenge;
        $this->allCategories = Category::orderBy('name')->get();
        
        // Tải dữ liệu cũ vào các thuộc tính
        $this->title = $challenge->title;
        $this->description = $challenge->description;
        $this->duration_days = $challenge->duration_days;
        $this->type = $challenge->type;
        $this->time_mode = $challenge->time_mode;
        $this->streak_mode = $challenge->streak_mode;
        $this->selectedCategories = $challenge->categories->pluck('id')->toArray();
        
        if ($challenge->image) {
            $this->existingImageUrl = Storage::url($challenge->image);
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
        ];
    }

    /**
     * Hàm này chỉ xử lý CẬP NHẬT
     */
    public function update()
    {
        $validatedData = $this->validate();

        // 1. Tách danh mục (categories) ra khỏi dữ liệu
        $categories = $validatedData['selectedCategories'];
        unset($validatedData['selectedCategories']);
        
        // 2. Tách file ảnh (image) ra khỏi dữ liệu
        // $newImage có thể là null (nếu không upload) hoặc là 1 file
        $newImage = $validatedData['image'];
        unset($validatedData['image']); // Xóa key 'image' khỏi mảng data chính

        // 3. Cập nhật dữ liệu TEXT (title, description,...)
        // Bằng cách này, cột 'image' trong CSDL sẽ KHÔNG bị ghi đè là null
        $this->challenge->update($validatedData);

        // 4. CHỈ xử lý ảnh NẾU có file MỚI được upload
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

        // 5. Đồng bộ hóa (sync) các danh mục
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