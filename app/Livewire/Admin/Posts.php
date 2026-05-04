<?php

namespace App\Livewire\Admin;

use App\Models\Category;
use App\Models\ParentCategory;
use App\Models\Post;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\File;

class Posts extends Component
{
    use WithPagination;

    public $perPage = 5;
    public $categories_html;

    public $search = null;
    public $author_id = null;
    public $category = null;
    public $visibility = null;
    public $sortBy = 'desc';
    public $post_visibility;

    protected $queryString = [
        'search' => ['except' => ''],
        'author_id' => ['except' => ''],
        'category' => ['except' => ''],
        'visibility' => ['except' => ''],
        'sortBy' => ['except' => '']
    ];

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedAuthor()
    {
        $this->resetPage();
    }

    public function updatedCategory()
    {
        $this->resetPage();
    }

    public function updatedVisibility()
    {
        $this->post_visibility = $this->visibility == 1 ? 0 : 1;
        $this->resetPage();
    }

    public function updatedSortBy()
    {
        $this->resetPage();
    }

    public function mount()
    {
        if (auth()->user()->type == "superAdmin") {
            $this->author_id = auth()->user()->id;
        } else {
            $this->author_id = null;
        }
        $this->post_visibility = $this->visibility == 1 ? 0 : 1;
        $categories = ParentCategory::with(['children' => function ($query) {
            $query->whereHas('posts');
        }])
            ->whereHas('children.posts')
            ->orderBy('name', 'asc')
            ->get();

        $categories_html = '';
        if ($categories->count() > 0) {
            foreach ($categories as $parent) {
                $categories_html .= '<optgroup label="' . $parent->name . '">';

                foreach ($parent->children as $child) {
                    if ($child->posts->count() > 0) {
                        $categories_html .= '<option value="' . $child->id . '">' . $child->name . '</option>';
                    }
                }

                $categories_html .= '</optgroup>';
            }
        }

        $this->categories_html = $categories_html;
    }

    public function render()
    {
        return view('livewire.admin.posts', ['posts' => auth()->user()->type == "superAdmin" ?
            Post::search(trim($this->search))
            ->when($this->author_id, function ($query) {
                $query->where('author_id', $this->author_id);
            })
            ->when($this->category, function ($query) {
                $query->where('category_id', $this->category);
            })
            ->when($this->visibility !== null && $this->visibility !== '', function ($query) {
                $query->where('visibility', $this->visibility);
            })
            ->when($this->sortBy, function ($query) {
                $query->orderBy('created_at', $this->sortBy);
            })
            ->paginate($this->perPage) :
            Post::search(trim($this->search))
            ->when($this->category, function ($query) {
                $query->where('category_id', $this->category);
            })
            ->when($this->visibility !== null && $this->visibility !== '', function ($query) {
                $query->where('visibility', $this->visibility);
            })
            ->when($this->sortBy, function ($query) {
                $query->orderBy('created_at', $this->sortBy);
            })
            ->where('author_id', auth()->id())
            ->paginate($this->perPage)]);
    }
}
