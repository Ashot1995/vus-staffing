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
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:255',
            'address' => 'required|string|max:500',
            'cv' => [
                'required',
                'file',
                'mimes:pdf,doc,docx',
                'max:3072',
            ],
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

        // Handle CV upload with error handling
        try {
            if (!$request->hasFile('cv')) {
                return redirect()->back()->withErrors(['cv' => __('messages.validation.cv_required')])->withInput();
            }
            
            $cvFile = $request->file('cv');
            $cvPath = $cvFile->store('cvs', 'public');
            
            if (!$cvPath) {
                return redirect()->back()->withErrors(['cv' => __('messages.validation.cv_upload_failed')])->withInput();
            }
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['cv' => __('messages.validation.cv_upload_error', ['message' => $e->getMessage()])])->withInput();
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
            'phone' => $phoneWithCode,
            'address' => $validated['address'],
            'cv_path' => $cvPath,
            'personal_image_path' => $personalImagePath,
            'cover_letter' => '', // Not in new form, but keep for compatibility
            'is_spontaneous' => false,
            'status' => 'pending',
            'consent_type' => $validated['consent_type'],
            'start_date_option' => $validated['start_date_option'],
            'start_date' => $startDate,
            'driving_license_b' => $request->has('driving_license_b'),
            'driving_license_own_car' => $request->has('driving_license_own_car'),
            'other' => $validated['other'] ?? null,
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
        $validated = $request->validate([
            'cover_letter' => 'required|string',
            'cv' => 'required|file|mimes:pdf,doc,docx|max:3072',
            'gdpr_consent' => 'required|accepted',
            'start_date_option' => 'required|in:immediately,one_week,one_month,custom',
            'start_date' => 'required_if:start_date_option,custom|nullable|date|after_or_equal:today',
        ]);

        $cvPath = $request->file('cv')->store('cvs', 'public');

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
            'cv_path' => $cvPath,
            'cover_letter' => $validated['cover_letter'],
            'is_spontaneous' => true,
            'status' => 'pending',
            'gdpr_consent' => true,
            'gdpr_consent_at' => now(),
            'consent_type' => $validated['consent_type'],
            'start_date_option' => $validated['start_date_option'],
            'start_date' => $startDate,
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
