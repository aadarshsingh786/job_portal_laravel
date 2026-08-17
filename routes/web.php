<?php

use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminMessageController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployerDashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\JobApplicationController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/jobs', [JobController::class, 'index'])->name('jobs.index');
Route::get('/jobs/{job}', [JobController::class, 'show'])->name('jobs.show');

// Authentication Routes
Auth::routes(['verify' => true]);

// Admin Authentication (separate session)
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login.submit');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');
});

// Admin Routes (own session, must stay OUTSIDE the user auth group)
Route::middleware(['admin.auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/users', [AdminDashboardController::class, 'users'])->name('users');
    Route::get('/users/{user}', [AdminDashboardController::class, 'userShow'])->name('users.show');
    Route::put('/users/{user}/role', [AdminDashboardController::class, 'updateRole'])->name('users.role');
    Route::delete('/users/{user}', [AdminDashboardController::class, 'deleteUser'])->name('users.delete');
    
    Route::get('/jobs', [AdminDashboardController::class, 'allJobs'])->name('jobs');
    Route::get('/jobs/create', [AdminDashboardController::class, 'createJob'])->name('jobs.create');
    Route::post('/jobs', [AdminDashboardController::class, 'storeJob'])->name('jobs.store');
    Route::get('/jobs/{job}/edit', [AdminDashboardController::class, 'editJob'])->name('jobs.edit');
    Route::put('/jobs/{job}', [AdminDashboardController::class, 'updateJob'])->name('jobs.update');
    Route::put('/jobs/{job}/feature', [AdminDashboardController::class, 'featureJob'])->name('jobs.feature');
    Route::put('/jobs/{job}/toggle-status', [AdminDashboardController::class, 'toggleJobStatus'])->name('jobs.toggle-status');

    Route::get('/applications', [AdminDashboardController::class, 'applications'])->name('applications');
    Route::put('/applications/{application}/status', [AdminDashboardController::class, 'updateApplicationStatus'])->name('applications.update-status');
    
    Route::get('/analytics', [AdminDashboardController::class, 'analytics'])->name('analytics');
    Route::get('/reports', [AdminDashboardController::class, 'reports'])->name('reports');
    Route::get('/settings', [AdminDashboardController::class, 'settings'])->name('settings');
    Route::put('/settings', [AdminDashboardController::class, 'updateSettings'])->name('settings.update');

    Route::get('/messages', [AdminMessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/create', [AdminMessageController::class, 'create'])->name('messages.create');
    Route::post('/messages', [AdminMessageController::class, 'store'])->name('messages.store');
    Route::get('/messages/{message}', [AdminMessageController::class, 'show'])->name('messages.show');
});

// Authenticated Routes
Route::middleware(['auth', 'verified'])->group(function () {
    
    // ============ DASHBOARD ROUTES ============
    Route::prefix('dashboard')->name('dashboard.')->group(function () {
        // Main Dashboard
        Route::get('/', [DashboardController::class, 'index'])->name('index');
        
        // Job Management
        Route::get('/jobs', [DashboardController::class, 'jobs'])->name('jobs');
        Route::get('/jobs/create', [DashboardController::class, 'createJob'])->name('jobs.create');
        Route::post('/jobs', [DashboardController::class, 'storeJob'])->name('jobs.store');
        Route::get('/jobs/{id}/edit', [DashboardController::class, 'editJob'])->name('jobs.edit');
        Route::put('/jobs/{id}', [DashboardController::class, 'updateJob'])->name('jobs.update');
        Route::delete('/jobs/{id}', [DashboardController::class, 'deleteJob'])->name('jobs.delete');
        Route::post('/jobs/{id}/toggle-status', [DashboardController::class, 'toggleJobStatus'])->name('jobs.toggle-status');
        Route::post('/jobs/{id}/toggle-feature', [DashboardController::class, 'toggleJobFeature'])->name('jobs.toggle-feature');
        
        // Applications Management
        Route::get('/applications', [DashboardController::class, 'applications'])->name('applications');
        Route::get('/applications/{id}', [DashboardController::class, 'viewApplication'])->name('applications.view');
        Route::put('/applications/{id}/status', [DashboardController::class, 'updateApplicationStatus'])->name('applications.update-status');
        
        // Users Management (Admin only)
        Route::get('/users', [DashboardController::class, 'users'])->name('users')->middleware('check.role:admin');
    });

    // Profile Routes
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'show'])->name('show');
        Route::get('/edit', [ProfileController::class, 'edit'])->name('edit');
        Route::put('/', [ProfileController::class, 'update'])->name('update');
        Route::post('/upload-avatar', [ProfileController::class, 'uploadAvatar'])->name('upload-avatar');
    });

    // Job Applications
    Route::post('/jobs/{job}/apply', [JobApplicationController::class, 'apply'])->name('jobs.apply');
    Route::get('/my-applications', [JobApplicationController::class, 'myApplications'])->name('applications.index');
    Route::get('/applications/{application}', [JobApplicationController::class, 'show'])->name('applications.show');
    Route::put('/applications/{application}/status', [JobApplicationController::class, 'updateStatus'])->name('applications.status');

    // Save/Unsave Jobs
    Route::post('/jobs/{job}/save', [JobController::class, 'saveJob'])->name('jobs.save');
    Route::delete('/jobs/{job}/unsave', [JobController::class, 'unsaveJob'])->name('jobs.unsave');

    // Messaging System
    Route::prefix('messages')->name('messages.')->group(function () {
        Route::get('/', [MessageController::class, 'index'])->name('index');
        Route::get('/create', [MessageController::class, 'create'])->name('create');
        Route::post('/', [MessageController::class, 'store'])->name('store');
        Route::get('/{message}', [MessageController::class, 'show'])->name('show');
        Route::put('/{message}/read', [MessageController::class, 'markAsRead'])->name('read');
    });

    // Notifications
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('index');
        Route::put('/{notification}/read', [NotificationController::class, 'markAsRead'])->name('read');
        Route::get('/{notification}/open', [NotificationController::class, 'open'])->name('open');
        Route::put('/read-all', [NotificationController::class, 'markAllAsRead'])->name('read-all');
    });

    // Employer Routes
    Route::middleware(['check.role:employer,admin'])->prefix('employer')->name('employer.')->group(function () {
        Route::get('/', fn() => redirect()->route('employer.dashboard'))->name('home');
        Route::get('/dashboard', [EmployerDashboardController::class, 'index'])->name('dashboard');
        Route::get('/jobs', [EmployerDashboardController::class, 'jobs'])->name('jobs');
        Route::get('/applications', [EmployerDashboardController::class, 'applications'])->name('applications');
        Route::put('/applications/{application}/status', [EmployerDashboardController::class, 'updateApplicationStatus'])->name('applications.update-status');
        Route::get('/analytics', [EmployerDashboardController::class, 'analytics'])->name('analytics');
        
        // Job CRUD
        Route::get('/jobs/create', [JobController::class, 'create'])->name('jobs.create');
        Route::post('/jobs', [JobController::class, 'store'])->name('jobs.store');
        Route::get('/jobs/{job}/edit', [JobController::class, 'edit'])->name('jobs.edit');
        Route::put('/jobs/{job}', [JobController::class, 'update'])->name('jobs.update');
        Route::delete('/jobs/{job}', [JobController::class, 'destroy'])->name('jobs.destroy');
    });
});

// Additional route for role middleware registration
Route::middleware('auth')->group(function () {
    // Add any additional authenticated routes here
});