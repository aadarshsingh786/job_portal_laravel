<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        return view('profile.show', compact('user'));
    }

    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'bio' => 'nullable|string|max:2000',
            'company_name' => 'nullable|string|max:255',
            'company_website' => 'nullable|url|max:255',
        ]);

        $user->update($validated);

        return redirect()->route('profile.show')->with('success', 'Profile updated successfully!');
    }

    public function uploadAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120'
        ]);

        $user = Auth::user();

        // Delete old avatar
        if ($user->profile_image) {
            Storage::delete('public/' . $user->profile_image);
        }

        $file = $request->file('avatar');
        $path = $this->processAvatar($file);

        $user->update([
            'profile_image' => $path
        ]);

        return back()->with('success', 'Profile picture updated!');
    }

    /**
     * Resize and center-crop the avatar to a small square thumbnail
     * so uploaded photos never stay huge.
     */
    protected function processAvatar($file)
    {
        $size = 300;
        $mime = $file->getMimeType();

        switch ($mime) {
            case 'image/png':
                $source = @imagecreatefrompng($file->getRealPath());
                break;
            case 'image/gif':
                $source = @imagecreatefromgif($file->getRealPath());
                break;
            default:
                $source = @imagecreatefromjpeg($file->getRealPath());
                $mime = 'image/jpeg';
                break;
        }

        if (!$source) {
            return $file->store('avatars', 'public');
        }

        $width = imagesx($source);
        $height = imagesy($source);

        // Center-crop to a square
        $cropSize = min($width, $height);
        $srcX = intdiv($width - $cropSize, 2);
        $srcY = intdiv($height - $cropSize, 2);

        $thumb = imagecreatetruecolor($size, $size);

        // Preserve transparency for PNG/GIF
        if (in_array($mime, ['image/png', 'image/gif'])) {
            imagealphablending($thumb, false);
            imagesavealpha($thumb, true);
            $transparent = imagecolorallocatealpha($thumb, 0, 0, 0, 127);
            imagefill($thumb, 0, 0, $transparent);
        }

        imagecopyresampled($thumb, $source, 0, 0, $srcX, $srcY, $size, $size, $cropSize, $cropSize);

        $filename = 'avatar-' . uniqid() . '.' . ($mime === 'image/png' ? 'png' : ($mime === 'image/gif' ? 'gif' : 'jpg'));
        $tmpPath = sys_get_temp_dir() . '/' . $filename;

        if ($mime === 'image/png') {
            imagepng($thumb, $tmpPath, 9);
        } elseif ($mime === 'image/gif') {
            imagegif($thumb, $tmpPath);
        } else {
            imagejpeg($thumb, $tmpPath, 90);
        }

        $storedPath = Storage::disk('public')->putFileAs('avatars', $tmpPath, $filename);
        @unlink($tmpPath);

        return $storedPath;
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