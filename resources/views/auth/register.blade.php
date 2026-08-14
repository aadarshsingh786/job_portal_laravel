@extends('layouts.app')

@section('title', 'Register')

@section('content')
<div class="min-h-screen bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md mx-auto">
        <!-- Logo/Brand -->
        <div class="text-center mb-8">
            <div class="flex justify-center items-center space-x-2 mb-4">
                <div class="w-12 h-12 bg-gradient-to-r from-blue-600 to-blue-800 rounded-xl flex items-center justify-center">
                    <i class="fas fa-briefcase text-white text-2xl"></i>
                </div>
                <span class="text-3xl font-bold bg-gradient-to-r from-blue-600 to-blue-800 bg-clip-text text-transparent">
                    JobPortal
                </span>
            </div>
            <h2 class="text-2xl font-bold text-gray-800">Create Your Account</h2>
            <p class="text-gray-600 mt-1">Start your journey to find your dream job</p>
        </div>

        <!-- Registration Card -->
        <div class="bg-white rounded-2xl shadow-xl p-6 md:p-8">
            <form method="POST" action="{{ route('register') }}">
                @csrf

                <!-- Name -->
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                        <i class="fas fa-user mr-2 text-blue-600"></i>Name
                    </label>
                    <input id="name" type="text" 
                           class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-300 @error('name') border-red-500 @enderror" 
                           name="name" value="{{ old('name') }}" required autocomplete="name" autofocus
                           placeholder="Enter your full name">
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                        <i class="fas fa-envelope mr-2 text-blue-600"></i>Email Address
                    </label>
                    <input id="email" type="email" 
                           class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-300 @error('email') border-red-500 @enderror" 
                           name="email" value="{{ old('email') }}" required autocomplete="email"
                           placeholder="Enter your email address">
                    @error('email')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                        <i class="fas fa-lock mr-2 text-blue-600"></i>Password
                    </label>
                    <div class="relative">
                        <input id="password" type="password" 
                               class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-300 @error('password') border-red-500 @enderror" 
                               name="password" required autocomplete="new-password"
                               placeholder="Enter your password">
                        <button type="button" onclick="togglePassword('password')" 
                                class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600">
                            <i class="fas fa-eye" id="password-icon"></i>
                        </button>
                    </div>
                    @error('password')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-gray-500 mt-1">Password must be at least 8 characters</p>
                </div>

                <!-- Confirm Password -->
                <div class="mb-6">
                    <label for="password-confirm" class="block text-sm font-medium text-gray-700 mb-1">
                        <i class="fas fa-check-circle mr-2 text-blue-600"></i>Confirm Password
                    </label>
                    <div class="relative">
                        <input id="password-confirm" type="password" 
                               class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-300" 
                               name="password_confirmation" required autocomplete="new-password"
                               placeholder="Confirm your password">
                        <button type="button" onclick="togglePassword('password-confirm')" 
                                class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600">
                            <i class="fas fa-eye" id="password-confirm-icon"></i>
                        </button>
                    </div>
                </div>

                <!-- Role Selection -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-user-tag mr-2 text-blue-600"></i>I want to
                    </label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="relative block cursor-pointer">
                            <input type="radio" name="role" value="job_seeker" 
                                   class="peer sr-only" checked>
                            <div class="border-2 border-gray-200 rounded-lg p-3 text-center hover:border-blue-500 peer-checked:border-blue-600 peer-checked:bg-blue-50 transition duration-300">
                                <i class="fas fa-user-tie text-2xl text-gray-400 peer-checked:text-blue-600"></i>
                                <p class="text-sm font-medium text-gray-700 peer-checked:text-blue-600 mt-1">Job Seeker</p>
                            </div>
                        </label>
                        <label class="relative block cursor-pointer">
                            <input type="radio" name="role" value="employer" 
                                   class="peer sr-only">
                            <div class="border-2 border-gray-200 rounded-lg p-3 text-center hover:border-blue-500 peer-checked:border-blue-600 peer-checked:bg-blue-50 transition duration-300">
                                <i class="fas fa-building text-2xl text-gray-400 peer-checked:text-blue-600"></i>
                                <p class="text-sm font-medium text-gray-700 peer-checked:text-blue-600 mt-1">Employer</p>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Terms & Conditions -->
                <div class="mb-6">
                    <div class="flex items-start">
                        <input type="checkbox" id="terms" name="terms" required
                               class="mt-1 w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                        <label for="terms" class="ml-2 text-sm text-gray-600">
                            I agree to the 
                            <a href="#" class="text-blue-600 hover:text-blue-700 underline">Terms of Service</a> 
                            and 
                            <a href="#" class="text-blue-600 hover:text-blue-700 underline">Privacy Policy</a>
                        </label>
                    </div>
                </div>

                <!-- Register Button -->
                <button type="submit" 
                        class="w-full bg-gradient-to-r from-blue-600 to-blue-800 text-white py-3 rounded-lg font-semibold hover:shadow-lg transition duration-300 transform hover:scale-[1.02]">
                    <i class="fas fa-user-plus mr-2"></i> Register
                </button>

                <!-- Login Link -->
                <div class="text-center mt-4">
                    <p class="text-sm text-gray-600">
                        Already have an account? 
                        <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-700 font-semibold">
                            Login here
                        </a>
                    </p>
                </div>
            </form>
        </div>

        <!-- Social Login -->
        <div class="mt-6">
            <div class="relative">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-gray-300"></div>
                </div>
                <div class="relative flex justify-center text-sm">
                    <span class="px-4 bg-gray-50 text-gray-500">Or continue with</span>
                </div>
            </div>
            <div class="mt-4 grid grid-cols-2 gap-3">
                <a href="#" class="flex items-center justify-center px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition duration-300">
                    <i class="fab fa-google text-red-500 text-xl"></i>
                    <span class="ml-2 text-sm text-gray-700">Google</span>
                </a>
                <a href="#" class="flex items-center justify-center px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition duration-300">
                    <i class="fab fa-linkedin text-blue-700 text-xl"></i>
                    <span class="ml-2 text-sm text-gray-700">LinkedIn</span>
                </a>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Toggle password visibility
