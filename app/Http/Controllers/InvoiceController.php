<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;
use App\Models\PurchaseOrder;
use App\Models\Vendor;
use Illuminate\Support\Facades\Log;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;


class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Fetch all invoices and return to view
        $invoices = Invoice::all();
        return view('vendors.invoices.index-invoice', compact('invoices'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        // Retrieve PO ID and Vendor ID from the form
        $poId = $request->input('po_id');
        $vendorId = $request->input('vendor_id');

        // Fetch the necessary data for the invoice creation
        $purchaseOrder = PurchaseOrder::findOrFail($poId);
        $vendor = Vendor::findOrFail($vendorId);
        $orderItems = $purchaseOrder->orderItems;

        // Redirect to the invoice creation page or preview, with data
        return view('vendors.invoices.create-invoice', compact('purchaseOrder', 'vendor', 'orderItems'));
    }
    /**
     * Store a newly created resource in storage.
     */


    public function store(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'purchase_order_id' => 'required|exists:purchase_orders,po_id', // Ensure the purchase order exists
        ]);

        // Start a database transaction
        DB::beginTransaction();

        try {
            // Retrieve the purchase order
            $purchaseOrder = PurchaseOrder::findOrFail($request->input('purchase_order_id'));

            Log::info('Purchase Order Retrieved', ['purchase_order_id' => $purchaseOrder->po_id, 'purchase_order_number' => $purchaseOrder->purchase_order_number]);

            // Extract the numeric part from the purchase order number (e.g., PO-1002 -> 1002)
            $purchaseOrderNumber = $purchaseOrder->purchase_order_number; // Assuming you have this field
            preg_match('/\d+/', $purchaseOrderNumber, $matches);
            $numericPart = $matches[0] ?? '0000'; // Fallback to '0000' if not found

            // Generate the invoice number
            $invoiceNumber = 'INV_' . $numericPart;
            Log::info('Generated Invoice Number', ['invoice_number' => $invoiceNumber]);

            // Create the invoice using details from the purchase order
            $invoice = Invoice::create([
                'invoice_number' => $invoiceNumber,  // Ensure this is set
                'po_id' => $purchaseOrder->po_id,    // Store the purchase order ID in the invoice
                'vendor_id' => $purchaseOrder->vendor->id, // Store the vendor ID in the invoice
                'invoice_date' => now(),
                'due_date' => Carbon::parse($purchaseOrder->due_date)->addDays(20), // Add 20 days to the due date
                'total_amount' => $purchaseOrder->orderItems->sum('total_price'), // Calculate total amount from related order items
                'status' => 'unpaid', // Set default status
            ]);

            Log::info('Invoice Created', ['invoice_id' => $invoice->id]);

            // Attach order items to the invoice
            $orderItems = OrderItem::where('po_id', $purchaseOrder->po_id)->get();
            Log::info('Order Items Retrieved', ['count' => $orderItems->count()]);

            foreach ($orderItems as $item) {
                // Save the item details as necessary
                $item->invoice_id = $invoice->id; // Assuming you have an `invoice_id` field in the OrderItems table
                $item->save();
                Log::info('Attached Order Item to Invoice', ['order_item_id' => $item->id, 'invoice_id' => $invoice->id]);
            }

            // Update the purchase order with the generated invoice number
            $purchaseOrder->invoice_number = $invoiceNumber;
            $purchaseOrder->save();
            Log::info('Purchase Order Updated with Invoice Number', ['purchase_order_id' => $purchaseOrder->po_id, 'invoice_number' => $invoiceNumber]);

            // Commit the transaction
            DB::commit();
            Log::info('Transaction Committed Successfully');

            // Redirect back with a success message
            return redirect()->route('invoices.index')->with('success', 'Invoice created successfully.');
        } catch (\Exception $e) {
            // Rollback the transaction in case of error
            DB::rollback();
            Log::error('Transaction Rolled Back', ['error' => $e->getMessage()]);

            // Redirect back with an error message
            return redirect()->back()->withErrors(['error' => 'Failed to create invoice. Please try again.']);
        }
    }



    /**
     * Display the specified resource.
     */
    // Show the invoice preview based on the selected purchase order
    public function show(Invoice $invoice)
    {
        // Return the view with the invoice data
        return view('invoices.show', compact('invoice'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Invoice $invoice)
    {
        // Show a form for editing the invoice
        return view('invoices.edit', compact('invoice'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Invoice $invoice)
    {
        // Validate and update the invoice
        $validatedData = $request->validate([
            'client_name' => 'required|string|max:255',
            'date_issued' => 'required|date',
            'total_amount' => 'required|numeric',
            // Add other fields as necessary
        ]);

        $invoice->update($validatedData);

        return redirect()->route('invoices.index')->with('success', 'Invoice updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Invoice $invoice)
    {
        // Delete the invoice
        $invoice->delete();

        return redirect()->route('invoices.index')->with('success', 'Invoice deleted successfully.');
    }
}
