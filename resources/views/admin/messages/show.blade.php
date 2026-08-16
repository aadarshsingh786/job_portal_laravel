@extends('admin.layout')

@section('title', 'Conversation')

@section('admin-content')
<div class="mb-6 flex items-center justify-between">
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.messages.index') }}" class="text-gray-600 hover:text-gray-800">
            <i class="fas fa-arrow-left text-xl"></i>
        </a>
        @if($otherUser->profile_image)
            <img src="{{ asset('storage/' . $otherUser->profile_image) }}" alt="{{ $otherUser->name }}"
                 class="w-11 h-11 rounded-full object-cover border-2 border-blue-100">
        @else
            <div class="w-11 h-11 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white font-bold">
                {{ strtoupper(substr($otherUser->name ?? 'U', 0, 1)) }}
            </div>
        @endif
        <div>
            <h1 class="text-xl font-bold text-gray-800">{{ $otherUser->name }}</h1>
            <p class="text-sm text-gray-500">{{ $otherUser->email }} · {{ ucfirst(str_replace('_', ' ', $otherUser->role)) }}</p>
        </div>
    </div>
    <a href="{{ route('admin.messages.create', ['to' => $otherUser->id]) }}" class="inline-flex items-center gap-2 text-sm bg-blue-600 text-white px-4 py-2.5 rounded-xl hover:bg-blue-700 transition">
        <i class="fas fa-reply"></i> Reply
    </a>
</div>

<div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 space-y-4">
    @foreach($conversation as $message)
        @php $mine = $message->sender_id === auth()->id(); @endphp
        <div class="flex {{ $mine ? 'justify-end' : 'justify-start' }}">
            <div class="max-w-[75%] {{ $mine ? 'bg-gradient-to-r from-blue-600 to-indigo-600 text-white' : 'bg-gray-100 text-gray-800' }} rounded-2xl px-4 py-3">
                <p class="text-sm leading-relaxed">{{ $message->message }}</p>
                <p class="text-xs mt-1.5 {{ $mine ? 'text-blue-200' : 'text-gray-400' }}">{{ $message->created_at->format('M d, H:i') }}</p>
            </div>
        </div>
    @endforeach
</div>
@endsection