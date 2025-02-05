<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\json;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

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

        // Handle confirmation (Approve)
        if ($action === 'confirm') {
            if ($purchaseOrder->order_status === 'Pending Approval') {
                $purchaseOrder->update([
                    'order_status' => 'Approved', // Change status to Approved
                ]);

                // Log the event to the timeline
                $purchaseOrder->timelineEvents()->create([
                    'event_date' => now(),
                    'event_title' => 'Purchase Order Approved',
                    'event_details' => 'Purchase order has been approved.',
                ]);

                return redirect()->route('purchase-orders.index')->with('success', 'Purchase order approved successfully.');
            } else {
                return redirect()->route('purchase-orders.index')->with('error', 'Only purchase orders pending approval can be approved.');
            }
        }

        // Handle rejection (Reject)
        if ($action === 'reject') {
            // Validate the rejection note
            $validator = Validator::make($request->all(), [
                'rejection_note' => 'required|string|max:50',
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
                ]);

                // Log the event to the timeline with the rejection note
                $purchaseOrder->timelineEvents()->create([
                    'event_date' => now(),
                    'event_title' => 'Purchase Order Rejected',
                    'event_details' => 'Note: ' . $rejectionNote,
                ]);

                return redirect()->route('purchase-orders.index')->with('success', 'Purchase order rejected successfully.');
            } else {
                return redirect()->route('purchase-orders.index')->with('error', 'Only purchase orders pending approval can be rejected.');
            }
        }

        // Handle re-submission (Resubmit)
        if ($action === 'resubmit') {
            if ($purchaseOrder->order_status === 'Rejected') {
                $purchaseOrder->update([
                    'order_status' => 'Pending Approval', // Change status back to Pending Approval
                ]);

                // Log the event to the timeline
                $purchaseOrder->timelineEvents()->create([
                    'event_date' => now(),
                    'event_title' => 'Purchase Order Resubmitted',
                    'event_details' => 'Purchase order has been resubmitted for approval.',
                ]);

                return redirect()->route('purchase-orders.index')->with('success', 'Purchase order re-submitted successfully.');
            } else {
                return redirect()->route('purchase-orders.index')->with('error', 'Only rejected orders can be re-submitted.');
            }
        }

        // Handle on-hold action (Hold)
        if ($action === 'hold') {
            if ($purchaseOrder->order_status === 'Approved' || $purchaseOrder->order_status === 'Pending Approval') {
                $holdNote = $request->input('hold_note'); // Get the hold note

                $purchaseOrder->update([
                    'order_status' => 'On Hold',
                ]);

                // Log the event to the timeline with optional hold note
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

        // Handle cancellation (Cancel)
        if ($action === 'cancel') {
            if ($purchaseOrder->order_status !== 'Canceled') {
                $purchaseOrder->update([
                    'order_status' => 'Canceled', // Change status to Canceled
                ]);

                // Log the event to the timeline
                $purchaseOrder->timelineEvents()->create([
                    'event_date' => now(),
                    'event_title' => 'Purchase Order Canceled',
                    'event_details' => 'Purchase order has been canceled.',
                ]);

                return redirect()->route('purchase-orders.index')->with('success', 'Purchase order canceled successfully.');
            } else {
                return redirect()->route('purchase-orders.index')->with('error', 'This order is already canceled.');
            }
        }

        // Handle initiate fulfillment (In Progress)
        if ($action === 'initiate_fulfillment') {
            if ($purchaseOrder->order_status === 'Approved') {
                $purchaseOrder->update([
                    'order_status' => 'In Progress', // Change status to In Progress for fulfillment
                ]);

                // Log the event to the timeline
                $purchaseOrder->timelineEvents()->create([
                    'event_date' => now(),
                    'event_title' => 'Fulfillment Initiated',
                    'event_details' => 'Fulfillment for the purchase order has been initiated.',
                ]);

                return redirect()->route('purchase-orders.index')->with('success', 'Fulfillment initiated successfully.');
            } else {
                return redirect()->route('purchase-orders.index')->with('error', 'Only approved orders can be initiated for fulfillment.');
            }
        }

        // Handle marking order as In Transit
        if ($action === 'mark_in_transit') {
            Log::debug('Marking order as In Transit', [
                'po_id' => $purchaseOrder->po_id,
                'current_status' => $purchaseOrder->order_status,
                'request_data' => $request->all()
            ]);

            if ($purchaseOrder->order_status !== 'In Progress') {
                Log::warning('Attempted to mark order not in progress as In Transit', [
                    'po_id' => $purchaseOrder->po_id,
                    'current_status' => $purchaseOrder->order_status
                ]);

                return redirect()->route('purchase-orders.index')
                    ->with('error', 'Only orders in progress can be marked as In Transit.');
            }

            DB::beginTransaction(); // Start a database transaction

            try {
                // Validate delivery service
                $validatedData = $request->validate([
                    'delivery_service' => 'required|in:own,third_party',
                ]);

                $updateData = ['order_status' => 'In Transit'];

                $shipmentData = ['po_id' => $purchaseOrder->po_id]; // Using po_id here

                // Handle Third-Party Delivery (e.g., Lalamove)
                if ($request->delivery_service === 'third_party') {
                    $validatedData += $request->validate([
                        'carrier_name' => 'required|in:lalamove,other',
                    ]);

                    $updateData['delivery_service'] = 'Third-Party';
                    $updateData['carrier_name'] = $request->carrier_name;

                    // Store shipment details
                    $shipmentData['carrier_name'] = $request->carrier_name;
                }

                // Handle Own Delivery Service
                if ($request->delivery_service === 'own') {
                    $validatedData += $request->validate([
                        'estimated_delivery_date' => 'required|date',
                        'shipment_method' => 'required|in:Ground,Air,Expedited', // Updated column name here
                        'shipping_cost' => 'required|numeric|min:0',
                        'weight' => 'required|numeric|min:0',
                    ]);

                    $updateData['estimated_delivery_date'] = $request->estimated_delivery_date;
                    $updateData['shipment_method'] = $request->shipment_method; // Updated column name here
                    $updateData['shipping_cost'] = $request->shipping_cost;
                    $updateData['weight'] = $request->weight;

                    // Store shipment details
                    $shipmentData['estimated_delivery_date'] = $request->estimated_delivery_date;
                    $shipmentData['shipment_method'] = $request->shipment_method; // Updated column name here
                    $shipmentData['shipping_cost'] = $request->shipping_cost;
                    $shipmentData['weight'] = $request->weight;
                }

                // Generate a unique tracking number
                $trackingNumber = strtoupper(uniqid('TRACK-', true)); // You can adjust this as needed
                $shipmentData['tracking_number'] = $trackingNumber;

                // Update the purchase order
                $purchaseOrder->update($updateData);
                Log::info('Purchase order updated successfully', [
                    'po_id' => $purchaseOrder->po_id,
                    'updated_data' => $updateData
                ]);

                // Store shipment details in the database
                DB::table('shipment_details')->updateOrInsert(
                    ['po_id' => $purchaseOrder->po_id], // Condition
                    $shipmentData // Data to insert/update
                );

                Log::info('Shipment details stored successfully', [
                    'po_id' => $purchaseOrder->po_id,
                    'shipment_data' => $shipmentData
                ]);

                // Log the event in the timeline
                $purchaseOrder->timelineEvents()->create([
                    'event_date' => now(),
                    'event_title' => 'Purchase Order Marked as In Transit',
                    'event_details' => 'The purchase order has been marked as In Transit with ' .
                        ($request->delivery_service === 'third_party' ? 'Third-Party Service (' . $request->carrier_name . ')' : 'Own Delivery Service.'),
                ]);

                Log::info('Timeline event created for purchase order', [
                    'po_id' => $purchaseOrder->po_id
                ]);

                DB::commit(); // Commit transaction

                return redirect()->route('purchase-orders.index')
                    ->with('success', 'Purchase order marked as In Transit successfully. Tracking number: ' . $trackingNumber);
            } catch (\Illuminate\Validation\ValidationException $e) {
                DB::rollBack(); // Rollback transaction on validation failure
                Log::error('Validation error occurred', ['errors' => $e->errors()]);
                return redirect()->route('purchase-orders.index')->withErrors($e->errors());
            } catch (\Exception $e) {
                DB::rollBack(); // Rollback transaction on any error
                Log::error('Unexpected error occurred', ['message' => $e->getMessage()]);
                return redirect()->route('purchase-orders.index')
                    ->with('error', 'An unexpected error occurred while marking the order as In Transit.');
            }
        }

        // If action is not recognized
        return redirect()->route('purchase-orders.index')->with('error', 'Invalid action.');
    }

    public function destroy(PurchaseOrder $purchaseOrder)
    {
        // Delete related shipment details using DB
        DB::table('shipment_details')->where('po_id', $purchaseOrder->po_id)->delete();

        // Delete related invoices using Eloquent
        $purchaseOrder->invoices()->delete();

        // Now delete the purchase order
        $purchaseOrder->delete();

        return redirect()->route('purchase-orders.index')->with('success', 'Purchase order deleted successfully.');
    }
}
