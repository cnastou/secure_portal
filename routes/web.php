<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Middleware\IsAdmin;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', function () {
    return view('login');
});

Route::get('/register', function () {
    return view('register');
});

// Allow both admin and non-admin users to access the dashboard.
// Admin-only functionality is handled inside the view/API and protected on server-side where necessary.
Route::get('/dashboard', function () {
    return view('dashboard');
});

// Email verification route
Route::get('/verify-email/{id}', [AuthController::class, 'verifyEmail'])->name('verify.email')->middleware('signed');