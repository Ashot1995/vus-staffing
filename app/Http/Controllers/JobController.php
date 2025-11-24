<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\Application;
use Illuminate\Http\Request;

class JobController extends Controller
{
    public function index(Request $request)
    {
        $query = Job::where('is_published', true);

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('location')) {
            $query->where('location', 'like', '%' . $request->location . '%');
        }

        if ($request->filled('employment_type')) {
            $query->where('employment_type', $request->employment_type);
        }

        $jobs = $query->latest()->paginate(10);

        return view('pages.jobs', compact('jobs'));
    }

    public function show(Job $job)
    {
        if (!$job->is_published) {
            abort(404);
        }

        return view('pages.job-detail', compact('job'));
    }

    public function spontaneous()
    {
        return view('pages.spontaneous');
    }

    public function apply(Job $job)
    {
        if (!$job->is_published) {
            abort(404);
        }

        return view('pages.apply', compact('job'));
    }

    public function submitApplication(Request $request, Job $job)
    {
        $validated = $request->validate([
            'cover_letter' => 'required|string',
            'cv' => 'required|file|mimes:pdf,doc,docx|max:5120',
        ]);

        $cvPath = $request->file('cv')->store('cvs', 'public');

        Application::create([
            'job_id' => $job->id,
            'user_id' => auth()->id(),
            'cv_path' => $cvPath,
            'cover_letter' => $validated['cover_letter'],
            'is_spontaneous' => false,
            'status' => 'pending',
        ]);

        return redirect()->route('jobs.show', $job)->with('success', 'Din ansökan har skickats!');
    }

    public function submitSpontaneous(Request $request)
    {
        $validated = $request->validate([
            'cover_letter' => 'required|string',
            'cv' => 'required|file|mimes:pdf,doc,docx|max:5120',
        ]);

        $cvPath = $request->file('cv')->store('cvs', 'public');

        Application::create([
            'job_id' => null,
            'user_id' => auth()->id(),
            'cv_path' => $cvPath,
            'cover_letter' => $validated['cover_letter'],
            'is_spontaneous' => true,
            'status' => 'pending',
        ]);

        return redirect()->route('jobs.apply-spontaneous')->with('success', 'Din spontanansökan har skickats!');
    }
}
