@extends('layouts.app')

@section('title', 'Find Your Dream Career - JobPortal')

@section('content')
<!-- ============ HERO ============ -->
<section class="relative overflow-hidden bg-gradient-to-br from-blue-700 via-blue-600 to-indigo-700">
    <!-- Decorative blobs -->
    <div class="absolute top-0 left-0 w-72 h-72 bg-white/10 rounded-full blur-3xl animate-float"></div>
    <div class="absolute bottom-0 right-0 w-96 h-96 bg-purple-500/20 rounded-full blur-3xl animate-float-slow"></div>
    <div class="absolute top-20 right-1/4 w-40 h-40 bg-cyan-300/10 rounded-full blur-2xl hidden md:block"></div>

    <!-- Grid pattern overlay -->
    <div class="absolute inset-0 opacity-[0.07]" style="background-image: url('data:image/svg+xml,%3Csvg width%3D%2260%22 height%3D%2260%22 viewBox%3D%220 0 60 60%22 xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%3E%3Cg fill%3D%22none%22 fill-rule%3D%22evenodd%22%3E%3Cg fill%3D%22%23ffffff%22 fill-opacity%3D%221%22%3E%3Cpath d%3D%22M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z%22%2F%3E%3C%2Fg%3E%3C%2Fg%3E%3C%2Fsvg%3E');"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-16 pb-24 lg:pt-24 lg:pb-32 text-center">
        <!-- Announcement badge -->
        <div class="reveal reveal-visible inline-flex items-center gap-2 bg-white/10 backdrop-blur border border-white/20 rounded-full px-4 py-1.5 text-sm text-blue-100 mb-8 hover:bg-white/20 transition">
            <span class="relative flex h-2.5 w-2.5">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-400"></span>
            </span>
            {{ $stats['open_jobs'] }}+ jobs available right now
        </div>

        <h1 class="text-4xl md:text-6xl lg:text-7xl font-bold text-white mb-6 leading-tight">
            Find Your <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-300 to-purple-300">Dream Career</span>
        </h1>
        <p class="text-lg md:text-xl text-blue-100 mb-10 max-w-2xl mx-auto leading-relaxed">
            Discover thousands of opportunities from top companies. Match your skills, apply instantly, and land your next role faster.
        </p>

        <!-- Search Bar -->
        <form action="{{ route('jobs.index') }}" method="GET"
              class="max-w-3xl mx-auto bg-white rounded-2xl shadow-2xl p-3 md:p-4 grid grid-cols-1 md:grid-cols-[1fr_1fr_auto] gap-3">
            <div class="relative">
                <i class="fas fa-search absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Job title, keyword or company"
                       class="w-full pl-12 pr-4 py-3.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-gray-800 text-sm md:text-base">
            </div>
            <div class="relative">
                <i class="fas fa-map-marker-alt absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                <input type="text" name="location" value="{{ request('location') }}"
                       placeholder="City or remote"
                       class="w-full pl-12 pr-4 py-3.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-gray-800 text-sm md:text-base">
            </div>
            <button type="submit"
                    class="bg-gradient-to-r from-blue-600 to-blue-700 text-white px-8 py-3.5 rounded-xl font-semibold hover:shadow-lg hover:from-blue-700 hover:to-blue-800 transition duration-300 flex items-center justify-center gap-2">
                <i class="fas fa-search"></i> Search
            </button>
        </form>

        <!-- Popular searches -->
        <div class="mt-8 flex flex-wrap items-center justify-center gap-2 text-sm text-blue-100">
            <span>Popular:</span>
            @foreach(['Laravel', 'React', 'Designer', 'Marketing', 'Remote'] as $tag)
                <a href="{{ route('jobs.index', ['search' => $tag]) }}"
                   class="bg-white/10 hover:bg-white/25 border border-white/20 rounded-full px-3 py-1 transition duration-300">
                    {{ $tag }}
                </a>
            @endforeach
        </div>
    </div>

    <!-- Wave divider -->
    <svg class="absolute bottom-0 left-0 right-0 w-full" viewBox="0 0 1440 120" fill="none" preserveAspectRatio="none">
        <path d="M0 120L60 110C120 100 240 80 360 70C480 60 600 60 720 70C840 80 960 100 1080 105C1200 110 1320 110 1380 110L1440 110V120H0Z" fill="#F9FAFB"/>
    </svg>
</section>

<!-- ============ TRUSTED COMPANIES ============ -->
<section class="bg-gray-50 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <p class="text-center text-sm font-medium text-gray-500 mb-8 tracking-wide uppercase">Trusted by teams at these companies</p>
        <div class="relative overflow-hidden [mask-image:linear-gradient(to_right,transparent,black_20%,black_80%,transparent)]">
            <div class="animate-marquee gap-14 text-gray-400 text-2xl font-bold">
                @foreach([['Google','bg-gray-100'],['Microsoft','bg-gray-100'],['Amazon','bg-gray-100'],['Meta','bg-gray-100'],['Netflix','bg-gray-100'],['Shopify','bg-gray-100'],['Stripe','bg-gray-100'],['Airbnb','bg-gray-100']] as $company)
                    <span class="flex items-center gap-2 whitespace-nowrap">
                        <i class="fas {{ $company[0] === 'Google' ? 'fa-brands fa-google' : ($company[0] === 'Microsoft' ? 'fa-brands fa-microsoft' : ($company[0] === 'Amazon' ? 'fa-brands fa-amazon' : ($company[0] === 'Meta' ? 'fa-brands fa-meta' : ($company[0] === 'Netflix' ? 'fa-brands fa-apple' : ($company[0] === 'Shopify' ? 'fa-brands fa-shopify' : ($company[0] === 'Stripe' ? 'fa-brands fa-stripe-s' : 'fa-brands fa-airbnb')))))) }}"></i>{{ $company[0] }}
                    </span>
                @endforeach
                @foreach([['Google','bg-gray-100'],['Microsoft','bg-gray-100'],['Amazon','bg-gray-100'],['Meta','bg-gray-100'],['Netflix','bg-gray-100'],['Shopify','bg-gray-100'],['Stripe','bg-gray-100'],['Airbnb','bg-gray-100']] as $company)
                    <span class="flex items-center gap-2 whitespace-nowrap">
                        <i class="fas {{ $company[0] === 'Google' ? 'fa-brands fa-google' : ($company[0] === 'Microsoft' ? 'fa-brands fa-microsoft' : ($company[0] === 'Amazon' ? 'fa-brands fa-amazon' : ($company[0] === 'Meta' ? 'fa-brands fa-meta' : ($company[0] === 'Netflix' ? 'fa-brands fa-apple' : ($company[0] === 'Shopify' ? 'fa-brands fa-shopify' : ($company[0] === 'Stripe' ? 'fa-brands fa-stripe-s' : 'fa-brands fa-airbnb')))))) }}"></i>{{ $company[0] }}
                    </span>
                @endforeach
            </div>
        </div>
    </div>
</section>

<!-- ============ STATS ============ -->
<section class="bg-white py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
            @foreach([
                ['icon' => 'fa-briefcase', 'color' => 'from-blue-500 to-blue-700', 'key' => 'open_jobs', 'label' => 'Open Jobs'],
                ['icon' => 'fa-building', 'color' => 'from-purple-500 to-purple-700', 'key' => 'companies', 'label' => 'Companies'],
                ['icon' => 'fa-file-signature', 'color' => 'from-emerald-500 to-emerald-700', 'key' => 'applications', 'label' => 'Applications'],
            ] as $stat)
                <div class="reveal card-lift bg-white border border-gray-100 rounded-2xl p-8 text-center shadow-sm hover:shadow-xl flex flex-col items-center">
                    <div class="w-14 h-14 rounded-xl bg-gradient-to-br {{ $stat['color'] }} text-white flex items-center justify-center text-xl shadow-lg mb-4">
                        <i class="fas {{ $stat['icon'] }}"></i>
                    </div>
                    <div class="text-4xl font-bold text-gray-800 mb-1" data-counter="{{ $stats[$stat['key']] }}">{{ number_format($stats[$stat['key']]) }}</div>
                    <div class="text-sm text-gray-500 font-medium">{{ $stat['label'] }}</div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- ============ POPULAR CATEGORIES ============ -->
