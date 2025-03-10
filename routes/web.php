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
use App\Http\Controllers\APIs\ApiUserController;



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




Route::middleware(['auth:vendor'])->group(function () {
    Route::resource('purchase-orders', PurchaseOrderController::class);
    Route::resource('invoices', InvoiceController::class);
    Route::resource('profiles', ProfileController::class);
    Route::resource('receipts', PurchaseReceiptController::class);
    Route::resource('returns', ReturnsController::class);
    Route::resource('biddings', BiddingController::class);

    Route::post('/profiles/{vendor}/verify-email', [ProfileController::class, 'resendVerificationEmail'])
        ->name('profiles.verifyEmail');
});

// Keep email verification routes outside authentication middleware
Route::get('/verify-email/{vendor}/{token}', [ProfileController::class, 'verifyEmail'])
    ->name('verify.email');




Route::get('/Faqs', function () {
    return view('vendors.faqs');
})->name('faqs')->middleware('auth:vendor');

Route::middleware(['auth:vendor'])->group(function () {
    Route::get('/paypal/create-order/{invoiceId}', [InvoiceController::class, 'createPayPalOrder'])->name('paypal.createOrder');
    Route::get('/paypal/capture-order/{orderId}', [InvoiceController::class, 'capturePayPalOrder'])->name('paypal.captureOrder');
    Route::get('/paypal/cancel', function () {
        return redirect()->route('invoices.index')->withErrors('Payment was cancelled.');
    })->name('paypal.cancel');
});



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
Route::get('/shipments', [ShipmentController::class, 'index'])->name('shipments.index')->middleware('auth:vendor');
Route::post('/shipments/assign-rider', [ShipmentController::class, 'assignRider'])->name('shipments.assign-rider')->middleware('auth:vendor');


Route::get('/getMyPerformance', [DashboardController::class, 'getMyPerformance'])->middleware('auth:vendor');
Route::get('/getTopSuppliers', [DashboardController::class, 'getTopSuppliers'])->middleware('auth:vendor');

Route::get('/analyze-suppliers', [SupplierController::class, 'analyzeSuppliers'])
    ->middleware('auth:vendor')
    ->name('analyze.suppliers');


Route::get('/api/supplier-analysis', [SupplierController::class, 'analyzeSuppliers'])->name('supplier.analysis')->middleware('auth:vendor');


Route::group(['middleware' => 'auth'], function () {
    Route::get('/developers.api/dashboard', [ApiUserController::class, 'getApiDashboard'])->name('api.dashboard')->middleware('auth');
    Route::post('/generate-token', [ApiUserController::class, 'generateApiToken'])->name('api.generateToken');
});




//FOR DELIVERIES


Route::get('/deliveries', [DeliveriesController::class, 'getDeliveryLogin'])->name('deliveries.show');
Route::post('/login-deliveries', [DeliveriesController::class, 'authenticate'])->name('deliveries.auth.login');
Route::middleware(['delivery'])->group(function () {
    Route::get('/dashboard/deliveries', [DeliveriesController::class, 'index'])->name('dashboard.deliveries');
    Route::get('/my-deliveries', [DeliveriesController::class, 'myDeliveries'])->name('deliveries.mydeliveries');
    Route::put('/deliveries/{id}/update-status', [DeliveriesController::class, 'updateStatus'])->name('deliveries.update');
});

Route::post('/deliveries/logout', [DeliveriesController::class, 'logout'])->name('deliveries.logout');

Route::put('/returns/update/{returnId}', [ReturnsController::class, 'update'])->name('returns.update');

use App\Http\Controllers\RouteController;

// Show the form
Route::get('/route', [RouteController::class, 'showForm'])->name('route.form');

// Handle the form submission
Route::post('/route', [RouteController::class, 'getRoute'])->name('get.route');
