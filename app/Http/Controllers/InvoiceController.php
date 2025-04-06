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

use Illuminate\Support\Facades\Http;
use stdClass;

class InvoiceController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $vendorId = Auth::guard('vendor')->id();
        $invoices = Invoice::where('vendor_id', $vendorId)->get();
        return view('vendors.invoices.index-invoice', compact('invoices'));
    }

    /**
     * Show the form for creating a new invoice.
     */
    public function create(Request $request)
    {
        $poId = $request->input('po_id');
        $vendorId = $request->input('vendor_id');

        $purchaseOrder = PurchaseOrder::findOrFail($poId);
        $vendor = Vendor::findOrFail($vendorId);
        $orderItems = $purchaseOrder->orderItems;

        // Check if invoice already exists
        if (Invoice::where('po_id', $poId)->exists()) {
            return redirect()->route('purchase-orders.index')
                ->with('error_message', 'An invoice has already been generated for this purchase order.');
        }

        return view('vendors.invoices.create-invoice', compact('purchaseOrder', 'vendor', 'orderItems'));
    }

    /**
     * Store a newly created invoice in the database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'purchase_order_id' => 'required|exists:purchase_orders,po_id', // Ensure using correct primary key
        ]);

        DB::beginTransaction();

        try {
            $purchaseOrder = PurchaseOrder::findOrFail($request->input('purchase_order_id'));

            // ✅ Extract PO Code (Corrected)
            $poCode = str_replace('PO-', '', $purchaseOrder->purchase_order_number); // Extract the PO suffix
            $invoiceNumber = 'INV-' . $poCode;

            // ✅ Ensure due_date is properly set
            $dueDate = $purchaseOrder->delivery_date ? Carbon::parse($purchaseOrder->delivery_date)->addDays(20) : Carbon::now()->addDays(20);

            // ✅ Create Invoice
            $invoice = Invoice::create([
                'invoice_number' => $invoiceNumber,
                'po_id' => $purchaseOrder->po_id, // Ensure using correct primary key
                'vendor_id' => $purchaseOrder->vendor_id,
                'invoice_date' => now(),
                'due_date' => $dueDate,
                'total_amount' => $purchaseOrder->orderItems->sum('total_price'),
                'status' => 'unpaid',
            ]);

            // ✅ Attach Order Items to Invoice
            foreach ($purchaseOrder->orderItems as $item) {
                $item->update(['invoice_id' => $invoice->invoice_id]); // Use update for efficiency
            }

            // ✅ Update Purchase Order
            $purchaseOrder->invoice_number = $invoiceNumber;
            $purchaseOrder->save();


            DB::commit();

            return redirect()->route('invoices.index')->with('success', 'Invoice created successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Invoice Creation Failed', ['error' => $e->getMessage()]);
            return redirect()->back()->withErrors(['error' => 'Failed to create invoice. Please try again.']);
        }
    }


    /**
     * Display a specific invoice.
     */
    public function show(Invoice $invoice)
    {
        return view('vendors.invoices.show-invoice', compact('invoice'));
    }

    /**
     * Create a PayPal order for invoice payment.
     */
    public function createPayPalOrder($invoiceId)
    {
        Log::info("Creating PayPal order for invoice ID: {$invoiceId}");

        $invoice = Invoice::findOrFail($invoiceId);
        $vendor = Vendor::findOrFail($invoice->vendor_id);

        if (!$vendor->paypal_email) {
            Log::error("Vendor does not have a PayPal email", ['vendor_id' => $vendor->id]);
            return redirect()->route('invoices.index')->withErrors('Vendor does not have a PayPal email set.');
        }

        // Get PayPal Access Token
        $tokenResponse = Http::withBasicAuth(env('PAYPAL_CLIENT_ID'), env('PAYPAL_SECRET'))
            ->asForm()
            ->post('https://api-m.sandbox.paypal.com/v1/oauth2/token', [
                'grant_type' => 'client_credentials'
            ]);

        if (!$tokenResponse->successful()) {
            Log::error("PayPal authentication failed", ['response' => $tokenResponse->body()]);
            return redirect()->route('invoices.index')->withErrors('PayPal authentication failed.');
        }

        $accessToken = $tokenResponse->json()['access_token'];

        // Create PayPal Order
        $orderPayload = [
            'intent' => 'CAPTURE',
            'purchase_units' => [[
                'amount' => [
                    'currency_code' => 'USD',
                    'value' => number_format($invoice->total_amount, 2, '.', '')
                ],
                'payee' => [
                    'email_address' => $vendor->paypal_email
                ]
            ]],
            'application_context' => [
                'return_url' => route('paypal.captureOrder', ['orderId' => '__ORDER_ID__']), // Placeholder
                'cancel_url' => route('paypal.cancel')
            ]
        ];

        $response = Http::withToken($accessToken)
            ->withHeaders(['Content-Type' => 'application/json'])
            ->post('https://api-m.sandbox.paypal.com/v2/checkout/orders', $orderPayload);

        if (!$response->successful()) {
            Log::error("PayPal order creation failed", ['response' => $response->body()]);
            return redirect()->route('invoices.index')->withErrors('PayPal order creation failed.');
        }

        $orderData = $response->json();
        $orderId = $orderData['id'];
        $approvalLink = collect($orderData['links'])->firstWhere('rel', 'approve')['href'];

        // 🔹 Now update the return URL with the real order ID
        $updatedReturnUrl = route('paypal.captureOrder', ['orderId' => $orderId]);

        // 🔹 This step is CRUCIAL: Create a new order with the correct return URL
        $orderPayload['application_context']['return_url'] = $updatedReturnUrl;

        // 🔹 Send the updated request
        $response = Http::withToken($accessToken)
            ->withHeaders(['Content-Type' => 'application/json'])
            ->post('https://api-m.sandbox.paypal.com/v2/checkout/orders', $orderPayload);

        if (!$response->successful()) {
            Log::error("PayPal order re-creation failed", ['response' => $response->body()]);
            return redirect()->route('invoices.index')->withErrors('PayPal order re-creation failed.');
        }

        $orderData = $response->json();
        $approvalLink = collect($orderData['links'])->firstWhere('rel', 'approve')['href'];

        // 🔹 Save the correct order ID in the database
        $invoice->paypal_order_id = $orderId;
        $invoice->save();

        Log::info("PayPal order created successfully", ['order_id' => $orderId, 'approval_url' => $approvalLink]);

        return redirect()->away($approvalLink);
    }



    /**
     * Capture PayPal payment and mark invoice as paid.
     */
    public function capturePayPalOrder($orderId)
    {
        Log::info("Capturing PayPal order: {$orderId}");

        // 🔹 Make sure we are receiving the correct order ID
        if ($orderId == '__ORDER_ID__') {
            Log::error("Invalid placeholder order ID received in capture.");
            return redirect()->route('invoices.index')->withErrors('Invalid PayPal order ID.');
        }

        // Get PayPal Access Token
        $tokenResponse = Http::withBasicAuth(env('PAYPAL_CLIENT_ID'), env('PAYPAL_SECRET'))
            ->asForm()
            ->post('https://api-m.sandbox.paypal.com/v1/oauth2/token', [
                'grant_type' => 'client_credentials'
            ]);

        if (!$tokenResponse->successful()) {
            Log::error("PayPal authentication failed", ['response' => $tokenResponse->body()]);
            return redirect()->route('invoices.index')->withErrors('PayPal authentication failed.');
        }

        $accessToken = $tokenResponse->json()['access_token'];

        // 🔹 Retrieve the correct order ID from the database
        $invoice = Invoice::where('paypal_order_id', $orderId)->first();

        if (!$invoice) {
            Log::error("Invoice not found for PayPal order ID", ['paypal_order_id' => $orderId]);
            return redirect()->route('invoices.index')->withErrors('Invalid PayPal order.');
        }

        // Capture Payment
        $response = Http::withToken($accessToken)
            ->withHeaders(['Content-Type' => 'application/json'])
            ->post("https://api-m.sandbox.paypal.com/v2/checkout/orders/{$orderId}/capture");

        $captureData = $response->json();

        if (!$response->successful() || !isset($captureData['status']) || $captureData['status'] !== 'COMPLETED') {
            Log::error("PayPal order capture failed", ['response' => $response->body()]);
            return redirect()->route('invoices.index')->withErrors('PayPal order capture failed.');
        }

        // Mark invoice as paid
        $invoice->status = 'paid';
        $invoice->save();

        Log::info("PayPal order captured successfully", ['order_id' => $captureData['id']]);

        return redirect()->route('invoices.index')->with('success', 'Payment successful!');
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
     * Update invoice details.
     */
    public function update(Request $request, Invoice $invoice)
    {
        $validatedData = $request->validate([
            'tax_amount' => 'required|numeric|min:0',
            'discount_amount' => [
                'required',
                'numeric',
                'min:0',
                function ($attribute, $value, $fail) use ($invoice) {
                    // Ensure discount doesn't make subtotal negative
                    $subtotalAfterDiscount = $invoice->total_amount - $value;
                    if ($subtotalAfterDiscount < 0) {
                        $fail('Discount amount cannot exceed the subtotal ($' . number_format($invoice->subtotal_amount, 2) . ').');
                    }
                }
            ],
        ]);

        // Calculate new values
        $subtotalAfterDiscount = $invoice->total_amount - $validatedData['discount_amount'];
        $newTotal = $subtotalAfterDiscount + $validatedData['tax_amount'];

        // Prevent negative total
        if ($newTotal < 0) {
            return back()
                ->withErrors(['total' => 'Invalid amounts would result in negative total.'])
                ->withInput();
        }

        // Use transaction to ensure both updates succeed or fail together
        DB::transaction(function () use ($invoice, $validatedData, $newTotal) {
            // Update invoice
            $invoice->update([
                'tax_amount' => $validatedData['tax_amount'],
                'discount_amount' => $validatedData['discount_amount'],
                'total_amount' => $newTotal,
            ]);

            // Update related purchase order if exists
            if ($invoice->purchaseOrder) {
                $invoice->purchaseOrder->update([
                    'total_amount' => $newTotal // Sync the same total
                ]);
            }
        });

        return redirect()
            ->route('invoices.index')
            ->with('success', 'Invoice and purchase order updated successfully.');
    }

    /**
     * Delete an invoice.
     */
    public function destroy(Invoice $invoice)
    {
        $invoice->delete();
        return redirect()->route('invoices.index')->with('success', 'Invoice deleted successfully.');
    }
}
