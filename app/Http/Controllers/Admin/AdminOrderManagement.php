<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PurchaseOrder;

class AdminOrderManagement extends Controller
{
    public function index()
    {
        $purchaseOrders = PurchaseOrder::all();
        return view('admin.vendors.orders.index-orders', compact('purchaseOrders'));
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
