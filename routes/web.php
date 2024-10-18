<?php


namespace App\Http\Controllers;

use App\Http\Controllers\Admin\CommentController as AdminCommentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FeedController;
use App\Http\Controllers\FollowerController;
use App\Http\Controllers\IdeaController;
use App\Http\Controllers\IdeaLikeController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\IdeaController as AdminIdeaController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PurchaseOrderController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Language route


Route::get('lang/{lang}', function ($lang) {

    app()->setLocale($lang);
    session()->put('locale', $lang);

    return redirect()->route('dashboard');
})->name('lang');

Route::get('', [DashboardController::class, 'index'])->name('dashboard')->middleware('auth:vendor');

Route::resource('purchase-orders', PurchaseOrderController::class)->middleware('auth:vendor');
Route::resource('invoices', InvoiceController::class)->middleware('auth:vendor');
Route::resource('profiles', ProfileController::class)->middleware('auth:vendor');

Route::get('/invoices/preview', [InvoiceController::class, 'preview'])->name('invoices.preview');



Route::get('/customer-rn', function () {
    return view('vendors.purchase-receipt');
})->name('customer_RN');

Route::get('/thepreview', function () {
    return view('vendors.invoices.preview-invoice');
})->name('customer_RN')->middleware('auth:vendor');



Route::get('/terms', function () {
    return view('terms');
})->name('terms');
