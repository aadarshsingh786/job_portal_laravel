@extends('layouts.app')

@section('title', 'Browse Jobs')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-800 rounded-2xl p-8 mb-8 text-white">
        <div class="max-w-3xl">
            <h1 class="text-4xl font-bold mb-4">Find Your Dream Job</h1>
            <p class="text-lg text-blue-100 mb-6">Browse thousands of job opportunities from top companies</p>
            
            <!-- Search Form -->
            <form action="{{ route('jobs.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
                <div class="flex-1 relative">
                    <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}"
                           placeholder="Job title, keywords, or company" 
                           class="w-full pl-10 pr-4 py-3 rounded-lg text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-400">
                </div>
                <div class="flex-1 relative">
                    <i class="fas fa-map-marker-alt absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    <input type="text" 
                           name="location" 
                           value="{{ request('location') }}"
                           placeholder="Location" 
                           class="w-full pl-10 pr-4 py-3 rounded-lg text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-400">
                </div>
                <button type="submit" class="bg-white text-blue-600 px-8 py-3 rounded-lg font-semibold hover:bg-blue-50 transition duration-300">
                    Search Jobs
                </button>
            </form>
        </div>
    </div>

    <div class="flex flex-col lg:flex-row gap-8">
        <!-- Filters Sidebar -->
        <div class="lg:w-1/4">
            <div class="bg-white rounded-xl shadow-md p-6 sticky top-4">
                <h3 class="font-semibold text-lg mb-4">Filters</h3>
                
                <form action="{{ route('jobs.index') }}" method="GET">
                    <!-- Category Filter -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                        <select name="category" class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>
                                    {{ $category }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Job Type Filter -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Job Type</label>
                        <select name="job_type" class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">All Types</option>
                            <option value="full-time" {{ request('job_type') == 'full-time' ? 'selected' : '' }}>Full Time</option>
                            <option value="part-time" {{ request('job_type') == 'part-time' ? 'selected' : '' }}>Part Time</option>
                            <option value="contract" {{ request('job_type') == 'contract' ? 'selected' : '' }}>Contract</option>
                            <option value="temporary" {{ request('job_type') == 'temporary' ? 'selected' : '' }}>Temporary</option>
                            <option value="internship" {{ request('job_type') == 'internship' ? 'selected' : '' }}>Internship</option>
                        </select>
                    </div>

                    <!-- Experience Level -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Experience Level</label>
                        <select name="experience_level" class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">All Levels</option>
                            <option value="entry" {{ request('experience_level') == 'entry' ? 'selected' : '' }}>Entry Level</option>
                            <option value="mid" {{ request('experience_level') == 'mid' ? 'selected' : '' }}>Mid Level</option>
                            <option value="senior" {{ request('experience_level') == 'senior' ? 'selected' : '' }}>Senior Level</option>
                            <option value="executive" {{ request('experience_level') == 'executive' ? 'selected' : '' }}>Executive</option>
                        </select>
                    </div>

                    <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition duration-300">
                        Apply Filters
                    </button>
                    
                    <a href="{{ route('jobs.index') }}" class="block text-center mt-2 text-sm text-blue-600 hover:underline">
                        Clear Filters
                    </a>
                </form>
            </div>
        </div>

        <!-- Job Listings -->
        <div class="lg:w-3/4">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800">
                    {{ $jobs->total() }} Jobs Found
                </h2>
                <div class="text-sm text-gray-600">
                    Showing {{ $jobs->firstItem() }}-{{ $jobs->lastItem() }} of {{ $jobs->total() }}
                </div>
            </div>

            @forelse($jobs as $job)
                <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition duration-300 mb-4 overflow-hidden">
                    <div class="p-6">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center space-x-2 mb-2">
                                    <h3 class="text-xl font-semibold text-gray-800">
                                        <a href="{{ route('jobs.show', $job) }}" class="hover:text-blue-600">
                                            {{ $job->title }}
                                        </a>
                                    </h3>
                                    @if($job->is_featured)
                                        <span class="bg-yellow-100 text-yellow-800 text-xs px-2 py-1 rounded-full">
                                            <i class="fas fa-star mr-1"></i> Featured
                                        </span>
                                    @endif
                                </div>
                                
                                <div class="flex flex-wrap items-center gap-3 mb-3">
                                    <span class="text-gray-700">
                                        <i class="fas fa-building mr-1 text-blue-600"></i>
                                        {{ $job->employer->company_name ?? $job->employer->name }}
                                    </span>
                                    <span class="text-gray-600">
                                        <i class="fas fa-map-marker-alt mr-1 text-blue-600"></i>
                                        {{ $job->location }}
                                    </span>
                                    <span class="text-gray-600">
                                        <i class="fas fa-clock mr-1 text-blue-600"></i>
                                        {{ ucfirst(str_replace('-', ' ', $job->job_type)) }}
                                    </span>
                                    @if($job->salary_min || $job->salary_max)
                                        <span class="text-green-600 font-medium">
                                            <i class="fas fa-dollar-sign mr-1"></i>
                                            {{ $job->salary_range }}
                                        </span>
                                    @endif
                                </div>

                                <div class="flex flex-wrap gap-2 mb-3">
                                    <span class="bg-blue-100 text-blue-800 text-xs px-3 py-1 rounded-full">
                                        {{ $job->category }}
                                    </span>
                                    <span class="bg-purple-100 text-purple-800 text-xs px-3 py-1 rounded-full">
                                        {{ ucfirst($job->experience_level) }}
                                    </span>
                                </div>

                                <p class="text-gray-600 text-sm line-clamp-2">
                                    {{ Str::limit($job->description, 200) }}
                                </p>
                            </div>

                            <div class="ml-4 flex flex-col items-end space-y-2">
                                <span class="text-sm text-gray-500">
                                    <i class="far fa-calendar-alt mr-1"></i>
                                    {{ $job->created_at->diffForHumans() }}
                                </span>
                                <span class="text-sm text-gray-500">
                                    <i class="far fa-eye mr-1"></i>
                                    {{ $job->views }} views
                                </span>
                                <a href="{{ route('jobs.show', $job) }}" 
                                   class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition duration-300 text-sm">
                                    View Details
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-xl shadow-md p-12 text-center">
                    <i class="fas fa-search text-6xl text-gray-300 mb-4"></i>
                    <h3 class="text-xl font-semibold text-gray-800 mb-2">No Jobs Found</h3>
                    <p class="text-gray-600">Try adjusting your search or filter criteria</p>
                </div>
            @endforelse

            <!-- Pagination -->
            <div class="mt-6">
                {{ $jobs->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
@endpush
@endsection