<?php

namespace App\Http\Controllers;

use App\Models\Job; // <-- Add this import
use App\Models\JobApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JobController extends Controller
{
    public function index(Request $request)
    {
        // Start with active jobs query
        $query = Job::with('employer')->active();

        // Search by keyword (title, description, company)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%")
                  ->orWhere('category', 'LIKE', "%{$search}%")
                  ->orWhereHas('employer', function($q2) use ($search) {
                      $q2->where('name', 'LIKE', "%{$search}%")
                         ->orWhere('company_name', 'LIKE', "%{$search}%");
                  });
            });
        }

        // Filter by location
        if ($request->filled('location')) {
            $query->where('location', 'LIKE', "%{$request->location}%");
        }

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Filter by job type
        if ($request->filled('job_type')) {
            $query->where('job_type', $request->job_type);
        }

        // Filter by experience level
        if ($request->filled('experience_level')) {
            $query->where('experience_level', $request->experience_level);
        }

        // Filter by salary range
        if ($request->filled('salary_min')) {
            $query->where('salary_min', '>=', $request->salary_min);
        }

        if ($request->filled('salary_max')) {
            $query->where('salary_max', '<=', $request->salary_max);
        }

        // Sort options
        if ($request->filled('sort')) {
            switch($request->sort) {
                case 'latest':
                    $query->orderBy('created_at', 'desc');
                    break;
                case 'oldest':
                    $query->orderBy('created_at', 'asc');
                    break;
                case 'salary_high':
                    $query->orderBy('salary_max', 'desc');
                    break;
                case 'salary_low':
                    $query->orderBy('salary_min', 'asc');
                    break;
                default:
                    $query->orderBy('created_at', 'desc');
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }

        // Get paginated results
        $jobs = $query->paginate(12)->withQueryString();

        // Get filter options for dropdowns
        $categories = Job::distinct()->pluck('category')->filter()->values();
        $locations = Job::distinct()->pluck('location')->filter()->values();
        $jobTypes = ['full-time', 'part-time', 'contract', 'temporary', 'internship'];
        $experienceLevels = ['entry', 'mid', 'senior', 'executive'];

        return view('jobs.index', compact(
            'jobs', 
            'categories', 
            'locations', 
            'jobTypes', 
            'experienceLevels'
        ));
    }

    public function show(Job $job)
    {
        $job->increment('views');
        $relatedJobs = Job::where('category', $job->category)
                          ->where('id', '!=', $job->id)
                          ->active()
                          ->limit(5)
                          ->get();

        $hasApplied = false;
        $isSaved = false;

        if (Auth::check()) {
            $hasApplied = JobApplication::where('job_id', $job->id)
                                        ->where('user_id', Auth::id())
                                        ->exists();

            $isSaved = Auth::user()->savedJobs()->where('job_id', $job->id)->exists();
        }

        return view('jobs.show', compact('job', 'relatedJobs', 'hasApplied', 'isSaved'));
    }

    public function create()
    {
        if (!Auth::user()->isEmployer() && !Auth::user()->isAdmin()) {
            return redirect()->route('home')->with('error', 'Only employers can post jobs.');
        }
        return view('jobs.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'requirements' => 'required|string',
            'benefits' => 'nullable|string',
            'location' => 'required|string|max:255',
            'salary_min' => 'nullable|numeric|min:0',
            'salary_max' => 'nullable|numeric|min:0|gt:salary_min',
            'job_type' => 'required|in:full-time,part-time,contract,temporary,internship',
            'category' => 'required|string|max:100',
            'experience_level' => 'required|in:entry,mid,senior,executive',
            'application_deadline' => 'nullable|date|after:today',
            'is_featured' => 'boolean'
        ]);

        $validated['employer_id'] = Auth::id();
        $validated['is_active'] = true;

        if (Auth::user()->isAdmin()) {
            $validated['is_featured'] = $request->has('is_featured');
        }

        Job::create($validated);

        return redirect()->route('employer.jobs')->with('success', 'Job posted successfully!');
    }

    public function edit(Job $job)
    {
        $this->authorize('update', $job);
        return view('jobs.edit', compact('job'));
    }

    public function update(Request $request, Job $job)
    {
        $this->authorize('update', $job);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'requirements' => 'required|string',
            'benefits' => 'nullable|string',
            'location' => 'required|string|max:255',
            'salary_min' => 'nullable|numeric|min:0',
            'salary_max' => 'nullable|numeric|min:0|gt:salary_min',
            'job_type' => 'required|in:full-time,part-time,contract,temporary,internship',
            'category' => 'required|string|max:100',
            'experience_level' => 'required|in:entry,mid,senior,executive',
            'application_deadline' => 'nullable|date|after:today',
            'is_active' => 'boolean'
        ]);

        if (Auth::user()->isAdmin()) {
            $validated['is_featured'] = $request->has('is_featured');
        }

        $job->update($validated);

        return redirect()->route('employer.jobs')->with('success', 'Job updated successfully!');
    }

    public function destroy(Job $job)
    {
        $this->authorize('delete', $job);
        $job->delete();

        return back()->with('success', 'Job deleted successfully!');
    }

    public function saveJob(Job $job)
    {
        Auth::user()->savedJobs()->attach($job->id);
        return response()->json(['success' => true, 'message' => 'Job saved!']);
    }

    public function unsaveJob(Job $job)
    {
        Auth::user()->savedJobs()->detach($job->id);
        return response()->json(['success' => true, 'message' => 'Job unsaved!']);
    }
}