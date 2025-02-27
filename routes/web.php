<?php

use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\ManageBookController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\ScheduleController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/welcome', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
})->name('welcome');

Route::get('/', [ScheduleController::class, 'index'])->name('schedule');
Route::get('/schedule', [ScheduleController::class, 'index'])->name('schedule.index');

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('books', BookController::class);
});

Route::middleware(['auth', 'role:superadmin,administrator'])->group(function () {
    Route::get('/users', [RegisteredUserController::class, 'index'])->name('users.index');
    Route::get('/users/{user}', [RegisteredUserController::class, 'edit'])->name('users.edit');
    Route::patch('/users/{user}', [RegisteredUserController::class, 'update'])->name('users.update');
    
    Route::resource('rooms', RoomController::class);
    Route::resource('manage-books', ManageBookController::class);
    Route::patch('/manage-books/{book}/approve', [ManageBookController::class, 'approve'])->name('manage-books.approve');
    Route::patch('/manage-books/{book}/reject', [ManageBookController::class, 'reject'])->name('manage-books.reject');
    Route::resource('organizations', OrganizationController::class);
});

require __DIR__.'/auth.php';
