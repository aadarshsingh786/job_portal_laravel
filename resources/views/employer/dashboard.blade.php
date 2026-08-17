@extends('employer.layout')

@section('title', 'Employer Dashboard')

@section('employer-content')
<div class="mb-8 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
    <div>
        <h1 class="text-3xl font-bold text-gray-800">Employer Dashboard</h1>
        <p class="text-gray-500 mt-1">Welcome back, {{ auth()->user()->name }}! Here's what's happening.</p>
    </div>
    <a href="{{ route('employer.jobs.create') }}" class="inline-flex items-center gap-2 bg-gradient-to-r from-purple-600 to-indigo-700 text-white px-5 py-3 rounded-xl font-semibold hover:shadow-lg transition duration-300">
        <i class="fas fa-upload"></i> Post Job
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
            ['label' => 'Total Jobs', 'value' => $stats['total_jobs'], 'icon' => 'fa-briefcase', 'grad' => 'from-blue-500 to-blue-700'],
            ['label' => 'Active Jobs', 'value' => $stats['active_jobs'], 'icon' => 'fa-circle-check', 'grad' => 'from-emerald-500 to-emerald-700'],
            ['label' => 'Applications', 'value' => $stats['total_applications'], 'icon' => 'fa-file-invoice', 'grad' => 'from-purple-500 to-purple-700'],
            ['label' => 'Pending Review', 'value' => $stats['pending_applications'], 'icon' => 'fa-hourglass-half', 'grad' => 'from-yellow-500 to-yellow-700'],
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
                <span class="w-1 h-6 bg-yellow-500 rounded mr-3"></span> Recent Applications
            </h3>
            <a href="{{ route('employer.applications', ['status' => 'pending']) }}" class="text-sm text-blue-600 hover:text-blue-700 font-medium">
                View all <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>
        <div class="space-y-3">
            @forelse($recentApplications as $application)
                <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl hover:bg-purple-50/50 transition">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-purple-500 to-indigo-600 flex items-center justify-center text-white font-bold text-sm shrink-0">
                        {{ strtoupper(substr($application->user->name ?? 'U', 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-semibold text-gray-800 text-sm truncate">{{ $application->user->name ?? 'N/A' }}</p>
                        <p class="text-xs text-gray-500 truncate">{{ $application->job->title ?? 'N/A' }}</p>
                    </div>
                    <span class="text-xs px-2 py-1 rounded-full {{ $application->status == 'hired' ? 'bg-green-100 text-green-800' : ($application->status == 'rejected' ? 'bg-red-100 text-red-800' : ($application->status == 'reviewed' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800')) }} shrink-0">
                        {{ ucfirst($application->status) }}
                    </span>
                    <div class="flex items-center gap-1 shrink-0">
                        @if($application->status == 'pending')
                            <form action="{{ route('employer.applications.update-status', $application) }}" method="POST" title="Mark as reviewed">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status" value="reviewed">
                                <button class="w-8 h-8 rounded-lg bg-blue-100 text-blue-700 hover:bg-blue-600 hover:text-white transition flex items-center justify-center">
                                    <i class="fas fa-check text-xs"></i>
                                </button>
                            </form>
                            <form action="{{ route('employer.applications.update-status', $application) }}" method="POST" title="Reject">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status" value="rejected">
                                <button class="w-8 h-8 rounded-lg bg-red-100 text-red-700 hover:bg-red-600 hover:text-white transition flex items-center justify-center">
                                    <i class="fas fa-xmark text-xs"></i>
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @empty
                <p class="text-gray-500 text-center py-8">
                    <i class="fas fa-circle-check text-green-500 text-2xl mb-2 block"></i>
                    No applications yet. All caught up!
                </p>
            @endforelse
        </div>
    </div>

    <!-- Quick actions -->
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-5 flex items-center">
            <span class="w-1 h-6 bg-purple-600 rounded mr-3"></span> Quick Actions
        </h3>
        <div class="space-y-3">
            <a href="{{ route('employer.jobs.create') }}"
               class="flex items-center gap-4 p-4 bg-blue-50 rounded-2xl hover:bg-blue-100 transition">
                <div class="w-11 h-11 rounded-xl bg-blue-600 text-white flex items-center justify-center">
                    <i class="fas fa-plus"></i>
                </div>
                <div>
                    <p class="font-semibold text-gray-800">Post a New Job</p>
                    <p class="text-sm text-gray-500">Create a new job listing</p>
                </div>
                <i class="fas fa-arrow-right ml-auto text-blue-600"></i>
            </a>
            <a href="{{ route('employer.jobs') }}"
               class="flex items-center gap-4 p-4 bg-purple-50 rounded-2xl hover:bg-purple-100 transition">
                <div class="w-11 h-11 rounded-xl bg-purple-600 text-white flex items-center justify-center">
                    <i class="fas fa-briefcase"></i>
                </div>
                <div>
                    <p class="font-semibold text-gray-800">Manage Jobs</p>
                    <p class="text-sm text-gray-500">Edit, activate or delete jobs</p>
                </div>
                <i class="fas fa-arrow-right ml-auto text-purple-600"></i>
            </a>
            <a href="{{ route('employer.applications') }}"
               class="flex items-center gap-4 p-4 bg-green-50 rounded-2xl hover:bg-green-100 transition">
                <div class="w-11 h-11 rounded-xl bg-green-600 text-white flex items-center justify-center">
                    <i class="fas fa-file-invoice"></i>
                </div>
                <div>
                    <p class="font-semibold text-gray-800">Review Applications</p>
                    <p class="text-sm text-gray-500">Approve or reject applicants</p>
                </div>
                <i class="fas fa-arrow-right ml-auto text-green-600"></i>
            </a>
            <a href="{{ route('messages.create') }}"
               class="flex items-center gap-4 p-4 bg-amber-50 rounded-2xl hover:bg-amber-100 transition">
                <div class="w-11 h-11 rounded-xl bg-amber-600 text-white flex items-center justify-center">
                    <i class="fas fa-paper-plane"></i>
                </div>
                <div>
                    <p class="font-semibold text-gray-800">Send Message</p>
                    <p class="text-sm text-gray-500">Message applicants or the admin</p>
                </div>
                <i class="fas fa-arrow-right ml-auto text-amber-600"></i>
            </a>
        </div>
    </div>
</div>
@endsection