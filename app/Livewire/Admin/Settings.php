<?php

namespace App\Livewire\Admin;

use Livewire\Component;

class Settings extends Component
{
    public $tab = null;
    public $default_tab = 'general_settings';
    public $queryString = ['tab' => ['keep' => true]];


    public function selectTab($tab)
    {
        $this->tab = $tab;
    }

    public function mount()
    {
        $this->tab = request()->tab ?? $this->default_tab;
    }

    

    public function render()
    {
        return view('livewire.admin.settings');
    }
}
