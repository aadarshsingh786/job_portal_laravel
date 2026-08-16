@extends('layouts.app')

@section('title', 'Post a Job')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex items-center mb-6">
        <a href="{{ route('employer.jobs') }}" class="text-gray-600 hover:text-gray-800 mr-4">
            <i class="fas fa-arrow-left text-xl"></i>
        </a>
        <h1 class="text-3xl font-bold text-gray-800">Post a New Job</h1>
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
        <form method="POST" action="{{ route('employer.jobs.store') }}">
            @csrf

            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Job Title *</label>
                <input type="text" name="title" value="{{ old('title') }}" required
                       class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('title') border-red-500 @enderror"
                       placeholder="e.g. Senior Laravel Developer">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Location *</label>
                <input type="text" name="location" value="{{ old('location') }}" required
                       class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('location') border-red-500 @enderror"
                       placeholder="e.g. Mumbai, India">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Job Type *</label>
                    <select name="job_type" required class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @foreach(['full-time' => 'Full Time', 'part-time' => 'Part Time', 'contract' => 'Contract', 'temporary' => 'Temporary', 'internship' => 'Internship'] as $value => $label)
                            <option value="{{ $value }}" {{ old('job_type') == $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Category *</label>
                    <select name="category" required class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @foreach(['Technology', 'Healthcare', 'Finance', 'Education', 'Marketing', 'Sales', 'Design', 'Data Science'] as $category)
                            <option value="{{ $category }}" {{ old('category') == $category ? 'selected' : '' }}>{{ $category }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Experience Level *</label>
                    <select name="experience_level" required class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @foreach(['entry' => 'Entry Level', 'mid' => 'Mid Level', 'senior' => 'Senior Level', 'executive' => 'Executive'] as $value => $label)
                            <option value="{{ $value }}" {{ old('experience_level') == $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Salary Min ($)</label>
                    <input type="number" name="salary_min" value="{{ old('salary_min') }}" class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="50000">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Salary Max ($)</label>
                    <input type="number" name="salary_max" value="{{ old('salary_max') }}" class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="80000">
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Description *</label>
                <textarea name="description" rows="5" required class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('description') border-red-500 @enderror" placeholder="Describe the role...">{{ old('description') }}</textarea>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Requirements *</label>
                <textarea name="requirements" rows="4" required class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('requirements') border-red-500 @enderror" placeholder="One requirement per line...">{{ old('requirements') }}</textarea>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Benefits</label>
                <textarea name="benefits" rows="3" class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Comma separated benefits...">{{ old('benefits') }}</textarea>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Application Deadline</label>
                <input type="date" name="application_deadline" value="{{ old('application_deadline') }}" class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            @if(auth()->user()->isAdmin())
                <div class="flex items-center mb-6">
                    <input type="checkbox" name="is_featured" id="is_featured" value="1" class="w-4 h-4 text-yellow-500 border-gray-300 rounded focus:ring-yellow-500">
                    <label for="is_featured" class="ml-2 text-sm text-gray-700">Feature this job</label>
                </div>
            @endif

            <div class="flex justify-end space-x-3">
                <a href="{{ route('employer.jobs') }}" class="px-6 py-2.5 border border-gray-300 rounded-xl hover:bg-gray-50 transition text-gray-700">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition duration-300 flex items-center">
                    <i class="fas fa-paper-plane mr-2"></i> Post Job
                </button>
            </div>
        </form>
    </div>
</div>
@endsection