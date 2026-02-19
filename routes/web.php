<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{AuthController, AdminController, HrController, HomeController};

Route::get('/', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected Routes
Route::middleware(['auth', 'approved'])->group(function() {
    
    // ==========================================
    // Home & Vehicle Owner Routes
    // ==========================================
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    
    // Route for Vehicle Owners to submit their details to an HR's advertisement
    Route::post('/ads/{advertisement}/submit', [HomeController::class, 'submitDetails'])->name('owner.submit_details');

    // ==========================================
    // Admin Routes
    // ==========================================
    Route::middleware('role:admin')->prefix('admin')->group(function() {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        
        // User & Registration Management
        Route::post('/users/{user}/{action}', [AdminController::class, 'handleUser'])->name('admin.user.action');
        
        // Sales Category Package Management
        Route::post('/packages/create', [AdminController::class, 'createPackage'])->name('admin.package.create');
        Route::put('/packages/{package}', [AdminController::class, 'updatePackage'])->name('admin.package.update'); // Edit Package
        Route::delete('/packages/{package}', [AdminController::class, 'deletePackage'])->name('admin.package.delete'); // Delete Package
        
        // Advertisement Approval/Rejection
        Route::post('/ads/{ad}/{action}', [AdminController::class, 'handleAd'])->name('admin.ad.action');
    });

    // ==========================================
    // Sales Company HR Routes
    // ==========================================
    Route::middleware('role:hr')->prefix('hr')->group(function() {
        Route::get('/dashboard', [HrController::class, 'dashboard'])->name('hr.dashboard');
        
        // Purchasing & Advertising
        Route::post('/buy/{package}', [HrController::class, 'buyPackage'])->name('hr.buy');
        Route::post('/ad/place/{hrPackage}', [HrController::class, 'placeAd'])->name('hr.place_ad');
        
        // Notifications & Viewing Owner Details
        Route::get('/notifications', [HrController::class, 'notifications'])->name('hr.notifications');
        Route::get('/ad/{advertisement}/submissions', [HrController::class, 'viewSubmissions'])->name('hr.ad.submissions');
    });
});