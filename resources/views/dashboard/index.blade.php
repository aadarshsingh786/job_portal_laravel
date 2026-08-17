@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Welcome Section -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Dashboard</h1>
        <p class="text-gray-600">Welcome back, {{ auth()->user()->name }}!</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-md p-6 hover:shadow-lg transition duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Total Jobs</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $stats['total_jobs'] ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-briefcase text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-md p-6 hover:shadow-lg transition duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Active Jobs</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $stats['active_jobs'] ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-check-circle text-green-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-md p-6 hover:shadow-lg transition duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Applications</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $stats['total_applications'] ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-file-alt text-purple-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-md p-6 hover:shadow-lg transition duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Employers</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $stats['total_employers'] ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-building text-orange-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Jobs & Applications -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Recent Jobs -->
        <div class="bg-white rounded-xl shadow-md p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Recent Jobs</h3>
                <a href="{{ route('dashboard.jobs') }}" class="text-sm text-blue-600 hover:text-blue-700">
                    View all <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
            <div class="space-y-4">
                @forelse($recentJobs ?? [] as $job)
                    <div class="flex items-center justify-between p-3 hover:bg-gray-50 rounded-lg transition">
                        <div>
                            <h4 class="font-medium text-gray-800">{{ $job->title }}</h4>
                            <p class="text-sm text-gray-500">{{ $job->employer->company_name ?? $job->employer->name ?? 'N/A' }}</p>
                        </div>
                        <span class="text-xs px-2 py-1 rounded-full {{ $job->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $job->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                @empty
                    <p class="text-gray-500 text-center py-4">No jobs found</p>
                @endforelse
            </div>
        </div>

        <!-- Recent Applications -->
        <div class="bg-white rounded-xl shadow-md p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Recent Applications</h3>
                <a href="{{ route('dashboard.applications') }}" class="text-sm text-blue-600 hover:text-blue-700">
                    View all <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
            <div class="space-y-4">
                @forelse($recentApplications ?? [] as $application)
                    <div class="flex items-center justify-between p-3 hover:bg-gray-50 rounded-lg transition">
                        <div>
                            <h4 class="font-medium text-gray-800">{{ $application->user->name ?? 'N/A' }}</h4>
                            <p class="text-sm text-gray-500">{{ $application->job->title ?? 'N/A' }}</p>
                        </div>
                        <span class="text-xs px-2 py-1 rounded-full
                            @if($application->status == 'pending') bg-yellow-100 text-yellow-800
                            @elseif($application->status == 'shortlisted') bg-blue-100 text-blue-800
                            @elseif($application->status == 'interview') bg-purple-100 text-purple-800
                            @elseif($application->status == 'hired') bg-green-100 text-green-800
                            @elseif($application->status == 'rejected') bg-red-100 text-red-800
                            @else bg-gray-100 text-gray-800 @endif">
                            {{ ucfirst($application->status ?? 'Pending') }}
                        </span>
                    </div>
                @empty
                    <p class="text-gray-500 text-center py-4">No applications found</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-4">
        <a href="{{ route('dashboard.jobs.create') }}" 
           class="bg-blue-600 text-white p-4 rounded-xl hover:bg-blue-700 transition duration-300 text-center">
            <i class="fas fa-plus-circle text-2xl mb-2 block"></i>
            <span class="font-semibold">Post New Job</span>
        </a>
        <a href="{{ route('dashboard.jobs') }}" 
           class="bg-green-600 text-white p-4 rounded-xl hover:bg-green-700 transition duration-300 text-center">
            <i class="fas fa-list text-2xl mb-2 block"></i>
            <span class="font-semibold">Manage Jobs</span>
        </a>
        <a href="{{ route('dashboard.applications') }}" 
           class="bg-purple-600 text-white p-4 rounded-xl hover:bg-purple-700 transition duration-300 text-center">
            <i class="fas fa-users text-2xl mb-2 block"></i>
            <span class="font-semibold">View Applications</span>
        </a>
    </div>
</div>
@endsection

@push('styles')
<style>
    .stat-card {
        transition: all 0.3s ease;
    }
    .stat-card:hover {
        transform: translateY(-4px);
    }
</style>
@endpush