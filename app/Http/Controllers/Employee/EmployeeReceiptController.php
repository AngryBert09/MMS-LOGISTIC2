<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PurchaseReceipt;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class EmployeeReceiptController extends Controller
{
    public function show($invoice_id)
    {
        // Log the received invoice_id for debugging
        Log::debug('Received invoice_id:', ['invoice_id' => $invoice_id]);

        // Fetch the receipt based on the invoice_id
        $receipt = PurchaseReceipt::with(['vendor', 'invoice', 'purchaseOrder'])
            ->where('invoice_id', $invoice_id)
            ->first(); // Use first() to find the first matching receipt

        // Check if the receipt is found
        if ($receipt) {
            // Log the found receipt for debugging
            Log::debug('Receipt found:', ['receipt' => $receipt]);

            return view('employee.receipts.index', compact('receipt'));
        } else {
            // Log the error if no receipt is found
            Log::error('Receipt not found for invoice_id:', ['invoice_id' => $invoice_id]);

            // Optionally, return a custom error page or message
            abort(404, 'Receipt not found');
        }
    }
}
