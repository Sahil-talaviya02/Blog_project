<?php

namespace App\Livewire\Admin;

use App\Models\GeneralSetting;
use Livewire\Component;

class Settings extends Component
{
    public $tab = null;

    public $default_tab = 'general_settings';

    public $queryString = ['tab' => ['keep' => true]];

    // General Settings Properties
    public $site_title;

    public $site_email;

    public $site_phone;

    public $site_meta_keywords;

    public $site_meta_description;

    // Logo & Favicon Properties
    // public $site_logo, $site_favicon;

    public function selectTab($tab)
    {
        $this->tab = $tab;
    }

    public function mount()
    {
        $this->tab = request()->tab ?? $this->default_tab;

        // Get General Settings
        $settings = GeneralSetting::take(1)->first();

        if (! is_null($settings)) {
            $this->site_title = $settings->site_title;
            $this->site_email = $settings->site_email;
            $this->site_phone = $settings->site_phone;
            $this->site_meta_keywords = $settings->site_meta_keywords;
            $this->site_meta_description = $settings->site_meta_description;
            // $this->site_logo = $settings->site_logo;
            // $this->site_favicon = $settings->site_favicon;
        }
    }

    public function updateSiteInfo()
    {
        $this->validate([
            'site_title' => 'required|string|max:255',
            'site_email' => 'required|email',
        ]);

        $settings = GeneralSetting::take(1)->first();

        $data = [[
            'site_title' => $this->site_title,
            'site_email' => $this->site_email,
            'site_phone' => $this->site_phone,
            'site_meta_keywords' => $this->site_meta_keywords,
            'site_meta_description' => $this->site_meta_description,
        ]];

        if (! is_null($settings)) {
            $query = $settings->update($data);
        } else {
            $query = GeneralSetting::insert($data);
        }

        if ($query) {
            $this->dispatch('showToast', ['type' => 'success', 'message' => 'General settings has been updated successfully']);
        } else {
            $this->dispatch('showToast', ['type' => 'error', 'message' => 'Failed to update general settings']);
        }
    }

    public function render()
    {
        return view('livewire.admin.settings');
    }
}
