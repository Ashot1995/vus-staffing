<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();

        // Handle CV upload
        if ($request->hasFile('cv')) {
            // Delete old CV if exists
            if ($user->cv_path && Storage::disk('public')->exists($user->cv_path)) {
                Storage::disk('public')->delete($user->cv_path);
            }

            // Store new CV
            $cvPath = $request->file('cv')->store('cvs', 'public');
            $user->cv_path = $cvPath;
            
            // Reset reminder count when user updates CV (they're maintaining profile)
            $user->profile_reminder_count = 0;
            $user->last_profile_reminder_at = now();
            
            $user->save();

            return Redirect::route('profile.edit')->with('status', 'profile-updated');
        }

        // Update profile information (name, email)
        $validated = $request->validated();
        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        // Reset reminder count when user updates profile (they're maintaining it)
        $user->profile_reminder_count = 0;
        $user->last_profile_reminder_at = now();

        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Display the user's applications.
     */
    public function applications(Request $request): View
    {
        $user = $request->user();
        $applications = $user->applications()->with('job')->latest()->get();

        return view('profile.applications', [
            'user' => $user,
            'applications' => $applications,
        ]);
    }

    /**
     * Display the edit form for an application.
     */
    public function editApplication(Request $request, $id): View
    {
        $user = $request->user();
        $application = $user->applications()->with('job')->findOrFail($id);

        return view('profile.application-edit', [
            'user' => $user,
            'application' => $application,
        ]);
    }

    /**
     * Update an application.
     */
    public function updateApplication(Request $request, $id): RedirectResponse
    {
        $user = $request->user();
        $application = $user->applications()->findOrFail($id);

        $validated = $request->validate([
            'cover_letter' => 'required|string',
            'cv' => 'nullable|file|mimes:pdf,doc,docx|max:3072',
            'start_date_option' => 'required|in:immediately,one_week,one_month,custom',
            'start_date' => 'required_if:start_date_option,custom|nullable|date|after_or_equal:today',
        ]);

        // Handle CV upload if provided
        if ($request->hasFile('cv')) {
            // Delete old CV if exists
            if ($application->cv_path && Storage::disk('public')->exists($application->cv_path)) {
                Storage::disk('public')->delete($application->cv_path);
            }

            // Store new CV
            $cvPath = $request->file('cv')->store('cvs', 'public');
            $application->cv_path = $cvPath;
        }

        // Calculate start date based on option
        $startDate = null;
        switch ($validated['start_date_option']) {
            case 'immediately':
                $startDate = now()->toDateString();
                break;
            case 'one_week':
                $startDate = now()->addWeek()->toDateString();
                break;
            case 'one_month':
                $startDate = now()->addMonth()->toDateString();
                break;
            case 'custom':
                $startDate = $validated['start_date'];
                break;
        }

        $application->update([
            'cover_letter' => $validated['cover_letter'],
            'start_date_option' => $validated['start_date_option'],
            'start_date' => $startDate,
        ]);

        return Redirect::route('profile.applications')->with('status', 'application-updated');
    }

    /**
     * Confirm profile maintenance reminder.
     */
    public function confirmReminder(Request $request, User $user): RedirectResponse
    {
        // Verify token - check current month and previous month for validity
        $token = $request->query('token');
        $currentToken = hash('sha256', $user->id . $user->email . config('app.key') . now()->format('Y-m'));
        $previousToken = hash('sha256', $user->id . $user->email . config('app.key') . now()->subMonth()->format('Y-m'));
        
        if ($token !== $currentToken && $token !== $previousToken) {
            return Redirect::route('home')->with('error', 'Invalid or expired confirmation link.');
        }

        // Reset reminder count and update last reminder date
        $user->update([
            'profile_reminder_count' => 0,
            'last_profile_reminder_at' => now(),
        ]);

        // If user is not logged in, log them in
        if (!Auth::check()) {
            Auth::login($user);
        }

        return Redirect::route('profile.edit')->with('status', 'profile-maintained');
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
