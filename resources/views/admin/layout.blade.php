<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Dashboard') - JobPortal Admin</title>

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
<body class="font-sans antialiased bg-gray-100">

<div class="min-h-screen lg:flex" x-data="{ sidebarOpen: false }">
    <!-- Mobile overlay -->
    <div x-show="sidebarOpen" @click="sidebarOpen = false" x-cloak
         class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm z-40 lg:hidden"></div>

    <!-- Sidebar -->
    <aside x-bind:class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
           class="fixed inset-y-0 left-0 z-50 w-64 bg-gradient-to-b from-slate-900 to-slate-800 shadow-xl transition-transform duration-200 lg:translate-x-0 lg:static lg:w-64 shrink-0 flex flex-col overflow-hidden">
        <!-- Brand -->
        <div class="flex items-center justify-between gap-3 px-5 h-16 border-b border-white/10">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-500 flex items-center justify-center text-white font-bold shadow-lg">
                    <i class="fas fa-shield-halved"></i>
                </div>
                <div>
                    <h2 class="font-bold text-white leading-tight">JobPortal</h2>
                    <p class="text-xs text-blue-200/70">Admin Panel</p>
                </div>
            </div>
            <button @click="sidebarOpen = false" class="lg:hidden text-white/70 hover:text-white p-1.5 rounded-lg hover:bg-white/10">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 overflow-y-auto p-4 space-y-1.5">
            @php
                $adminMenu = [
                    ['route' => 'admin.dashboard', 'icon' => 'fa-gauge-high', 'label' => 'Dashboard'],
                    ['route' => 'admin.applications', 'icon' => 'fa-file-invoice', 'label' => 'Applications'],
                    ['route' => 'admin.messages.index', 'icon' => 'fa-envelope', 'label' => 'Messages'],
                    ['route' => 'admin.jobs', 'icon' => 'fa-briefcase', 'label' => 'Jobs'],
                    ['route' => 'admin.users', 'icon' => 'fa-users', 'label' => 'Users'],
                    ['route' => 'admin.analytics', 'icon' => 'fa-chart-line', 'label' => 'Analytics'],
                    ['route' => 'admin.reports', 'icon' => 'fa-file-lines', 'label' => 'Reports'],
                    ['route' => 'admin.settings', 'icon' => 'fa-gear', 'label' => 'Settings'],
                ];
                $pendingCount = \App\Models\JobApplication::where('status','pending')->count();
            @endphp
            @foreach($adminMenu as $item)
                <a href="{{ route($item['route']) }}"
                   class="flex items-center gap-3 px-4 py-2.5 rounded-xl transition duration-200 text-sm font-medium {{ request()->routeIs($item['route']) ? 'bg-gradient-to-r from-blue-600 to-indigo-600 text-white shadow-lg shadow-blue-900/40' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
                    <i class="fas {{ $item['icon'] }} w-5 text-center"></i>
                    {{ $item['label'] }}
                    @if($item['route'] == 'admin.applications' && $pendingCount > 0)
                        <span class="ml-auto bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center animate-pulse">
                            {{ $pendingCount }}
                        </span>
                    @endif
                </a>
            @endforeach

            <div class="pt-4 mt-4 border-t border-white/10 space-y-1.5">
                <a href="{{ route('home') }}" target="_blank" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium text-slate-300 hover:bg-white/10 hover:text-white transition duration-200">
                    <i class="fas fa-globe w-5 text-center"></i> View Website
                </a>
                <a href="{{ route('admin.logout') }}" onclick="event.preventDefault(); document.getElementById('admin-logout').submit();"
                   class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium text-red-400 hover:bg-red-500/10 transition duration-200">
                    <i class="fas fa-sign-out-alt w-5 text-center"></i> Logout
                </a>
                <form id="admin-logout" action="{{ route('admin.logout') }}" method="POST" class="hidden">
                    @csrf
                </form>
            </div>
        </nav>

        <!-- Admin user card -->
        <div class="p-4 border-t border-white/10">
            <div class="flex items-center gap-3 bg-white/5 rounded-xl p-3">
                @if(auth()->user()->profile_image)
                    <img src="{{ asset('storage/' . auth()->user()->profile_image) }}" alt="Admin"
                         class="w-10 h-10 rounded-full object-cover border-2 border-blue-500">
                @else
                    <div class="w-10 h-10 rounded-full bg-gradient-to-r from-blue-600 to-indigo-600 flex items-center justify-center text-white font-semibold text-sm">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                @endif
                <div class="leading-tight min-w-0">
                    <p class="text-sm font-semibold text-white truncate">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-slate-400 truncate">{{ auth()->user()->email }}</p>
                </div>
            </div>
        </div>
    </aside>

    <!-- Main column -->
    <div class="flex-1 flex flex-col min-w-0">
        <!-- Top bar -->
        <header class="sticky top-0 z-30 bg-white/95 backdrop-blur border-b border-gray-200 h-16 flex items-center px-4 sm:px-6 gap-4">
            <button @click="sidebarOpen = true" class="lg:hidden p-2 rounded-lg hover:bg-gray-100 text-gray-700">
                <i class="fas fa-bars text-lg"></i>
            </button>
            <div class="hidden lg:flex items-center gap-2 text-sm text-gray-500">
                <i class="fas fa-shield-halved text-blue-600"></i>
                <i class="fas fa-chevron-right text-xs text-gray-300"></i>
                <span class="text-gray-700 font-medium">@yield('title', 'Dashboard')</span>
            </div>
            <div class="ml-auto flex items-center gap-3">
                <a href="{{ route('home') }}" target="_blank" class="hidden sm:flex items-center gap-2 text-sm text-gray-600 hover:text-blue-600 transition px-3 py-2 rounded-lg hover:bg-gray-100">
                    <i class="fas fa-globe"></i> View Site
                </a>
                <div class="flex items-center gap-2.5 pl-3 border-l border-gray-200">
                    @if(auth()->user()->profile_image)
                        <img src="{{ asset('storage/' . auth()->user()->profile_image) }}" alt="Admin"
                             class="w-9 h-9 rounded-full object-cover border-2 border-blue-600">
                    @else
                        <div class="w-9 h-9 rounded-full bg-gradient-to-r from-blue-600 to-indigo-700 flex items-center justify-center text-white font-semibold text-sm">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                    @endif
                    <div class="hidden md:block leading-tight">
                        <p class="text-sm font-semibold text-gray-800">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-gray-500">Administrator</p>
                    </div>
                </div>
            </div>
        </header>

        <!-- Content -->
        <main class="flex-1 p-4 sm:p-6 lg:p-8">
            @yield('admin-content')
        </main>

        <footer class="px-4 sm:px-6 lg:px-8 py-4 text-center text-xs text-gray-400 border-t border-gray-100">
            &copy; {{ date('Y') }} JobPortal Admin Panel. All rights reserved.
        </footer>
    </div>
</div>

@stack('scripts')
@if (!file_exists(public_path('build/manifest.json')))
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@2.8.2/dist/alpine.min.js"></script>
@endif
</body>
</html>