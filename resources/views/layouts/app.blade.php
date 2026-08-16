<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Job Portal') - Find Your Dream Job</title>

    <script>document.documentElement.classList.add('js');</script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    @if (file_exists(public_path('build/manifest.json')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/alpinejs@2.8.2/dist/alpine.min.js" defer></script>
    @endif
    @stack('styles')
</head>
<body class="font-sans antialiased bg-gray-50">
    <div id="app" x-data="{ mobileOpen: false, notifOpen: false, msgOpen: false, profileOpen: false }">
        <!-- Navigation -->
        <nav class="bg-white/95 backdrop-blur border-b border-gray-200 sticky top-0 z-50 shadow-sm" x-data="{ scrolled: false }" @scroll.window="scrolled = window.scrollY > 10">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <!-- Logo -->
                    <div class="flex items-center">
                        <a href="{{ route('home') }}" class="flex items-center space-x-2">
                            <div class="w-10 h-10 bg-gradient-to-r from-blue-600 to-blue-800 rounded-lg flex items-center justify-center shadow-md">
                                <i class="fas fa-briefcase text-white text-xl"></i>
                            </div>
                            <span class="text-2xl font-bold bg-gradient-to-r from-blue-600 to-blue-800 bg-clip-text text-transparent">
                                JobPortal
                            </span>
                        </a>
                    </div>

                    <!-- Desktop Navigation -->
                    <div class="hidden md:flex items-center space-x-8">
                        <a href="{{ route('jobs.index') }}" class="text-gray-700 hover:text-blue-600 transition duration-300">
                            <i class="fas fa-search mr-1"></i> Browse Jobs
                        </a>

                        @auth
                            @if(auth()->user()->isEmployer() || auth()->user()->isAdmin())
                                <a href="{{ route('employer.dashboard') }}" class="text-gray-700 hover:text-blue-600 transition duration-300">
                                    <i class="fas fa-chart-line mr-1"></i> Dashboard
                                </a>
                            @endif

                            @if(auth()->user()->isAdmin())
                                <a href="{{ route('admin.dashboard') }}" class="text-gray-700 hover:text-blue-600 transition duration-300">
                                    <i class="fas fa-cog mr-1"></i> Admin
                                </a>
                            @endif
                        @endauth
                    </div>

                    <!-- Right Menu -->
                    <div class="flex items-center space-x-4">
                        @auth
                            <!-- Notifications -->
                            <div class="relative hidden sm:block">
                                <button @click="notifOpen = !notifOpen" class="text-gray-600 hover:text-blue-600 transition duration-300 relative">
                                    <i class="fas fa-bell text-xl"></i>
                                    @auth
                                        @if(auth()->user()->unreadNotifications()->count() > 0)
                                            <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">
                                                {{ auth()->user()->unreadNotifications()->count() }}
                                            </span>
                                        @endif
                                    @endauth
                                </button>
                                
                                <!-- Notification Dropdown -->
                                @auth
                                    <div x-show="notifOpen" @click.away="notifOpen = false" 
                                        class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-lg border border-gray-200 z-50 max-h-96 overflow-y-auto">
                                        <div class="p-4 border-b border-gray-200">
                                            <div class="flex justify-between items-center">
                                                <h3 class="font-semibold text-gray-800">Notifications</h3>
                                                @if(auth()->user()->unreadNotifications()->count() > 0)
                                                    <form method="POST" action="{{ route('notifications.read-all') }}" class="inline">
                                                        @csrf
                                                        @method('PUT')
                                                        <button type="submit" class="text-sm text-blue-600 hover:text-blue-700">
                                                            Mark all as read
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="divide-y divide-gray-100">
                                            @forelse(auth()->user()->notifications()->latest()->limit(5)->get() as $notification)
                                                <a href="{{ $notification->link ?? '#' }}" class="block p-4 hover:bg-gray-50 transition duration-300">
                                                    <div class="flex items-start gap-3">
                                                        <div class="flex-shrink-0">
                                                            @if(!$notification->is_read)
                                                                <span class="w-2 h-2 bg-blue-600 rounded-full inline-block mt-2"></span>
                                                            @else
                                                                <span class="w-2 h-2 bg-gray-300 rounded-full inline-block mt-2"></span>
                                                            @endif
                                                        </div>
                                                        <div>
                                                            <p class="text-sm font-medium text-gray-800">{{ $notification->title }}</p>
                                                            <p class="text-xs text-gray-600">{{ $notification->message }}</p>
                                                            <p class="text-xs text-gray-400 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                                                        </div>
                                                    </div>
                                                </a>
                                            @empty
                                                <div class="p-4 text-center text-gray-500">
                                                    <i class="fas fa-bell-slash text-2xl mb-2 block"></i>
                                                    <p class="text-sm">No notifications</p>
                                                </div>
                                            @endforelse
                                        </div>
                                        @if(auth()->user()->notifications()->count() > 5)
                                            <div class="p-2 border-t border-gray-200 text-center">
                                                <a href="{{ route('notifications.index') }}" class="text-sm text-blue-600 hover:text-blue-700">
                                                    View all notifications
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                @endauth
                            </div>

                            <!-- Messages -->
                            <a href="{{ route('messages.index') }}" class="text-gray-600 hover:text-blue-600 transition duration-300 relative p-2 rounded-lg hover:bg-gray-100 hidden sm:block">
                                <i class="fas fa-envelope text-xl"></i>
                                @if(auth()->user()->receivedMessages()->where('is_read', false)->count() > 0)
                                    <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center shadow">
                                        {{ auth()->user()->receivedMessages()->where('is_read', false)->count() }}
                                    </span>
                                @endif
                            </a>

                            <!-- Profile Dropdown -->
                            <div class="relative hidden sm:block">
                                <button @click="profileOpen = !profileOpen" class="flex items-center space-x-2 focus:outline-none hover:bg-gray-100 rounded-lg p-1.5 transition">
                                    @if(auth()->user()->profile_image)
                                        <img src="{{ asset('storage/' . auth()->user()->profile_image) }}"
                                             alt="Profile"
                                             class="w-8 h-8 rounded-full object-cover border-2 border-blue-600">
                                    @else
                                        <div class="w-8 h-8 rounded-full bg-gradient-to-r from-blue-600 to-blue-800 flex items-center justify-center text-white font-semibold">
                                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                        </div>
                                    @endif
                                    <span class="text-gray-700 hidden lg:block">{{ auth()->user()->name }}</span>
                                    <i class="fas fa-chevron-down text-gray-500 text-xs"></i>
                                </button>

                                <div x-show="profileOpen" @click.away="profileOpen = false" x-cloak
                                     class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-xl py-2 border border-gray-100 z-50">
                                    <a href="{{ route('profile.show') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-50 transition duration-300">
                                        <i class="fas fa-user mr-2"></i> Profile
                                    </a>
                                    <a href="{{ route('applications.index') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-50 transition duration-300">
                                        <i class="fas fa-file-alt mr-2"></i> My Applications
                                    </a>
                                    <hr class="my-2">
                                    <a href="{{ route('logout') }}"
                                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                                       class="block px-4 py-2 text-red-600 hover:bg-gray-50 transition duration-300">
                                        <i class="fas fa-sign-out-alt mr-2"></i> Logout
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                                        @csrf
                                    </form>
                                </div>
                            </div>
                        @else
                            <a href="{{ route('login') }}" class="text-gray-700 hover:text-blue-600 transition duration-300 px-3 py-2 rounded-lg hover:bg-gray-100">
                                <i class="fas fa-sign-in-alt mr-1"></i> Login
                            </a>
                            <a href="{{ route('register') }}" class="bg-gradient-to-r from-blue-600 to-blue-800 text-white px-4 py-2 rounded-lg hover:shadow-lg transition duration-300 hidden sm:block">
                                <i class="fas fa-user-plus mr-1"></i> Register
                            </a>
                        @endauth

                        <!-- Mobile Hamburger -->
                        <button @click="mobileOpen = !mobileOpen" class="md:hidden p-2 rounded-lg hover:bg-gray-100 text-gray-700 focus:outline-none">
                            <svg x-show="!mobileOpen" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                            <svg x-show="mobileOpen" x-cloak xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Mobile Menu -->
            <div x-show="mobileOpen" @click.away="mobileOpen = false" x-cloak x-transition class="md:hidden bg-white border-t border-gray-200 shadow-lg">
                <div class="px-4 py-4 space-y-3">
                    <a href="{{ route('jobs.index') }}" class="block px-4 py-2.5 rounded-lg text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition duration-300">
                        <i class="fas fa-search mr-2"></i> Browse Jobs
                    </a>
                    @auth
                        @if(auth()->user()->isEmployer() || auth()->user()->isAdmin())
                            <a href="{{ route('employer.dashboard') }}" class="block px-4 py-2.5 rounded-lg text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition duration-300">
                                <i class="fas fa-chart-line mr-2"></i> Dashboard
                            </a>
                        @endif
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2.5 rounded-lg text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition duration-300">
                                <i class="fas fa-cog mr-2"></i> Admin
                            </a>
                        @endif
                        <a href="{{ route('profile.show') }}" class="block px-4 py-2.5 rounded-lg text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition duration-300">
                            <i class="fas fa-user mr-2"></i> Profile
                        </a>
                        <a href="{{ route('applications.index') }}" class="block px-4 py-2.5 rounded-lg text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition duration-300">
                            <i class="fas fa-file-alt mr-2"></i> My Applications
                        </a>
                        <a href="{{ route('logout') }}"
                           onclick="event.preventDefault(); document.getElementById('logout-form-mobile').submit();"
                           class="block px-4 py-2.5 rounded-lg text-red-600 hover:bg-red-50 transition duration-300">
                            <i class="fas fa-sign-out-alt mr-2"></i> Logout
                        </a>
                        <form id="logout-form-mobile" action="{{ route('logout') }}" method="POST" class="hidden">
                            @csrf
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="block px-4 py-2.5 rounded-lg text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition duration-300">
                            <i class="fas fa-sign-in-alt mr-2"></i> Login
                        </a>
                        <a href="{{ route('register') }}" class="block px-4 py-2.5 rounded-lg bg-gradient-to-r from-blue-600 to-blue-800 text-white text-center transition duration-300">
                            <i class="fas fa-user-plus mr-1"></i> Register
                        </a>
                    @endauth
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main>
            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="bg-white border-t border-gray-200 mt-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                    <div>
                        <div class="flex items-center space-x-2 mb-4">
                            <div class="w-8 h-8 bg-gradient-to-r from-blue-600 to-blue-800 rounded-lg flex items-center justify-center">
                                <i class="fas fa-briefcase text-white"></i>
                            </div>
                            <span class="text-xl font-bold bg-gradient-to-r from-blue-600 to-blue-800 bg-clip-text text-transparent">
                                JobPortal
                            </span>
                        </div>
                        <p class="text-gray-600 text-sm">Find your dream job and build your career with us.</p>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-800 mb-4">Quick Links</h3>
                        <ul class="space-y-2">
                            <li><a href="{{ route('jobs.index') }}" class="text-gray-600 hover:text-blue-600 text-sm">Browse Jobs</a></li>
                            <li><a href="#" class="text-gray-600 hover:text-blue-600 text-sm">Companies</a></li>
                            <li><a href="#" class="text-gray-600 hover:text-blue-600 text-sm">Blog</a></li>
                        </ul>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-800 mb-4">For Employers</h3>
                        <ul class="space-y-2">
                            <li><a href="{{ route('employer.dashboard') }}" class="text-gray-600 hover:text-blue-600 text-sm">Post a Job</a></li>
                            <li><a href="#" class="text-gray-600 hover:text-blue-600 text-sm">Pricing</a></li>
                            <li><a href="#" class="text-gray-600 hover:text-blue-600 text-sm">Resources</a></li>
                        </ul>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-800 mb-4">Contact</h3>
                        <ul class="space-y-2">
                            <li><a href="#" class="text-gray-600 hover:text-blue-600 text-sm">Support</a></li>
                            <li><a href="#" class="text-gray-600 hover:text-blue-600 text-sm">Privacy Policy</a></li>
                            <li><a href="#" class="text-gray-600 hover:text-blue-600 text-sm">Terms of Service</a></li>
                        </ul>
                    </div>
                </div>
                <div class="border-t border-gray-200 mt-8 pt-8 text-center text-gray-600 text-sm">
                    &copy; {{ date('Y') }} JobPortal. All rights reserved.
                </div>
            </div>
        </footer>
    </div>

    @stack('scripts')
    @if (!file_exists(public_path('build/manifest.json')))
        <script src="https://cdn.jsdelivr.net/npm/alpinejs@2.8.2/dist/alpine.min.js"></script>
    @endif
</body>
</html>