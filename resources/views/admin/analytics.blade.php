@extends('admin.layout')

@section('title', 'Analytics')

@section('admin-content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-800">Analytics</h1>
    <p class="text-gray-500 mt-1">Platform overview and insights</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
    @php
        $cards = [
            ['label' => 'Total Users', 'value' => $totalUsers, 'icon' => 'fa-users', 'grad' => 'from-blue-500 to-blue-700'],
            ['label' => 'Total Jobs', 'value' => $totalJobs, 'icon' => 'fa-briefcase', 'grad' => 'from-purple-500 to-purple-700'],
            ['label' => 'Total Applications', 'value' => $totalApplications, 'icon' => 'fa-file-invoice', 'grad' => 'from-orange-500 to-orange-700'],
        ];
    @endphp
    @foreach($cards as $card)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="w-11 h-11 rounded-xl bg-gradient-to-br {{ $card['grad'] }} text-white flex items-center justify-center mb-3">
                <i class="fas {{ $card['icon'] }}"></i>
            </div>
            <p class="text-3xl font-bold text-gray-800">{{ $card['value'] }}</p>
            <p class="text-sm text-gray-500 mt-1">{{ $card['label'] }}</p>
        </div>
    @endforeach
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
                             style="width: {{ $totalApplications > 0 ? ($item->total / $totalApplications) * 100 : 0 }}%"></div>
                    </div>
                </div>
            @empty
                <p class="text-gray-500 text-center py-8">No data available.</p>
            @endforelse
        </div>
    </div>

    <!-- Top employers -->
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-5">Top Employers by Jobs</h3>
        <div class="space-y-3">
            @forelse($jobsPerEmployer as $item)
                <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-purple-500 to-indigo-600 flex items-center justify-center text-white font-bold text-sm">
                        {{ strtoupper(substr($item->employer->company_name ?? $item->employer->name ?? 'E', 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-semibold text-gray-800 text-sm truncate">{{ $item->employer->company_name ?? $item->employer->name }}</p>
                    </div>
                    <span class="text-sm font-bold text-blue-600">{{ $item->total }} jobs</span>
                </div>
            @empty
                <p class="text-gray-500 text-center py-8">No data available.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection