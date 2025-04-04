<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PurchaseOrder;

class EmployeeOrderController extends Controller
{
    public function index()
    {
        // Fetch the latest purchase orders from the database, ordered by 'created_at' in descending order
        $purchaseOrders = PurchaseOrder::orderBy('created_at', 'desc')->get();

        // Pass the purchase orders to the view
        return view('employee.orders.index-orders', compact('purchaseOrders'));
    }




    public function show($id)
    {
        // Find the purchase order by ID
        $purchaseOrder = PurchaseOrder::findOrFail($id);

        // Load the related order items and timeline events
        $purchaseOrder->load(['orderItems', 'timelineEvents']); // Load both order items and timeline events

        // Return the purchase order with order items and timeline events as JSON
        return response()->json($purchaseOrder);
    }
}
