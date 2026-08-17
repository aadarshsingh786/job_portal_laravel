@extends('layouts.app')

@section('title', 'Edit Profile')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex items-center mb-6">
        <a href="{{ route('profile.show') }}" class="text-gray-600 hover:text-gray-800 mr-4">
            <i class="fas fa-arrow-left text-xl"></i>
        </a>
        <h1 class="text-3xl font-bold text-gray-800">Edit Profile</h1>
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

    <div class="bg-white rounded-xl shadow-md p-6">
        <form method="POST" action="{{ route('profile.update') }}">
            @csrf
            @method('PUT')

            <!-- Name -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Full Name *</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}"
                       class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @enderror"
                       required>
                @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Phone -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Phone</label>
                <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                       class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500"
                       placeholder="e.g. +91 98765 43210">
            </div>

            <!-- Bio -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Bio</label>
                <textarea name="bio" rows="4"
                          class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500"
                          placeholder="Tell us a little about yourself...">{{ old('bio', $user->bio) }}</textarea>
            </div>

            @if($user->role == 'employer')
                <hr class="my-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Company Information</h3>
                <!-- Company Name -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Company Name</label>
                    <input type="text" name="company_name" value="{{ old('company_name', $user->company_name) }}"
                           class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="e.g. Acme Corp">
                </div>
                <!-- Company Website -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Company Website</label>
                    <input type="url" name="company_website" value="{{ old('company_website', $user->company_website) }}"
                           class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="https://example.com">
                    @error('company_website')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            @endif

            <!-- Form Buttons -->
            <div class="flex justify-end space-x-3 mt-6">
                <a href="{{ route('profile.show') }}"
                   class="px-6 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition text-gray-700">
                    Cancel
                </a>
                <button type="submit"
                        class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-300 flex items-center">
                    <i class="fas fa-save mr-2"></i> Save Changes
                </button>
            </div>
        </form>
    </div>

    <!-- Avatar upload -->
    <div class="bg-white rounded-xl shadow-md p-6 mt-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Profile Picture</h3>
        <form method="POST" action="{{ route('profile.upload-avatar') }}" enctype="multipart/form-data">
            @csrf
            <div class="flex items-center gap-4">
                @if($user->profile_image)
                    <img src="{{ asset('storage/' . $user->profile_image) }}" alt="Profile"
                         class="w-16 h-16 rounded-full object-cover border-2 border-blue-600">
                @else
                    <div class="w-16 h-16 rounded-full bg-gradient-to-r from-blue-600 to-blue-800 flex items-center justify-center text-white text-xl font-bold">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                @endif
                <div class="flex-1">
                    <input type="file" name="avatar" accept="image/*" required
                           class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    <p class="text-xs text-gray-400 mt-1">JPG, PNG or GIF — max 2MB</p>
                </div>
                <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-300 text-sm">
                    Upload
                </button>
            </div>
        </form>
    </div>
</div>
@endsection