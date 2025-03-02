<?php


namespace App\Http\Controllers\APIs;

use App\Http\Resources\InvoiceResource;
use App\Models\Invoice;
use App\Models\PurchaseOrder;
use App\Models\Vendor;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;
use App\Models\PurchaseReceipt;
use Illuminate\Support\Facades\Log;

class InvoiceController extends Controller
{

    // public function __construct()
    // {
    //     // Apply Sanctum authentication to all methods
    //     $this->middleware('auth:sanctum');
    // }

    public function index()
    {
        $invoices = Invoice::all();

        return response()->json([
            'success' => true,
            'data' => InvoiceResource::collection($invoices),
        ]);
    }

    public function show(Invoice $invoice)
    {
        return response()->json([
            'success' => true,
            'data' => new InvoiceResource($invoice),
        ]);
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
                'invoice_id' => $invoice->id,
                'payment_amount' => $paymentAmount,
                'remaining_balance' => $invoice->total_amount
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Payment amount exceeds the remaining balance.',
            ], 400);
        }

        $originalTotalAmount = $invoice->orderItems->sum('total_price'); // Store original total from order items
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
            // Update invoice
            $invoice->update([
                'total_amount' => $remainingBalance,
                'status' => $status,
            ]);


            $receiptNumber = null;

            if ($status === 'paid') {
                // Generate receipt number from invoice number
                preg_match('/\d+/', $invoice->invoice_number, $matches);
                $numericPart = isset($matches[0]) ? $matches[0] : '0000';
                $receiptNumber = 'RCP-' . $numericPart;

                Log::info('Generating Receipt', [
                    'invoice_id' => $invoice->id,
                    'receipt_number' => $receiptNumber,
                ]);

                // Create the receipt
                $receipt = PurchaseReceipt::create([
                    'receipt_number' => $receiptNumber,
                    'vendor_id' => $invoice->vendor_id,
                    'invoice_id' => $invoice->invoice_id,
                    'po_id' => $invoice->po_id,
                    'receipt_date' => now(),
                    'total_amount' => $originalTotalAmount, // Use the original total amount
                    'tax_amount' => $invoice->tax_amount ?? 0,
                    'payment_method' => $validatedData['paymentMethod'] ?? null,
                    'status' => 'completed',
                ]);


                // Update order items to store receipt_id
                OrderItem::where('po_id', $invoice->po_id)->update([
                    'receipt_id' => $receipt->id,
                ]);

                Log::info('Order Items Updated with Receipt ID', [
                    'po_id' => $invoice->po_id,
                    'receipt_id' => $receipt->id,
                ]);
            }

            DB::commit();
            Log::info('Transaction Committed Successfully', ['invoice_id' => $invoice->id]);

            return response()->json([
                'success' => true,
                'message' => 'Payment applied successfully.',
                'data' => [
                    'invoiceNumber' => $invoice->invoice_number,
                    'remainingBalance' => $remainingBalance,
                    'status' => $status,
                    'receiptNumber' => $receiptNumber,
                ],
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Transaction Rolled Back', [
                'error' => $e->getMessage(),
                'stack_trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to apply payment.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
