<?php

namespace App\Http\Controllers;

use App\Mail\NewJobApplicationMail;
use App\Models\Application;
use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

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
        // Check if authenticated user already applied to this job
        if (auth()->check()) {
            $existingApplication = Application::where('job_id', $job->id)
                ->where('user_id', auth()->id())
                ->first();

            if ($existingApplication) {
                return redirect()->back()->withErrors([
                    'application' => __('messages.validation.already_applied'),
                ])->withInput();
            }
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
            'driving_license_category_1' => 'nullable|boolean',
            'driving_license_category_2' => 'nullable|boolean',
            'driving_license_category_3' => 'nullable|boolean',
            'driving_license_category_4' => 'nullable|boolean',
            'driving_license_category_5' => 'nullable|boolean',
            'driving_license_category_6' => 'nullable|boolean',
            'driving_license_own_car' => 'nullable|boolean',
            'cover_letter' => 'required|string',
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

        $application = Application::create([
            'job_id' => $job->id,
            'user_id' => auth()->id() ?? null,
            'email' => $validated['email'],
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
            'is_spontaneous' => false,
            'status' => 'pending',
            'consent_type' => $validated['consent_type'],
            'start_date_option' => $validated['start_date_option'],
            'start_date' => $startDate,
            'driving_license_b' => $request->has('driving_license_b'),
            'driving_license_category_1' => $request->has('driving_license_category_1'),
            'driving_license_category_2' => $request->has('driving_license_category_2'),
            'driving_license_category_3' => $request->has('driving_license_category_3'),
            'driving_license_category_4' => $request->has('driving_license_category_4'),
            'driving_license_category_5' => $request->has('driving_license_category_5'),
            'driving_license_category_6' => $request->has('driving_license_category_6'),
            'driving_license_own_car' => $request->has('driving_license_own_car'),
            'additional_information' => $validated['additional_information'] ?? null,
        ]);

        if (auth()->check()) {
            $user = auth()->user();
            if (! $user->gdpr_consent_at) {
                $user->update(['gdpr_consent_at' => now()]);
            }
        }

        try {
            Mail::to('abdulrazek.mahmoud@vus-bemanning.se')
                ->send(new NewJobApplicationMail($application));
        } catch (\Exception $e) {
            \Log::error('Failed to send job application email: ' . $e->getMessage());
        }

        return redirect()->route('jobs.show', $job)->with('success', __('messages.validation.application_sent'));
    }

    public function submitSpontaneous(Request $request)
    {
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
            'driving_license_category_1' => 'nullable|boolean',
            'driving_license_category_2' => 'nullable|boolean',
            'driving_license_category_3' => 'nullable|boolean',
            'driving_license_category_4' => 'nullable|boolean',
            'driving_license_category_5' => 'nullable|boolean',
            'driving_license_category_6' => 'nullable|boolean',
            'driving_license_own_car' => 'nullable|boolean',
            'cover_letter' => 'required|string',
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

        $application = Application::create([
            'job_id' => null,
            'user_id' => auth()->id() ?? null,
            'email' => $validated['application_email'],
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
            'consent_type' => $validated['consent_type'],
            'start_date_option' => $validated['start_date_option'],
            'start_date' => $startDate,
            'driving_license_b' => $request->has('driving_license_b'),
            'driving_license_category_1' => $request->has('driving_license_category_1'),
            'driving_license_category_2' => $request->has('driving_license_category_2'),
            'driving_license_category_3' => $request->has('driving_license_category_3'),
            'driving_license_category_4' => $request->has('driving_license_category_4'),
            'driving_license_category_5' => $request->has('driving_license_category_5'),
            'driving_license_category_6' => $request->has('driving_license_category_6'),
            'driving_license_own_car' => $request->has('driving_license_own_car'),
        ]);

        if (auth()->check()) {
            $user = auth()->user();
            if (! $user->gdpr_consent_at) {
                $user->update(['gdpr_consent_at' => now()]);
            }
        }

        try {
            Mail::to('abdulrazek.mahmoud@vus-bemanning.se')
                ->send(new NewJobApplicationMail($application));
        } catch (\Exception $e) {
            \Log::error('Failed to send spontaneous application email: ' . $e->getMessage());
        }

        return redirect()->route('jobs.apply-spontaneous')->with('success', __('messages.validation.spontaneous_sent'));
    }
}
