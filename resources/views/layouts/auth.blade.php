<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'JobPortal')</title>

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

<!-- Top Navbar -->
<nav class="bg-white/95 backdrop-blur border-b border-gray-200 sticky top-0 z-50 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
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
            <div class="hidden md:flex items-center space-x-6">
                <a href="{{ route('jobs.index') }}" class="text-gray-700 hover:text-blue-600 transition duration-300">
                    <i class="fas fa-search mr-1"></i> Browse Jobs
                </a>
                <a href="{{ route('login') }}" class="text-gray-700 hover:text-blue-600 transition duration-300 px-3 py-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('login') ? 'text-blue-600' : '' }}">
                    <i class="fas fa-sign-in-alt mr-1"></i> Login
                </a>
                <a href="{{ route('register') }}" class="bg-gradient-to-r from-blue-600 to-blue-800 text-white px-4 py-2 rounded-lg hover:shadow-lg transition duration-300 {{ request()->routeIs('register') ? 'ring-2 ring-blue-400' : '' }}">
                    <i class="fas fa-user-plus mr-1"></i> Register
                </a>
            </div>
            <a href="{{ route('register') }}" class="md:hidden bg-gradient-to-r from-blue-600 to-blue-800 text-white px-4 py-2 rounded-lg text-sm flex items-center gap-1">
                <i class="fas fa-user-plus"></i> Register
            </a>
        </div>
    </div>
</nav>

<div class="min-h-screen lg:grid lg:grid-cols-2">

    <!-- ============ LEFT BRAND PANEL ============ -->
    <div class="relative hidden lg:flex flex-col justify-between overflow-hidden bg-gradient-to-br from-blue-700 via-blue-600 to-indigo-700 p-12 text-white">
        <!-- Decorative blobs -->
        <div class="absolute -top-24 -left-24 w-96 h-96 bg-white/10 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-purple-500/20 rounded-full blur-3xl"></div>

        <!-- Grid overlay -->
        <div class="absolute inset-0 opacity-[0.07]" style="background-image: url('data:image/svg+xml,%3Csvg width%3D%2260%22 height%3D%2260%22 viewBox%3D%220 0 60 60%22 xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%3E%3Cg fill%3D%22none%22 fill-rule%3D%22evenodd%22%3E%3Cg fill%3D%22%23ffffff%22 fill-opacity%3D%221%22%3E%3Cpath d%3D%22M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z%22%2F%3E%3C%2Fg%3E%3C%2Fg%3E%3C%2Fsvg%3E');"></div>

        <div class="relative">
            <a href="{{ route('home') }}" class="flex items-center space-x-2">
                <div class="w-10 h-10 bg-white/20 backdrop-blur rounded-lg flex items-center justify-center">
                    <i class="fas fa-briefcase text-xl"></i>
                </div>
                <span class="text-2xl font-bold">JobPortal</span>
            </a>
        </div>

        <div class="relative max-w-md">
            <h2 class="text-4xl font-bold leading-tight mb-6">
                Your career journey starts here
            </h2>
            <p class="text-blue-100 text-lg mb-10 leading-relaxed">
                Join thousands of professionals who found their dream job through our platform.
            </p>

            <!-- Floating stat card -->
            <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl p-6 animate-float">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-white text-blue-600 flex items-center justify-center text-xl">
                        <i class="fas fa-check"></i>
                    </div>
                    <div>
                        <div class="font-bold">John just got hired</div>
                        <div class="text-sm text-blue-100">Senior Frontend Developer at Google</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="relative flex items-center gap-6 text-sm text-blue-100">
            <div><span class="text-2xl font-bold text-white">2,400+</span><br>Jobs available</div>
            <div class="w-px h-10 bg-white/20"></div>
            <div><span class="text-2xl font-bold text-white">1,200+</span><br>Companies</div>
            <div class="w-px h-10 bg-white/20"></div>
            <div><span class="text-2xl font-bold text-white">89%</span><br>Success rate</div>
        </div>
    </div>

    <!-- ============ RIGHT FORM PANEL ============ -->
    <div class="flex flex-col justify-center px-4 sm:px-8 py-10">
        <!-- Mobile logo -->
        <a href="{{ route('home') }}" class="lg:hidden flex items-center space-x-2 mb-10 justify-center">
            <div class="w-10 h-10 bg-gradient-to-r from-blue-600 to-blue-800 rounded-lg flex items-center justify-center">
                <i class="fas fa-briefcase text-white text-xl"></i>
            </div>
            <span class="text-2xl font-bold bg-gradient-to-r from-blue-600 to-blue-800 bg-clip-text text-transparent">JobPortal</span>
        </a>

        <div class="w-full max-w-md mx-auto">
            @yield('auth-content')
        </div>
    </div>
</div>

@stack('scripts')
@if (!file_exists(public_path('build/manifest.json')))
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@2.8.2/dist/alpine.min.js"></script>
@endif
</body>
</html>