@extends('layouts.app')

@section('title', $job->title . ' - Job Details')

@section('content')
<div class="bg-gray-50 min-h-screen pb-28 md:pb-8"
     x-data="jobDetails({
         hasApplied: {{ $hasApplied ? 'true' : 'false' }},
         isSaved: {{ $isSaved ? 'true' : 'false' }},
         isJobSeeker: {{ auth()->check() && auth()->user()->isJobSeeker() ? 'true' : 'false' }},
         isGuest: {{ auth()->check() ? 'false' : 'true' }},
         jobId: {{ $job->id }}
     })">

    <!-- ============ STICKY ACTION BAR ============ -->
    <div class="fixed top-16 left-0 right-0 z-40 bg-white/95 backdrop-blur border-b border-gray-200 shadow-sm transition-all duration-300"
         x-cloak
         x-show="stickyBar"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="-translate-y-full opacity-0"
         x-transition:enter-end="translate-y-0 opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="translate-y-0 opacity-100"
         x-transition:leave-end="-translate-y-full opacity-0">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between gap-4">
            <div class="flex items-center gap-3 min-w-0">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white font-bold shrink-0">
                    {{ strtoupper(substr($job->display_company ?? $job->title, 0, 1)) }}
                </div>
                <div class="min-w-0">
                    <p class="font-bold text-gray-800 truncate">{{ $job->title }}</p>
                    @if($job->display_company)
                        <p class="text-xs text-gray-500 truncate">{{ $job->display_company }}</p>
                    @endif
                </div>
            </div>
            <div class="flex items-center gap-2 shrink-0">
                <button @click="toggleSave"
                        class="px-3 py-2 rounded-xl text-sm font-semibold transition duration-300 flex items-center gap-1.5"
                        :class="isSaved ? 'bg-yellow-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'">
                    <i class="fa-bookmark" :class="isSaved ? 'fas' : 'far'"></i>
                </button>
                <a x-show="isGuest" href="{{ route('login') }}"
                   class="bg-gradient-to-r from-blue-600 to-blue-700 text-white px-5 py-2 rounded-xl text-sm font-semibold hover:shadow-lg transition">
                    <i class="fas fa-sign-in-alt mr-1.5"></i>Login
                </a>
                <template x-if="!isGuest && isJobSeeker && !hasApplied">
                    <button @click="openApplyModal"
                            class="bg-gradient-to-r from-blue-600 to-blue-700 text-white px-5 py-2 rounded-xl text-sm font-semibold hover:shadow-lg transition">
                        <i class="fas fa-paper-plane mr-1.5"></i>Apply Now
                    </button>
                </template>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6">

        <!-- Breadcrumb -->
        <nav class="flex items-center gap-2 text-sm text-gray-500 mb-6 bg-white p-3 rounded-xl shadow-sm overflow-x-auto whitespace-nowrap">
            <a href="{{ route('home') }}" class="hover:text-blue-600 transition flex-shrink-0">
                <i class="fas fa-home"></i>
            </a>
            <i class="fas fa-chevron-right text-xs flex-shrink-0"></i>
            <a href="{{ route('jobs.index') }}" class="hover:text-blue-600 transition flex-shrink-0">Jobs</a>
            <i class="fas fa-chevron-right text-xs flex-shrink-0"></i>
            <span class="text-gray-700 font-medium truncate">{{ Str::limit($job->title, 30) }}</span>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- ============ MAIN CONTENT ============ -->
            <div class="lg:col-span-2 space-y-6 min-w-0">

                <!-- Job Header Hero -->
                <div class="reveal reveal-visible relative overflow-hidden bg-gradient-to-br from-blue-700 via-blue-600 to-indigo-700 rounded-3xl p-6 md:p-8 text-white shadow-xl">
                    <div class="absolute -top-16 -right-16 w-64 h-64 bg-white/10 rounded-full blur-3xl"></div>
                    <div class="absolute -bottom-16 -left-16 w-64 h-64 bg-purple-500/20 rounded-full blur-3xl"></div>

                    <div class="relative">
                        <div class="flex flex-col sm:flex-row sm:items-start gap-5 mb-6">
                            <div class="w-16 h-16 md:w-20 md:h-20 rounded-2xl bg-white/20 backdrop-blur flex items-center justify-center text-2xl md:text-3xl font-bold shrink-0 shadow-lg">
                                {{ strtoupper(substr($job->display_company ?? $job->title, 0, 1)) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex flex-wrap items-center gap-2 mb-2">
                                    <h1 class="text-2xl md:text-4xl font-bold leading-tight">{{ $job->title }}</h1>
                                    @if($job->is_featured)
                                        <span class="bg-yellow-400 text-yellow-900 text-xs px-2.5 py-1 rounded-full font-semibold">
                                            <i class="fas fa-star mr-1"></i> Featured
                                        </span>
                                    @endif
                                </div>
                                <p class="text-blue-100 font-medium flex items-center gap-1.5">
                                    @if($job->display_company)
                                        <i class="fas fa-building"></i>{{ $job->display_company }}
                                        <i class="fas fa-circle-check text-emerald-400 ml-1" title="Verified employer"></i>
                                    @endif
                                </p>
                            </div>
                        </div>

                        <div class="flex flex-wrap gap-2.5 mb-5">
                            <span class="bg-white/10 border border-white/20 rounded-full px-3.5 py-1.5 text-sm flex items-center">
                                <i class="fas fa-map-marker-alt mr-2 text-cyan-300"></i>{{ $job->location }}
                            </span>
                            <span class="bg-white/10 border border-white/20 rounded-full px-3.5 py-1.5 text-sm flex items-center">
                                <i class="fas fa-clock mr-2 text-cyan-300"></i>{{ ucfirst(str_replace('-', ' ', $job->job_type)) }}
                            </span>
                            <span class="bg-white/10 border border-white/20 rounded-full px-3.5 py-1.5 text-sm flex items-center">
                                <i class="fas fa-tag mr-2 text-cyan-300"></i>{{ $job->category }}
                            </span>
                            <span class="bg-white/10 border border-white/20 rounded-full px-3.5 py-1.5 text-sm flex items-center">
                                <i class="fas fa-user-graduate mr-2 text-cyan-300"></i>{{ ucfirst($job->experience_level) }}
                            </span>
                        </div>

                        <div class="flex flex-wrap items-center gap-x-6 gap-y-2 text-sm text-blue-100 border-t border-white/10 pt-4">
                            <span><i class="far fa-calendar-alt mr-2"></i>Posted {{ $job->created_at->diffForHumans() }}</span>
                            @if($job->application_deadline)
                                <span class="flex items-center gap-1.5 {{ $job->application_deadline->isPast() ? 'text-red-300' : '' }}">
                                    <i class="far fa-hourglass mr-1"></i>
                                    Deadline: {{ $job->application_deadline->format('M d, Y') }}
                                    @if($job->application_deadline->isPast())
                                        <span class="bg-red-500/20 text-red-200 text-xs px-2 py-0.5 rounded-full font-semibold">Expired</span>
                                    @endif
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Stats strip -->
                <div class="reveal reveal-visible grid grid-cols-2 md:grid-cols-4 gap-3">
                    @php
                        $stats = [
                            ['icon' => 'fa-dollar-sign', 'bg' => 'from-emerald-500 to-emerald-700', 'label' => 'Salary', 'value' => $job->salary_min || $job->salary_max ? $job->salary_range : 'Not specified', 'small' => true],
                            ['icon' => 'fa-eye', 'bg' => 'from-blue-500 to-blue-700', 'label' => 'Views', 'value' => number_format($job->views)],
                            ['icon' => 'fa-file-signature', 'bg' => 'from-purple-500 to-purple-700', 'label' => 'Applicants', 'value' => number_format($job->applications_count ?? 0)],
                            ['icon' => 'fa-calendar-check', 'bg' => 'from-orange-500 to-orange-700', 'label' => 'Posted', 'value' => $job->created_at->format('M d, Y')],
                        ];
                    @endphp
                    @foreach($stats as $stat)
                        <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100 flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-br {{ $stat['bg'] }} text-white flex items-center justify-center shrink-0">
                                <i class="fas {{ $stat['icon'] }} text-sm"></i>
                            </div>
                            <div class="min-w-0">
                                <p class="text-xs text-gray-500">{{ $stat['label'] }}</p>
                                <p class="font-bold text-gray-800 truncate text-sm {{ $stat['small'] ?? false ? 'text-xs' : '' }}">{{ $stat['value'] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Main content sections -->
                <div id="description" class="reveal bg-white rounded-3xl shadow-sm border border-gray-100 p-6 md:p-8 scroll-mt-24">
                    <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                        <span class="w-1 h-6 bg-blue-600 rounded mr-3"></span>
                        Job Description
                    </h2>
                    <div class="text-gray-700 leading-relaxed whitespace-pre-line">{{ $job->description }}</div>
                </div>

                <div id="requirements" class="reveal bg-white rounded-3xl shadow-sm border border-gray-100 p-6 md:p-8 scroll-mt-24">
                    <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                        <span class="w-1 h-6 bg-purple-600 rounded mr-3"></span>
                        Requirements
                    </h2>
                    @php
                        $reqLines = collect(explode("\n", $job->requirements))->filter(fn($l) => trim($l) !== '')->values();
                    @endphp
                    @if($reqLines->count() > 1)
                        <ul class="space-y-3">
                            @foreach($reqLines as $line)
                                <li class="flex items-start gap-3">
                                    <span class="mt-1.5 w-2 h-2 rounded-full bg-purple-500 flex-shrink-0"></span>
                                    <span class="text-gray-700 leading-relaxed">{{ $line }}</span>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="text-gray-700 leading-relaxed whitespace-pre-line">{{ $job->requirements }}</div>
                    @endif
                </div>

                @if($job->benefits)
                    <div id="benefits" class="reveal bg-white rounded-3xl shadow-sm border border-gray-100 p-6 md:p-8 scroll-mt-24">
                        <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                            <span class="w-1 h-6 bg-emerald-600 rounded mr-3"></span>
                            Benefits & Perks
                        </h2>
                        @php
                            $benefitItems = collect(explode(",", str_replace(["\n", ";"], ",", $job->benefits)))->map(fn($b) => trim($b))->filter(fn($b) => $b !== '')->values();
                        @endphp
                        @if($benefitItems->count() > 1)
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                @foreach($benefitItems as $benefit)
                                    <div class="flex items-center gap-3 bg-emerald-50 rounded-xl px-4 py-3 hover:bg-emerald-100 transition duration-300">
                                        <i class="fas fa-circle-check text-emerald-500"></i>
                                        <span class="text-gray-700 text-sm">{{ $benefit }}</span>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-gray-700 leading-relaxed whitespace-pre-line">{{ $job->benefits }}</div>
                        @endif
                    </div>
                @endif
            </div>

            <!-- ============ SIDEBAR ============ -->
            <div class="space-y-6">
                <!-- Sticky sidebar -->
                <div class="lg:sticky lg:top-20 space-y-6">
                    <!-- Apply card -->
                    <div class="reveal reveal-visible bg-white rounded-3xl shadow-sm border border-gray-100 p-6">
                        <h3 class="font-bold text-gray-800 mb-4 flex items-center">
                            <span class="w-1 h-6 bg-blue-600 rounded mr-3"></span>
                            Quick Apply
                        </h3>
                        @auth
                            @if(auth()->user()->isJobSeeker())
                                <template x-if="hasApplied">
                                    <div class="bg-green-50 border border-green-200 text-green-700 rounded-xl p-4 text-sm font-medium flex items-center gap-2">
                                        <i class="fas fa-check-circle"></i> You've already applied for this job.
                                    </div>
                                </template>
                                <template x-if="!hasApplied">
                                    <button @click="openApplyModal"
                                            class="w-full bg-gradient-to-r from-blue-600 to-blue-700 text-white py-3.5 rounded-xl font-semibold hover:from-blue-700 hover:to-blue-800 hover:shadow-lg transition duration-300 flex items-center justify-center gap-2 group">
                                        <i class="fas fa-paper-plane"></i>
                                        <span>Apply Now</span>
                                        <i class="fas fa-arrow-right group-hover:translate-x-1 transition"></i>
                                    </button>
                                </template>
                            @elseif(auth()->user()->isEmployer())
                                <div class="bg-gray-100 text-gray-600 rounded-xl p-4 text-sm font-medium text-center">
                                    You're viewing as an employer
                                </div>
                            @elseif(auth()->user()->isAdmin())
                                <div class="bg-gray-100 text-gray-600 rounded-xl p-4 text-sm font-medium text-center">
                                    Admin preview
                                </div>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="block w-full bg-gradient-to-r from-blue-600 to-blue-700 text-white py-3.5 rounded-xl font-semibold hover:shadow-lg transition duration-300 text-center">
                                <i class="fas fa-sign-in-alt mr-2"></i>Login to Apply
                            </a>
                        @endauth

                        <button @click="toggleSave"
                                class="mt-3 w-full py-3 rounded-xl font-semibold transition duration-300 flex items-center justify-center gap-2"
                                :class="isSaved ? 'bg-yellow-500 text-white hover:bg-yellow-600' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'">
                            <i class="fa-bookmark" :class="isSaved ? 'fas' : 'far'"></i>
                            <span x-text="isSaved ? 'Saved' : 'Save Job'"></span>
                        </button>
                    </div>

                    <!-- Inline apply form -->
                    <template x-if="!isGuest && isJobSeeker && !hasApplied">
                        <div class="reveal bg-white rounded-3xl shadow-sm border border-gray-100 p-6">
                            <h3 class="font-bold text-gray-800 mb-4 flex items-center">
                                <span class="w-1 h-6 bg-blue-600 rounded mr-3"></span>
                                Submit Your Application
                            </h3>
                            <form @submit.prevent="submitApplication" class="space-y-4">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Cover Letter</label>
                                    <textarea x-model="coverLetter" rows="4"
                                              placeholder="Why are you a great fit for this role?"
                                              class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-gray-800 placeholder-gray-400 transition resize-none text-sm"></textarea>
                                    <p class="mt-1 text-xs text-gray-400" x-text="coverLetter.length + ' / 5000 characters'"></p>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Resume (PDF, DOC)</label>
                                    <label class="flex items-center justify-center w-full border-2 border-dashed border-gray-300 rounded-xl p-4 cursor-pointer hover:border-blue-500 hover:bg-blue-50/50 transition duration-300"
                                           :class="resumeFile ? 'border-emerald-400 bg-emerald-50/50' : ''">
                                        <template x-if="!resumeFile">
                                            <span class="text-sm text-gray-500"><i class="fas fa-cloud-arrow-up mr-2"></i>Click to upload resume</span>
                                        </template>
                                        <template x-if="resumeFile">
                                            <span class="text-sm text-emerald-700 font-medium flex items-center"><i class="fas fa-file-pdf mr-2"></i><span x-text="resumeFile.name"></span></span>
                                        </template>
                                        <input type="file" class="hidden" accept=".pdf,.doc,.docx" @change="resumeFile = $event.target.files[0]">
                                    </label>
                                </div>
                                <template x-if="submitState === 'error'">
                                    <div class="bg-red-50 border border-red-200 text-red-700 rounded-xl p-3 text-sm flex items-center gap-2">
                                        <i class="fas fa-exclamation-circle"></i><span x-text="errorMessage"></span>
                                    </div>
                                </template>
                                <button type="submit" :disabled="submitting"
                                        class="w-full bg-gradient-to-r from-blue-600 to-blue-700 text-white py-3 rounded-xl font-semibold hover:shadow-lg transition duration-300 flex items-center justify-center gap-2">
                                    <template x-if="!submitting">
                                        <span class="flex items-center gap-2"><i class="fas fa-paper-plane"></i> Submit Application</span>
                                    </template>
                                    <template x-if="submitting">
                                        <span class="flex items-center gap-2"><i class="fas fa-spinner fa-spin"></i> Submitting...</span>
                                    </template>
                                </button>
                            </form>
                        </div>
                    </template>

                    <!-- Job details -->
                    <div class="reveal bg-white rounded-3xl shadow-sm border border-gray-100 p-6">
                        <h3 class="text-lg font-bold text-gray-800 mb-5 flex items-center">
                            <span class="w-1 h-6 bg-indigo-600 rounded mr-3"></span>
                            Job Overview
                        </h3>
                        <div class="space-y-4">
                            @foreach([
                                ['icon' => 'fa-user-graduate', 'bg' => 'bg-blue-100', 'color' => 'text-blue-600', 'label' => 'Experience', 'value' => ucfirst($job->experience_level)],
                                ['icon' => 'fa-tag', 'bg' => 'bg-purple-100', 'color' => 'text-purple-600', 'label' => 'Category', 'value' => $job->category],
                                ['icon' => 'fa-clock', 'bg' => 'bg-green-100', 'color' => 'text-green-600', 'label' => 'Job Type', 'value' => ucfirst(str_replace('-', ' ', $job->job_type))],
                                ['icon' => 'fa-map-marker-alt', 'bg' => 'bg-orange-100', 'color' => 'text-orange-600', 'label' => 'Location', 'value' => $job->location],
                                ['icon' => 'fa-calendar-alt', 'bg' => 'bg-indigo-100', 'color' => 'text-indigo-600', 'label' => 'Posted On', 'value' => $job->created_at->format('M d, Y')],
                            ] as $detail)
                                <div class="flex items-start gap-3">
                                    <div class="w-9 h-9 {{ $detail['bg'] }} rounded-lg flex items-center justify-center flex-shrink-0">
                                        <i class="fas {{ $detail['icon'] }} {{ $detail['color'] }} text-sm"></i>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-xs text-gray-500 font-medium">{{ $detail['label'] }}</p>
                                        <p class="text-sm font-semibold text-gray-800">{{ $detail['value'] }}</p>
                                    </div>
                                </div>
                            @endforeach
                            @if($job->application_deadline)
                                <div class="flex items-start gap-3">
                                    <div class="w-9 h-9 bg-red-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <i class="fas fa-hourglass-end text-red-600 text-sm"></i>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-xs text-gray-500 font-medium">Application Deadline</p>
                                        <p class="text-sm font-semibold {{ $job->application_deadline->isPast() ? 'text-red-500' : 'text-gray-800' }}">
                                            {{ $job->application_deadline->format('M d, Y') }}
                                        </p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Company -->
                    @if($job->about_company)
                    <div class="reveal bg-white rounded-3xl shadow-sm border border-gray-100 p-6">
                        <h3 class="text-lg font-bold text-gray-800 mb-5 flex items-center">
                            <span class="w-1 h-6 bg-purple-600 rounded mr-3"></span>
                            About Company
                        </h3>
                        <div class="text-center">
                            <div class="w-20 h-20 bg-gradient-to-br from-blue-500 to-purple-600 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg">
                                <i class="fas fa-building text-white text-3xl"></i>
                            </div>
                            <p class="text-sm text-gray-600 text-left leading-relaxed">{{ $job->about_company }}</p>
                        </div>
                    </div>
                    @endif

                    <!-- Share -->
                    <div class="reveal bg-white rounded-3xl shadow-sm border border-gray-100 p-6">
                        <h3 class="text-lg font-bold text-gray-800 mb-5 flex items-center">
                            <span class="w-1 h-6 bg-green-600 rounded mr-3"></span>
                        Share This Job
                        </h3>
                        @php
                            $url = urlencode(url()->current());
                            $title = urlencode($job->title);
                        @endphp
                        <div class="flex flex-wrap justify-center gap-3">
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ $url }}" target="_blank" rel="noopener"
                               class="w-11 h-11 bg-[#1877F2] text-white rounded-full flex items-center justify-center hover:scale-110 hover:shadow-lg transition duration-300" aria-label="Share on Facebook">
                                <i class="fab fa-facebook-f text-lg"></i>
                            </a>
                            <a href="https://twitter.com/intent/tweet?url={{ $url }}&text={{ $title }}" target="_blank" rel="noopener"
                               class="w-11 h-11 bg-[#000000] text-white rounded-full flex items-center justify-center hover:scale-110 hover:shadow-lg transition duration-300" aria-label="Share on X">
                                <i class="fab fa-x-twitter text-lg"></i>
                            </a>
                            <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ $url }}" target="_blank" rel="noopener"
                               class="w-11 h-11 bg-[#0A66C2] text-white rounded-full flex items-center justify-center hover:scale-110 hover:shadow-lg transition duration-300" aria-label="Share on LinkedIn">
                                <i class="fab fa-linkedin-in text-lg"></i>
                            </a>
                            <a href="https://api.whatsapp.com/send?text={{ $title }}%20{{ $url }}" target="_blank" rel="noopener"
                               class="w-11 h-11 bg-[#25D366] text-white rounded-full flex items-center justify-center hover:scale-110 hover:shadow-lg transition duration-300" aria-label="Share on WhatsApp">
                                <i class="fab fa-whatsapp text-lg"></i>
                            </a>
                            <button @click="copyLink"
                                    class="w-11 h-11 bg-gray-800 text-white rounded-full flex items-center justify-center hover:scale-110 hover:shadow-lg transition duration-300" aria-label="Copy link">
                                <i class="fas fa-link text-lg"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Related Jobs -->
        @if($relatedJobs->count() > 0)
            <div class="mt-14">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold text-gray-800">Similar Jobs You Might Like</h2>
                    <a href="{{ route('jobs.index') }}" class="hidden sm:inline-flex items-center gap-2 text-blue-600 hover:text-blue-700 font-semibold text-sm group">
                        View all <i class="fas fa-arrow-right group-hover:translate-x-1 transition"></i>
                    </a>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach($relatedJobs as $relatedJob)
                        <article class="reveal card-lift group bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-xl transition duration-300">
                            <div class="flex items-start justify-between mb-3">
                                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center font-bold text-gray-600">
                                    {{ strtoupper(substr($relatedJob->employer->company_name ?? $relatedJob->employer->name, 0, 1)) }}
                                </div>
                                @if($relatedJob->is_featured)
                                    <span class="bg-yellow-100 text-yellow-700 text-xs px-2 py-1 rounded-full"><i class="fas fa-star mr-1 text-yellow-500"></i>Featured</span>
                                @endif
                            </div>
                            <h4 class="font-bold text-gray-800 text-lg mb-1 group-hover:text-blue-600 transition">
                                <a href="{{ route('jobs.show', $relatedJob) }}">{{ $relatedJob->title }}</a>
                            </h4>
                            <p class="text-sm text-gray-500 mb-3">{{ $relatedJob->employer->company_name ?? $relatedJob->employer->name }}</p>
                            <div class="flex flex-wrap gap-2 mb-3 text-xs">
                                <span class="bg-blue-50 text-blue-700 px-2.5 py-1 rounded-full"><i class="fas fa-map-marker-alt mr-1"></i>{{ $relatedJob->location }}</span>
                                <span class="bg-emerald-50 text-emerald-700 px-2.5 py-1 rounded-full"><i class="fas fa-clock mr-1"></i>{{ ucfirst(str_replace('-', ' ', $relatedJob->job_type)) }}</span>
                            </div>
                            @if($relatedJob->salary_min || $relatedJob->salary_max)
                                <p class="text-sm text-green-600 font-medium mb-4"><i class="fas fa-dollar-sign mr-1"></i>{{ $relatedJob->salary_range }}</p>
                            @endif
                            <a href="{{ route('jobs.show', $relatedJob) }}"
                               class="block text-center bg-blue-600 text-white px-4 py-2.5 rounded-xl text-sm hover:bg-blue-700 transition shadow-md hover:shadow-lg">
                                View Details <i class="fas fa-arrow-right ml-1"></i>
                            </a>
                        </article>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Back Button -->
        <div class="mt-8">
            <a href="{{ route('jobs.index') }}"
               class="inline-flex items-center gap-2 text-gray-600 hover:text-blue-600 transition bg-white px-6 py-3 rounded-xl shadow-sm hover:shadow-md border border-gray-100">
                <i class="fas fa-arrow-left"></i> Back to Job Listings
            </a>
        </div>
    </div>

    <!-- ============ STICKY MOBILE CTA BAR ============ -->
    <div class="fixed bottom-0 inset-x-0 z-40 md:hidden bg-white border-t border-gray-200 shadow-[0_-4px_20px_rgba(0,0,0,0.08)] px-4 py-3"
         x-show="showMobileBar"
         x-transition
         x-cloak>
        <div class="flex gap-3">
            <template x-if="isGuest">
                <a href="{{ route('login') }}" class="flex-1 bg-gradient-to-r from-blue-600 to-blue-700 text-white py-3 rounded-xl font-semibold text-center flex items-center justify-center gap-2">
                    <i class="fas fa-sign-in-alt"></i> Login to Apply
                </a>
            </template>
            <template x-if="!isGuest && isJobSeeker && !hasApplied">
                <button @click="openApplyModal" class="flex-1 bg-gradient-to-r from-blue-600 to-blue-700 text-white py-3 rounded-xl font-semibold flex items-center justify-center gap-2">
                    <i class="fas fa-paper-plane"></i> Apply Now
                </button>
            </template>
            <template x-if="hasApplied">
                <div class="flex-1 bg-green-500 text-white py-3 rounded-xl font-semibold text-center flex items-center justify-center gap-2">
                    <i class="fas fa-check-circle"></i> Applied
                </div>
            </template>
            <button @click="toggleSave"
                    class="px-5 rounded-xl font-semibold transition duration-300 flex items-center justify-center"
                    :class="isSaved ? 'bg-yellow-500 text-white' : 'bg-gray-100 text-gray-700'">
                <i class="fa-bookmark text-lg" :class="isSaved ? 'fas' : 'far'"></i>
            </button>
        </div>
    </div>

    <!-- Back to top -->
    <button @click="scrollTop"
            x-show="showBackTop"
            x-transition
            x-cloak
            class="fixed bottom-20 md:bottom-6 right-4 md:right-6 z-40 w-11 h-11 rounded-full bg-blue-600 text-white shadow-lg hover:bg-blue-700 hover:scale-110 transition duration-300 flex items-center justify-center">
        <i class="fas fa-arrow-up"></i>
    </button>

    <!-- ============ APPLY MODAL ============ -->
    <div x-show="applyOpen" x-cloak
         class="fixed inset-0 z-50 flex items-end sm:items-center justify-center p-0 sm:p-4"
         x-transition.opacity
         @keydown.escape.window="closeApplyModal">
        <!-- Backdrop -->
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" @click="closeApplyModal"></div>

        <!-- Modal panel -->
        <div class="relative bg-white w-full sm:max-w-lg rounded-t-3xl sm:rounded-3xl shadow-2xl max-h-[90vh] overflow-y-auto"
             x-show="applyOpen"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="translate-y-full opacity-0 sm:translate-y-4 sm:scale-95"
             x-transition:enter-end="translate-y-0 opacity-100 sm:scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="translate-y-0 opacity-100 sm:scale-100"
             x-transition:leave-end="translate-y-full opacity-0 sm:translate-y-4 sm:scale-95">

            <!-- Modal header -->
            <div class="sticky top-0 bg-gradient-to-r from-blue-600 to-indigo-700 text-white px-6 py-5 rounded-t-3xl flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-xl bg-white/20 backdrop-blur flex items-center justify-center text-lg font-bold">
                        {{ strtoupper(substr($job->display_company ?? $job->title, 0, 1)) }}
                    </div>
                    <div>
                        <h3 class="font-bold">Apply for {{ Str::limit($job->title, 35) }}</h3>
                        @if($job->display_company)
                            <p class="text-xs text-blue-100">{{ $job->display_company }}</p>
                        @endif
                    </div>
                </div>
                <button @click="closeApplyModal" class="w-9 h-9 rounded-full bg-white/10 hover:bg-white/25 transition flex items-center justify-center">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <!-- Modal body -->
            <div class="p-6">
                <template x-if="submitState === 'success'">
                    <div class="text-center py-8">
                        <div class="w-20 h-20 mx-auto bg-green-100 rounded-full flex items-center justify-center mb-5">
                            <i class="fas fa-check text-green-600 text-4xl"></i>
                        </div>
                        <h4 class="text-2xl font-bold text-gray-800 mb-2">Application Sent!</h4>
                        <p class="text-gray-500 mb-6">Your application has been submitted successfully. The employer will review it shortly.</p>
                        <button @click="closeApplyModal(); hasApplied = true"
                                class="w-full bg-blue-600 text-white py-3 rounded-xl font-semibold hover:bg-blue-700 transition">
                            Done
                        </button>
                    </div>
                </template>

                <template x-if="submitState !== 'success'">
                    <form @submit.prevent="submitApplication" class="space-y-5" enctype="multipart/form-data">
                        <!-- Cover letter -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Cover Letter <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <i class="fas fa-align-left absolute left-4 top-4 text-gray-400"></i>
                                <textarea name="cover_letter" rows="5" x-model="coverLetter"
                                          placeholder="Write a short note about why you're a great fit for this role..."
                                          class="w-full pl-11 pr-4 py-3.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-gray-800 placeholder-gray-400 transition resize-none"></textarea>
                            </div>
                            <p class="mt-1 text-xs text-gray-400" x-text="coverLetter.length + ' / 5000 characters'"></p>
                        </div>

                        <!-- Resume -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Resume (PDF, DOC)</label>
                            <label class="flex flex-col items-center justify-center w-full border-2 border-dashed border-gray-300 rounded-xl p-6 cursor-pointer hover:border-blue-500 hover:bg-blue-50/50 transition duration-300"
                                   :class="resumeFile ? 'border-emerald-400 bg-emerald-50/50' : ''">
                                <template x-if="!resumeFile">
                                    <div class="text-center">
                                        <i class="fas fa-cloud-arrow-up text-3xl text-gray-300 mb-2"></i>
                                        <p class="text-sm font-medium text-gray-600">Click to upload your resume</p>
                                        <p class="text-xs text-gray-400 mt-1">PDF, DOC or DOCX — max 5MB</p>
                                    </div>
                                </template>
                                <template x-if="resumeFile">
                                    <div class="text-center">
                                        <i class="fas fa-file-pdf text-3xl text-emerald-500 mb-2"></i>
                                        <p class="text-sm font-medium text-emerald-700" x-text="resumeFile.name"></p>
                                        <p class="text-xs text-emerald-500 mt-1" x-text="(resumeFile.size / 1024).toFixed(0) + ' KB'"></p>
                                    </div>
                                </template>
                                <input type="file" name="resume" class="hidden" accept=".pdf,.doc,.docx"
                                       @change="resumeFile = $event.target.files[0]">
                            </label>
                        </div>

                        <template x-if="submitState === 'error'">
                            <div class="bg-red-50 border border-red-200 text-red-700 rounded-xl p-3 text-sm flex items-center gap-2">
                                <i class="fas fa-exclamation-circle"></i>
                                <span x-text="errorMessage"></span>
                            </div>
                        </template>

                        <button type="submit" :disabled="submitting"
                                class="w-full bg-gradient-to-r from-blue-600 to-indigo-700 text-white py-3.5 rounded-xl font-semibold hover:shadow-lg transition duration-300 flex items-center justify-center gap-2">
                            <template x-if="!submitting">
                                <span class="flex items-center gap-2"><i class="fas fa-paper-plane"></i> Submit Application</span>
                            </template>
                            <template x-if="submitting">
                                <span class="flex items-center gap-2"><i class="fas fa-spinner fa-spin"></i> Submitting...</span>
                            </template>
                        </button>
                    </form>
                </template>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
    function jobDetails(config) {
        return {
            jobId: config.jobId,
            hasApplied: config.hasApplied,
            isSaved: config.isSaved,
            isJobSeeker: config.isJobSeeker,
            isGuest: config.isGuest,

            stickyBar: false,
            showMobileBar: false,
            showBackTop: false,
            applyOpen: false,
            submitting: false,
            submitState: 'idle',
            errorMessage: '',
            coverLetter: '',
            resumeFile: null,

            init() {
                window.addEventListener('scroll', () => {
                    const y = window.scrollY;
                    this.stickyBar = y > 400;
                    this.showMobileBar = y > 400;
                    this.showBackTop = y > 700;
                }, { passive: true });
            },

            openApplyModal() {
                this.applyOpen = true;
                this.submitState = 'idle';
                document.body.style.overflow = 'hidden';
            },

            closeApplyModal() {
                this.applyOpen = false;
                document.body.style.overflow = '';
            },

            async submitApplication() {
                this.submitting = true;
                this.submitState = 'idle';

                const formData = new FormData();
                formData.append('cover_letter', this.coverLetter);
                if (this.resumeFile) formData.append('resume', this.resumeFile);

                try {
                    const response = await axios.post(`/jobs/${this.jobId}/apply`, formData, {
                        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
                    });
                    if (response.data.success) {
                        this.submitState = 'success';
                        this.hasApplied = true;
                        this.showToast(response.data.message, 'success');
                    }
                } catch (error) {
                    this.submitState = 'error';
                    this.errorMessage = error.response?.data?.message || 'Error submitting application';
                } finally {
                    this.submitting = false;
                }
            },

            async toggleSave() {
                const method = this.isSaved ? 'delete' : 'post';
                const url = this.isSaved ? `/jobs/${this.jobId}/unsave` : `/jobs/${this.jobId}/save`;
                try {
                    const response = await axios({ method, url, headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }});
                    if (response.data.success) {
                        this.isSaved = !this.isSaved;
                        this.showToast(response.data.message, 'success');
                    }
                } catch (error) {
                    this.showToast(error.response?.data?.message || 'Error saving job', 'error');
                }
            },

            copyLink() {
                navigator.clipboard.writeText(window.location.href).then(() => {
                    this.showToast('Job link copied to clipboard!', 'success');
                });
            },

            scrollTop() {
                window.scrollTo({ top: 0, behavior: 'smooth' });
            },

            showToast(message, type = 'info') {
                const colors = { success: 'bg-green-500', error: 'bg-red-500', info: 'bg-blue-500' };
                const toast = document.createElement('div');
                toast.className = `fixed bottom-20 md:bottom-6 right-4 ${colors[type]} text-white px-6 py-3 rounded-xl shadow-xl z-[60] transition duration-500`;
                toast.style.transform = 'translateY(100px)';
                toast.innerHTML = `<div class="flex items-center gap-2"><i class="fas ${type === 'success' ? 'fa-check-circle' : type === 'error' ? 'fa-exclamation-circle' : 'fa-info-circle'}"></i><span>${message}</span></div>`;
                document.body.appendChild(toast);
                requestAnimationFrame(() => { toast.style.transform = 'translateY(0)'; });
                setTimeout(() => { toast.style.transform = 'translateY(100px)'; setTimeout(() => toast.remove(), 500); }, 4000);
            }
        };
    }
</script>
@endpush

@push('styles')
<style>
    .whitespace-pre-line { white-space: pre-line; }
    html { scroll-behavior: smooth; }
</style>
@endpush