<section class="bg-gray-50 py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-14">
            <span class="inline-block bg-blue-100 text-blue-700 text-xs font-bold uppercase tracking-wider px-4 py-1.5 rounded-full mb-4">Browse by category</span>
            <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">Explore Popular Categories</h2>
            <p class="text-gray-500 max-w-xl mx-auto">Find your perfect role across hundreds of industries</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @forelse($categories as $cat)
                @php
                    $iconMap = [
                        'Technology' => 'fa-laptop-code text-blue-600 bg-blue-100',
                        'Healthcare' => 'fa-heart-pulse text-rose-600 bg-rose-100',
                        'Finance' => 'fa-chart-line text-emerald-600 bg-emerald-100',
                        'Education' => 'fa-graduation-cap text-amber-600 bg-amber-100',
                        'Marketing' => 'fa-bullhorn text-purple-600 bg-purple-100',
                        'Construction' => 'fa-hard-hat text-orange-600 bg-orange-100',
                        'Retail' => 'fa-store text-teal-600 bg-teal-100',
                        'Design' => 'fa-paintbrush text-pink-600 bg-pink-100',
                        'Development' => 'fa-code text-indigo-600 bg-indigo-100',
                    ];
                    [$iconFw, $iconBg] = explode(' ', $iconMap[$cat->category] ?? 'fa-briefcase text-gray-600 bg-gray-100');
                @endphp
                <a href="{{ route('jobs.index', ['category' => $cat->category]) }}"
                   class="reveal card-lift group bg-white border border-gray-100 rounded-2xl p-8 shadow-sm hover:shadow-xl flex items-start gap-5">
                    <div class="w-14 h-14 rounded-xl flex items-center justify-center text-xl {{ $iconBg }} shrink-0 group-hover:scale-110 transition duration-300">
                        <i class="fas {{ $iconFw }}"></i>
                    </div>
                    <div class="flex-1">
                        <h3 class="font-bold text-gray-800 mb-1 group-hover:text-blue-600 transition">{{ $cat->category }}</h3>
                        <p class="text-sm text-gray-500">{{ $cat->jobs_count }} open positions</p>
                    </div>
                    <i class="fas fa-arrow-right text-gray-300 group-hover:text-blue-600 group-hover:translate-x-1 transition duration-300 mt-1"></i>
                </a>
            @empty
                <div class="col-span-full text-center text-gray-500 py-10">
                    <i class="fas fa-folder-open text-4xl text-gray-300 mb-3"></i>
                    <p>No categories available yet.</p>
                </div>
            @endforelse
        </div>
    </div>
</section>

<!-- ============ FEATURED JOBS ============ -->
<section class="bg-white py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-14 gap-4">
            <div>
                <span class="inline-block bg-purple-100 text-purple-700 text-xs font-bold uppercase tracking-wider px-4 py-1.5 rounded-full mb-4">Featured</span>
                <h2 class="text-3xl md:text-4xl font-bold text-gray-800">Featured Job Openings</h2>
            </div>
            <a href="{{ route('jobs.index') }}" class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-700 font-semibold group">
                View all jobs <i class="fas fa-arrow-right group-hover:translate-x-1 transition duration-300"></i>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($featuredJobs as $job)
                <article class="reveal card-lift group bg-white border border-gray-100 rounded-2xl p-6 shadow-sm hover:shadow-xl flex flex-col">
                    <div class="flex items-start justify-between mb-5">
                        <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center text-xl font-bold text-gray-600">
                            {{ strtoupper(substr($job->employer->company_name ?? $job->employer->name, 0, 1)) }}
                        </div>
                        <span class="bg-yellow-100 text-yellow-700 text-xs font-semibold px-3 py-1 rounded-full flex items-center gap-1">
                            <i class="fas fa-star text-yellow-500"></i> Featured
                        </span>
                    </div>

                    <h3 class="font-bold text-lg text-gray-800 mb-1 group-hover:text-blue-600 transition">
                        <a href="{{ route('jobs.show', $job) }}">{{ $job->title }}</a>
                    </h3>
                    <p class="text-sm text-gray-500 mb-4">{{ $job->employer->company_name ?? $job->employer->name }}</p>

                    <div class="flex flex-wrap gap-2 mb-5">
                        <span class="bg-blue-50 text-blue-700 text-xs px-3 py-1 rounded-full"><i class="fas fa-map-marker-alt mr-1"></i>{{ $job->location }}</span>
                        <span class="bg-emerald-50 text-emerald-700 text-xs px-3 py-1 rounded-full"><i class="fas fa-clock mr-1"></i>{{ ucfirst(str_replace('-', ' ', $job->job_type)) }}</span>
                        @if($job->salary_min || $job->salary_max)
                            <span class="bg-gray-100 text-gray-700 text-xs px-3 py-1 rounded-full"><i class="fas fa-dollar-sign mr-1"></i>{{ $job->salary_range }}</span>
                        @endif
                    </div>

                    <p class="text-sm text-gray-600 line-clamp-2 mb-6 flex-1">{{ Str::limit($job->description, 150) }}</p>

                    <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                        <span class="text-xs text-gray-400"><i class="far fa-calendar-alt mr-1"></i>{{ $job->created_at->diffForHumans() }}</span>
                        <a href="{{ route('jobs.show', $job) }}" class="inline-flex items-center gap-2 text-sm font-semibold text-blue-600 hover:text-blue-700">
                            Apply now <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </article>
            @empty
                <div class="col-span-full text-center text-gray-500 py-10">
                    <i class="fas fa-star text-4xl text-gray-300 mb-3"></i>
                    <p>No featured jobs available yet.</p>
                </div>
            @endforelse
        </div>
    </div>
</section>

