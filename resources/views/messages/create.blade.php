@extends('layouts.app')

@section('title', 'New Message')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex items-center mb-6">
        <a href="{{ route('messages.index') }}" class="text-gray-600 hover:text-gray-800 mr-4">
            <i class="fas fa-arrow-left text-xl"></i>
        </a>
        <h1 class="text-3xl font-bold text-gray-800">New Message</h1>
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

    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6">
        <form method="POST" action="{{ route('messages.store') }}">
            @csrf

            <!-- Recipient -->
            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Recipient *</label>

                @if($recipient)
                    <div class="flex items-center gap-3 bg-blue-50 border border-blue-100 rounded-xl px-4 py-3 mb-3">
                        @if($recipient->profile_image)
                            <img src="{{ asset('storage/' . $recipient->profile_image) }}" alt="{{ $recipient->name }}"
                                 class="w-10 h-10 rounded-full object-cover border-2 border-blue-200">
                        @else
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white font-bold shrink-0">
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
                            class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('receiver_id') border-red-500 @enderror">
                        <option value="">Select a user</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ old('receiver_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }} ({{ ucfirst(str_replace('_', ' ', $user->role)) }}) — {{ $user->email }}
                            </option>
                        @endforeach
                    </select>
                @endif
            </div>

            @if($job)
                <div class="mb-4">
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
            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Message *</label>
                <textarea name="message" rows="6" required
                          class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('message') border-red-500 @enderror"
                          placeholder="Type your message...">{{ old('message') }}</textarea>
            </div>

            <!-- Buttons -->
            <div class="flex justify-end space-x-3">
                <a href="{{ route('messages.index') }}"
                   class="px-6 py-2.5 border border-gray-300 rounded-xl hover:bg-gray-50 transition text-gray-700">
                    Cancel
                </a>
                <button type="submit"
                        class="px-6 py-2.5 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition duration-300 flex items-center">
                    <i class="fas fa-paper-plane mr-2"></i> Send Message
                </button>
            </div>
        </form>
    </div>
</div>
@endsection