<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\TwoFactorController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\APIs\ApiUserController;

Route::group(['middleware' => 'guest:vendor'], function () {
    Route::get('/register', [AuthController::class, 'register'])->name('register');

    Route::post('/register', [AuthController::class, 'store']);

    Route::get('/login', [AuthController::class, 'login'])->name('login');

    Route::get('/', function () {
        return redirect()->route('login');
    });


    Route::post('/login', [AuthController::class, 'authenticate']);
});


Route::get('/developers.api-documentation', function () {
    return view('api.api-documentation');
})->name('api.docs');

Route::group(['middleware' => 'guest'], function () {


    Route::get('/developers.api/login', [ApiUserController::class, 'getApiLogin'])->name('api.login');
    Route::get('/developers.api/signup', [ApiUserController::class, 'getApiRegister'])->name('api.register');
    Route::post('/developers.api/signup', [ApiUserController::class, 'register'])->name('api.store');

    Route::post('/developers.api/login', [ApiUserController::class, 'login'])->name('api.auth');
});

Route::post('/developers.api/logout', [ApiUserController::class, 'logout'])->name('api.logout');


// Routes accessible without 2FA authentication
Route::middleware(['auth:vendor', 'restrict.2fa'])->group(function () {
    Route::get('2fa/verify', [TwoFactorController::class, 'showVerifyForm'])->name('2fa.verify');
    Route::post('2fa/verify', [TwoFactorController::class, 'verify']);
    Route::post('2fa/resend', [TwoFactorController::class, 'resendOtp'])->name('2fa.resend');
});

// Routes that require 2FA authentication
Route::middleware(['auth:vendor', '2fa'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    // Add other authenticated routes here
});


Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:vendor')->name('logout');
