@extends('layouts.app')

@section('title', 'Edit Job - ' . $job->title)

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex items-center mb-6">
        <a href="{{ route('dashboard.jobs') }}" class="text-gray-600 hover:text-gray-800 mr-4">
            <i class="fas fa-arrow-left text-xl"></i>
        </a>
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Edit Job</h1>
            <p class="text-sm text-gray-500 mt-1">{{ $job->title }}</p>
        </div>
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
        <form method="POST" action="{{ route('dashboard.jobs.update', $job->id) }}" id="editJobForm">
            @csrf
            @method('PUT')

            <!-- Job Title -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Job Title *</label>
                <input type="text" name="title" id="title" value="{{ old('title', $job->title) }}"
                       class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('title') border-red-500 @enderror"
                       placeholder="e.g. Web Developer" required>
                @error('title')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Location -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Location *</label>
                <input type="text" name="location" id="location" value="{{ old('location', $job->location) }}"
                       class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('location') border-red-500 @enderror"
                       placeholder="e.g. Thane" required>
                @error('location')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Job Type & Category -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Job Type *</label>
                    <select name="job_type" id="job_type" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('job_type') border-red-500 @enderror" required>
                        <option value="">Select type</option>
                        @foreach(['full-time' => 'Full Time', 'part-time' => 'Part Time', 'contract' => 'Contract', 'temporary' => 'Temporary', 'internship' => 'Internship'] as $value => $label)
                            <option value="{{ $value }}" {{ old('job_type', $job->job_type) == $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('job_type')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Category *</label>
                    <select name="category" id="category" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('category') border-red-500 @enderror" required>
                        <option value="">Select category</option>
                        @foreach(['Technology', 'Healthcare', 'Finance', 'Education', 'Marketing', 'Sales', 'Design', 'Data Science'] as $category)
                            <option value="{{ $category }}" {{ old('category', $job->category) == $category ? 'selected' : '' }}>{{ $category }}</option>
                        @endforeach
                    </select>
                    @error('category')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Experience Level & Salary -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Experience Level *</label>
                    <select name="experience_level" id="experience_level" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('experience_level') border-red-500 @enderror" required>
                        <option value="">Select level</option>
                        @foreach(['entry' => 'Entry Level', 'mid' => 'Mid Level', 'senior' => 'Senior Level', 'executive' => 'Executive'] as $value => $label)
                            <option value="{{ $value }}" {{ old('experience_level', $job->experience_level) == $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('experience_level')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Salary Min ($)</label>
                    <input type="number" name="salary_min" id="salary_min" value="{{ old('salary_min', $job->salary_min) }}"
                           class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="50000">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Salary Max ($)</label>
                    <input type="number" name="salary_max" id="salary_max" value="{{ old('salary_max', $job->salary_max) }}"
                           class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="80000">
                </div>
            </div>

            <!-- Description -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Description *</label>
                <textarea name="description" id="description" rows="4"
                          class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('description') border-red-500 @enderror"
                          placeholder="Enter job description..." required>{{ old('description', $job->description) }}</textarea>
                @error('description')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Requirements -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Requirements *</label>
                <textarea name="requirements" id="requirements" rows="4"
                          class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('requirements') border-red-500 @enderror"
                          placeholder="Enter job requirements..." required>{{ old('requirements', $job->requirements) }}</textarea>
                @error('requirements')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Benefits -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Benefits</label>
                <textarea name="benefits" id="benefits" rows="3"
                          class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500"
                          placeholder="Enter job benefits...">{{ old('benefits', $job->benefits) }}</textarea>
            </div>

            <!-- Deadline -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Application Deadline</label>
                <input type="date" name="application_deadline" id="application_deadline" value="{{ old('application_deadline', $job->application_deadline ? $job->application_deadline->format('Y-m-d') : '') }}"
                       class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500">
                @error('application_deadline')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Active & Featured -->
            <div class="flex flex-wrap gap-6 mb-6">
                <div class="flex items-center">
                    <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $job->is_active) ? 'checked' : '' }}
                           class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                    <label for="is_active" class="ml-2 text-sm text-gray-700">Active Job</label>
                </div>
                <div class="flex items-center">
                    <input type="checkbox" name="is_featured" id="is_featured" value="1" {{ old('is_featured', $job->is_featured) ? 'checked' : '' }}
                           class="w-4 h-4 text-yellow-500 border-gray-300 rounded focus:ring-yellow-500">
                    <label for="is_featured" class="ml-2 text-sm text-gray-700">Featured Job</label>
                </div>
            </div>

            <!-- Form Buttons -->
            <div class="flex justify-end space-x-3">
                <a href="{{ route('dashboard.jobs') }}"
                   class="px-6 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition text-gray-700">
                    Cancel
                </a>
                <button type="submit" id="submitBtn"
                        class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-300 flex items-center">
                    <i class="fas fa-save mr-2"></i> Update Job
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('editJobForm');
        const submitBtn = document.getElementById('submitBtn');

        form.addEventListener('submit', function(e) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Updating...';
        });
    });
</script>
@endpush
@endsection