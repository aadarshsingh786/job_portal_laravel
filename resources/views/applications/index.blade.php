@extends('layouts.app')

@section('title', 'My Applications')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">My Applications</h1>
            <p class="text-gray-500 mt-1">Track all the jobs you've applied for</p>
        </div>
        <a href="{{ route('jobs.index') }}"
           class="inline-flex items-center bg-blue-600 text-white px-5 py-2.5 rounded-lg hover:bg-blue-700 transition duration-300">
            <i class="fas fa-search mr-2"></i> Browse Jobs
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if($applications->count() > 0)
        <!-- Status summary -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
            @php
                $counts = [
                    'pending' => $applications->where('status', 'pending')->count() + 0,
                    'shortlisted' => $applications->where('status', 'shortlisted')->count() + $applications->where('status', 'reviewed')->count() + $applications->where('status', 'interview')->count(),
                    'hired' => $applications->where('status', 'hired')->count(),
                    'rejected' => $applications->where('status', 'rejected')->count(),
                ];
                $total = $applications->total();
            @endphp
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                <p class="text-2xl font-bold text-gray-800">{{ $total }}</p>
                <p class="text-xs text-gray-500 mt-1">Total Applications</p>
            </div>
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                <p class="text-2xl font-bold text-yellow-600">{{ $counts['pending'] }}</p>
                <p class="text-xs text-gray-500 mt-1">Pending</p>
            </div>
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                <p class="text-2xl font-bold text-blue-600">{{ $counts['shortlisted'] }}</p>
                <p class="text-xs text-gray-500 mt-1">In Progress</p>
            </div>
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                <p class="text-2xl font-bold text-green-600">{{ $counts['hired'] }}</p>
                <p class="text-xs text-gray-500 mt-1">Hired</p>
            </div>
        </div>

        <div class="space-y-4">
            @foreach($applications as $application)
                @php
                    $job = $application->job;
                    $statusColors = [
                        'pending' => 'bg-yellow-100 text-yellow-800',
                        'reviewed' => 'bg-blue-100 text-blue-800',
                        'shortlisted' => 'bg-purple-100 text-purple-800',
                        'interview' => 'bg-indigo-100 text-indigo-800',
                        'hired' => 'bg-green-100 text-green-800',
                        'rejected' => 'bg-red-100 text-red-800',
                    ];
                @endphp
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition duration-300">
                    <div class="flex items-start gap-4">
                        <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white font-bold text-lg shrink-0">
                            {{ strtoupper(substr($job->employer->company_name ?? $job->employer->name ?? 'J', 0, 1)) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex flex-wrap items-start justify-between gap-2">
                                <div>
                                    <h3 class="font-bold text-gray-800 text-lg">
                                        <a href="{{ route('applications.show', $application) }}" class="hover:text-blue-600 transition">
                                            {{ $job->title ?? 'N/A' }}
                                        </a>
                                    </h3>
                                    <p class="text-sm text-gray-500 mt-0.5">
                                        <i class="fas fa-building mr-1"></i>{{ $job->employer->company_name ?? $job->employer->name ?? 'N/A' }}
                                        @if($job->location)
                                            <i class="fas fa-map-marker-alt ml-3 mr-1 text-gray-400"></i>{{ $job->location }}
                                        @endif
                                    </p>
                                </div>
                                <span class="text-xs px-2.5 py-1 rounded-full {{ $statusColors[$application->status] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ ucfirst($application->status ?? 'Pending') }}
                                </span>
                            </div>
                            <div class="flex flex-wrap items-center gap-x-5 gap-y-2 mt-3 text-sm">
                                <span class="text-gray-500">
                                    <i class="far fa-calendar-alt mr-1 text-gray-400"></i>
                                    Applied {{ $application->created_at->format('M d, Y') }}
                                </span>
                                @if($job->salary_min || $job->salary_max)
                                    <span class="text-green-600 font-medium">
                                        <i class="fas fa-dollar-sign mr-1"></i>{{ $job->salary_range }}
                                    </span>
                                @endif
                                <a href="{{ route('applications.show', $application) }}"
                                   class="inline-flex items-center text-blue-600 hover:text-blue-700 font-medium group">
                                    View Details <i class="fas fa-arrow-right ml-1 group-hover:translate-x-1 transition"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $applications->links() }}
        </div>
    @else
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-16 text-center">
            <div class="w-20 h-20 mx-auto bg-blue-50 rounded-full flex items-center justify-center mb-5">
                <i class="fas fa-file-alt text-blue-500 text-3xl"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-800 mb-2">No Applications Yet</h3>
            <p class="text-gray-500 mb-6 max-w-md mx-auto">You haven't applied for any jobs yet. Browse available jobs and start applying!</p>
            <a href="{{ route('jobs.index') }}"
               class="inline-flex items-center bg-blue-600 text-white px-6 py-3 rounded-xl hover:bg-blue-700 transition duration-300">
                <i class="fas fa-search mr-2"></i> Browse Jobs
            </a>
        </div>
    @endif
</div>
@endsection