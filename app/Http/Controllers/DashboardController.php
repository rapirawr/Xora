<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view('dashboard', compact('user'));
    }

    public function profile()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        return view('profile', compact('user'));
    }

    public function uploadProfilePhoto(Request $request)
    {
        $request->validate([
            'profile_photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = Auth::user();

        if ($request->hasFile('profile_photo')) {
            // Delete old profile photo if exists
            if ($user->profile_photo && \Storage::disk('public')->exists($user->profile_photo)) {
                \Storage::disk('public')->delete($user->profile_photo);
            }

            // Store new profile photo
            $path = $request->file('profile_photo')->store('profile-photos', 'public');

            // Update user profile photo
            $user->update(['profile_photo' => $path]);
        }

        return redirect()->back()->with('success', 'Profile photo updated successfully!');
    }
}
