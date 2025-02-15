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
use Illuminate\Support\Facades\Auth;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Fetch invoices that belong to the authenticated vendor
        $vendorId = Auth::guard('vendor')->id(); // Get the authenticated vendor ID
        $invoices = Invoice::where('vendor_id', $vendorId)->get(); // Filter invoices by vendor_id

        return view('vendors.invoices.index-invoice', compact('invoices'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $poId = $request->input('po_id');
        $vendorId = $request->input('vendor_id');

        // Fetch the necessary data for the invoice creation
        $purchaseOrder = PurchaseOrder::findOrFail($poId);
        $vendor = Vendor::findOrFail($vendorId);
        $orderItems = $purchaseOrder->orderItems;

        // Check if an invoice number already exists for the given purchase order
        $existingInvoice = Invoice::where('po_id', $poId)->first();

        if ($existingInvoice) {
            return redirect()->route('purchase-orders.index')
                ->with('error_message', 'An invoice has already been generated for this purchase order.');
        }

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
            $purchaseOrderNumber = $purchaseOrder->purchase_order_number;
            preg_match('/\d+/', $purchaseOrderNumber, $matches);
            $numericPart = $matches[0] ?? '0000'; // Fallback to '0000' if not found

            // Generate the invoice number
            $invoiceNumber = 'INV_' . $numericPart;
            Log::info('Generated Invoice Number', ['invoice_number' => $invoiceNumber]);

            // Create the invoice using details from the purchase order
            $invoice = Invoice::create([
                'invoice_number' => $invoiceNumber,
                'po_id' => $purchaseOrder->po_id,
                'vendor_id' => $purchaseOrder->vendor->id,
                'invoice_date' => now(),
                'due_date' => Carbon::parse($purchaseOrder->due_date)->addDays(20),
                'total_amount' => $purchaseOrder->orderItems->sum('total_price'),
                'status' => 'unpaid',
            ]);

            Log::info('Invoice Created', ['invoice_id' => $invoice->invoice_id]);

            // Attach order items to the invoice
            $orderItems = OrderItem::where('po_id', $purchaseOrder->po_id)->get();
            Log::info('Order Items Retrieved', ['count' => $orderItems->count()]);

            foreach ($orderItems as $item) {
                // Check if the item already has an invoice_id before saving
                $item->invoice_id = $invoice->invoice_id;
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
            Log::error('Transaction Rolled Back', ['error' => $e->getMessage(), 'stack' => $e->getTraceAsString()]);

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
        return view('vendors.invoices.show-invoice', compact('invoice'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Invoice $invoice)
    {
        // Show a form for editing the invoice
        return view('vendors.invoices.edit-invoice', compact('invoice'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Invoice $invoice)
    {
        // Validate and update only the specified fields
        $validatedData = $request->validate([
            'tax_amount' => 'required|numeric',  // Validating the tax amount
            'discount_amount' => 'required|numeric',  // Validating the discount amount
        ]);

        // Retrieve the current total amount from the invoice
        $currentTotalAmount = $invoice->total_amount;

        // Apply the tax amount to the current total amount
        $totalWithTax = $currentTotalAmount + $validatedData['tax_amount']; // Adding tax to the current total

        // Subtract the discount amount from the total with tax
        $newTotalAmount = $totalWithTax - $validatedData['discount_amount'];

        // Update the invoice with the new values
        $invoice->update([
            'tax_amount' => $validatedData['tax_amount'],  // Update the tax amount
            'discount_amount' => $validatedData['discount_amount'],  // Update the discount amount
            'total_amount' => $newTotalAmount,  // Update the total amount after applying tax and discount
        ]);

        // Redirect back with a success message
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
