<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\json;
use Illuminate\Support\Facades\Validator;

class PurchaseOrderController extends Controller
{
    public function index()
    {
        // Get authenticated vendor ID
        $vendorId = auth()->user()->id;

        // Fetch purchase orders for this vendor and format the dates
        $purchaseOrders = PurchaseOrder::where('vendor_id', $vendorId)
            ->get()
            ->map(function ($order) {
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
        // Load the related order items
        $purchaseOrder->load(['orderItems', 'timelineEvents']); // Load both order items and timeline events

        // Return the purchase order with order items and timeline events as JSON
        return response()->json($purchaseOrder);
    }



    public function update(Request $request, PurchaseOrder $purchaseOrder)
    {
        // Validate the action to be taken
        $action = $request->input('action');

        // Handle confirmation
        if ($action === 'confirm') {
            if ($purchaseOrder->order_status === 'Pending Approval') {
                $purchaseOrder->update([
                    'order_status' => 'Approved', // Change status to Approved
                    // Include other fields that need to be updated upon approval here
                ]);

                // Log the event to the timeline
                $purchaseOrder->timelineEvents()->create([
                    'event_date' => now(), // Current date and time
                    'event_title' => 'Purchase Order Approved',
                    'event_details' => 'Purchase order has been approved.',
                ]);

                return redirect()->route('purchase-orders.index')->with('success', 'Purchase order approved successfully.');
            } else {
                return redirect()->route('purchase-orders.index')->with('error', 'Only purchase orders pending approval can be approved.');
            }
        }

        // Handles Rejection
        if ($action === 'reject') {
            // Validate the rejection note
            $validator = Validator::make($request->all(), [
                'rejection_note' => 'required|string|max:50', // Validation rule for rejection note
            ]);

            if ($validator->fails()) {
                return redirect()->route('purchase-orders.index')
                    ->withErrors($validator)
                    ->withInput();
            }

            if ($purchaseOrder->order_status === 'Pending Approval') {
                $rejectionNote = $request->input('rejection_note'); // Get the rejection note

                $purchaseOrder->update([
                    'order_status' => 'Rejected', // Change status to Rejected
                    // Add any other necessary updates here if needed
                ]);

                // Log the event to the timeline with the rejection note
                $purchaseOrder->timelineEvents()->create([
                    'event_date' => now(), // Current date and time
                    'event_title' => 'Purchase Order Rejected',
                    'event_details' => 'Note: ' . $rejectionNote,
                ]);

                return redirect()->route('purchase-orders.index')->with('success', 'Purchase order rejected successfully.');
            } else {
                return redirect()->route('purchase-orders.index')->with('error', 'Only purchase orders pending approval can be rejected.');
            }
        }


        // Handle re-submission
        if ($action === 'resubmit') {
            if ($purchaseOrder->order_status === 'Rejected') {
                $purchaseOrder->update([
                    'order_status' => 'Pending Approval', // Change status back to Pending Approval
                    // Reset other fields as necessary for re-submission if needed
                ]);

                // Log the event to the timeline
                $purchaseOrder->timelineEvents()->create([
                    'event_date' => now(), // Current date and time
                    'event_title' => 'Purchase Order Resubmitted',
                    'event_details' => 'Purchase order has been resubmitted for approval.',
                ]);

                return redirect()->route('purchase-orders.index')->with('success', 'Purchase order re-submitted successfully.');
            } else {
                return redirect()->route('purchase-orders.index')->with('error', 'Only rejected orders can be re-submitted.');
            }
        }
        // Handles onhold
        if ($action === 'hold') {
            if ($purchaseOrder->order_status === 'Approved' || $purchaseOrder->order_status === 'Pending Approval') {
                $holdNote = $request->input('hold_note'); // Get the hold note

                $purchaseOrder->update([
                    'order_status' => 'On Hold',
                ]);

                // Log to the timeline with optional hold note
                $purchaseOrder->timelineEvents()->create([
                    'event_date' => now(),
                    'event_title' => 'Purchase Order Put on Hold',
                    'event_details' => $holdNote ? 'Reason: ' . $holdNote : 'No reason provided',
                ]);

                return redirect()->route('purchase-orders.index')->with('success', 'Purchase order put on hold successfully.');
            } else {
                return redirect()->route('purchase-orders.index')->with('error', 'Only approved or pending orders can be put on hold.');
            }
        }


        // Handle cancellation
        if ($action === 'cancel') {
            if ($purchaseOrder->order_status !== 'Canceled') {
                $purchaseOrder->update([
                    'order_status' => 'Canceled', // Change status to Canceled
                ]);

                // Log the event to the timeline
                $purchaseOrder->timelineEvents()->create([
                    'event_date' => now(), // Current date and time
                    'event_title' => 'Purchase Order Canceled',
                    'event_details' => 'Purchase order has been canceled.',
                ]);

                return redirect()->route('purchase-orders.index')->with('success', 'Purchase order canceled successfully.');
            } else {
                return redirect()->route('purchase-orders.index')->with('error', 'This order is already canceled.');
            }
        }

        // Handle initiate fulfillment
        if ($action === 'initiate_fulfillment') {
            if ($purchaseOrder->order_status === 'Approved') {
                $purchaseOrder->update([
                    'order_status' => 'In Progress', // Change status to In Progress for fulfillment
                    // Include any other fields that need to be updated during fulfillment here
                ]);

                // Log the event to the timeline
                $purchaseOrder->timelineEvents()->create([
                    'event_date' => now(), // Current date and time
                    'event_title' => 'Fulfillment Initiated',
                    'event_details' => 'Fulfillment for the purchase order has been initiated.',
                ]);

                return redirect()->route('purchase-orders.index')->with('success', 'Fulfillment initiated successfully.');
            } else {
                return redirect()->route('purchase-orders.index')->with('error', 'Only approved orders can be initiated for fulfillment.');
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
