@extends('admin.layout')

@section('title', 'Reports')

@section('admin-content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-3xl font-bold text-gray-800">Job Reports</h1>
        <p class="text-gray-500 mt-1">All jobs posted on the platform</p>
    </div>
    <button onclick="window.print()" class="bg-blue-600 text-white px-5 py-2.5 rounded-xl hover:bg-blue-700 transition">
        <i class="fas fa-print mr-2"></i> Print Report
    </button>
</div>

<div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Job</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Employer</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Applications</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Posted</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($jobs as $index => $job)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $index + 1 }}</td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">{{ $job->title }}</div>
                            <div class="text-sm text-gray-500">{{ $job->location }}</div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $job->employer->company_name ?? $job->employer->name }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $job->category }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500 text-center">{{ $job->applications_count }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $job->created_at->format('M d, Y') }}</td>
                        <td class="px-6 py-4">
                            <span class="text-xs px-2 py-1 rounded-full {{ $job->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $job->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">No jobs found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection