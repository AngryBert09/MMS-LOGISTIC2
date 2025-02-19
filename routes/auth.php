<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\TwoFactorController;

Route::group(['middleware' => 'guest:vendor'], function () {
    Route::get('/register', [AuthController::class, 'register'])->name('register');

    Route::post('/register', [AuthController::class, 'store']);

    Route::get('/login', [AuthController::class, 'login'])->name('login');

    Route::post('/login', [AuthController::class, 'authenticate']);
});

Route::middleware(['auth:vendor'])->group(function () {
    Route::get('2fa/verify', [TwoFactorController::class, 'showVerifyForm'])->name('2fa.verify');
    Route::post('2fa/verify', [TwoFactorController::class, 'verify']);
    Route::post('2fa/resend', [TwoFactorController::class, 'resendOtp'])->name('2fa.resend');
});


Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:vendor')->name('logout');
