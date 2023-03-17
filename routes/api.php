<?php

use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::apiResource('products', ProductController::class);

Route::post('/login', [LoginController::class, 'login']);

Route::post('/register', [RegisterController::class, 'create']);

Route::get("/verify/{id}", [EmailVerificationController::class, 'verify'])->name('verification.verify');

Route::post('/forgot-password', [ResetPasswordController::class, 'forgot'])->name('password.email');

Route::post("/reset-password/{token}", [ResetPasswordController::class, 'reset'])->name('password.reset');

// Route::get('/admin', [ProductController::class, 'admin'])->middleware('auth:sanctum');


//Route to get Google Search Console data
Route::get('/search-console', [AnalyticsController::class, 'searchConsole']);//->middleware(['auth:sanctum', 'verified']);

//Route to get Google Analytics data
Route::get('/analytics', [AnalyticsController::class, 'googleAnalytics']);//->middleware(['auth:sanctum', 'verified']);

//Route to get Google Ads data
Route::get('/google-ads', [AnalyticsController::class, 'googleAds']);//->middleware(['auth:sanctum', 'verified']);
