@extends('layouts.app')

@section('title', 'Conversation')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center">
            <a href="{{ route('messages.index') }}" class="text-gray-600 hover:text-gray-800 mr-4">
                <i class="fas fa-arrow-left text-xl"></i>
            </a>
            <div class="flex items-center gap-3">
                @if($otherUser->profile_image)
                    <img src="{{ asset('storage/' . $otherUser->profile_image) }}" alt="{{ $otherUser->name }}"
                         class="w-12 h-12 rounded-full object-cover border-2 border-blue-100">
                @else
                    <div class="w-12 h-12 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white font-bold">
                        {{ strtoupper(substr($otherUser->name, 0, 1)) }}
                    </div>
                @endif
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">{{ $otherUser->name }}</h1>
                    <p class="text-sm text-gray-500">{{ ucfirst(str_replace('_', ' ', $otherUser->role)) }}</p>
                </div>
            </div>
        </div>
        <a href="{{ route('messages.create', ['to' => $otherUser->id]) }}"
           class="inline-flex items-center bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition duration-300 text-sm">
            <i class="fas fa-reply mr-1"></i> Reply
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 space-y-4">
        @foreach($conversation as $message)
            @php
                $isMine = $message->sender_id === auth()->id();
            @endphp
            <div class="flex {{ $isMine ? 'justify-end' : 'justify-start' }}">
                <div class="max-w-[80%]">
                    <div class="{{ $isMine ? 'bg-gradient-to-r from-blue-600 to-blue-700 text-white' : 'bg-gray-100 text-gray-800' }} rounded-2xl px-5 py-3 shadow-sm">
                        <p class="text-sm leading-relaxed">{{ $message->message }}</p>
                    </div>
                    <p class="text-xs text-gray-400 mt-1 {{ $isMine ? 'text-right' : '' }}">
                        {{ $message->created_at->format('M d, Y h:i A') }}
                        @if($isMine && $message->is_read)
                            <i class="fas fa-check-double text-blue-500 ml-1" title="Read"></i>
                        @endif
                    </p>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection