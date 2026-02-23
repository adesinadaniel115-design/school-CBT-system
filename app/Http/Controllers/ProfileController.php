<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function editAdmin()
    {
        return view('admin.profile', ['user' => auth()->user()]);
    }

    public function editStudent()
    {
        return view('student.profile', ['user' => auth()->user()]);
    }

    public function updateAdmin(Request $request)
    {
        $this->updateProfile($request);

        return redirect()->route('admin.profile.edit')
            ->with('status', 'Profile updated successfully.');
    }

    public function updateStudent(Request $request)
    {
        $this->updateProfile($request);

        return redirect()->route('student.profile.edit')
            ->with('status', 'Profile updated successfully.');
    }

    private function updateProfile(Request $request): void
    {
        $user = $request->user();

        $request->validate([
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ]);

        if ($request->filled('password')) {
            $request->validate([
                'current_password' => ['required', 'current_password'],
                'password' => ['string', 'min:6', 'confirmed'],
            ]);
        }

        if ($request->hasFile('photo')) {
            if ($user->profile_photo_path) {
                Storage::disk('public')->delete($user->profile_photo_path);
            }

            $path = $request->file('photo')->store('profile-photos', 'public');
            $user->profile_photo_path = $path;
        } elseif ($request->boolean('remove_photo')) {
            if ($user->profile_photo_path) {
                Storage::disk('public')->delete($user->profile_photo_path);
            }

            $user->profile_photo_path = null;
        }

        if ($request->filled('password')) {
            $user->password = Hash::make($request->input('password'));
        }

        $user->save();
    }
}
