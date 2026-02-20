<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{AuthController, AdminController, HrController, HomeController, ProfileController};

Route::get('/', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected Routes
Route::middleware(['auth', 'approved'])->group(function() {
    
    // Profile Management (For All Users)
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Home & Vehicle Owner Routes
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::post('/ads/{advertisement}/submit', [HomeController::class, 'submitDetails'])->name('owner.submit_details');

    // Admin Routes
    Route::middleware('role:admin')->prefix('admin')->group(function() {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::post('/users/{user}/{action}', [AdminController::class, 'handleUser'])->name('admin.user.action');
        
        Route::post('/packages/create', [AdminController::class, 'createPackage'])->name('admin.package.create');
        Route::put('/packages/{package}', [AdminController::class, 'updatePackage'])->name('admin.package.update');
        Route::delete('/packages/{package}', [AdminController::class, 'deletePackage'])->name('admin.package.delete');
        
        Route::post('/ads/{ad}/{action}', [AdminController::class, 'handleAd'])->name('admin.ad.action');
    });

    // Sales Company HR Routes
    Route::middleware('role:hr')->prefix('hr')->group(function() {
        Route::get('/dashboard', [HrController::class, 'dashboard'])->name('hr.dashboard');
        Route::post('/buy/{package}', [HrController::class, 'buyPackage'])->name('hr.buy');
        Route::post('/ad/place/{hrPackage}', [HrController::class, 'placeAd'])->name('hr.place_ad');
        
        Route::get('/notifications', [HrController::class, 'notifications'])->name('hr.notifications');
        Route::get('/ad/{advertisement}/submissions', [HrController::class, 'viewSubmissions'])->name('hr.ad.submissions');
    });
});