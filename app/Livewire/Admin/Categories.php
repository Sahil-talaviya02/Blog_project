<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Category;
use App\Models\ParentCategory;
use Livewire\WithPagination;
use Illuminate\Support\Str;

class Categories extends Component
{
    protected $listeners = [
        'deleteParentCategory',
        'deleteCategory'
    ];

    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    // parent category
    public $pcategory_per_page = 5;

    // category
    public $category_per_page = 5;

    public $isUpdateParentCategoryMode = false;
    public $pcategory_id, $pcategory_name;

    public $isUpdateCategoryMode = false;
    public $category_id, $category_name, $category_parent_id;

    // ================= PARENT CATEGORY =================

    public function addParentCategory()
    {
        $this->reset(['pcategory_id', 'pcategory_name']);
        $this->isUpdateParentCategoryMode = false;
        $this->dispatch('showParentCategoryModalForm');
    }

    public function createParentCategory()
    {
        $this->validate([
            'pcategory_name' => 'required|unique:parent_categories,name'
        ]);

        ParentCategory::create([
            'name' => $this->pcategory_name
        ]);

        $this->dispatch('hideParentCategoryModalForm');
    }

    public function editParentCategory($id)
    {
        $data = ParentCategory::findOrFail($id);

        $this->pcategory_id = $data->id;
        $this->pcategory_name = $data->name;
        $this->isUpdateParentCategoryMode = true;

        $this->dispatch('showParentCategoryModalForm');
    }

    public function updateParentCategory()
    {
        $data = ParentCategory::findOrFail($this->pcategory_id);

        $this->validate([
            'pcategory_name' => 'required|unique:parent_categories,name,' . $data->id
        ]);

        $data->update([
            'name' => $this->pcategory_name
        ]);

        $this->dispatch('hideParentCategoryModalForm');
    }

    public function confirmDelete($id)
    {
        $this->dispatch('confirmDelete', id: $id);
    }

    public function deleteParentCategory($id)
    {
        ParentCategory::findOrFail($id)->delete();
    }

    // ================= CATEGORY =================

    public function addCategory()
    {
        $this->reset(['category_id', 'category_name', 'category_parent_id']);
        $this->isUpdateCategoryMode = false;
        $this->dispatch('showCategoryModalForm');
    }

    public function createCategory()
    {
        $this->validate([
            'category_name' => 'required|unique:categories,name',
            'category_parent_id' => 'required|exists:parent_categories,id'
        ]);

        Category::create([
            'name' => $this->category_name,
            'slug' => Str::slug($this->category_name),
            'parent_id' => $this->category_parent_id
        ]);

        $this->dispatch('hideCategoryModalForm');
    }

    public function editCategory($id)
    {
        $cat = Category::findOrFail($id);

        $this->category_id = $cat->id;
        $this->category_name = $cat->name;
        $this->category_parent_id = $cat->parent_id;
        $this->isUpdateCategoryMode = true;

        $this->dispatch('showCategoryModalForm');
    }

    public function updateCategory()
    {
        $cat = Category::findOrFail($this->category_id);

        $this->validate([
            'category_name' => 'required|unique:categories,name,' . $cat->id,
            'category_parent_id' => 'required|exists:parent_categories,id'
        ]);

        $cat->update([
            'name' => $this->category_name,
            'slug' => Str::slug($this->category_name),
            'parent_id' => $this->category_parent_id
        ]);

        $this->dispatch('hideCategoryModalForm');
    }

    public function confirmDeleteCategory($id)
    {
        $this->dispatch('confirmDeleteCategory', id: $id);
    }

    public function deleteCategory($id)
    {
        $category = Category::findOrFail($id);

        // delete posts if exists
        if (method_exists($category, 'posts')) {
            $category->posts()->delete();
        }

        $category->delete();
    }

    // ================= RENDER =================

    public function render()
    {
        return view('livewire.admin.categories', [
            'parentCategories' => ParentCategory::with('children')
                ->paginate($this->pcategory_per_page, ['*'], 'parentPage'),

            'categories' => Category::with('parentCategory')
                ->paginate($this->category_per_page, ['*'], 'categoryPage'),

            // ✅ ADD THIS (for dropdown)
            'allParentCategories' => ParentCategory::all()
        ]);
    }
}
