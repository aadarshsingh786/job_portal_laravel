@extends('layouts.app')

@section('title', 'My Profile')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">My Profile</h1>
            <p class="text-gray-500 mt-1">Manage your personal information</p>
        </div>
        <a href="{{ route('profile.edit') }}"
           class="inline-flex items-center bg-blue-600 text-white px-5 py-2.5 rounded-lg hover:bg-blue-700 transition duration-300">
            <i class="fas fa-edit mr-2"></i> Edit Profile
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-4">
            <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
        </div>
    @endif

    <!-- Avatar + header card -->
    <div class="bg-white rounded-3xl shadow-md p-8 mb-6 relative overflow-hidden">
        <div class="absolute -top-16 -right-16 w-64 h-64 bg-blue-50 rounded-full"></div>
        <div class="relative flex flex-col sm:flex-row items-center gap-6">
            @if($user->profile_image)
                <img src="{{ asset('storage/' . $user->profile_image) }}"
                     alt="Profile" class="w-28 h-28 rounded-full object-cover border-4 border-blue-600 shadow-lg">
            @else
                <div class="w-28 h-28 rounded-full bg-gradient-to-r from-blue-600 to-blue-800 flex items-center justify-center text-white text-4xl font-bold shadow-lg">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
            @endif
            <div class="text-center sm:text-left">
                <h2 class="text-2xl font-bold text-gray-800">{{ $user->name }}</h2>
                <p class="text-gray-500 flex items-center justify-center sm:justify-start gap-2 mt-1">
                    <i class="fas fa-envelope"></i>{{ $user->email }}
                </p>
                @if($user->phone)
                    <p class="text-gray-500 flex items-center justify-center sm:justify-start gap-2 mt-1">
                        <i class="fas fa-phone"></i>{{ $user->phone }}
                    </p>
                @endif
                <span class="inline-block mt-3 text-xs px-3 py-1 rounded-full {{ $user->role == 'employer' ? 'bg-purple-100 text-purple-800' : ($user->role == 'admin' ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800') }}">
                    {{ ucfirst(str_replace('_', ' ', $user->role)) }}
                </span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- About / Bio -->
        <div class="bg-white rounded-3xl shadow-md p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                <span class="w-1 h-6 bg-blue-600 rounded mr-3"></span> About
            </h3>
            @if($user->bio)
                <p class="text-gray-700 leading-relaxed">{{ $user->bio }}</p>
            @else
                <p class="text-gray-500 italic">No bio added yet.</p>
            @endif
        </div>

        <!-- Company info (employers) -->
        @if($user->role == 'employer')
            <div class="bg-white rounded-3xl shadow-md p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                    <span class="w-1 h-6 bg-purple-600 rounded mr-3"></span> Company
                </h3>
                <div class="space-y-3">
                    <div>
                        <p class="text-xs text-gray-500 font-medium">Company Name</p>
                        <p class="text-sm font-semibold text-gray-800">{{ $user->company_name ?? 'Not set' }}</p>
                    </div>
                    @if($user->company_website)
                        <div>
                            <p class="text-xs text-gray-500 font-medium">Website</p>
                            <a href="{{ $user->company_website }}" target="_blank" rel="noopener"
                               class="text-sm font-semibold text-blue-600 hover:text-blue-700">
                                <i class="fas fa-globe mr-1"></i>{{ $user->company_website }}
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        <!-- Stats -->
        <div class="{{ $user->role == 'employer' ? 'md:col-span-2' : 'md:col-span-1' }} bg-white rounded-3xl shadow-md p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                <span class="w-1 h-6 bg-green-600 rounded mr-3"></span> Quick Stats
            </h3>
            <div class="grid grid-cols-2 gap-4">
                @if($user->role == 'job_seeker')
                    <div class="bg-gray-50 rounded-xl p-4 text-center">
                        <p class="text-2xl font-bold text-blue-600">{{ $user->applications()->count() }}</p>
                        <p class="text-xs text-gray-500 mt-1">Applications</p>
                    </div>
                    <div class="bg-gray-50 rounded-xl p-4 text-center">
                        <p class="text-2xl font-bold text-purple-600">{{ $user->savedJobs()->count() }}</p>
                        <p class="text-xs text-gray-500 mt-1">Saved Jobs</p>
                    </div>
                @elseif($user->role == 'employer')
                    <div class="bg-gray-50 rounded-xl p-4 text-center">
                        <p class="text-2xl font-bold text-blue-600">{{ $user->jobs()->count() }}</p>
                        <p class="text-xs text-gray-500 mt-1">Posted Jobs</p>
                    </div>
                @endif
                <div class="bg-gray-50 rounded-xl p-4 text-center">
                    <p class="text-2xl font-bold text-green-600">{{ $user->created_at->format('M Y') }}</p>
                    <p class="text-xs text-gray-500 mt-1">Member Since</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection