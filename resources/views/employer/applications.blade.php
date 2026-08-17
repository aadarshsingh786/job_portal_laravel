@extends('employer.layout')

@section('title', 'Applications')

@section('employer-content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-800">Applications</h1>
    <p class="text-gray-500 mt-1">Review and respond to applicants</p>
</div>

@if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl mb-6">
        <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
    </div>
@endif

<!-- Filter tabs -->
<div class="flex flex-wrap gap-2 mb-6">
    @php
        $tabs = [null, 'pending', 'reviewed', 'shortlisted', 'interview', 'hired', 'rejected'];
        $labels = ['All', 'Pending', 'Reviewed', 'Shortlisted', 'Interview', 'Hired', 'Rejected'];
    @endphp
    @foreach($tabs as $i => $key)
        <a href="{{ route('employer.applications', $key ? ['status' => $key] : []) }}"
           class="px-4 py-2 rounded-xl text-sm font-medium transition duration-200 {{ ($status ?? null) == $key ? 'bg-purple-600 text-white shadow-md' : 'bg-white border border-gray-200 text-gray-600 hover:bg-purple-50 hover:text-purple-600' }}">
            {{ $labels[$i] }}
        </a>
    @endforeach
</div>

<div class="space-y-4">
    @forelse($applications as $application)
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
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6">
            <div class="flex flex-col lg:flex-row lg:items-center gap-4">
                <div class="flex items-center gap-4 flex-1 min-w-0">
                    <div class="w-12 h-12 rounded-full bg-gradient-to-br from-purple-500 to-indigo-600 flex items-center justify-center text-white font-bold shrink-0">
                        {{ strtoupper(substr($application->user->name ?? 'U', 0, 1)) }}
                    </div>
                    <div class="min-w-0">
                        <p class="font-bold text-gray-800">{{ $application->user->name ?? 'N/A' }}</p>
                        <p class="text-sm text-gray-500 truncate">{{ $application->user->email ?? '' }}</p>
                        <p class="text-xs text-gray-400 mt-0.5">Applied {{ $application->created_at->diffForHumans() }}</p>
                    </div>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs text-gray-500 font-medium">Applied For</p>
                    <p class="font-semibold text-gray-800 truncate">{{ $application->job->title ?? 'N/A' }}</p>
                </div>
                <span class="text-xs px-3 py-1.5 rounded-full font-semibold {{ $statusColors[$application->status] ?? 'bg-gray-100 text-gray-800' }}">
                    {{ ucfirst($application->status ?? 'Pending') }}
                </span>
            </div>

            @if($application->cover_letter)
                <details class="mt-4 bg-gray-50 rounded-xl p-4">
                    <summary class="text-sm font-semibold text-gray-700 cursor-pointer">
                        <i class="fas fa-file-alt mr-2 text-purple-500"></i> View Cover Letter
                    </summary>
                    <p class="text-sm text-gray-600 mt-3 leading-relaxed whitespace-pre-line">{{ $application->cover_letter }}</p>
                </details>
            @endif

            <!-- Resume -->
            <div class="mt-4 flex flex-col sm:flex-row gap-3">
                @if($application->resume_path)
                    <a href="{{ asset('storage/' . $application->resume_path) }}" target="_blank"
                       class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-red-50 text-red-700 text-sm font-semibold hover:bg-red-100 transition">
                        <i class="fas fa-file-pdf text-lg"></i> View Resume (PDF)
                    </a>
                    <a href="{{ asset('storage/' . $application->resume_path) }}" download
                       class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-gray-50 text-gray-700 text-sm font-semibold hover:bg-gray-100 transition">
                        <i class="fas fa-download text-lg"></i> Download
                    </a>
                @else
                    <span class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-gray-50 text-gray-400 text-sm font-medium">
                        <i class="fas fa-file-pdf text-lg"></i> No resume uploaded
                    </span>
                @endif
            </div>

            <div class="mt-5 pt-5 border-t border-gray-100 flex flex-wrap items-center gap-3">
                <form method="POST" action="{{ route('employer.applications.update-status', $application) }}" class="flex flex-wrap items-center gap-2">
                    @csrf
                    @method('PUT')
                    <select name="status" class="px-3 py-2 rounded-xl border border-gray-300 focus:outline-none focus:ring-2 focus:ring-purple-500 text-sm">
                        @foreach(['pending', 'reviewed', 'shortlisted', 'interview', 'hired', 'rejected'] as $s)
                            <option value="{{ $s }}" {{ $application->status == $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                        @endforeach
                    </select>
                    <input type="text" name="employer_notes" value="{{ $application->employer_notes }}"
                           placeholder="Add a note..." class="px-3 py-2 rounded-xl border border-gray-300 focus:outline-none focus:ring-2 focus:ring-purple-500 text-sm flex-1 min-w-[180px]">
                    <button type="submit" class="px-4 py-2 rounded-xl bg-purple-600 text-white text-sm font-semibold hover:bg-purple-700 transition">
                        <i class="fas fa-check mr-1"></i> Update
                    </button>
                </form>
                <div class="flex gap-2 ml-auto">
                    <a href="{{ route('messages.create', ['to' => $application->user_id, 'job' => $application->job_id]) }}"
                       class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-indigo-600 text-white text-sm font-semibold hover:bg-indigo-700 transition">
                        <i class="fas fa-envelope"></i> Message
                    </a>
                    <form method="POST" action="{{ route('employer.applications.update-status', $application) }}">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="status" value="hired">
                        <button type="submit" class="px-4 py-2 rounded-xl bg-green-600 text-white text-sm font-semibold hover:bg-green-700 transition">
                            <i class="fas fa-user-check mr-1"></i> Accept
                        </button>
                    </form>
                    <form method="POST" action="{{ route('employer.applications.update-status', $application) }}">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="status" value="rejected">
                        <button type="submit" class="px-4 py-2 rounded-xl bg-red-100 text-red-700 text-sm font-semibold hover:bg-red-200 transition">
                            <i class="fas fa-user-xmark mr-1"></i> Reject
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @empty
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-16 text-center">
            <div class="w-20 h-20 mx-auto bg-purple-50 rounded-full flex items-center justify-center mb-5">
                <i class="fas fa-file-invoice text-purple-500 text-3xl"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-800 mb-2">No Applications</h3>
            <p class="text-gray-500">No applications found for this filter.</p>
        </div>
    @endforelse
</div>

<div class="mt-8">
    {{ $applications->links() }}
</div>
@endsection