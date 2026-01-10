<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
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
            'first_name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'birth_month' => 'required|integer|min:1|max:12',
            'birth_day' => 'required|integer|min:1|max:31',
            'birth_year' => 'required|integer|min:1900|max:' . date('Y'),
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:255',
            'address' => 'required|string|max:500',
            'documents' => [
                'nullable',
                'array',
                'max:3',
            ],
            'documents.*' => [
                'file',
                'mimes:pdf,doc,docx,jpeg,png,jpg,gif',
                'max:3072',
            ],
            'delete_cv' => 'nullable|string',
            'delete_additional_files' => 'nullable|array',
            'delete_additional_files.*' => 'nullable|integer',
            'personal_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'driving_license_b' => 'nullable|boolean',
            'driving_license_own_car' => 'nullable|boolean',
            'other' => 'nullable|string|max:1000',
            'start_date_option' => 'required|in:immediately,one_month,two_three_months',
            'consent_type' => 'required|in:full,limited',
        ]);

        // Build date of birth
        $dateOfBirth = sprintf('%04d-%02d-%02d', $validated['birth_year'], $validated['birth_month'], $validated['birth_day']);
        
        // Validate date
        if (!checkdate($validated['birth_month'], $validated['birth_day'], $validated['birth_year'])) {
            return redirect()->back()->withErrors(['date_of_birth' => __('messages.validation.date_of_birth_invalid')])->withInput();
        }

        // Build phone with country code
        $phoneWithCode = ($request->input('phone_country_code', '+46') . ' ' . $validated['phone']);

        // Handle file deletions
        if ($request->has('delete_cv') && $request->input('delete_cv') == '1') {
            // Delete CV
            if ($application->cv_path && Storage::disk('public')->exists($application->cv_path)) {
                Storage::disk('public')->delete($application->cv_path);
            }
            $application->cv_path = null;
        }

        // Handle additional files deletion
        $additionalFiles = $application->additional_files ?? [];
        if ($request->has('delete_additional_files') && is_array($request->input('delete_additional_files'))) {
            $indexesToDelete = array_map('intval', $request->input('delete_additional_files'));
            // Sort in descending order to delete from end to beginning
            rsort($indexesToDelete);
            foreach ($indexesToDelete as $index) {
                if (isset($additionalFiles[$index])) {
                    // Delete file from storage
                    if (Storage::disk('public')->exists($additionalFiles[$index])) {
                        Storage::disk('public')->delete($additionalFiles[$index]);
                    }
                    // Remove from array
                    unset($additionalFiles[$index]);
                }
            }
            // Re-index array
            $additionalFiles = array_values($additionalFiles);
        }

        // Handle new documents upload
        if ($request->hasFile('documents') && count($request->file('documents')) > 0) {
            $documents = $request->file('documents');
            $currentFileCount = 0;
            
            // Count existing files (CV + additional files)
            if ($application->cv_path && !$request->has('delete_cv')) {
                $currentFileCount++;
            }
            $currentFileCount += count($additionalFiles);
            
            // Check total file count doesn't exceed 3
            if ($currentFileCount + count($documents) > 3) {
                return redirect()->back()->withErrors([
                    'documents' => __('messages.validation.max_files_exceeded', ['max' => 3])
                ])->withInput();
            }

            // First document becomes CV if no CV exists, otherwise all are additional files
            $hasCv = $application->cv_path && !($request->has('delete_cv') && $request->input('delete_cv') == '1');
            $firstIsCv = !$hasCv;
            $cvPath = $hasCv ? $application->cv_path : null;
            
            foreach ($documents as $index => $file) {
                try {
                    if ($index === 0 && $firstIsCv) {
                        // First file becomes CV
                        $cvPath = $file->store('cvs', 'public');
                    } else {
                        // Additional files
                        $filePath = $file->store('additional-files', 'public');
                        if ($filePath) {
                            $additionalFiles[] = $filePath;
                        }
                    }
                } catch (\Exception $e) {
                    // Log error but continue with other files
                    Log::error('File upload error: ' . $e->getMessage());
                }
            }
        } else {
            // No new files uploaded, keep existing CV if not deleted
            $cvPath = ($request->has('delete_cv') && $request->input('delete_cv') == '1') ? null : $application->cv_path;
        }

        // Handle personal image upload if provided
        $personalImagePath = $application->personal_image_path;
        if ($request->hasFile('personal_image')) {
            // Delete old image if exists
            if ($application->personal_image_path && Storage::disk('public')->exists($application->personal_image_path)) {
                Storage::disk('public')->delete($application->personal_image_path);
            }

            // Store new image
            $personalImagePath = $request->file('personal_image')->store('personal-images', 'public');
        }

        // Calculate start date based on option
        $startDate = null;
        switch ($validated['start_date_option']) {
            case 'immediately':
                $startDate = now()->toDateString();
                break;
            case 'one_month':
                $startDate = now()->addMonth()->toDateString();
                break;
            case 'two_three_months':
                // Set to 2.5 months (average of 2-3 months)
                $startDate = now()->addMonths(2)->addDays(15)->toDateString();
                break;
        }

        $application->update([
            'first_name' => $validated['first_name'],
            'surname' => $validated['surname'],
            'date_of_birth' => $dateOfBirth,
            'phone' => $phoneWithCode,
            'address' => $validated['address'],
            'email' => $validated['email'],
            'cv_path' => $cvPath,
            'additional_files' => !empty($additionalFiles) ? $additionalFiles : null,
            'personal_image_path' => $personalImagePath,
            'start_date_option' => $validated['start_date_option'],
            'start_date' => $startDate,
            'consent_type' => $validated['consent_type'],
            'driving_license_b' => $request->has('driving_license_b'),
            'driving_license_own_car' => $request->has('driving_license_own_car'),
            'other' => $validated['other'] ?? null,
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
