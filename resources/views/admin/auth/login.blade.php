@extends('layouts.auth')

@section('title', 'Admin Login')

@section('auth-content')
<div class="bg-white rounded-3xl shadow-xl border border-gray-100 p-8">
    <div class="flex items-center gap-3 mb-6">
        <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-blue-600 to-indigo-700 text-white flex items-center justify-center text-xl shadow-lg shadow-blue-600/30">
            <i class="fas fa-shield-halved"></i>
        </div>
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Admin Login</h1>
            <p class="text-sm text-gray-500">Access the admin panel</p>
        </div>
    </div>

    @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-6 flex items-start gap-2">
            <i class="fas fa-circle-exclamation mt-0.5"></i>
            <div class="text-sm">{{ $errors->first() }}</div>
        </div>
    @endif

    @if(session('status'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl mb-6 flex items-start gap-2">
            <i class="fas fa-check-circle mt-0.5"></i>
            <div class="text-sm">{{ session('status') }}</div>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.login.submit') }}" class="space-y-5">
        @csrf

        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">Email Address</label>
            <div class="relative">
                <i class="fas fa-envelope absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                       placeholder="admin@example.com"
                       class="w-full pl-12 pr-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
        </div>

        <div>
            <label for="password" class="block text-sm font-medium text-gray-700 mb-1.5">Password</label>
            <div class="relative">
                <i class="fas fa-lock absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                <input id="password" type="password" name="password" required placeholder="••••••••"
                       class="w-full pl-12 pr-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
        </div>

        <div class="flex items-center justify-between">
            <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer">
                <input type="checkbox" name="remember" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                Remember me
            </label>
        </div>

        <button type="submit"
                class="w-full bg-gradient-to-r from-blue-600 to-indigo-700 text-white py-3 rounded-xl font-semibold hover:shadow-lg hover:shadow-blue-600/30 transition duration-300 flex items-center justify-center gap-2">
            <i class="fas fa-sign-in-alt"></i> Sign In to Admin Panel
        </button>
    </form>

    <div class="mt-6 pt-6 border-t border-gray-100 text-center">
        <a href="{{ route('login') }}" class="text-sm text-blue-600 hover:text-blue-700 font-medium">
            <i class="fas fa-arrow-left mr-1"></i> Back to User Login
        </a>
    </div>
</div>
@endsection