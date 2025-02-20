<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\APIs\VendorManagementController;
use App\Http\Controllers\APIs\ApiUserController;


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



Route::middleware(['auth:sanctum', 'sanctum.json'])->group(function () {
    Route::get('vendors', [VendorManagementController::class, 'index']); // Retrieve all vendors
    Route::get('/vendor', [VendorManagementController::class, 'show']); // Retrieve a specific vendor
    Route::put('vendor/{id}', [VendorManagementController::class, 'update']); // Update a vendor
    Route::patch('vendor/{id}', [VendorManagementController::class, 'patch']); // Partial update of a vendor
});

Route::get('/supplier-analysis', [SupplierController::class, 'analyzeSuppliers']);