function togglePassword(fieldId) {
    const input = document.getElementById(fieldId);
    const icon = document.getElementById(fieldId + '-icon');
    
    if (input.type === 'password') {
        input.type = 'text';
        icon.className = 'fas fa-eye-slash';
    } else {
        input.type = 'password';
        icon.className = 'fas fa-eye';
    }
}

// Password strength indicator (optional)
document.getElementById('password')?.addEventListener('input', function() {
    const password = this.value;
    const strengthIndicator = document.getElementById('password-strength');
    
    if (password.length === 0) {
        if (strengthIndicator) strengthIndicator.textContent = '';
        return;
    }
    
    let strength = 'Weak';
    let color = 'red';
    
    if (password.length >= 8) {
        if (/[a-z]/.test(password) && /[A-Z]/.test(password) && /[0-9]/.test(password)) {
            strength = 'Strong';
            color = 'green';
        } else if (/[a-z]/.test(password) && /[0-9]/.test(password)) {
            strength = 'Medium';
            color = 'orange';
        }
    }
    
    if (strengthIndicator) {
        strengthIndicator.textContent = `Password strength: ${strength}`;
        strengthIndicator.className = `text-${color}-500 text-xs mt-1`;
    }
});
</script>
@endpush

@push('styles')
<style>
    /* Custom styles for registration page */
    .register-container {
        min-height: 100vh;
        background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
    }
    
    .register-card {
        backdrop-filter: blur(10px);
        background: rgba(255, 255, 255, 0.95);
    }
    
    /* Radio button custom styling */
    input[type="radio"]:checked + div {
        border-color: #2563eb;
        background-color: #eff6ff;
    }
    
    /* Smooth transitions */
    .transition-all {
        transition: all 0.3s ease;
    }
</style>
@endpush