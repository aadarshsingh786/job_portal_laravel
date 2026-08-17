@extends('layouts.app')

@section('title', 'Messages')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Messages</h1>
            <p class="text-gray-500 mt-1">Your conversations</p>
        </div>
        <a href="{{ route('messages.create') }}"
           class="inline-flex items-center bg-blue-600 text-white px-5 py-2.5 rounded-lg hover:bg-blue-700 transition duration-300">
            <i class="fas fa-plus mr-2"></i> New Message
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if($conversations->count() > 0)
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 divide-y divide-gray-100 overflow-hidden">
            @foreach($conversations as $conversation)
                @php
                    $other = $conversation->sender_id === auth()->id() ? $conversation->receiver : $conversation->sender;
                    $unread = \App\Models\Message::where('sender_id', $other->id ?? 0)
                        ->where('receiver_id', auth()->id())
                        ->where('is_read', false)
                        ->count();
                @endphp
                <a href="{{ route('messages.show', $conversation) }}"
                   class="flex items-center gap-4 p-5 hover:bg-gray-50 transition duration-300">
                    @if($other && $other->profile_image)
                        <img src="{{ asset('storage/' . $other->profile_image) }}" alt="{{ $other->name }}"
                             class="w-12 h-12 rounded-full object-cover border-2 border-blue-100">
                    @else
                        <div class="w-12 h-12 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white font-bold shrink-0">
                            {{ strtoupper(substr($other->name ?? 'U', 0, 1)) }}
                        </div>
                    @endif
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between gap-2">
                            <h3 class="font-semibold text-gray-800 {{ $unread > 0 ? 'font-bold' : '' }}">{{ $other->name ?? 'Unknown' }}</h3>
                            <span class="text-xs text-gray-400 shrink-0">{{ $conversation->created_at->diffForHumans() }}</span>
                        </div>
                        <p class="text-sm text-gray-600 truncate mt-0.5">
                            @if($conversation->sender_id === auth()->id())
                                <span class="text-gray-400">You: </span>
                            @endif
                            {{ $conversation->message }}
                        </p>
                    </div>
                    @if($unread > 0)
                        <span class="w-6 h-6 bg-blue-600 text-white text-xs rounded-full flex items-center justify-center shrink-0">
                            {{ $unread }}
                        </span>
                    @endif
                </a>
            @endforeach
        </div>
    @else
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-16 text-center">
            <div class="w-20 h-20 mx-auto bg-blue-50 rounded-full flex items-center justify-center mb-5">
                <i class="fas fa-envelope-open-text text-blue-500 text-3xl"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-800 mb-2">No Messages Yet</h3>
            <p class="text-gray-500 mb-6">Start a conversation with an employer or job seeker.</p>
            <a href="{{ route('messages.create') }}"
               class="inline-flex items-center bg-blue-600 text-white px-6 py-3 rounded-xl hover:bg-blue-700 transition duration-300">
                <i class="fas fa-plus mr-2"></i> New Message
            </a>
        </div>
    @endif
</div>
@endsection