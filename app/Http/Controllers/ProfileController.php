<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function uploadAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $user = Auth::user();

        // Delete old avatar
        if ($user->profile_image) {
            Storage::delete('public/' . $user->profile_image);
        }

        $path = $request->file('avatar')->store('avatars', 'public');

        $user->update([
            'profile_image' => $path
        ]);

        return back()->with('success', 'Profile picture updated!');
    }

    public function uploadResume(Request $request)
    {
        $request->validate([
            'resume' => 'required|file|mimes:pdf,doc,docx|max:5120'
        ]);

        $path = $request->file('resume')->store('resumes', 'public');

        // Save resume path in database
        Auth::user()->update([
            'resume' => $path
        ]);

        return back()->with('success', 'Resume uploaded successfully!');
    }
}