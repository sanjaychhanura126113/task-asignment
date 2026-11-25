<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\MemberController as AdminMemberController;
use App\Http\Controllers\Admin\TaskController as AdminTaskController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Member\DashboardController as MemberDashboardController;
use App\Http\Controllers\Member\TaskController as MemberTaskController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Root redirect
Route::get('/', function () {
    if (Auth::check()) {
        return Auth::user()->isAdmin()
            ? redirect()->route('admin.dashboard')
            : redirect()->route('member.dashboard');
    }
    return redirect()->route('login');
});

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
});

// 1-Click Quick Demo Login (Accessible for testing)
Route::get('/quick-login/{id}', [AuthController::class, 'quickLogin'])->name('quick.login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Admin Routes (Protected by Auth + Admin Role)
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Task CRUD
    Route::resource('tasks', AdminTaskController::class);
    Route::post('tasks/{task}/comments', [AdminTaskController::class, 'addComment'])->name('tasks.comments.store');

    // Team Members CRUD
    Route::resource('members', AdminMemberController::class)->except(['show']);
});

// Member Routes (Protected by Auth)
Route::middleware('auth')->prefix('member')->name('member.')->group(function () {
    Route::get('/dashboard', [MemberDashboardController::class, 'index'])->name('dashboard');
    Route::get('/tasks', [MemberTaskController::class, 'index'])->name('tasks.index');
    Route::get('/tasks/{task}', [MemberTaskController::class, 'show'])->name('tasks.show');
    Route::post('/tasks/{task}/status', [MemberTaskController::class, 'updateStatus'])->name('tasks.status.update');
    Route::post('/tasks/{task}/comments', [MemberTaskController::class, 'addComment'])->name('tasks.comments.store');
});
