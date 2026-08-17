@extends('admin.layout')

@section('title', 'Admin Dashboard')

@section('admin-content')
<div class="mb-8 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
    <div>
        <h1 class="text-3xl font-bold text-gray-800">Admin Dashboard</h1>
        <p class="text-gray-500 mt-1">Welcome back, {{ auth()->user()->name }}! Here's what's happening.</p>
    </div>
    <a href="{{ route('admin.jobs.create') }}" class="inline-flex items-center gap-2 bg-gradient-to-r from-blue-600 to-indigo-700 text-white px-5 py-3 rounded-xl font-semibold hover:shadow-lg transition duration-300">
        <i class="fas fa-upload"></i> Upload Job
    </a>
</div>

@if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl mb-6">
        <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
    </div>
@endif

<!-- Stats -->
<div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-4 mb-8">
    @php
        $cards = [
            ['label' => 'Total Users', 'value' => $stats['total_users'], 'icon' => 'fa-users', 'grad' => 'from-blue-500 to-blue-700'],
            ['label' => 'Total Jobs', 'value' => $stats['total_jobs'], 'icon' => 'fa-briefcase', 'grad' => 'from-purple-500 to-purple-700'],
            ['label' => 'Active Jobs', 'value' => $stats['active_jobs'], 'icon' => 'fa-circle-check', 'grad' => 'from-emerald-500 to-emerald-700'],
            ['label' => 'Applications', 'value' => $stats['total_applications'], 'icon' => 'fa-file-invoice', 'grad' => 'from-orange-500 to-orange-700'],
            ['label' => 'Pending Approval', 'value' => $stats['pending_applications'], 'icon' => 'fa-hourglass-half', 'grad' => 'from-yellow-500 to-yellow-700'],
            ['label' => 'Employers', 'value' => $stats['total_employers'], 'icon' => 'fa-building', 'grad' => 'from-cyan-500 to-cyan-700'],
            ['label' => 'Job Seekers', 'value' => $stats['total_job_seekers'], 'icon' => 'fa-user-tie', 'grad' => 'from-pink-500 to-pink-700'],
        ];
    @endphp
    @foreach($cards as $card)
        <div class="card-lift bg-white rounded-2xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition duration-300">
            <div class="w-11 h-11 rounded-xl bg-gradient-to-br {{ $card['grad'] }} text-white flex items-center justify-center mb-3 shadow-md">
                <i class="fas {{ $card['icon'] }}"></i>
            </div>
            <p class="text-2xl font-bold text-gray-800" data-counter="{{ $card['value'] }}">{{ number_format($card['value']) }}</p>
            <p class="text-xs text-gray-500 mt-1">{{ $card['label'] }}</p>
        </div>
    @endforeach
</div>

