<?php

namespace App\Livewire\Admin;

use Livewire\Component;

class Categories extends Component
{

    public $isUpdateParentCategoryMode = false;
    public $pcategory_id, $pcateogry_name;

    
    public function addParentCategory()
    {
        $this->pcategory_id = null;
        $this->pcateogry_name = null;
        $this->isUpdateParentCategoryMode = false;
        $this->showParentCategoryModalForm();
    }

    public function showParentCategoryModalForm()
    {
        $this->resetErrorBag();
        $this->dispatch('showParentCategoryModalForm');
    }

    public function hideParentCategoryModalForm()
    {
        $this->dispatch('hideParentCategoryModalForm');
        $this->isUpdateParentCategoryMode = false;
        $this->pcategory_id = null;
        $this->pcateogry_name = null;
    }

    public function render()
    {
        return view('livewire.admin.categories');
    }
}
