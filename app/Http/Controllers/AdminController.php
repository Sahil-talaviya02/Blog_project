<?php

namespace App\Http\Controllers;

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
    public function updateProfilePicture(Request $request)
    {
        // Validate
        $request->validate([
            'profilePictureFile' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $user = User::findOrFail(auth()->id());

        // Create filename  
        $filename = 'user_' . $user->id . '_' . time() . '.' .
            $request->file('profilePictureFile')->getClientOriginalExtension();

        // Save directly into public/
        $request->file('profilePictureFile')->move(public_path('images/users'), $filename);

        // Delete old image (if exists)
        if ($user->picture && file_exists(public_path('images/users/' . $user->picture))) {
            @unlink(public_path('images/users/' . $user->picture));
        }

        // Save in DB
        $user->update([
            'picture' => $filename
        ]);

        return response()->json([
            'status' => 1,
            'msg' => 'Profile image updated successfully',
            'url' => asset('images/users/' . $filename)
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
