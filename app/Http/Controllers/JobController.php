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
                'application' => 'You have already applied to this job. You can edit your application from your profile page.',
            ])->withInput();
        }

        $validated = $request->validate([
            'cover_letter' => 'required|string',
            'cv' => 'required|file|mimes:pdf,doc,docx|max:5120',
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

        Application::create([
            'job_id' => $job->id,
            'user_id' => auth()->id(),
            'cv_path' => $cvPath,
            'cover_letter' => $validated['cover_letter'],
            'is_spontaneous' => false,
            'status' => 'pending',
            'gdpr_consent' => true,
            'gdpr_consent_at' => now(),
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

        return redirect()->route('jobs.show', $job)->with('success', 'Din ansökan har skickats!');
    }

    public function submitSpontaneous(Request $request)
    {
        $validated = $request->validate([
            'cover_letter' => 'required|string',
            'cv' => 'required|file|mimes:pdf,doc,docx|max:5120',
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

        Application::create([
            'job_id' => null,
            'user_id' => auth()->id(),
            'cv_path' => $cvPath,
            'cover_letter' => $validated['cover_letter'],
            'is_spontaneous' => true,
            'status' => 'pending',
            'gdpr_consent' => true,
            'gdpr_consent_at' => now(),
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

        return redirect()->route('jobs.apply-spontaneous')->with('success', 'Din spontanansökan har skickats!');
    }
}
