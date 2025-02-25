<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\APIs\VendorManagementController;
use App\Http\Controllers\APIs\ApiUserController;
use App\Http\Controllers\APIs\ShipmentDetailController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});



Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('vendors', [VendorManagementController::class, 'index']); // Retrieve all vendors
    Route::get('/vendor', [VendorManagementController::class, 'show']); // Retrieve a specific vendor
    Route::put('vendor/{id}', [VendorManagementController::class, 'update']); // Update a vendor
    Route::patch('vendor/{id}', [VendorManagementController::class, 'patch']); // Partial update of a vendor
});

Route::get('/supplier-analysis', [SupplierController::class, 'analyzeSuppliers']);

// API Routes for Shipment Details
Route::prefix('shipment-details')->group(function () {
    // Get all shipment details
    Route::get('/', [ShipmentDetailController::class, 'index']);

    // Create a new shipment detail
    Route::post('/', [ShipmentDetailController::class, 'store']);

    // Get a specific shipment detail by ID
    Route::get('/{id}', [ShipmentDetailController::class, 'show']);

    // Update a specific shipment detail by ID
    Route::put('/{id}', [ShipmentDetailController::class, 'update']);
    Route::patch('/{id}', [ShipmentDetailController::class, 'update']);

    // Delete a specific shipment detail by ID
    Route::delete('/{id}', [ShipmentDetailController::class, 'destroy']);
});
