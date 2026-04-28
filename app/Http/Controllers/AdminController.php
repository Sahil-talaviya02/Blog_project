<?php

namespace App\Http\Controllers;

use App\Models\GeneralSetting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use SawaStacks\Utils\Kropify as UtilsKropify;

class AdminController extends Controller
{
    public function adminDashboard()
    {
        $data = [
            'pageTitle' => 'Admin Dashboard'
        ];
        return view('back.pages.dashboard', $data);
    }
    
    // ✅ Categories Page
    public function categoriesPage(Request $request)
    {
        $data = [
            'pageTitle' => 'Categories Page'
        ];
        return view('back.pages.categories_page', $data);
    }

    public function logoutHandler(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login')->with('success', 'Logged out successfully');
    }

    public function profileView(Request $request)
    {
        $data = [
            'pageTitle' => 'Admin Profile'
        ];
        return view('back.pages.profile', $data);
    }

    // ✅ Update Profile Image
    public function updateLogo(Request $request)
    {
        $request->validate([
            'site_logo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'site_favicon' => 'nullable|image|mimes:jpg,jpeg,png,ico|max:1024',
        ]);

        $setting = GeneralSetting::first();

        if (!$setting) {
            return response()->json([
                'status' => 0,
                'msg' => 'Update general settings first'
            ]);
        }

        $path = 'images/logos/';

        // ✅ Upload function
        $uploadFile = function ($file, $oldFile, $prefix) use ($path) {
            $filename = $prefix . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

            $file->move(public_path($path), $filename);

            if ($oldFile && file_exists(public_path($path . $oldFile))) {
                File::delete(public_path($path . $oldFile));
            }

            return $filename;
        };

        // ✅ Logo
        if ($request->hasFile('site_logo')) {
            $setting->site_logo = $uploadFile(
                $request->file('site_logo'),
                $setting->site_logo,
                'logo'
            );
        }

        // ✅ Favicon
        if ($request->hasFile('site_favicon')) {
            $setting->site_favicon = $uploadFile(
                $request->file('site_favicon'),
                $setting->site_favicon,
                'favicon'
            );
        }

        $setting->save();

        return response()->json([
            'status' => 1,
            'msg' => 'Updated successfully',
            'logo' => asset($path . $setting->site_logo),
            'favicon' => asset($path . $setting->site_favicon),
        ]);
    }

    // ✅ Update Personal Details
    public function updateProfile(Request $request)
    {
        $user = User::findOrFail(auth()->id());

        $request->validate([
            'name' => 'required|string|max:50',
            'username' => 'required|unique:users,username,' . $user->id,
        ]);

        $updated = $user->update([
            'name' => $request->name,
            'username' => $request->username,
            'bio' => $request->bio,
        ]);

        if ($updated) {
            return response()->json([
                'status' => 1,
                'msg' => 'Profile updated successfully'
            ]);
        }

        return response()->json([
            'status' => 0,
            'msg' => 'Failed to update profile'
        ]);
    }

    // ✅ General Settings
    public function generalSettings()
    {
        $data = [
            'pageTitle' => 'General Settings'
        ];
        return view('back.pages.general_settings', $data);
    }

}
