@extends('layouts.app')

@section('title', 'Application Details')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex items-center mb-6">
        <a href="{{ route('applications.index') }}" class="text-gray-600 hover:text-gray-800 mr-4">
            <i class="fas fa-arrow-left text-xl"></i>
        </a>
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Application Details</h1>
            <p class="text-sm text-gray-500 mt-1">{{ $application->job->title ?? 'N/A' }}</p>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-4">
            {{ session('success') }}
        </div>
    @endif

    <!-- Job + Status header -->
    <div class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-3xl p-6 md:p-8 text-white shadow-xl mb-6 relative overflow-hidden">
        <div class="absolute -top-10 -right-10 w-48 h-48 bg-white/10 rounded-full blur-2xl"></div>
        <div class="relative flex flex-col sm:flex-row items-start sm:items-center gap-5">
            <div class="w-16 h-16 rounded-2xl bg-white/20 backdrop-blur flex items-center justify-center text-2xl font-bold shrink-0">
                {{ strtoupper(substr($application->job->employer->company_name ?? $application->job->employer->name ?? 'J', 0, 1)) }}
            </div>
            <div class="flex-1">
                <h2 class="text-2xl font-bold">{{ $application->job->title ?? 'N/A' }}</h2>
                <p class="text-blue-100 mt-1">
                    <i class="fas fa-building mr-1"></i>{{ $application->job->employer->company_name ?? $application->job->employer->name ?? 'N/A' }}
                    @if($application->job->location)
                        <i class="fas fa-map-marker-alt ml-3 mr-1"></i>{{ $application->job->location }}
                    @endif
                </p>
            </div>
            @php
                $statusColors = [
                    'pending' => 'bg-yellow-400 text-yellow-900',
                    'reviewed' => 'bg-blue-400 text-blue-900',
                    'shortlisted' => 'bg-purple-400 text-purple-900',
                    'interview' => 'bg-indigo-400 text-indigo-900',
                    'hired' => 'bg-green-400 text-green-900',
                    'rejected' => 'bg-red-400 text-red-900',
                ];
            @endphp
            <span class="text-xs px-3 py-1.5 rounded-full font-semibold {{ $statusColors[$application->status] ?? 'bg-gray-300 text-gray-800' }}">
                {{ ucfirst($application->status ?? 'Pending') }}
            </span>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Timeline / details -->
        <div class="md:col-span-2 space-y-6">
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                    <span class="w-1 h-6 bg-blue-600 rounded mr-3"></span> Application Timeline
                </h3>
                <div class="space-y-0">
                    @php
                        $timeline = [
                            ['label' => 'Application Submitted', 'date' => $application->created_at, 'icon' => 'fa-paper-plane', 'color' => 'bg-blue-500'],
                            ['label' => 'Under Review', 'date' => in_array($application->status, ['reviewed', 'shortlisted', 'interview', 'hired']) ? $application->updated_at : null, 'icon' => 'fa-search', 'color' => 'bg-yellow-500'],
                            ['label' => 'Decision', 'date' => in_array($application->status, ['hired', 'rejected']) ? $application->updated_at : null, 'icon' => 'fa-check', 'color' => $application->status == 'hired' ? 'bg-green-500' : ($application->status == 'rejected' ? 'bg-red-500' : 'bg-gray-300')],
                        ];
                    @endphp
                    @foreach($timeline as $step)
                        <div class="flex gap-4 pb-6 last:pb-0 relative">
                            @if(!$loop->last)
                                <div class="absolute left-5 top-11 bottom-0 w-0.5 bg-gray-200"></div>
                            @endif
                            <div class="w-10 h-10 {{ $step['color'] }} rounded-full flex items-center justify-center text-white shrink-0 relative z-10 shadow">
                                <i class="fas {{ $step['icon'] }} text-sm"></i>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-800 {{ $step['date'] ? '' : 'opacity-50' }}">{{ $step['label'] }}</p>
                                <p class="text-sm text-gray-500">{{ $step['date'] ? $step['date']->format('M d, Y h:i A') : 'Pending' }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                    <span class="w-1 h-6 bg-purple-600 rounded mr-3"></span> My Cover Letter
                </h3>
                @if($application->cover_letter)
                    <div class="text-gray-700 leading-relaxed whitespace-pre-line bg-gray-50 rounded-xl p-5">
                        {{ $application->cover_letter }}
                    </div>
                @else
                    <p class="text-gray-500 italic">No cover letter provided.</p>
                @endif
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                    <span class="w-1 h-6 bg-green-600 rounded mr-3"></span> Job Details
                </h3>
                @if($application->job)
                    <div class="space-y-3 text-sm">
                        @if($application->job->salary_min || $application->job->salary_max)
                            <div class="flex justify-between">
                                <span class="text-gray-500">Salary</span>
                                <span class="font-semibold text-green-600">{{ $application->job->salary_range }}</span>
                            </div>
                        @endif
                        @if($application->job->job_type)
                            <div class="flex justify-between">
                                <span class="text-gray-500">Job Type</span>
                                <span class="font-semibold text-gray-800">{{ ucfirst(str_replace('-', ' ', $application->job->job_type)) }}</span>
                            </div>
                        @endif
                        @if($application->job->experience_level)
                            <div class="flex justify-between">
                                <span class="text-gray-500">Experience</span>
                                <span class="font-semibold text-gray-800">{{ ucfirst($application->job->experience_level) }}</span>
                            </div>
                        @endif
                        @if($application->job->location)
                            <div class="flex justify-between">
                                <span class="text-gray-500">Location</span>
                                <span class="font-semibold text-gray-800">{{ $application->job->location }}</span>
                            </div>
                        @endif
                    </div>
                    <a href="{{ route('jobs.show', $application->job) }}"
                       class="mt-5 block text-center bg-blue-600 text-white px-4 py-2.5 rounded-xl text-sm hover:bg-blue-700 transition shadow">
                        <i class="fas fa-briefcase mr-1"></i> View Full Job
                    </a>
                @endif
            </div>

            @if($application->resume_path)
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                        <span class="w-1 h-6 bg-orange-600 rounded mr-3"></span> Resume
                    </h3>
                    <a href="{{ asset('storage/' . $application->resume_path) }}" target="_blank"
                       class="inline-flex w-full justify-center items-center bg-orange-50 text-orange-600 hover:bg-orange-100 px-4 py-2.5 rounded-xl transition font-medium">
                        <i class="fas fa-file-pdf mr-2"></i> View Resume
                    </a>
                </div>
            @endif

            @if($application->employer_notes)
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-3 flex items-center">
                        <span class="w-1 h-6 bg-red-600 rounded mr-3"></span> Employer Note
                    </h3>
                    <p class="text-sm text-gray-700 leading-relaxed bg-red-50 rounded-xl p-4">{{ $application->employer_notes }}</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection