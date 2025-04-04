<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\PurchaseReceipt;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\PurchaseOrder;
use App\Models\Vendor;
use Carbon\Carbon;

class EmployeeInvoiceController extends Controller
{
    public function index()
    {
        // Fetch all invoices from the database
        $invoices = Invoice::all(); // You can use pagination if needed: Invoice::paginate(10);

        // Pass the invoices to the view
        return view('employee.invoices.index-invoice', compact('invoices'));
    }

    public function show($id)
    {

        $invoice = Invoice::findOrFail($id);

        return view('employee.invoices.view-invoice', compact('invoice'));
    }

    public function update(Request $request, Invoice $invoice)
    {
        $validatedData = $request->validate([
            'paymentAmount' => 'required|numeric|min:1',
            'paymentMethod' => 'nullable|string',
        ]);

        $paymentAmount = $validatedData['paymentAmount'];
        Log::info('Payment received', ['invoice_id' => $invoice->id, 'payment_amount' => $paymentAmount]);

        if ($paymentAmount > $invoice->total_amount) {
            Log::warning('Payment amount exceeds balance', [
                'invoice_id' => $invoice->invoice_id,
                'payment_amount' => $paymentAmount,
                'remaining_balance' => $invoice->total_amount
            ]);

            return redirect()->back()->with('error', 'Payment amount exceeds the remaining balance.');
        }

        $originalTotalAmount = $invoice->orderItems->sum('total_price');
        $remainingBalance = $invoice->total_amount - $paymentAmount;
        $status = $remainingBalance == 0 ? 'paid' : 'partial';

        Log::info('Calculating remaining balance', [
            'invoice_id' => $invoice->id,
            'original_total' => $originalTotalAmount,
            'remaining_balance' => $remainingBalance,
            'new_status' => $status,
        ]);

        DB::beginTransaction();

        try {
            // Update invoice with new balance and status
            $invoice->update([
                'total_amount' => $remainingBalance,
                'status' => $status,
            ]);

            $receiptNumber = null;

            if ($status === 'paid') {
                // Generate receipt number from the invoice number
                $poCode = str_replace('INV-', '', $invoice->invoice_number);
                $receiptNumber = 'RCP-' . strtoupper($poCode);

                Log::info('Generating Receipt', [
                    'invoice_id' => $invoice->invoice_id,
                    'receipt_number' => $receiptNumber,
                ]);

                // Create the purchase receipt
                $receipt = PurchaseReceipt::create([
                    'receipt_number' => $receiptNumber,
                    'vendor_id' => $invoice->vendor_id,
                    'invoice_id' => $invoice->invoice_id,
                    'po_id' => $invoice->po_id,
                    'receipt_date' => now(),
                    'total_amount' => $originalTotalAmount,
                    'tax_amount' => $invoice->tax_amount ?? 0,
                    'payment_method' => $validatedData['paymentMethod'] ?? null,
                    'status' => 'completed',
                ]);

                // Update order items with the receipt id
                OrderItem::where('po_id', $invoice->po_id)->update([
                    'receipt_id' => $receipt->receipt_id,
                ]);

                Log::info('Order Items Updated with Receipt ID', [
                    'po_id' => $invoice->po_id,
                    'receipt_id' => $receipt->receipt_id,
                ]);
            }

            DB::commit();
            Log::info('Transaction Committed Successfully', ['invoice_id' => $invoice->id]);

            return redirect()
                ->route('employee.invoice', $invoice->invoice_id)
                ->with('success', 'Payment applied successfully. Status: ' . $status . ($receiptNumber ? " | Receipt #: $receiptNumber" : ''));
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Transaction Rolled Back', [
                'error' => $e->getMessage(),
                'stack_trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()->with('error', 'Failed to apply payment: ' . $e->getMessage());
        }
    }
}
