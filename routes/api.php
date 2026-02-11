<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

// ------------------------
// PUBLIC ROUTES
// ------------------------
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/resend-verification-email', [AuthController::class, 'resendVerificationEmail']);
Route::post('/admin/create', [AuthController::class, 'createAdmin']); // For development/setup only

// ------------------------
// PROTECTED ROUTES
// ------------------------
Route::middleware('auth:api')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
});
