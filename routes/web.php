<?php


namespace App\Http\Controllers;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\MessageController;


/*


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

Route::resource('receipts', PurchaseReceiptController::class)->middleware('auth:vendor');



Route::get('/Biddings', function () {
    return view('vendors.Biddings.index');
})->name('bidding')->middleware('auth:vendor');


Route::get('/terms', function () {
    return view('terms');
})->name('terms');

Route::get('paypal/checkout', [PayPalController::class, 'checkout'])->name('paypal.checkout');
Route::get('paypal/confirm', [PayPalController::class, 'confirm'])->name('paypal.confirm');
Route::get('paypal/cancel', function () {
    return redirect()->route('paypal.checkout')->with('error', 'Payment was cancelled.');
})->name('paypal.cancel');

Route::post('/send-message', [MessageController::class, 'store'])->name('messages.send')->middleware('auth:vendor');
Route::get('/chat', [MessageController::class, 'showChat'])->name('chat')->middleware('auth:vendor');
Route::get('/messages/{vendorId}', [MessageController::class, 'getMessages'])->name('messages.get')->middleware('auth:vendor');
