<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\User;
use App\Models\JobApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users' => User::count(),
            'total_jobs' => Job::count(),
            'active_jobs' => Job::where('is_active', true)->count(),
            'total_applications' => JobApplication::count(),
            'pending_applications' => JobApplication::where('status', 'pending')->count(),
            'total_employers' => User::where('role', 'employer')->count(),
            'total_job_seekers' => User::where('role', 'job_seeker')->count(),
        ];

        $recentApplications = JobApplication::with(['job', 'user'])
            ->where('status', 'pending')
            ->latest()
            ->limit(8)
            ->get();

        $recentJobs = Job::with('employer')->latest()->limit(5)->get();
        $recentUsers = User::latest()->limit(5)->get();

        return view('admin.dashboard', compact('stats', 'recentApplications', 'recentJobs', 'recentUsers'));
    }

    public function users()
    {
        $users = User::withCount(['jobs', 'applications'])
            ->latest()
            ->paginate(10);

        return view('admin.users', compact('users'));
    }

    public function userShow(User $user)
    {
        $user->loadCount(['jobs', 'applications']);
        $applications = $user->applications()->with('job')->latest()->get();
        return view('admin.user-show', compact('user', 'applications'));
    }

    public function updateRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|in:admin,employer,job_seeker',
        ]);

        if ($user->id === Auth::id()) {
            return back()->with('error', 'You cannot change your own role.');
        }

        $user->update(['role' => $request->role]);

        return back()->with('success', "{$user->name}'s role updated to " . ucfirst(str_replace('_', ' ', $request->role)) . '.');
    }

    public function deleteUser(User $user)
    {
        if ($user->id === Auth::id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        $user->delete();

        return back()->with('success', 'User deleted successfully.');
    }

    public function allJobs()
    {
        $jobs = Job::with('employer')
            ->latest()
            ->paginate(10);

        return view('admin.jobs', compact('jobs'));
    }

    public function createJob()
    {
        return view('admin.job-create');
    }

    public function storeJob(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'requirements' => 'required|string',
            'about_company' => 'nullable|string|max:5000',
            'benefits' => 'nullable|string',
            'location' => 'required|string|max:255',
            'salary_min' => 'nullable|numeric|min:0',
            'salary_max' => 'nullable|numeric|min:0|gt:salary_min',
            'job_type' => 'required|in:full-time,part-time,contract,temporary,internship',
            'category' => 'required|string|max:100',
            'experience_level' => 'required|in:entry,mid,senior,executive',
            'application_deadline' => 'nullable|date|after:today',
            'is_featured' => 'boolean',
        ]);

        $validated['employer_id'] = Auth::id();
        $validated['is_active'] = true;
        $validated['is_featured'] = $request->boolean('is_featured');

        Job::create($validated);

        return redirect()->route('admin.jobs')->with('success', 'Job posted successfully!');
    }

    public function editJob(Job $job)
    {
        return view('admin.job-edit', compact('job'));
    }

    public function updateJob(Request $request, Job $job)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'requirements' => 'required|string',
            'about_company' => 'nullable|string|max:5000',
            'benefits' => 'nullable|string',
            'location' => 'required|string|max:255',
            'salary_min' => 'nullable|numeric|min:0',
            'salary_max' => 'nullable|numeric|min:0|gt:salary_min',
            'job_type' => 'required|in:full-time,part-time,contract,temporary,internship',
            'category' => 'required|string|max:100',
            'experience_level' => 'required|in:entry,mid,senior,executive',
            'application_deadline' => 'nullable|date|after:today',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active');
        $validated['is_featured'] = $request->boolean('is_featured');

        $job->update($validated);

        return redirect()->route('admin.jobs')->with('success', 'Job updated successfully!');
    }

    public function applications(Request $request)
    {
        $status = $request->query('status');

        $applications = JobApplication::with(['job', 'job.employer', 'user'])
            ->when($status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->latest()
            ->paginate(12);

        $counts = [
            'all' => JobApplication::count(),
            'pending' => JobApplication::where('status', 'pending')->count(),
            'reviewed' => JobApplication::where('status', 'reviewed')->count(),
            'shortlisted' => JobApplication::where('status', 'shortlisted')->count(),
            'interview' => JobApplication::where('status', 'interview')->count(),
            'hired' => JobApplication::where('status', 'hired')->count(),
            'rejected' => JobApplication::where('status', 'rejected')->count(),
        ];

        return view('admin.applications', compact('applications', 'counts', 'status'));
    }

    public function updateApplicationStatus(Request $request, JobApplication $application)
    {
        $request->validate([
            'status' => 'required|in:pending,reviewed,shortlisted,interview,hired,rejected',
            'employer_notes' => 'nullable|string|max:2000',
        ]);

        $application->update([
            'status' => $request->status,
            'employer_notes' => $request->employer_notes ?? $application->employer_notes,
        ]);

        return back()->with('success', 'Application ' . $application->user->name . ' marked as ' . ucfirst($request->status) . '.');
    }

    public function featureJob(Job $job)
    {
        $job->update(['is_featured' => !$job->is_featured]);

        return back()->with('success', 'Job ' . ($job->is_featured ? 'featured' : 'unfeatured') . ' successfully.');
    }

    public function toggleJobStatus(Job $job)
    {
        $job->update(['is_active' => !$job->is_active]);

        return back()->with('success', 'Job ' . ($job->is_active ? 'activated' : 'deactivated') . ' successfully.');
    }

    public function analytics()
    {
        $totalUsers = User::count();
        $totalJobs = Job::count();
        $totalApplications = JobApplication::count();

        $jobsPerEmployer = Job::selectRaw('employer_id, count(*) as total')
            ->groupBy('employer_id')
            ->orderByDesc('total')
            ->with('employer')
            ->limit(5)
            ->get();

        $applicationsByStatus = JobApplication::selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->get();

        return view('admin.analytics', compact('totalUsers', 'totalJobs', 'totalApplications', 'jobsPerEmployer', 'applicationsByStatus'));
    }

    public function reports()
    {
        $jobs = Job::with('employer')->latest()->get();
        return view('admin.reports', compact('jobs'));
    }

    public function settings()
    {
        return view('admin.settings');
    }

    public function updateSettings(Request $request)
    {
        $request->validate([
            'site_name' => 'required|string|max:100',
        ]);

        cache()->forever('site_name', $request->site_name);

        return back()->with('success', 'Settings updated successfully.');
    }
}