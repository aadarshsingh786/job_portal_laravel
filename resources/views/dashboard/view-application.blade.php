@extends('layouts.app')

@section('title', 'Application Details')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex items-center mb-6">
        <a href="{{ route('dashboard.applications') }}" class="text-gray-600 hover:text-gray-800 mr-4">
            <i class="fas fa-arrow-left text-xl"></i>
        </a>
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Application Details</h1>
            <p class="text-sm text-gray-500 mt-1">{{ $application->job->title ?? 'N/A' }} — {{ $application->user->name ?? 'Unknown applicant' }}</p>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-4">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Applicant info -->
        <div class="md:col-span-1 space-y-6">
            <div class="bg-white rounded-xl shadow-md p-6 text-center">
                <div class="w-20 h-20 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center mx-auto mb-4 text-white text-2xl font-bold">
                    {{ strtoupper(substr($application->user->name ?? 'U', 0, 1)) }}
                </div>
                <h2 class="font-bold text-gray-800 text-lg">{{ $application->user->name ?? 'N/A' }}</h2>
                <p class="text-sm text-gray-500 mt-1">{{ $application->user->email ?? '' }}</p>
                @if($application->user->phone)
                    <p class="text-sm text-gray-500 mt-1"><i class="fas fa-phone mr-1"></i>{{ $application->user->phone }}</p>
                @endif
                @if($application->user->bio)
                    <p class="text-sm text-gray-600 mt-4 text-left leading-relaxed">{{ $application->user->bio }}</p>
                @endif
            </div>

            <!-- Application info -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Application Info</h3>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Job</span>
                        <span class="font-medium text-gray-800 text-right">{{ $application->job->title ?? 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Applied On</span>
                        <span class="font-medium text-gray-800">{{ $application->created_at ? $application->created_at->format('M d, Y') : 'N/A' }}</span>
                    </div>
                    @if($application->resume_path)
                        <div class="pt-2">
                            <a href="{{ asset('storage/' . $application->resume_path) }}" target="_blank"
                               class="inline-flex w-full justify-center items-center bg-blue-50 text-blue-600 hover:bg-blue-100 px-4 py-2.5 rounded-lg transition font-medium">
                                <i class="fas fa-file-pdf mr-2"></i> View Resume
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Cover letter + status update -->
        <div class="md:col-span-2 space-y-6">
            <div class="bg-white rounded-xl shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-file-alt text-blue-600 mr-2"></i> Cover Letter
                </h3>
                @if($application->cover_letter)
                    <div class="text-gray-700 leading-relaxed whitespace-pre-line bg-gray-50 rounded-lg p-4">
                        {{ $application->cover_letter }}
                    </div>
                @else
                    <p class="text-gray-500 italic">No cover letter provided.</p>
                @endif
            </div>

            <div class="bg-white rounded-xl shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-clipboard-check text-green-600 mr-2"></i> Update Status
                </h3>
                @php
                    $statusColors = [
                        'pending' => 'bg-yellow-100 text-yellow-800',
                        'reviewed' => 'bg-blue-100 text-blue-800',
                        'shortlisted' => 'bg-purple-100 text-purple-800',
                        'interview' => 'bg-indigo-100 text-indigo-800',
                        'hired' => 'bg-green-100 text-green-800',
                        'rejected' => 'bg-red-100 text-red-800',
                    ];
                @endphp
                <span class="inline-block text-xs px-2.5 py-1 rounded-full mb-4 {{ $statusColors[$application->status] ?? 'bg-gray-100 text-gray-800' }}">
                    Current: {{ ucfirst($application->status ?? 'Pending') }}
                </span>

                <form method="POST" action="{{ route('dashboard.applications.update-status', $application->id) }}">
                    @csrf
                    @method('PUT')
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <select name="status" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @foreach(['pending', 'reviewed', 'shortlisted', 'interview', 'hired', 'rejected'] as $status)
                                <option value="{{ $status }}" {{ $application->status == $status ? 'selected' : '' }}>
                                    {{ ucfirst($status) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Employer Notes</label>
                        <textarea name="employer_notes" rows="3" 
                                  class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                  placeholder="Add private notes for this applicant...">{{ $application->employer_notes }}</textarea>
                    </div>
                    <button type="submit" 
                            class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition duration-300 flex items-center">
                        <i class="fas fa-check mr-2"></i> Update Status
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection