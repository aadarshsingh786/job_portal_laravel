@extends('employer.layout')

@section('title', 'My Jobs')

@section('employer-content')
<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-3xl font-bold text-gray-800">My Jobs</h1>
        <p class="text-gray-500 mt-1">Manage your job listings</p>
    </div>
    <a href="{{ route('employer.jobs.create') }}"
       class="inline-flex items-center bg-blue-600 text-white px-5 py-2.5 rounded-xl hover:bg-blue-700 transition duration-300">
        <i class="fas fa-plus mr-2"></i> Post Job
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
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Applications</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
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
                        <td class="px-6 py-4">
                            <span class="text-xs px-2 py-1 rounded-full bg-blue-50 text-blue-700">
                                {{ ucfirst(str_replace('-', ' ', $job->job_type)) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500 text-center">{{ $job->applications_count }}</td>
                        <td class="px-6 py-4">
                            <span class="text-xs px-2 py-1 rounded-full {{ $job->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $job->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('employer.jobs.edit', $job) }}"
                                   class="text-blue-600 hover:text-blue-900 bg-blue-50 hover:bg-blue-100 px-3 py-1.5 rounded-lg text-sm transition">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="{{ route('jobs.show', $job) }}" target="_blank"
                                   class="text-gray-600 hover:text-gray-900 bg-gray-50 hover:bg-gray-100 px-3 py-1.5 rounded-lg text-sm transition">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <form method="POST" action="{{ route('employer.jobs.destroy', $job) }}"
                                      onsubmit="return confirm('Delete this job? This cannot be undone.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 px-3 py-1.5 rounded-lg text-sm transition">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                            No jobs yet. <a href="{{ route('employer.jobs.create') }}" class="text-blue-600 hover:underline">Post your first job</a>
                        </td>
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