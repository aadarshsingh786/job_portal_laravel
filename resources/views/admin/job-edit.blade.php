@extends('admin.layout')

@section('title', 'Edit Job')

@section('admin-content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <h1 class="text-3xl font-bold text-gray-800">Edit Job</h1>
        <p class="text-gray-500 mt-1">Update the job listing</p>
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
    <form method="POST" action="{{ route('admin.jobs.update', $job) }}">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Job Title *</label>
                <input type="text" name="title" value="{{ old('title', $job->title) }}" required
                       class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('title') border-red-400 @enderror">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Location *</label>
                <input type="text" name="location" value="{{ old('location', $job->location) }}" required
                       class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Job Type *</label>
                <select name="job_type" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @foreach(['full-time' => 'Full Time', 'part-time' => 'Part Time', 'contract' => 'Contract', 'temporary' => 'Temporary', 'internship' => 'Internship'] as $value => $label)
                        <option value="{{ $value }}" {{ old('job_type', $job->job_type) == $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Category *</label>
                <select name="category" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @foreach(['Technology', 'Healthcare', 'Finance', 'Education', 'Marketing', 'Sales', 'Design', 'Data Science'] as $category)
                        <option value="{{ $category }}" {{ old('category', $job->category) == $category ? 'selected' : '' }}>{{ $category }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Experience Level *</label>
                <select name="experience_level" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @foreach(['entry' => 'Entry Level', 'mid' => 'Mid Level', 'senior' => 'Senior Level', 'executive' => 'Executive'] as $value => $label)
                        <option value="{{ $value }}" {{ old('experience_level', $job->experience_level) == $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Application Deadline</label>
                <input type="date" name="application_deadline" value="{{ old('application_deadline', optional($job->application_deadline)->format('Y-m-d')) }}"
                       class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Salary Min ($)</label>
                <input type="number" name="salary_min" value="{{ old('salary_min', $job->salary_min) }}"
                       class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Salary Max ($)</label>
                <input type="number" name="salary_max" value="{{ old('salary_max', $job->salary_max) }}"
                       class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
        </div>

        <div class="mt-5">
            <label class="block text-sm font-semibold text-gray-700 mb-2">Description *</label>
            <textarea name="description" rows="5" required
                      class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('description') border-red-400 @enderror">{{ old('description', $job->description) }}</textarea>
        </div>

        <div class="mt-5">
            <label class="block text-sm font-semibold text-gray-700 mb-2">Requirements *</label>
            <textarea name="requirements" rows="4" required
                      class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('requirements') border-red-400 @enderror">{{ old('requirements', $job->requirements) }}</textarea>
        </div>

        <div class="mt-5">
            <label class="block text-sm font-semibold text-gray-700 mb-2">
                <i class="fas fa-building text-blue-500 mr-1"></i> About Company
                <span class="text-xs text-gray-400 font-normal">(optional)</span>
            </label>
            <textarea name="about_company" rows="4"
                      class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('about_company') border-red-400 @enderror"
                      placeholder="Tell applicants about the company — culture, mission, what it's like to work here...">{{ old('about_company', $job->about_company) }}</textarea>
            <p class="text-xs text-gray-400 mt-1.5 flex items-center gap-1">
                <i class="fas fa-info-circle"></i> Shown to job seekers on the posting. Do not include admin account information.
            </p>
        </div>

        <div class="mt-5">
            <label class="block text-sm font-semibold text-gray-700 mb-2">Benefits</label>
            <textarea name="benefits" rows="3"
                      class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('benefits', $job->benefits) }}</textarea>
        </div>

        <div class="mt-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div class="flex flex-wrap items-center gap-4">
                <label class="flex items-center gap-2.5 cursor-pointer bg-yellow-50 border border-yellow-200 rounded-xl px-4 py-3">
                    <input type="checkbox" name="is_featured" value="1" {{ $job->is_featured ? 'checked' : '' }} class="w-4 h-4 text-yellow-500 border-gray-300 rounded focus:ring-yellow-500">
                    <span class="text-sm text-gray-700 font-medium"><i class="fas fa-star text-yellow-500 mr-1"></i> Featured</span>
                </label>
                <label class="flex items-center gap-2.5 cursor-pointer bg-green-50 border border-green-200 rounded-xl px-4 py-3">
                    <input type="checkbox" name="is_active" value="1" {{ $job->is_active ? 'checked' : '' }} class="w-4 h-4 text-green-500 border-gray-300 rounded focus:ring-green-500">
                    <span class="text-sm text-gray-700 font-medium"><i class="fas fa-play text-green-500 mr-1"></i> Active</span>
                </label>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('admin.jobs') }}" class="px-6 py-3 border border-gray-300 rounded-xl hover:bg-gray-50 transition text-gray-700 font-medium">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-700 text-white rounded-xl hover:shadow-lg transition duration-300 font-semibold flex items-center gap-2">
                    <i class="fas fa-save"></i> Save Changes
                </button>
            </div>
        </div>
    </form>
</div>
@endsection