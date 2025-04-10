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

    public function updateOrderStatus(Request $request)
    {
        $poId = $request->input('po_id'); // Get the Purchase Order ID
        $purchaseOrder = PurchaseOrder::find($poId); // Find the PO in the database

        if ($purchaseOrder) {
            $purchaseOrder->order_status = 'Completed'; // Update the status
            $purchaseOrder->save(); // Save the changes

            return response()->json([
                'success' => true,
                'message' => 'Purchase Order status updated to Completed'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Purchase Order not found'
        ]);
    }
}
