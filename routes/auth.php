<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\TwoFactorController;
use App\Http\Controllers\DashboardController;

Route::group(['middleware' => 'guest:vendor'], function () {
    Route::get('/register', [AuthController::class, 'register'])->name('register');

    Route::post('/register', [AuthController::class, 'store']);

    Route::get('/login', [AuthController::class, 'login'])->name('login');

    Route::get('/', function () {
        return redirect()->route('login');
    });


    Route::post('/login', [AuthController::class, 'authenticate']);
});




Route::group(['middleware' => ['auth:vendor', '2fa']], function () {

    Route::get('2fa/verify', [TwoFactorController::class, 'showVerifyForm'])->name('2fa.verify');
    Route::post('2fa/verify', [TwoFactorController::class, 'verify']);
    Route::post('2fa/resend', [TwoFactorController::class, 'resendOtp'])->name('2fa.resend');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    // Add other authenticated routes here
});


Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:vendor')->name('logout');
