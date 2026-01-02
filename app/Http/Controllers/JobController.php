<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Job;
use Illuminate\Http\Request;

class JobController extends Controller
{
    public function index(Request $request)
    {
        $query = Job::where('is_published', true);

        if ($request->filled('search')) {
            $query->where('title', 'like', '%'.$request->search.'%');
        }

        if ($request->filled('location')) {
            $query->where('location', 'like', '%'.$request->location.'%');
        }

        if ($request->filled('employment_type')) {
            $query->where('employment_type', $request->employment_type);
        }

        $jobs = $query->latest()->paginate(10);

        return view('pages.jobs', compact('jobs'));
    }

    public function show(Job $job)
    {
        if (! $job->is_published) {
            abort(404);
        }

        $existingApplication = null;
        if (auth()->check()) {
            $existingApplication = Application::where('job_id', $job->id)
                ->where('user_id', auth()->id())
                ->first();
        }

        return view('pages.job-detail', compact('job', 'existingApplication'));
    }

    public function spontaneous()
    {
        return view('pages.spontaneous');
    }

    public function apply(Job $job)
    {
        if (! $job->is_published) {
            abort(404);
        }

        return view('pages.apply', compact('job'));
    }

    public function submitApplication(Request $request, Job $job)
    {
        // Check if user already applied to this job
        $existingApplication = Application::where('job_id', $job->id)
            ->where('user_id', auth()->id())
            ->first();

        if ($existingApplication) {
            return redirect()->back()->withErrors([
                'application' => __('messages.validation.already_applied'),
            ])->withInput();
        }

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'birth_month' => 'required|integer|min:1|max:12',
            'birth_day' => 'required|integer|min:1|max:31',
            'birth_year' => 'required|integer|min:1900|max:' . date('Y'),
            'is_18_or_older' => 'required|boolean',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:255',
            'address' => 'required|string|max:500',
            'documents' => [
                'required',
                'array',
                'min:1',
                'max:3',
            ],
            'documents.*' => [
                'required',
                'file',
                'mimes:pdf,doc,docx,jpeg,png,jpg,gif',
                'max:3072',
            ],
            'personal_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'driving_license_b' => 'nullable|boolean',
            'driving_license_own_car' => 'nullable|boolean',
            'additional_information' => 'nullable|string|max:2000',
            'start_date_option' => 'required|in:immediately,one_month,two_three_months',
            'consent_type' => 'required|in:full,limited',
        ]);

        // Build date of birth
        $dateOfBirth = sprintf('%04d-%02d-%02d', $validated['birth_year'], $validated['birth_month'], $validated['birth_day']);
        
        // Validate date
        if (!checkdate($validated['birth_month'], $validated['birth_day'], $validated['birth_year'])) {
            return redirect()->back()->withErrors(['date_of_birth' => __('messages.validation.date_of_birth_invalid')])->withInput();
        }

        // Handle documents upload - first file is CV, rest are additional files
        $cvPath = null;
        $additionalFiles = [];
        
        try {
            if (!$request->hasFile('documents') || count($request->file('documents')) === 0) {
                return redirect()->back()->withErrors(['documents' => __('messages.validation.cv_required')])->withInput();
            }
            
            $documents = $request->file('documents');
            
            // First file is CV
            $cvFile = $documents[0];
            $cvPath = $cvFile->store('cvs', 'public');
            
            if (!$cvPath) {
                return redirect()->back()->withErrors(['documents' => __('messages.validation.cv_upload_failed')])->withInput();
            }
            
            // Remaining files are additional files
            for ($i = 1; $i < count($documents); $i++) {
                try {
                    $file = $documents[$i];
                    $filePath = $file->store('additional-files', 'public');
                    if ($filePath) {
                        $additionalFiles[] = $filePath;
                    }
                } catch (\Exception $e) {
                    // Log error but don't fail the entire submission
                }
            }
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['documents' => __('messages.validation.cv_upload_error', ['message' => $e->getMessage()])])->withInput();
        }

        $personalImagePath = null;
        if ($request->hasFile('personal_image')) {
            $personalImagePath = $request->file('personal_image')->store('personal-images', 'public');
        }

        // Build phone with country code
        $phoneWithCode = ($request->input('phone_country_code', '+46') . ' ' . $validated['phone']);

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

        Application::create([
            'job_id' => $job->id,
            'user_id' => auth()->id(),
            'first_name' => $validated['first_name'],
            'surname' => $validated['surname'],
            'date_of_birth' => $dateOfBirth,
            'is_18_or_older' => (bool)$validated['is_18_or_older'],
            'phone' => $phoneWithCode,
            'address' => $validated['address'],
            'cv_path' => $cvPath,
            'additional_files' => !empty($additionalFiles) ? $additionalFiles : null,
            'personal_image_path' => $personalImagePath,
            'cover_letter' => '', // Not in new form, but keep for compatibility
            'is_spontaneous' => false,
            'status' => 'pending',
            'consent_type' => $validated['consent_type'],
            'start_date_option' => $validated['start_date_option'],
            'start_date' => $startDate,
            'driving_license_b' => $request->has('driving_license_b'),
            'driving_license_own_car' => $request->has('driving_license_own_car'),
            'additional_information' => $validated['additional_information'] ?? null,
        ]);

        // Update user GDPR consent if not set
        $user = auth()->user();
        if (! $user->gdpr_consent_at) {
            $user->update([
                'gdpr_consent_at' => now(),
            ]);
        }

        return redirect()->route('jobs.show', $job)->with('success', __('messages.validation.application_sent'));
    }

    public function submitSpontaneous(Request $request)
    {
        // Handle registration if user is not authenticated
        if (!auth()->check()) {
            $registrationValidated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users'],
                'password' => ['required', 'confirmed', \Illuminate\Validation\Rules\Password::defaults()],
            ]);

            $user = \App\Models\User::create([
                'name' => $registrationValidated['name'],
                'email' => $registrationValidated['email'],
                'password' => \Illuminate\Support\Facades\Hash::make($registrationValidated['password']),
            ]);

            \Illuminate\Support\Facades\Auth::login($user);
            \Illuminate\Auth\Events\Registered::dispatch($user);
        }

        // Validate application data
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'birth_month' => 'required|integer|min:1|max:12',
            'birth_day' => 'required|integer|min:1|max:31',
            'birth_year' => 'required|integer|min:1900|max:' . date('Y'),
            'is_18_or_older' => 'required|boolean',
            'application_email' => 'required|email|max:255',
            'phone' => 'required|string|max:255',
            'address' => 'required|string|max:500',
            'documents' => [
                'required',
                'array',
                'min:1',
                'max:3',
            ],
            'documents.*' => [
                'required',
                'file',
                'mimes:pdf,doc,docx,jpeg,png,jpg,gif',
                'max:3072',
            ],
            'personal_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'driving_license_b' => 'nullable|boolean',
            'driving_license_own_car' => 'nullable|boolean',
            'cover_letter' => 'required|string',
            'additional_information' => 'nullable|string|max:2000',
            'start_date_option' => 'required|in:immediately,one_month,two_three_months',
            'spontaneous_consent' => 'required|accepted',
        ]);

        // Build date of birth
        $dateOfBirth = sprintf('%04d-%02d-%02d', $validated['birth_year'], $validated['birth_month'], $validated['birth_day']);
        
        // Validate date
        if (!checkdate($validated['birth_month'], $validated['birth_day'], $validated['birth_year'])) {
            return redirect()->back()->withErrors(['date_of_birth' => __('messages.validation.date_of_birth_invalid')])->withInput();
        }

        // Handle documents upload - first file is CV, rest are additional files
        $cvPath = null;
        $additionalFiles = [];
        
        try {
            if (!$request->hasFile('documents') || count($request->file('documents')) === 0) {
                return redirect()->back()->withErrors(['documents' => __('messages.validation.cv_required')])->withInput();
            }
            
            $documents = $request->file('documents');
            
            // First file is CV
            $cvFile = $documents[0];
            $cvPath = $cvFile->store('cvs', 'public');
            
            if (!$cvPath) {
                return redirect()->back()->withErrors(['documents' => __('messages.validation.cv_upload_failed')])->withInput();
            }
            
            // Remaining files are additional files
            for ($i = 1; $i < count($documents); $i++) {
                try {
                    $file = $documents[$i];
                    $filePath = $file->store('additional-files', 'public');
                    if ($filePath) {
                        $additionalFiles[] = $filePath;
                    }
                } catch (\Exception $e) {
                    // Log error but don't fail the entire submission
                }
            }
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['documents' => __('messages.validation.cv_upload_error', ['message' => $e->getMessage()])])->withInput();
        }

        $personalImagePath = null;
        if ($request->hasFile('personal_image')) {
            $personalImagePath = $request->file('personal_image')->store('personal-images', 'public');
        }

        // Build phone with country code
        $phoneWithCode = ($request->input('phone_country_code', '+46') . ' ' . $validated['phone']);

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

        Application::create([
            'job_id' => null,
            'user_id' => auth()->id(),
            'first_name' => $validated['first_name'],
            'surname' => $validated['surname'],
            'date_of_birth' => $dateOfBirth,
            'is_18_or_older' => (bool)$validated['is_18_or_older'],
            'phone' => $phoneWithCode,
            'address' => $validated['address'],
            'cv_path' => $cvPath,
            'additional_files' => !empty($additionalFiles) ? $additionalFiles : null,
            'personal_image_path' => $personalImagePath,
            'cover_letter' => $validated['cover_letter'],
            'additional_information' => $validated['additional_information'] ?? null,
            'is_spontaneous' => true,
            'status' => 'pending',
            'consent_type' => 'full', // Spontaneous applications always use full consent
            'start_date_option' => $validated['start_date_option'],
            'start_date' => $startDate,
            'driving_license_b' => $request->has('driving_license_b'),
            'driving_license_own_car' => $request->has('driving_license_own_car'),
        ]);

        // Update user GDPR consent if not set
        $user = auth()->user();
        if (! $user->gdpr_consent_at) {
            $user->update([
                'gdpr_consent_at' => now(),
            ]);
        }

        return redirect()->route('jobs.apply-spontaneous')->with('success', __('messages.validation.spontaneous_sent'));
    }
}
