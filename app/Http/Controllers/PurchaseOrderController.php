<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\json;

class PurchaseOrderController extends Controller
{
    public function index()
    {
        $purchaseOrders = PurchaseOrder::all()->map(function ($order) {
            $order->order_date = Carbon::parse($order->order_date)->format('Y-m-d'); // Format to date only
            $order->delivery_date = Carbon::parse($order->delivery_date)->format('Y-m-d'); // Format to date only
            return $order;
        });
        return view('vendors.purchase-orders.index', compact('purchaseOrders'));
    }


    public function create()
    {
        return view('purchase_orders.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'order_date' => 'required|date',
            'delivery_date' => 'required|date',
            'order_status' => 'required|string|max:255',
            'total_amount' => 'required|numeric',
            'payment_terms' => 'required|string|max:255',
            'delivery_location' => 'required|string|max:255',
            'notes_instructions' => 'nullable|string',
        ]);

        PurchaseOrder::create($request->all());
        return redirect()->route('purchase_orders.index')->with('success', 'Purchase order created successfully.');
    }

    public function show(PurchaseOrder $purchaseOrder)
    {
        return response()->json($purchaseOrder);
    }

    public function update(Request $request, PurchaseOrder $purchaseOrder)
    {
        // Validate the action to be taken
        $action = $request->input('action');

        // Handle confirmation
        if ($action === 'confirm') {
            if ($purchaseOrder->order_status === 'Draft') {
                $purchaseOrder->update([
                    'order_status' => 'Confirmed',
                    // Include other fields that need to be updated upon confirmation here
                ]);
                return redirect()->route('purchase-orders.index')->with('success', 'Purchase order confirmed successfully.');
            } else {
                return redirect()->route('purchase-orders.index')->with('error', 'Only draft orders can be confirmed.');
            }
        }

        // Handle rejection
        if ($action === 'reject') {
            if ($purchaseOrder->order_status !== 'Rejected') {
                $purchaseOrder->update([
                    'order_status' => 'Rejected',
                    // Add any other necessary updates here if needed
                ]);
                return redirect()->route('purchase-orders.index')->with('success', 'Purchase order rejected successfully.');
            } else {
                return redirect()->route('purchase-orders.index')->with('error', 'This order is already rejected.');
            }
        }

        // Handle re-submission
        if ($action === 'resubmit') {
            if ($purchaseOrder->order_status === 'Rejected') {
                $purchaseOrder->update([
                    'order_status' => 'Draft',
                    // Reset other fields as necessary for re-submission
                ]);
                return redirect()->route('purchase-orders.index')->with('success', 'Purchase order re-submitted successfully.');
            } else {
                return redirect()->route('purchase-orders.index')->with('error', 'Only rejected orders can be re-submitted.');
            }
        }

        // Handle edit
        if ($action === 'edit') {
            if ($purchaseOrder->order_status !== 'Draft') {
                return redirect()->route('purchase-orders.index')->with('error', 'Only draft orders can be edited.');
            }

            // Validate the fields that are allowed to be updated
            $request->validate([
                'total_amount' => 'required|numeric|min:0',
                'shipping_method' => 'required|string|max:255',
                'notes_instructions' => 'nullable|string',
            ]);

            // Update only the fields that are editable by the vendor
            $purchaseOrder->update([
                'total_amount' => $request->total_amount,
                'shipping_method' => $request->shipping_method,
                'notes_instructions' => $request->notes_instructions,
            ]);

            return redirect()->route('purchase-orders.index')->with('success', 'Purchase order updated successfully.');
        }

        // Handle cancellation
        if ($action === 'cancel') {
            if ($purchaseOrder->order_status !== 'Canceled') {
                $purchaseOrder->update([
                    'order_status' => 'Canceled',
                ]);
                return redirect()->route('purchase-orders.index')->with('success', 'Purchase order canceled successfully.');
            } else {
                return redirect()->route('purchase-orders.index')->with('error', 'This order is already canceled.');
            }
        }

        // If action is not recognized
        return redirect()->route('purchase-orders.index')->with('error', 'Invalid action.');
    }



    public function destroy(PurchaseOrder $purchaseOrder)
    {
        $purchaseOrder->delete();
        return redirect()->route('purchase-orders.index')->with('success', 'Purchase order deleted successfully.');
    }
}
