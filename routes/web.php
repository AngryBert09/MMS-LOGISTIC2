<?php


namespace App\Http\Controllers;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\DeliveryController;
use App\Http\Controllers\HereMapController;
use App\Http\Controllers\LalamoveController;
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




Route::get('', [DashboardController::class, 'index'])->name('dashboard')->middleware('auth:vendor');

Route::resource('purchase-orders', PurchaseOrderController::class)->middleware('auth:vendor');
Route::resource('invoices', InvoiceController::class)->middleware('auth:vendor');
Route::resource('profiles', ProfileController::class)->middleware('auth:vendor');
Route::resource('receipts', PurchaseReceiptController::class)->middleware('auth:vendor');
Route::resource('returns', ReturnsController::class)->middleware('auth:vendor');
Route::resource('biddings', BiddingController::class)->middleware('auth:vendor');
Route::get('/verify-email/{vendor}/{token}', [ProfileController::class, 'verifyEmail'])->name('verify.email');
Route::post('/profiles/{vendor}/verify-email', [ProfileController::class, 'resendVerificationEmail'])->name('profiles.verifyEmail');







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
Route::get('/vendors-with-unread', [MessageController::class, 'getVendorsWithUnreadCount'])->name('vendors.unread')->middleware('auth:vendor');
Route::post('/mark-as-read/{vendor}', [MessageController::class, 'markAsRead'])->middleware('auth:vendor')->name('markAsRead');



Route::get('/forgot-password', [ForgotPasswordController::class, 'showForgotPasswordForm'])->name('forgot.password.form');
Route::post('/send-forgot-password', [ForgotPasswordController::class, 'sendResetLink'])->name('forgot.password.send');
Route::get('/reset-password/{token}', [ForgotPasswordController::class, 'showResetPasswordForm'])->name('password.reset');
Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword'])->name('reset.password.update');


// Add this to your web.php or routes file
Route::get('/shipment-details/{orderId}', [ShipmentController::class, 'getShipmentDetails']);


// TESTING PURPOSES


// Render the Blade view
// Route::get('/delivery', [DeliveryController::class, 'showDeliveryPage']);

// AJAX endpoints
// Route::get('/api/current-delivery-time', [DeliveryController::class, 'getCurrentDeliveryTime']);
// Route::post('/api/adjust-delivery-time', [DeliveryController::class, 'adjustDeliveryTime']);




// Route::get('/lalamove/create-order', [LalamoveController::class, 'createOrderForm'])->name('lalamove.create.order');
// Route::post('/lalamove/create-order', [LalamoveController::class, 'createOrder'])->name('lalamove.create.order.submit');
// Route::get('/lalamove/order-status/{orderId}', [LalamoveController::class, 'getOrderStatus'])->name('lalamove.order.status');




Route::get('/here-map', [HereMapController::class, 'showMap'])->name('here.map');
