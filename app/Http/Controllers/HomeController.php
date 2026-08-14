<?php

namespace App\Http\Controllers;

use App\Models\Job;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Get featured jobs
        $featuredJobs = Job::with('employer')
            ->active()
            ->featured()
            ->limit(3)
            ->get();
            
        // Get recent jobs
        $recentJobs = Job::with('employer')
            ->active()
            ->orderBy('created_at', 'desc')
            ->limit(6)
            ->get();
            
        // Get categories with job counts
        $categories = Job::select('category')
            ->selectRaw('count(*) as jobs_count')
            ->where('is_active', true)
            ->groupBy('category')
            ->orderByDesc('jobs_count')
            ->limit(8)
            ->get();
            
        // Get stats
        $stats = [
            'open_jobs' => Job::active()->count(),
            'companies' => Job::distinct()->count('employer_id'),
            'applications' => \App\Models\JobApplication::count(),
        ];
        
        return view('home', compact('featuredJobs', 'recentJobs', 'categories', 'stats'));
    }
}