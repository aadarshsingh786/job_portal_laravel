@extends('admin.layout')

@section('title', 'User Details')

@section('admin-content')
<div class="mb-6">
    <a href="{{ route('admin.users') }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium">
        <i class="fas fa-arrow-left mr-1"></i> Back to Users
    </a>
    <h1 class="text-3xl font-bold text-gray-800 mt-2">{{ $user->name }}</h1>
    <p class="text-gray-500 mt-1">{{ $user->email }}</p>
</div>

@if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl mb-6">
        <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
    </div>
@endif

<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <!-- Profile -->
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 text-center">
        @if($user->profile_image)
            <img src="{{ asset('storage/' . $user->profile_image) }}" alt="" class="w-20 h-20 rounded-full object-cover mx-auto mb-4 border-2 border-blue-600">
        @else
            <div class="w-20 h-20 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white text-2xl font-bold mx-auto mb-4">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
        @endif
        <h3 class="font-bold text-gray-800">{{ $user->name }}</h3>
        <p class="text-sm text-gray-500">{{ $user->email }}</p>
        @if($user->phone)
            <p class="text-sm text-gray-500 mt-1">{{ $user->phone }}</p>
        @endif
        <span class="inline-block mt-3 text-xs px-3 py-1 rounded-full {{ $user->role == 'admin' ? 'bg-red-100 text-red-800' : ($user->role == 'employer' ? 'bg-purple-100 text-purple-800' : 'bg-green-100 text-green-800') }}">
            {{ ucfirst(str_replace('_', ' ', $user->role)) }}
        </span>
        <div class="grid grid-cols-2 gap-3 mt-5 pt-5 border-t border-gray-100">
            <div class="bg-gray-50 rounded-xl p-3">
                <p class="text-xl font-bold text-blue-600">{{ $user->jobs_count }}</p>
                <p class="text-xs text-gray-500">Jobs</p>
            </div>
            <div class="bg-gray-50 rounded-xl p-3">
                <p class="text-xl font-bold text-purple-600">{{ $user->applications_count }}</p>
                <p class="text-xs text-gray-500">Applications</p>
            </div>
        </div>
    </div>

    <!-- Applications -->
    <div class="md:col-span-2 bg-white rounded-3xl shadow-sm border border-gray-100 p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
            <span class="w-1 h-6 bg-blue-600 rounded mr-3"></span> Applications
        </h3>
        <div class="space-y-3">
            @forelse($applications as $application)
                <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl">
                    <div class="flex-1 min-w-0">
                        <p class="font-semibold text-gray-800 text-sm">{{ $application->job->title ?? 'N/A' }}</p>
                        <p class="text-xs text-gray-500">{{ $application->created_at->format('M d, Y') }}</p>
                    </div>
                    <span class="text-xs px-2 py-1 rounded-full {{ $application->status == 'hired' ? 'bg-green-100 text-green-800' : ($application->status == 'rejected' ? 'bg-red-100 text-red-800' : ($application->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-blue-100 text-blue-800')) }}">
                        {{ ucfirst($application->status) }}
                    </span>
                </div>
            @empty
                <p class="text-gray-500 text-center py-8">No applications.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection