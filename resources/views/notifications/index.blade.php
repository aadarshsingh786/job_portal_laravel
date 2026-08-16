@extends('layouts.app')

@section('title', 'Notifications')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Notifications</h1>
            <p class="text-gray-500 mt-1">Stay up to date with your activity</p>
        </div>
        @if($notifications->where('is_read', false)->count() > 0)
            <form method="POST" action="{{ route('notifications.read-all') }}">
                @csrf
                @method('PUT')
                <button type="submit"
                        class="inline-flex items-center bg-blue-600 text-white px-5 py-2.5 rounded-lg hover:bg-blue-700 transition duration-300">
                    <i class="fas fa-check-double mr-2"></i> Mark All as Read
                </button>
            </form>
        @endif
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if($notifications->count() > 0)
        <div class="space-y-3">
            @foreach($notifications as $notification)
                <div class="bg-white rounded-2xl shadow-sm border {{ $notification->is_read ? 'border-gray-100' : 'border-blue-200 bg-blue-50/50' }} p-5 hover:shadow-md transition duration-300 flex items-start gap-4">
                    <div class="w-11 h-11 rounded-xl {{ $notification->is_read ? 'bg-gray-100 text-gray-500' : 'bg-blue-100 text-blue-600' }} flex items-center justify-center shrink-0">
                        @php
                            $icons = ['application' => 'fa-file-alt', 'message' => 'fa-envelope', 'job' => 'fa-briefcase', 'system' => 'fa-cog'];
                            $icon = $icons[$notification->type] ?? 'fa-bell';
                        @endphp
                        <i class="fas {{ $icon }}"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between gap-2">
                            <h3 class="font-semibold text-gray-800 {{ $notification->is_read ? '' : 'font-bold' }}">{{ $notification->title }}</h3>
                            @if(!$notification->is_read)
                                <span class="w-2.5 h-2.5 bg-blue-600 rounded-full mt-1.5 shrink-0"></span>
                            @endif
                        </div>
                        <p class="text-sm text-gray-600 mt-0.5">{{ $notification->message }}</p>
                        <div class="flex items-center justify-between mt-2">
                            <span class="text-xs text-gray-400"><i class="far fa-clock mr-1"></i>{{ $notification->created_at->diffForHumans() }}</span>
                            <div class="flex items-center gap-3">
                                @if($notification->link)
                                    <a href="{{ $notification->link }}" class="text-sm text-blue-600 hover:text-blue-700 font-medium">
                                        View <i class="fas fa-arrow-right ml-0.5"></i>
                                    </a>
                                @endif
                                @if(!$notification->is_read)
                                    <form method="POST" action="{{ route('notifications.read', $notification) }}">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="text-xs text-gray-500 hover:text-blue-600 transition">
                                            Mark as read
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $notifications->links() }}
        </div>
    @else
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-16 text-center">
            <div class="w-20 h-20 mx-auto bg-blue-50 rounded-full flex items-center justify-center mb-5">
                <i class="fas fa-bell-slash text-blue-500 text-3xl"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-800 mb-2">No Notifications</h3>
            <p class="text-gray-500">You're all caught up!</p>
        </div>
    @endif
</div>
@endsection