<!-- ============ HOW IT WORKS ============ -->
<section class="bg-gradient-to-br from-blue-50 via-white to-purple-50 py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-14">
            <span class="inline-block bg-indigo-100 text-indigo-700 text-xs font-bold uppercase tracking-wider px-4 py-1.5 rounded-full mb-4">Simple process</span>
            <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">How It Works</h2>
            <p class="text-gray-500 max-w-xl mx-auto">Get hired in three simple steps</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach([
                ['step' => '01', 'icon' => 'fa-user-plus', 'color' => 'from-blue-500 to-blue-700', 'title' => 'Create Account', 'desc' => 'Sign up as a job seeker or employer in under a minute and start exploring opportunities.'],
                ['step' => '02', 'icon' => 'fa-magnifying-glass', 'color' => 'from-purple-500 to-purple-700', 'title' => 'Search & Apply', 'desc' => 'Browse thousands of jobs, filter by role, salary and location, then apply with your resume.'],
                ['step' => '03', 'icon' => 'fa-rocket', 'color' => 'from-emerald-500 to-emerald-700', 'title' => 'Get Hired', 'desc' => 'Track applications, chat with employers, and land the job of your dreams.'],
            ] as $i => $step)
                <div class="reveal reveal-delay-{{ $i + 1 }} card-lift relative bg-white rounded-2xl p-8 shadow-sm hover:shadow-xl border border-gray-100 text-center">
                    <span class="absolute top-6 right-6 text-5xl font-black text-gray-100">{{ $step['step'] }}</span>
                    <div class="w-16 h-16 rounded-2xl bg-gradient-to-br {{ $step['color'] }} text-white flex items-center justify-center text-2xl shadow-lg mx-auto mb-6">
                        <i class="fas {{ $step['icon'] }}"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-3">{{ $step['title'] }}</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">{{ $step['desc'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- ============ TESTIMONIALS ============ -->
<section class="bg-white py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-14">
            <span class="inline-block bg-emerald-100 text-emerald-700 text-xs font-bold uppercase tracking-wider px-4 py-1.5 rounded-full mb-4">Success stories</span>
            <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">What People Say</h2>
            <p class="text-gray-500 max-w-xl mx-auto">Thousands of careers started here</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach([
                ['name' => 'Priya Sharma', 'role' => 'Senior Frontend Developer', 'avatar' => 'PS', 'color' => 'from-blue-500 to-blue-700', 'quote' => 'Found my dream job within two weeks of joining. The interface is so clean and the application process takes minutes.'],
                ['name' => 'Rahul Verma', 'role' => 'Product Manager', 'avatar' => 'RV', 'color' => 'from-purple-500 to-purple-700', 'quote' => 'As a hiring manager, the candidate quality is outstanding. We filled three positions in the first month.'],
                ['name' => 'Ananya Gupta', 'role' => 'Data Scientist', 'avatar' => 'AG', 'color' => 'from-emerald-500 to-emerald-700', 'quote' => 'The featured jobs and salary filters helped me negotiate a 40% hike. Absolutely love this platform!'],
            ] as $testimonial)
                <figure class="reveal card-lift bg-gray-50 rounded-2xl p-8 border border-gray-100 hover:shadow-xl relative">
                    <i class="fas fa-quote-right text-4xl text-gray-200 absolute top-6 right-6"></i>
                    <div class="flex text-amber-400 gap-1 mb-5">
                        @for($s = 0; $s < 5; $s++)
                            <i class="fas fa-star text-sm"></i>
                        @endfor
                    </div>
                    <blockquote class="text-gray-600 leading-relaxed mb-6">"{{ $testimonial['quote'] }}"</blockquote>
                    <figcaption class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-full bg-gradient-to-br {{ $testimonial['color'] }} text-white flex items-center justify-center font-bold">
                            {{ $testimonial['avatar'] }}
                        </div>
                        <div>
                            <div class="font-semibold text-gray-800">{{ $testimonial['name'] }}</div>
                            <div class="text-sm text-gray-500">{{ $testimonial['role'] }}</div>
                        </div>
                    </figcaption>
                </figure>
            @endforeach
        </div>
    </div>
</section>

<!-- ============ CTA ============ -->
<section class="bg-gray-50 pb-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="reveal relative overflow-hidden bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 rounded-3xl p-10 md:p-16 text-center text-white shadow-2xl">
            <div class="absolute -top-20 -left-20 w-72 h-72 bg-white/10 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-20 -right-20 w-72 h-72 bg-cyan-300/10 rounded-full blur-3xl"></div>
            <div class="relative">
                <h2 class="text-3xl md:text-5xl font-bold mb-4">Ready to Take the Next Step?</h2>
                <p class="text-blue-100 text-lg mb-8 max-w-2xl mx-auto">Join {{ number_format($stats['open_jobs'] ?? 0) }}+ job seekers and employers who trust JobPortal for their career journey.</p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('jobs.index') }}" class="bg-white text-blue-700 px-8 py-3.5 rounded-xl font-semibold hover:shadow-xl hover:scale-105 transition duration-300">
                        <i class="fas fa-search mr-2"></i> Browse Jobs
                    </a>
                    <a href="{{ route('register') }}" class="bg-white/10 text-white border-2 border-white/40 px-8 py-3.5 rounded-xl font-semibold backdrop-blur hover:bg-white/20 hover:scale-105 transition duration-300">
                        <i class="fas fa-user-plus mr-2"></i> Get Started Free
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
@endpush