@extends('employer.layout')

@section('title', 'Analytics')

@section('employer-content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-800">Analytics</h1>
    <p class="text-gray-500 mt-1">Insights about your job postings</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <!-- Applications by status -->
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-5">Applications by Status</h3>
        <div class="space-y-4">
            @php
                $statusMeta = [
                    'pending' => ['Pending', 'bg-yellow-500'],
                    'reviewed' => ['Reviewed', 'bg-blue-500'],
                    'shortlisted' => ['Shortlisted', 'bg-purple-500'],
                    'interview' => ['Interview', 'bg-indigo-500'],
                    'hired' => ['Hired', 'bg-green-500'],
                    'rejected' => ['Rejected', 'bg-red-500'],
                ];
                $total = $applicationsByStatus->sum('total');
            @endphp
            @forelse($applicationsByStatus as $item)
                @php $meta = $statusMeta[$item->status] ?? [ucfirst($item->status), 'bg-gray-500']; @endphp
                <div>
                    <div class="flex justify-between text-sm mb-1">
                        <span class="font-medium text-gray-700">{{ $meta[0] }}</span>
                        <span class="text-gray-500">{{ $item->total }}</span>
                    </div>
                    <div class="h-2.5 bg-gray-100 rounded-full overflow-hidden">
                        <div class="h-full {{ $meta[1] }} rounded-full"
                             style="width: {{ $total > 0 ? ($item->total / $total) * 100 : 0 }}%"></div>
                    </div>
                </div>
            @empty
                <p class="text-gray-500 text-center py-8">No data available.</p>
            @endforelse
        </div>
    </div>

    <!-- Recent jobs -->
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-5">Your Recent Jobs</h3>
        <div class="space-y-3">
            @forelse($recentJobs as $job)
                <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-purple-500 to-indigo-600 flex items-center justify-center text-white font-bold text-sm">
                        {{ strtoupper(substr($job->title, 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-semibold text-gray-800 text-sm truncate">{{ $job->title }}</p>
                        <p class="text-xs text-gray-500">{{ $job->applications_count }} applications • {{ $job->created_at->format('M d, Y') }}</p>
                    </div>
                    <span class="text-xs px-2 py-1 rounded-full {{ $job->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $job->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>
            @empty
                <p class="text-gray-500 text-center py-8">No jobs posted yet.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection