<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\NewsController;
use App\Http\Controllers\Api\FetchNewsController;

// Public Routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/news', [NewsController::class, 'index']);

// Authenticated (Protected) Routes
Route::middleware('auth:sanctum')->group(function () {
    // News Fetching (requires authentication)
    Route::post('/fetch-news', [FetchNewsController::class, 'fetch']);

    // User Session & Profile
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/user/update', [AuthController::class, 'updateProfile']);

    // User Preferences
    Route::get('/preferences', [AuthController::class, 'getPreferences']);
    Route::post('/preferences', [AuthController::class, 'savePreferences']);
});
