<?php


use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\TwoFactorController;
use App\Http\Controllers\APIs\ApiUserController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\auth\AdminAuthController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Employee\Auth\EmployeeAuthController;

Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {
    Route::group(['middleware' => 'guest'], function () {
        Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [AdminAuthController::class, 'login']);
    });

    Route::post('/logout', [AdminAuthController::class, 'logout'])->middleware('admin')->name('logout');

    Route::group(['middleware' => 'admin'], function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    });
});






Route::group(['middleware' => ['guest:vendor', 'guest']], function () {
    Route::get('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/register', [AuthController::class, 'store']);

    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'authenticate']);

    Route::get('/', function () {
        return redirect()->route('login');
    });
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



Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:vendor')->name('logout');
