<?php


namespace App\Http\Controllers;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\ReturnsController;


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



Route::get('/dashboard', [DashboardController::class, 'index'])
    ->name('dashboard')
    ->middleware('auth:vendor', '2fa');


Route::resource('purchase-orders', PurchaseOrderController::class)->middleware('auth:vendor');
Route::resource('invoices', InvoiceController::class)->middleware('auth:vendor');
Route::resource('profiles', ProfileController::class)->middleware('auth:vendor');
Route::resource('receipts', PurchaseReceiptController::class)->middleware('auth:vendor');
Route::resource('returns', ReturnsController::class)->middleware('auth:vendor');
Route::resource('biddings', BiddingController::class)->middleware('auth:vendor');
Route::get('/verify-email/{vendor}/{token}', [ProfileController::class, 'verifyEmail'])->name('verify.email');
Route::post('/profiles/{vendor}/verify-email', [ProfileController::class, 'resendVerificationEmail'])->name('profiles.verifyEmail');



Route::get('/Faqs', function () {
    return view('vendors.faqs');
})->name('faqs')->middleware('auth:vendor');

Route::get('paypal/checkout', [PayPalController::class, 'checkout'])->name('paypal.checkout');
Route::get('paypal/confirm', [PayPalController::class, 'confirm'])->name('paypal.confirm');
Route::get('paypal/cancel', function () {
    return redirect()->route('paypal.checkout')->with('error', 'Payment was cancelled.');
})->name('paypal.cancel');

Route::post('/send-message', [MessageController::class, 'store'])->name('messages.send')->middleware('auth:vendor');
Route::get('/chat', [MessageController::class, 'showChat'])->name('chat')->middleware('auth:vendor');
Route::get('/messages/{vendorId}', [MessageController::class, 'getMessages'])->name('messages.get')->middleware('auth:vendor');
Route::get('/vendors-with-unread', [MessageController::class, 'getVendorsWithUnreadCount'])->name('vendors.unread')->middleware('auth:vendor');
Route::post('/mark-as-read/{vendor}', [MessageController::class, 'markAsRead'])->middleware('auth:vendor')->name('markAsRead');



Route::get('/forgot-password', [ForgotPasswordController::class, 'showForgotPasswordForm'])->name('forgot.password.form');
Route::post('/send-forgot-password', [ForgotPasswordController::class, 'sendResetLink'])->name('forgot.password.send');
Route::get('/reset-password/{token}', [ForgotPasswordController::class, 'showResetPasswordForm'])->name('password.reset');
Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword'])->name('reset.password.update');


// Add this to your web.php or routes file
Route::get('/shipment-details/{orderId}', [ShipmentController::class, 'getShipmentDetails']);

Route::get('/getMyPerformance', [DashboardController::class, 'getMyPerformance'])->middleware('auth:vendor');
Route::get('/getTopSuppliers', [DashboardController::class, 'getTopSuppliers'])->middleware('auth:vendor');

Route::get('/analyze-suppliers', [SupplierController::class, 'analyzeSuppliers'])
    ->middleware('auth:vendor')
    ->name('analyze.suppliers');


Route::get('/order-tracking', function () {
    return view('DeliveryTracking.order-status');
});

Route::get('/api/supplier-analysis', [SupplierController::class, 'analyzeSuppliers'])->name('supplier.analysis')->middleware('auth:vendor');


Route::get('/developers.api-documentation', function () {
    return view('api.api-documentation');
});
