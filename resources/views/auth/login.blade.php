@extends('layouts.auth')

@section('title', 'Login - JobPortal')

@section('auth-content')
<div x-data="{{ $errors->any() ? '{ showErrors: true, passwordVisible: false }' : '{ showErrors: false, passwordVisible: false }' }}" class="reveal reveal-visible">

    <div class="text-center lg:text-left mb-8">
        <span class="inline-block bg-blue-100 text-blue-700 text-xs font-bold uppercase tracking-wider px-4 py-1.5 rounded-full mb-4">Welcome back</span>
        <h1 class="text-3xl font-bold text-gray-800 mb-2">Sign in to your account</h1>
        <p class="text-gray-500">Enter your credentials to access your dashboard</p>
    </div>

    @if (session('status'))
        <div class="mb-6 rounded-xl bg-green-50 border border-green-200 p-4 flex items-start gap-3">
            <i class="fas fa-check-circle text-green-500 mt-0.5"></i>
            <div class="text-sm text-green-700">{{ session('status') }}</div>
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-6 rounded-xl bg-red-50 border border-red-200 p-4" x-show="showErrors" x-transition x-cloak>
            <div class="flex items-start gap-3">
                <i class="fas fa-exclamation-circle text-red-500 mt-0.5"></i>
                <div class="text-sm text-red-700">
                    <strong>Unable to sign in.</strong>
                    <ul class="mt-1 list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        {{-- Email --}}
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
            <div class="relative group">
                <i class="fas fa-envelope absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400 group-focus-within:text-blue-600 transition"></i>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus
                       placeholder="you@example.com"
                       class="w-full pl-12 pr-4 py-3.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-gray-800 placeholder-gray-400 transition @error('email') border-red-300 @enderror">
            </div>
            @error('email')
                <p class="mt-1.5 text-sm text-red-600"><i class="fas fa-circle-exclamation mr-1"></i>{{ $message }}</p>
            @enderror
        </div>

        {{-- Password --}}
        <div>
            <div class="flex items-center justify-between mb-2">
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-sm text-blue-600 hover:text-blue-700 font-medium">Forgot password?</a>
                @endif
            </div>
            <div class="relative group">
                <i class="fas fa-lock absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400 group-focus-within:text-blue-600 transition"></i>
                <input id="password" :type="passwordVisible ? 'text' : 'password'" name="password" required autocomplete="current-password"
                       placeholder="••••••••"
                       class="w-full pl-12 pr-12 py-3.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-gray-800 placeholder-gray-400 transition @error('password') border-red-300 @enderror">
                <button type="button" @click="passwordVisible = !passwordVisible"
                        class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-blue-600 transition focus:outline-none">
                    <i class="far" :class="passwordVisible ? 'fa-eye-slash' : 'fa-eye'"></i>
                </button>
            </div>
            @error('password')
                <p class="mt-1.5 text-sm text-red-600"><i class="fas fa-circle-exclamation mr-1"></i>{{ $message }}</p>
            @enderror
        </div>

        {{-- Remember --}}
        <div class="flex items-center justify-between">
            <label class="flex items-center space-x-2.5 cursor-pointer select-none group">
                <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}
                       class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500 focus:ring-offset-0 cursor-pointer">
                <span class="text-sm text-gray-600 group-hover:text-gray-800">Remember me</span>
            </label>
        </div>

        {{-- Submit --}}
        <button type="submit"
                class="w-full bg-gradient-to-r from-blue-600 to-blue-700 text-white py-3.5 rounded-xl font-semibold hover:shadow-lg hover:from-blue-700 hover:to-blue-800 transition duration-300 transform hover:scale-[1.01] flex items-center justify-center gap-2 group">
            <span>Sign In</span>
            <i class="fas fa-arrow-right group-hover:translate-x-1 transition duration-300"></i>
        </button>
    </form>

    {{-- Divider --}}
    <div class="relative my-8">
        <div class="absolute inset-0 flex items-center">
            <div class="w-full border-t border-gray-200"></div>
        </div>
        <div class="relative flex justify-center text-sm">
            <span class="px-4 bg-white text-gray-500">or continue with</span>
        </div>
    </div>

    {{-- Social --}}
    <div class="grid grid-cols-2 gap-4">
        <button type="button" onclick="showSocialToast('Google')"
                class="flex items-center justify-center gap-2 bg-white border border-gray-200 py-3 rounded-xl text-gray-700 hover:bg-gray-50 hover:border-gray-300 transition duration-300 font-medium">
            <i class="fab fa-google text-red-500"></i> Google
        </button>
        <button type="button" onclick="showSocialToast('GitHub')"
                class="flex items-center justify-center gap-2 bg-white border border-gray-200 py-3 rounded-xl text-gray-700 hover:bg-gray-50 hover:border-gray-300 transition duration-300 font-medium">
            <i class="fab fa-github"></i> GitHub
        </button>
    </div>

    {{-- Register link --}}
    <p class="text-center text-gray-600 mt-8">
        Don't have an account?
        <a href="{{ route('register') }}" class="text-blue-600 hover:text-blue-700 font-semibold">Create one now</a>
    </p>
</div>
@endsection

@push('scripts')
<script>
    function showSocialToast(provider) {
        const toast = document.createElement('div');
        toast.className = 'fixed bottom-4 right-4 bg-gray-800 text-white px-6 py-3 rounded-xl shadow-xl z-50 transition duration-500 flex items-center gap-2';
        toast.innerHTML = `<i class="fas fa-info-circle"></i><span>${provider} sign-in is coming soon!</span>`;
        document.body.appendChild(toast);
        setTimeout(() => { toast.style.opacity = '0'; setTimeout(() => toast.remove(), 500); }, 3500);
    }
</script>
@endpush