<div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
    <!-- Pending applications -->
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6">
        <div class="flex justify-between items-center mb-5">
            <h3 class="text-lg font-bold text-gray-800 flex items-center">
                <span class="w-1 h-6 bg-yellow-500 rounded mr-3"></span> Pending Approvals
            </h3>
            <a href="{{ route('admin.applications', ['status' => 'pending']) }}" class="text-sm text-blue-600 hover:text-blue-700 font-medium">
                View all <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>
        <div class="space-y-3">
            @forelse($recentApplications as $application)
                <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl hover:bg-blue-50/50 transition">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white font-bold text-sm shrink-0">
                        {{ strtoupper(substr($application->user->name ?? 'U', 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-semibold text-gray-800 text-sm truncate">{{ $application->user->name ?? 'N/A' }}</p>
                        <p class="text-xs text-gray-500 truncate">{{ $application->job->title ?? 'N/A' }}</p>
                    </div>
                    <span class="text-xs px-2 py-1 rounded-full bg-yellow-100 text-yellow-800 shrink-0">Pending</span>
                    <a href="{{ route('admin.messages.create', ['to' => $application->user_id, 'job' => $application->job_id]) }}"
                       title="Message applicant"
                       class="w-8 h-8 rounded-lg bg-blue-100 text-blue-700 hover:bg-blue-600 hover:text-white transition flex items-center justify-center shrink-0">
                        <i class="fas fa-envelope text-xs"></i>
                    </a>
                    <div class="flex items-center gap-1 shrink-0">
                        <form action="{{ route('admin.applications.update-status', $application) }}" method="POST" title="Mark as reviewed">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" value="reviewed">
                            <button class="w-8 h-8 rounded-lg bg-blue-100 text-blue-700 hover:bg-blue-600 hover:text-white transition flex items-center justify-center">
                                <i class="fas fa-check text-xs"></i>
                            </button>
                        </form>
                        <form action="{{ route('admin.applications.update-status', $application) }}" method="POST" title="Reject">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" value="rejected">
                            <button class="w-8 h-8 rounded-lg bg-red-100 text-red-700 hover:bg-red-600 hover:text-white transition flex items-center justify-center">
                                <i class="fas fa-xmark text-xs"></i>
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <p class="text-gray-500 text-center py-8">
                    <i class="fas fa-circle-check text-green-500 text-2xl mb-2 block"></i>
                    No pending applications. All caught up!
                </p>
            @endforelse
        </div>
    </div>

    <!-- Recent jobs -->
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6">
        <div class="flex justify-between items-center mb-5">
            <h3 class="text-lg font-bold text-gray-800 flex items-center">
                <span class="w-1 h-6 bg-purple-600 rounded mr-3"></span> Recent Jobs
            </h3>
            <a href="{{ route('admin.jobs') }}" class="text-sm text-blue-600 hover:text-blue-700 font-medium">
                View all <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>
        <div class="space-y-3">
            @forelse($recentJobs as $job)
                <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl hover:bg-purple-50/50 transition">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-purple-500 to-indigo-600 flex items-center justify-center text-white font-bold text-sm shrink-0">
                        {{ strtoupper(substr($job->employer->company_name ?? $job->employer->name ?? 'J', 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-semibold text-gray-800 text-sm truncate">{{ $job->title }}</p>
                        <p class="text-xs text-gray-500 truncate">{{ $job->employer->company_name ?? $job->employer->name }} • {{ $job->location }}</p>
                    </div>
                    <span class="text-xs px-2 py-1 rounded-full {{ $job->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }} shrink-0">
                        {{ $job->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>
            @empty
                <p class="text-gray-500 text-center py-8">No jobs found.</p>
            @endforelse
        </div>
    </div>
</div>

<!-- Recent users -->
<div class="mt-6 bg-white rounded-3xl shadow-sm border border-gray-100 p-6">
    <div class="flex justify-between items-center mb-5">
        <h3 class="text-lg font-bold text-gray-800 flex items-center">
            <span class="w-1 h-6 bg-blue-600 rounded mr-3"></span> Recent Users
        </h3>
        <a href="{{ route('admin.users') }}" class="text-sm text-blue-600 hover:text-blue-700 font-medium">
            View all <i class="fas fa-arrow-right ml-1"></i>
        </a>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
        @forelse($recentUsers as $user)
            <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl hover:bg-blue-50/50 transition">
                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-cyan-600 flex items-center justify-center text-white font-bold text-sm shrink-0">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="font-semibold text-gray-800 text-sm truncate">{{ $user->name }}</p>
                    <p class="text-xs text-gray-500 truncate">{{ $user->email }}</p>
                </div>
                <span class="text-xs px-2 py-1 rounded-full {{ $user->role == 'admin' ? 'bg-red-100 text-red-800' : ($user->role == 'employer' ? 'bg-purple-100 text-purple-800' : 'bg-green-100 text-green-800') }} shrink-0">
                    {{ ucfirst(str_replace('_', ' ', $user->role)) }}
                </span>
            </div>
        @empty
            <p class="text-gray-500 text-center py-8 col-span-full">No users found.</p>
        @endforelse
    </div>
</div>
@endsection