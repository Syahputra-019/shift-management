<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Show the profile edit form.
     */
    public function edit(Request $request)
    {
        $user = $request->user();

        // Calculate statistics
        $totalShifts = $user->schedules()->whereNotNull('shift_id')->count();
        $totalSwapSent = $user->swapRequestsSent()->count();
        $totalSwapReceived = $user->swapRequestsReceived()->count();

        return view('profile', compact('user', 'totalShifts', 'totalSwapSent', 'totalSwapReceived'));
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'avatar_color' => [
                'required', 
                'string', 
                Rule::in([
                    'bg-blue-600',
                    'bg-indigo-600',
                    'bg-emerald-600',
                    'bg-purple-600',
                    'bg-pink-600',
                    'bg-amber-600',
                    'bg-cyan-600',
                    'bg-teal-600',
                    'bg-rose-600'
                ])
            ],
        ]);

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return redirect()->route('profile.edit')->with('success', 'Profil Anda berhasil diperbarui.');
    }

    /**
     * Update the user's password.
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $request->user()->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('profile.edit')->with('success', 'Kata sandi berhasil diperbarui.');
    }
}
