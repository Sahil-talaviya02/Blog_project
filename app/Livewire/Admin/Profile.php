<?php

namespace App\Livewire\Admin;

use App\Helpers\CMail;
use App\Models\User;
use App\Models\UserSocialLink;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\Component;

class Profile extends Component
{
    public $tab = null;
    public $tabname = 'personal_details';
    protected $queryString = ['tab' => ['keep' => true]];

    public $name, $email, $username, $bio;
    // ✅ ADD THIS
    public $profilePictureFile;

    protected $listeners = ['updateProfile' => '$refresh'];

    public $current_password, $new_password, $new_password_confirmation;

    public $facebook_url, $twitter_url, $instagram_url, $youtube_url, $linkedin_url, $github_url;

    public function selectTab($tab)
    {
        $this->tab = $tab;
    }

    public function mount()
    {
        $this->tab = request()->get('tab', $this->tabname);

        // Load user once
        $user = User::with('socialLinks')->findOrFail(auth()->id());

        // Populate fields
        $this->name = $user->name;
        $this->email = $user->email;
        $this->username = $user->username;
        $this->bio = $user->bio;

        //populate social links
        if(!is_null ($user->social_links)){
            $this->facebook_url = $user->social_links->facebook_url;
            $this->twitter_url = $user->social_links->twitter_url;
            $this->instagram_url = $user->social_links->instagram_url;
            $this->youtube_url = $user->social_links->youtube_url;
            $this->linkedin_url = $user->social_links->linkedin_url;
            $this->github_url = $user->social_links->github_url;
        }
    }
    // ✅ IMAGE UPLOAD HANDLER
    public function updatedProfilePictureFile()
    {
        $user = User::findOrFail(auth()->id());
        $this->validate([
            'profilePictureFile' => 'image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Create filename
        $filename = 'user_' . $this->user->id . '_' . time() . '.' .
            $this->profilePictureFile->getClientOriginalExtension();

        // Save directly in public/
        $this->profilePictureFile->move(public_path(), $filename);

        // Optional: delete old image
        if ($user->picture && file_exists(public_path($user->picture))) {
            @unlink(public_path($user->picture));
        }

        // Update
        $user->update([
            'picture' => $filename
        ]);

        // Refresh user data
        $user = User::find($user->id);

        // Reset input
        $this->reset('profilePictureFile');

        // Toast
        $this->dispatch('showToastr', [
            'type' => 'success',
            'message' => 'Profile picture updated'
        ]);

        // Update header/profile UI
        $this->dispatch('updateTopUserInfo')->to(TopUserInfo::class);
    }

    public function updatePersonalDetails()
    {
        $this->validate([
            'name' => 'required|string|max:50',
            'username' => [
                'required',
                Rule::unique('users', 'username')->ignore($this->user->id),
            ],
        ]);

        $updated = $this->user->update([
            'name' => $this->name,
            'username' => $this->username,
            'bio' => $this->bio,
        ]);

        if ($updated) {
            $this->dispatch('showToastr', [
                'type' => 'success',
                'message' => 'Profile updated successfully'
            ]);
            $this->dispatch('updateTopUserInfo')->to(TopUserInfo::class);
        } else {
            $this->dispatch('showToastr', [
                'type' => 'error',
                'message' => 'Failed to update profile'
            ]);
        }
    }

    // update password
    public function updatePassword()
    {
        $user = User::findOrFail(auth()->id());

        $this->validate([
            'current_password' => [
                'required',
                function ($attribute, $value, $fail) use ($user) {
                    if (!Hash::check($value, $user->password)) {
                        return $fail('Current password does not match');
                    }
                },
            ],
            'new_password' => 'required|min:6|confirmed',
        ]);

        //update user password
        $updated = $user->update([
            'password' => Hash::make($this->new_password),
        ]);

        if ($updated) {
            //send email notification to this user
            $data = array(
                'user' => $user,
                'new_password' => $this->new_password,
            );

            $email_body = view('email_templates.password_changes_template', $data)->render();

            $mail_config = array(
                'recipient_name' => $user->name,
                'recipient_email' => $user->email,
                'subject' => 'Password Changed',
                'body' => $email_body,
            );

            CMail::sendMail($mail_config);
            auth()->logout();
            session()->flash('info', 'Password updated successfully. Please login again with your new password.');
            $this->redirectRoute('admin.login');
        } else {
            $this->dispatch('showToastr', [
                'type' => 'error',
                'message' => 'Failed to update password'
            ]);
        }
    }

    // update social links
    public function updateSocialLinks()
    {
        $this->validate([
            'facebook_url' => 'nullable|url',
            'twitter_url' => 'nullable|url',
            'instagram_url' => 'nullable|url',
            'youtube_url' => 'nullable|url',
            'linkedin_url' => 'nullable|url',
            'github_url' => 'nullable|url',
        ]);

        $user = User::findOrFail(auth()->id());
        $data = array(
            'facebook_url' => $this->facebook_url,
            'twitter_url' => $this->twitter_url,
            'instagram_url' => $this->instagram_url,
            'youtube_url' => $this->youtube_url,
            'linkedin_url' => $this->linkedin_url,
            'github_url' => $this->github_url,
        );

        if (!is_null($user->social_links)) {
            $query = $user->social_links->update($data);
        } else {
            $data['user_id'] = $user->id;
            $query = UserSocialLink::insert($data);
        }

        if ($query) {
            $this->dispatch('showToastr', [
                'type' => 'success',
                'message' => 'Social links updated successfully'
            ]);
        } else {
            $this->dispatch('showToastr', [
                'type' => 'error',
                'message' => 'Failed to update social links'
            ]);
        }
    }

    public function render()
    {
        return view('livewire.admin.profile', [
            'user' => User::findOrFail(auth()->id())
        ]);
    }
}
