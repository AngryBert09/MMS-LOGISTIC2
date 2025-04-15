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
use App\Http\Controllers\Admin\AdminVendorController;
use App\Http\Controllers\Admin\AdminBiddingController;
use App\Http\Controllers\Admin\AdminOrderManagement;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminInvoiceController;
use App\Http\Controllers\Employee\EmployeeDashboardController;
use App\Http\Controllers\Employee\ProcurementController;
use App\Http\Controllers\RouteController;
use App\Http\Controllers\Employee\Auth\EmployeeAuthController;
use App\Http\Controllers\Admin\AdminProcurementController;
use App\Http\Controllers\Employee\EmployeeBiddingController;
use App\Http\Controllers\Employee\EmployeeOrderController;
use App\Http\Controllers\Employee\EmployeeInvoiceController;
use App\Http\Controllers\Employee\EmployeeReceiptController;
use App\Http\Controllers\Admin\AdminReceiptController;

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

Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {
    Route::group(['middleware' => 'admin'], function () {
        //DASHBOARD
        Route::get('/recent-approved-vendors', [AdminDashboardController::class, 'getRecentApprovedVendors']);
        Route::get('/get-total-purchase-orders', [AdminDashboardController::class, 'getTotalPurchaseOrders']);
        Route::get('/get-vendor-count', [AdminDashboardController::class, 'getVendorCount']);
        Route::get('/get-total-biddings', [AdminDashboardController::class, 'getTotalBiddings']);
        Route::get('/get-total-invoice-amount', [AdminDashboardController::class, 'getTotalEarnings']);
        Route::get('/get-ongoing-biddings', [AdminDashboardController::class, 'countOngoingBiddings']);
        Route::get('/vendor-performance/{id}', [AdminVendorController::class, 'getVendorPerformance']);

        //VENDORS
        Route::get('/vendor-management', [AdminVendorController::class, 'index'])->name('vendors');
        Route::get('/vendor-list', [AdminVendorController::class, 'getVendorList'])->name('vendors-list');
        Route::post('/analyze-supplier-performance', [AdminVendorController::class, 'analyzeSupplierPerformance'])
            ->name('analyzeSupplierPerformance');
        Route::get('/vendor-profile/{id}', [AdminVendorController::class, 'getVendorDetails'])->name('vendor-profile');
        Route::post('/vendors/invite', [AdminVendorController::class, 'inviteVendor'])->name('vendors-invite');
        Route::post('/vendors/{id}/update-status', [AdminVendorController::class, 'updateVendorStatus'])
            ->name('vendors.updateStatus');


        //REPORTS
        Route::get('/vendor-reports', [AdminVendorController::class, 'reports'])->name('reports');
        //BIDDINGS
        Route::get('/biddings-overview', [AdminBiddingController::class, 'index'])->name('biddings');
        Route::get('/bidding/{id}/vendor-bids', [AdminBiddingController::class, 'getVendorBids'])->name('bidding.vendorBids');


        //ORDERS
        Route::get('/order-management', [AdminOrderManagement::class, 'index'])->name('orders');
        Route::get('/order-details/{id}', [AdminOrderManagement::class, 'show'])->name('show');

        //INVOICES
        Route::get('/invoices', [AdminInvoiceController::class, 'index'])->name('invoices');
        Route::get('/invoice-details/{id}', [AdminInvoiceController::class, 'show'])->name('invoice.show');
        Route::get('/invoice/{invoice}/transactions', [InvoiceController::class, 'transactions'])->name('transactions');


        //RECEIPT
        Route::get('/receipt/{id}', [AdminReceiptController::class, 'show'])->name('receipt');

        //PROCUREMENT
        Route::get('/procurements', [AdminProcurementController::class, 'index'])->name('procurements');
        Route::put('/procurement-request/{id}/update-status', [AdminProcurementController::class, 'updateStatus'])->name('procurement.updateStatus');
    });
});



Route::group(['prefix' => 'employee', 'as' => 'employee.'], function () {
    Route::group(['middleware' => 'employee'], function () {
        Route::get('/dashboard', [EmployeeDashboardController::class, 'index'])->name('dashboard');
        Route::get('/notifications', [EmployeeDashboardController::class, 'getEmployeeNotifications']);


        //PROCUREMENT
        Route::get('/procurement-request', [ProcurementController::class, 'index'])->name('procurement.request');
        Route::post('/procurements/store', [ProcurementController::class, 'store'])->name('procurement.store');
        Route::get('/my-procurements', [ProcurementController::class, 'myProcurements'])->name('procurements');

        //BIDDINGS
        Route::get('/biddings', [EmployeeBiddingController::class, 'index'])->name('biddings');
        Route::get('/bidding/{id}/vendors', [EmployeeBiddingController::class, 'showVendors'])->name('bidding.showVendors');
        Route::post('/bidding/bid/{bidId}/update', [EmployeeBiddingController::class, 'updateBidStatus'])->name('bidding.updateBidStatus');

        //ORDERS
        Route::get('/orders', [EmployeeOrderController::class, 'index'])->name('orders');
        Route::get('/order-details/{id}', [EmployeeOrderController::class, 'show'])->name('orders.show');
        Route::post('/update-order-status', [EmployeeOrderController::class, 'updateOrderStatus'])->name('orders.update');


        //INVOICE
        Route::get('/invoices', [EmployeeInvoiceController::class, 'index'])->name('invoices');
        Route::get('/invoice-details/{id}', [EmployeeInvoiceController::class, 'show'])->name('invoice.show');
        // Invoice Payment Update Route (used in the modal form)
        Route::put('/invoice/{invoice}', [EmployeeInvoiceController::class, 'update'])->name('invoice.update');
        Route::get('/invoice/{invoice}/transactions', [InvoiceController::class, 'transactions'])->name('transactions');


        //RECEIPT
        Route::get('/receipt/{id}', [EmployeeReceiptController::class, 'show'])->name('receipt');
    });
    // FOR EMPLOYEES
    Route::post('/logout', [EmployeeAuthController::class, 'logout'])->middleware('employee')->name('logout');
});


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



Route::middleware(['auth:vendor', '2fa'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    // Add other authenticated routes here
});
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



Route::get('/route', [RouteController::class, 'showForm'])->name('route.form');
Route::post('/route', [RouteController::class, 'getRoute'])->name('get.route');
