<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\JobApplication;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JobApplicationController extends Controller
{
    public function apply(Request $request, Job $job)
    {
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Please login to apply.'], 401);
        }

        if (!Auth::user()->isJobSeeker()) {
            return response()->json(['success' => false, 'message' => 'Only job seekers can apply.'], 403);
        }

        $alreadyApplied = JobApplication::where('job_id', $job->id)
            ->where('user_id', Auth::id())
            ->exists();

        if ($alreadyApplied) {
            return response()->json(['success' => false, 'message' => 'You have already applied for this job.'], 422);
        }

        $validated = $request->validate([
            'cover_letter' => 'nullable|string|max:5000',
            'resume' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
        ]);

        $resumePath = null;
        if ($request->hasFile('resume')) {
            $resumePath = $request->file('resume')->store('resumes', 'public');
        }

        $application = JobApplication::create([
            'job_id' => $job->id,
            'user_id' => Auth::id(),
            'cover_letter' => $validated['cover_letter'] ?? 'I am very interested in this position.',
            'resume_path' => $resumePath ?? '',
            'status' => 'pending',
            'applied_date' => now(),
        ]);

        $job->increment('applications_count');

        $this->notifyAdmins($application);

        return response()->json([
            'success' => true,
            'message' => 'Application submitted successfully!',
            'application_id' => $application->id,
        ]);
    }

    protected function notifyAdmins(JobApplication $application)
    {
        $recipients = User::where('role', 'admin')->pluck('id');

        $job = $application->job;
        $applicant = $application->user;

        foreach ($recipients as $adminId) {
            Notification::create([
                'user_id' => $adminId,
                'title' => 'New Job Application',
                'message' => $applicant->name . ' applied for "' . $job->title . '".',
                'type' => 'application',
                'is_read' => false,
                'link' => route('admin.applications', ['status' => 'pending']),
            ]);
        }
    }

    public function myApplications()
    {
        $applications = JobApplication::with(['job', 'job.employer'])
            ->where('user_id', Auth::id())
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('applications.index', compact('applications'));
    }

    public function show(JobApplication $application)
    {
        if (Auth::id() !== $application->user_id && !Auth::user()->isAdmin()) {
            abort(403);
        }
        $application->load(['job', 'job.employer', 'user']);
        return view('applications.show', compact('application'));
    }

    public function updateStatus(Request $request, JobApplication $application)
    {
        if (!Auth::user()->isEmployer() && !Auth::user()->isAdmin()) {
            abort(403);
        }

        $validated = $request->validate([
            'status' => 'required|in:pending,reviewed,shortlisted,interview,hired,rejected',
            'employer_notes' => 'nullable|string|max:2000',
        ]);

        $application->update($validated);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Application status updated!']);
        }

        return back()->with('success', 'Application status updated!');
    }
}
