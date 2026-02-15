<?php

use App\Http\Controllers\API\ActivityLogController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\PotentialCustomerController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicController;
use App\Models\PotentialCustomer;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });

Route::post('/register-lead', [PublicController::class, 'store'])
    ->name('lead.public.store');

Route::middleware(['auth'])->group(function(){

    // Route::get('/dashboard',[PotentialCustomerController::class,'index'])->name('dashboard');
    
    Route::get('/leads', [PotentialCustomerController::class,'index'])
        ->name('lead.index');
    Route::post('/leads',[PotentialCustomerController::class,'store'])
        ->name('lead.store');
    Route::put('/leads/{lead}',[PotentialCustomerController::class,'update'])
        ->name('lead.update');
    Route::delete('/leads/{lead}',[PotentialCustomerController::class,'destroy'])
        ->name('lead.destroy');

    Route::get('/activity-logs',[ActivityLogController::class,'index'])
        ->name('activityLogs');
});

require __DIR__.'/auth.php';
