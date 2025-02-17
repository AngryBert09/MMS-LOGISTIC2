<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\APIs\VendorManagementController;



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



Route::get('vendors', [VendorManagementController::class, 'index']); // Retrieve all vendors
Route::get('/vendor', [VendorManagementController::class, 'show']);
Route::put('vendor/{id}', [VendorManagementController::class, 'update']); // Update a vendor



Route::get('/supplier-analysis', [SupplierController::class, 'analyzeSuppliers']);
