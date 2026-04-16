<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $user = $request->user()->fresh();

        if ($user->hasRole('student')) {
            $user->load([
                'clubMemberships.club',
                'eventRegistrations.event'
            ]);
            return view('profile.student', compact('user'));
        }

        return view('profile.edit', compact('user'));
    }

    /**
     * Update the user's profile photo.
     */
    public function updatePhoto(Request $request): RedirectResponse
    {
        $request->validate([
            'profile_photo' => 'required|image|max:2048',
        ]);

        $user = $request->user();

        if ($user->profile_photo) {
            if (\Storage::disk('uploads')->exists($user->profile_photo)) {
                \Storage::disk('uploads')->delete($user->profile_photo);
            } else {
                \Storage::disk('public')->delete($user->profile_photo);
            }
        }

        $path = $request->file('profile_photo')->store('profiles', 'uploads');
        $user->update(['profile_photo' => $path]);

        // Auth cache'ini yenile
        auth()->setUser($user->fresh());

        return back()->with('photo_success', 'Profil fotoğrafınız güncellendi.');
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
