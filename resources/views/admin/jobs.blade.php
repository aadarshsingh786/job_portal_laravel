@extends('admin.layout')

@section('title', 'Jobs')

@section('admin-content')
<div class="mb-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
    <div>
        <h1 class="text-3xl font-bold text-gray-800">Manage Jobs</h1>
        <p class="text-gray-500 mt-1">Review, feature and toggle all jobs</p>
    </div>
    <a href="{{ route('admin.jobs.create') }}" class="inline-flex items-center gap-2 bg-gradient-to-r from-blue-600 to-indigo-700 text-white px-5 py-3 rounded-xl font-semibold hover:shadow-lg transition duration-300">
        <i class="fas fa-plus"></i> Post New Job
    </a>
</div>

@if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl mb-6">
        <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
    </div>
@endif

<div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Job</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Employer</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Featured</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($jobs as $job)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">{{ $job->title }}</div>
                            <div class="text-sm text-gray-500">{{ $job->location }}</div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            {{ $job->employer->company_name ?? $job->employer->name ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-xs px-2 py-1 rounded-full bg-blue-50 text-blue-700">
                                {{ ucfirst(str_replace('-', ' ', $job->job_type)) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-xs px-2 py-1 rounded-full {{ $job->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $job->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-xs px-2 py-1 rounded-full {{ $job->is_featured ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-600' }}">
                                {{ $job->is_featured ? 'Featured' : 'Standard' }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <form method="POST" action="{{ route('admin.jobs.feature', $job) }}">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="px-3 py-1.5 rounded-lg text-sm transition {{ $job->is_featured ? 'bg-yellow-100 text-yellow-800 hover:bg-yellow-200' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                                        <i class="fas fa-star mr-1"></i>{{ $job->is_featured ? 'Unfeature' : 'Feature' }}
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('admin.jobs.toggle-status', $job) }}">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="px-3 py-1.5 rounded-lg text-sm transition {{ $job->is_active ? 'bg-red-100 text-red-700 hover:bg-red-200' : 'bg-green-100 text-green-700 hover:bg-green-200' }}">
                                        <i class="fas {{ $job->is_active ? 'fa-pause' : 'fa-play' }} mr-1"></i>{{ $job->is_active ? 'Deactivate' : 'Activate' }}
                                    </button>
                                </form>
                                <a href="{{ route('admin.jobs.edit', $job) }}" class="inline-flex items-center justify-center gap-1 px-3 py-1.5 rounded-lg text-sm transition bg-indigo-50 text-indigo-700 hover:bg-indigo-100">
                                    <i class="fas fa-pen mr-1"></i> Edit
                                </a>
                                <a href="{{ route('jobs.show', $job) }}" target="_blank"
                                   class="text-blue-600 hover:text-blue-900 bg-blue-50 hover:bg-blue-100 px-3 py-1.5 rounded-lg text-sm transition">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">No jobs found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
        {{ $jobs->links() }}
    </div>
</div>
@endsection