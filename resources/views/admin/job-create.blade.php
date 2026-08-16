@extends('admin.layout')

@section('title', 'Post a Job')

@section('admin-content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <h1 class="text-3xl font-bold text-gray-800">Post a New Job</h1>
        <p class="text-gray-500 mt-1">Create a job listing for the platform</p>
    </div>
    <a href="{{ route('admin.jobs') }}" class="inline-flex items-center gap-2 text-sm text-gray-600 hover:text-blue-600 bg-white border border-gray-200 px-4 py-2 rounded-xl transition">
        <i class="fas fa-arrow-left"></i> Back to Jobs
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

<div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 lg:p-8">
    <form method="POST" action="{{ route('admin.jobs.store') }}">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Job Title *</label>
                <input type="text" name="title" value="{{ old('title') }}" required
                       class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('title') border-red-400 @enderror"
                       placeholder="e.g. Senior Laravel Developer">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Location *</label>
                <input type="text" name="location" value="{{ old('location') }}" required
                       class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('location') border-red-400 @enderror"
                       placeholder="e.g. Mumbai, India">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Job Type *</label>
                <select name="job_type" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @foreach(['full-time' => 'Full Time', 'part-time' => 'Part Time', 'contract' => 'Contract', 'temporary' => 'Temporary', 'internship' => 'Internship'] as $value => $label)
                        <option value="{{ $value }}" {{ old('job_type') == $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Category *</label>
                <select name="category" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @foreach(['Technology', 'Healthcare', 'Finance', 'Education', 'Marketing', 'Sales', 'Design', 'Data Science'] as $category)
                        <option value="{{ $category }}" {{ old('category') == $category ? 'selected' : '' }}>{{ $category }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Experience Level *</label>
                <select name="experience_level" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @foreach(['entry' => 'Entry Level', 'mid' => 'Mid Level', 'senior' => 'Senior Level', 'executive' => 'Executive'] as $value => $label)
                        <option value="{{ $value }}" {{ old('experience_level') == $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Application Deadline</label>
                <input type="date" name="application_deadline" value="{{ old('application_deadline') }}"
                       class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Salary Min ($)</label>
                <input type="number" name="salary_min" value="{{ old('salary_min') }}" placeholder="50000"
                       class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Salary Max ($)</label>
                <input type="number" name="salary_max" value="{{ old('salary_max') }}" placeholder="80000"
                       class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
        </div>

        <div class="mt-5">
            <label class="block text-sm font-semibold text-gray-700 mb-2">Description *</label>
            <textarea name="description" rows="5" required
                      class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('description') border-red-400 @enderror"
                      placeholder="Describe the role...">{{ old('description') }}</textarea>
        </div>

        <div class="mt-5">
            <label class="block text-sm font-semibold text-gray-700 mb-2">Requirements *</label>
            <textarea name="requirements" rows="4" required
                      class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('requirements') border-red-400 @enderror"
                      placeholder="One requirement per line...">{{ old('requirements') }}</textarea>
        </div>

        <div class="mt-5">
            <label class="block text-sm font-semibold text-gray-700 mb-2">
                <i class="fas fa-building text-blue-500 mr-1"></i> About Company
                <span class="text-xs text-gray-400 font-normal">(optional)</span>
            </label>
            <textarea name="about_company" rows="4"
                      class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('about_company') border-red-400 @enderror"
                      placeholder="Tell applicants about your company, culture, mission and what it's like to work here...">{{ old('about_company') }}</textarea>
            <p class="text-xs text-gray-400 mt-1.5 flex items-center gap-1">
                <i class="fas fa-info-circle"></i> This information is shown to job seekers on the job posting — keep it about the company, not the admin account.
            </p>
        </div>

        <div class="mt-5">
            <label class="block text-sm font-semibold text-gray-700 mb-2">Benefits</label>
            <textarea name="benefits" rows="3"
                      class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500"
                      placeholder="Comma separated benefits...">{{ old('benefits') }}</textarea>
        </div>

        <div class="mt-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <label class="flex items-center gap-2.5 cursor-pointer bg-yellow-50 border border-yellow-200 rounded-xl px-4 py-3">
                <input type="checkbox" name="is_featured" value="1" class="w-4 h-4 text-yellow-500 border-gray-300 rounded focus:ring-yellow-500">
                <span class="text-sm text-gray-700 font-medium">
                    <i class="fas fa-star text-yellow-500 mr-1"></i> Feature this job
                </span>
            </label>

            <div class="flex items-center gap-3">
                <a href="{{ route('admin.jobs') }}" class="px-6 py-3 border border-gray-300 rounded-xl hover:bg-gray-50 transition text-gray-700 font-medium">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-700 text-white rounded-xl hover:shadow-lg transition duration-300 font-semibold flex items-center gap-2">
                    <i class="fas fa-upload"></i> Publish Job
                </button>
            </div>
        </div>
    </form>
</div>
@endsection