<?php

namespace App\Livewire\Admin\Categories;
use Livewire\Attributes\Layout;
use App\Models\Category;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
#[Layout('layouts.admin')]
class CategoryList extends Component
{
    use WithFileUploads;

    public $categories;
    public $newCategoryName;
    public $newCategorySlug;
    public $newCategoryIcon;

    public function mount()
    {
        $this->loadCategories();
    }

    public function loadCategories()
    {
        $this->categories = Category::all();
    }

    public function updatedNewCategoryName($value)
    {
        $this->newCategorySlug = Str::slug($value);
    }

    public function addCategory()
    {
        $this->validate([
            'newCategoryName' => 'required|min:3|unique:categories,name',
            'newCategorySlug' => 'required|unique:categories,slug',
            'newCategoryIcon' => 'nullable|image|max:1024', // 1MB Max
        ]);

        $iconPath = null;
        if ($this->newCategoryIcon) {
            $iconPath = $this->newCategoryIcon->store('category_icons', 'public');
        }

        Category::create([
            'name' => $this->newCategoryName,
            'slug' => $this->newCategorySlug,
            'icon' => $iconPath,
        ]);

        session()->flash('message', 'Category added successfully.');

        $this->reset(['newCategoryName', 'newCategorySlug', 'newCategoryIcon']);
        $this->loadCategories();
    }

    public function deleteCategory($categoryId)
    {
        $category = Category::findOrFail($categoryId);

        if ($category->icon) {
            Storage::disk('public')->delete($category->icon);
        }

        $category->delete();

        session()->flash('message', 'Category deleted successfully.');
        $this->loadCategories();
    }

    public function render()
    {
        return view('livewire.admin.categories.category-list');
    }
}
