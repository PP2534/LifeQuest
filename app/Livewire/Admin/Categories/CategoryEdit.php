<?php

namespace App\Livewire\Admin\Categories;

use App\Models\Category;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
#[Layout('layouts.admin')]
class CategoryEdit extends Component
{
    use WithFileUploads;

    public Category $category;
    public $name;
    public $slug;
    public $currentIcon;
    public $newIcon;

    public function mount(Category $category)
    {
        $this->category = $category;
        $this->name = $category->name;
        $this->slug = $category->slug;
        $this->currentIcon = $category->icon;
    }

    public function updatedName($value)
    {
        $this->slug = Str::slug($value);
    }

    public function updateCategory()
    {
        $this->validate([
            'name' => 'required|min:3|unique:categories,name,' . $this->category->id,
            'slug' => 'required|unique:categories,slug,' . $this->category->id,
            'newIcon' => 'nullable|image|max:1024', // 1MB Max
        ]);

        if ($this->newIcon) {
            // Delete old icon if exists
            if ($this->currentIcon) {
                Storage::disk('public')->delete($this->currentIcon);
            }
            $iconPath = $this->newIcon->store('category_icons', 'public');
            $this->category->icon = $iconPath;
        }

        $this->category->name = $this->name;
        $this->category->slug = $this->slug;
        $this->category->save();

        session()->flash('message', 'Category updated successfully.');

        return $this->redirectRoute('admin.categories.list', navigate: true);
    }

    public function render()
    {
        return view('livewire.admin.categories.category-edit');
    }
}
