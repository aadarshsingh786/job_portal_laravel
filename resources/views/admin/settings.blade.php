@extends('admin.layout')

@section('title', 'Settings')

@section('admin-content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-800">Settings</h1>
    <p class="text-gray-500 mt-1">Platform configuration</p>
</div>

@if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl mb-6">
        <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
    </div>
@endif

<div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 max-w-2xl">
    <form method="POST" action="{{ route('admin.settings.update') }}">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label class="block text-sm font-semibold text-gray-700 mb-2">Site Name *</label>
            <input type="text" name="site_name" value="{{ cache('site_name', config('app.name')) }}"
                   class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500"
                   required>
        </div>

        <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition duration-300 flex items-center">
            <i class="fas fa-save mr-2"></i> Save Settings
        </button>
    </form>
</div>
@endsection