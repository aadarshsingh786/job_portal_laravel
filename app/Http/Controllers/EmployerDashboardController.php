<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\JobApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmployerDashboardController extends Controller
{
    public function index()
    {
        $employerId = Auth::id();

        $stats = [
            'total_jobs' => Job::where('employer_id', $employerId)->count(),
            'active_jobs' => Job::where('employer_id', $employerId)->where('is_active', true)->count(),
            'total_applications' => JobApplication::whereHas('job', fn($q) => $q->where('employer_id', $employerId))->count(),
            'pending_applications' => JobApplication::whereHas('job', fn($q) => $q->where('employer_id', $employerId))->where('status', 'pending')->count(),
        ];

        $recentApplications = JobApplication::with(['job', 'user'])
            ->whereHas('job', fn($q) => $q->where('employer_id', $employerId))
            ->latest()
            ->limit(8)
            ->get();

        return view('employer.dashboard', compact('stats', 'recentApplications'));
    }

    public function jobs()
    {
        $jobs = Job::with('employer')
            ->where('employer_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('employer.jobs', compact('jobs'));
    }

    public function applications(Request $request)
    {
        $status = $request->query('status');

        $applications = JobApplication::with(['job', 'user'])
            ->whereHas('job', fn($q) => $q->where('employer_id', Auth::id()))
            ->when($status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->latest()
            ->paginate(12);

        return view('employer.applications', compact('applications', 'status'));
    }

    public function updateApplicationStatus(Request $request, JobApplication $application)
    {
        if ($application->job->employer_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403);
        }

        $request->validate([
            'status' => 'required|in:pending,reviewed,shortlisted,interview,hired,rejected',
            'employer_notes' => 'nullable|string|max:2000',
        ]);

        $application->update([
            'status' => $request->status,
            'employer_notes' => $request->employer_notes ?? $application->employer_notes,
        ]);

        return back()->with('success', 'Application status updated to ' . ucfirst($request->status) . '.');
    }

    public function analytics()
    {
        $employerId = Auth::id();

        $applicationsByStatus = JobApplication::selectRaw('status, count(*) as total')
            ->whereHas('job', fn($q) => $q->where('employer_id', $employerId))
            ->groupBy('status')
            ->get();

        $recentJobs = Job::where('employer_id', $employerId)->latest()->limit(5)->get();

        return view('employer.analytics', compact('applicationsByStatus', 'recentJobs'));
    }
}