<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\User;
use App\Models\JobApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        // Get statistics
        $stats = [
            'total_jobs' => Job::count(),
            'active_jobs' => Job::where('is_active', true)->count(),
            'total_applications' => JobApplication::count(),
            'total_employers' => User::where('role', 'employer')->count(),
            'total_job_seekers' => User::where('role', 'job_seeker')->count(),
        ];

        // Get recent jobs
        $recentJobs = Job::with('employer')
            ->latest()
            ->limit(5)
            ->get();

        // Get recent applications
        $recentApplications = JobApplication::with(['job', 'user'])
            ->latest()
            ->limit(5)
            ->get();

        return view('dashboard.index', compact('stats', 'recentJobs', 'recentApplications'));
    }

    public function jobs()
    {
        $jobs = Job::with('employer')
            ->latest()
            ->paginate(10);
            
        return view('dashboard.jobs', compact('jobs'));
    }

    public function createJob()
    {
        return view('dashboard.create-job');
    }

    public function storeJob(Request $request)
    {
        // Validate the request
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
        ]);

        // Create the job
        $job = new Job();
        $job->employer_id = Auth::id();
        $job->title = $request->title;
        $job->description = $request->description;
        $job->requirements = $request->requirements;
        $job->benefits = $request->benefits;
        $job->location = $request->location;
        $job->salary_min = $request->salary_min;
        $job->salary_max = $request->salary_max;
        $job->job_type = $request->job_type;
        $job->category = $request->category;
        $job->experience_level = $request->experience_level;
        $job->application_deadline = $request->application_deadline;
        $job->is_active = $request->has('is_active') ? true : false;
        $job->is_featured = $request->has('is_featured') ? true : false;
        $job->save();

        // Redirect with success message
        return redirect()->route('dashboard.jobs')
            ->with('success', 'Job created successfully!');
    }

    public function editJob($id)
    {
        $job = Job::findOrFail($id);
        
        // Check if user can edit this job
        if (Auth::user()->role !== 'admin' && $job->employer_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        
        return view('dashboard.edit-job', compact('job'));
    }

    public function updateJob(Request $request, $id)
    {
        $job = Job::findOrFail($id);
        
        // Check if user can update this job
        if (Auth::user()->role !== 'admin' && $job->employer_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

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
            'is_active' => 'boolean',
            'is_featured' => 'boolean'
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['is_featured'] = $request->has('is_featured');

        $job->update($validated);

        return redirect()->route('dashboard.jobs')
            ->with('success', 'Job updated successfully!');
    }

    public function deleteJob($id)
    {
        $job = Job::findOrFail($id);
        
        // Check if user can delete this job
        if (Auth::user()->role !== 'admin' && $job->employer_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $job->delete();

        return redirect()->route('dashboard.jobs')
            ->with('success', 'Job deleted successfully!');
    }

    public function applications()
    {
        $applications = JobApplication::with(['job', 'user'])
            ->latest()
            ->paginate(10);
            
        return view('dashboard.applications', compact('applications'));
    }

    public function viewApplication($id)
    {
        $application = JobApplication::with(['job', 'user'])
            ->findOrFail($id);
            
        return view('dashboard.view-application', compact('application'));
    }

    public function updateApplicationStatus(Request $request, $id)
    {
        $application = JobApplication::findOrFail($id);
        
        $request->validate([
            'status' => 'required|in:pending,reviewed,shortlisted,interview,hired,rejected'
        ]);

        $application->update([
            'status' => $request->status,
            'employer_notes' => $request->employer_notes
        ]);

        return redirect()->route('dashboard.applications')
            ->with('success', 'Application status updated!');
    }

    public function users()
    {
        $users = User::latest()->paginate(10);
        return view('dashboard.users', compact('users'));
    }

    public function toggleJobStatus($id)
    {
        $job = Job::findOrFail($id);
        $job->is_active = !$job->is_active;
        $job->save();

        return back()->with('success', 'Job status updated!');
    }

    public function toggleJobFeature($id)
    {
        $job = Job::findOrFail($id);
        $job->is_featured = !$job->is_featured;
        $job->save();

        return back()->with('success', 'Job featured status updated!');
    }
}