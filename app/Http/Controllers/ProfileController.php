<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdatePasswordRequest;
use App\Http\Requests\UpdateProfileRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the authenticated user's profile and settings.
     */
    public function show(Request $request): View
    {
        $user = $request->user();
        $user->loadCount(['createdOrders', 'enteredResults', 'generatedReports']);

        return view('profile.show', compact('user'));
    }

    /**
     * Update the authenticated user's profile information.
     */
    public function update(UpdateProfileRequest $request): RedirectResponse
    {
        $request->user()->update($request->validated());

        return redirect()
            ->route('profile.show')
            ->with('success', 'Your profile information has been updated successfully.');
    }

    /**
     * Update the authenticated user's password.
     */
    public function updatePassword(UpdatePasswordRequest $request): RedirectResponse
    {
        $request->user()->update([
            'password' => Hash::make($request->validated('password')),
        ]);

        return redirect()
            ->route('profile.show')
            ->with('success', 'Your password has been changed successfully.');
    }
}
