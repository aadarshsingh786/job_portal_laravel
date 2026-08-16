@extends('admin.layout')

@section('title', 'New Message')

@section('admin-content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <h1 class="text-3xl font-bold text-gray-800">New Message</h1>
        <p class="text-gray-500 mt-1">Send a message to an applicant</p>
    </div>
    <a href="{{ route('admin.messages.index') }}" class="inline-flex items-center gap-2 text-sm text-gray-600 hover:text-blue-600 bg-white border border-gray-200 px-4 py-2 rounded-xl transition">
        <i class="fas fa-arrow-left"></i> Back to Messages
    </a>
</div>

@if($errors->any())
    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-6 flex items-start gap-2">
        <i class="fas fa-circle-exclamation mt-0.5"></i>
        <ul class="list-disc list-inside text-sm">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="max-w-2xl bg-white rounded-3xl shadow-sm border border-gray-100 p-6 lg:p-8">
    <form method="POST" action="{{ route('admin.messages.store') }}">
        @csrf

        <!-- Recipient -->
        <div class="mb-5">
            <label class="block text-sm font-semibold text-gray-700 mb-2">Recipient *</label>
            @if($recipient)
                <div class="flex items-center gap-3 bg-blue-50 border border-blue-100 rounded-xl px-4 py-3 mb-3">
                    @if($recipient->profile_image)
                        <img src="{{ asset('storage/' . $recipient->profile_image) }}" alt="{{ $recipient->name }}"
                             class="w-11 h-11 rounded-full object-cover border-2 border-blue-200">
                    @else
                        <div class="w-11 h-11 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white font-bold shrink-0">
                            {{ strtoupper(substr($recipient->name, 0, 1)) }}
                        </div>
                    @endif
                    <div class="flex-1 min-w-0">
                        <p class="font-semibold text-gray-800 truncate">{{ $recipient->name }}</p>
                        <p class="text-sm text-gray-500 truncate">{{ $recipient->email }} · {{ ucfirst(str_replace('_', ' ', $recipient->role)) }}</p>
                    </div>
                </div>
                <input type="hidden" name="receiver_id" value="{{ $recipient->id }}">
            @else
                <select name="receiver_id" required
                        class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('receiver_id') border-red-400 @enderror">
                    <option value="">Select an applicant</option>
                    @foreach(\App\Models\User::where('id', '!=', auth()->id())->where('role', 'job_seeker')->orderBy('name')->get() as $user)
                        <option value="{{ $user->id }}" {{ old('receiver_id') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }} — {{ $user->email }}
                        </option>
                    @endforeach
                </select>
            @endif
        </div>

        @if($job)
            <div class="mb-5">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Regarding Job</label>
                <input type="hidden" name="job_id" value="{{ $job->id }}">
                <div class="flex items-center gap-3 bg-gray-50 border border-gray-100 rounded-xl px-4 py-3">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-purple-500 to-indigo-600 flex items-center justify-center text-white font-bold shrink-0">
                        {{ strtoupper(substr($job->title, 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-semibold text-gray-800 truncate">{{ $job->title }}</p>
                        <p class="text-sm text-gray-500 truncate">{{ $job->employer->company_name ?? $job->employer->name ?? '' }} · {{ $job->location }}</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Message -->
        <div class="mb-6">
            <label class="block text-sm font-semibold text-gray-700 mb-2">Message *</label>
            <textarea name="message" rows="6" required
                      class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('message') border-red-400 @enderror"
                      placeholder="Type your message...">{{ old('message') }}</textarea>
        </div>

        <div class="flex justify-end gap-3">
            <a href="{{ route('admin.messages.index') }}" class="px-6 py-3 border border-gray-300 rounded-xl hover:bg-gray-50 transition text-gray-700 font-medium">
                Cancel
            </a>
            <button type="submit" class="px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-700 text-white rounded-xl hover:shadow-lg transition duration-300 font-semibold flex items-center gap-2">
                <i class="fas fa-paper-plane"></i> Send Message
            </button>
        </div>
    </form>
</div>
@endsection