<?php

namespace App\Http\Controllers;

use App\Models\PurchaseReceipt;
use App\Models\Vendor;
use App\Models\Invoice;
use App\Models\PurchaseOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PurchaseReceiptController extends Controller
{
    // Display a listing of the receipts
    public function index()
    {
        $receipts = PurchaseReceipt::all(); // Retrieve only data from the PurchaseReceipt table
        return view('vendors.purchase-receipts.index-receipt', compact('receipts'));
    }


    // Show the form for creating a new receipt
    public function create()
    {
        $vendors = Vendor::all();
        $invoices = Invoice::all();
        $purchaseOrders = PurchaseOrder::all();
        return view('receipts.create', compact('vendors', 'invoices', 'purchaseOrders'));
    }

    // Store a newly created receipt in storage
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'vendor_id' => 'required|integer|exists:vendors,id',
            'invoice_id' => 'nullable|integer|exists:invoices,invoice_id',
            'po_id' => 'nullable|integer|exists:purchase_orders,po_id',
            'receipt_date' => 'required|date',
            'total_amount' => 'required|numeric',
            'tax_amount' => 'nullable|numeric',
            'payment_method' => 'nullable|string|max:50',
            'currency' => 'nullable|string|max:10',
            'status' => 'required|in:pending,completed,canceled',
            'notes' => 'nullable|string',
        ]);

        PurchaseReceipt::create($validatedData);

        return redirect()->route('receipts.index')->with('success', 'Receipt created successfully.');
    }

    // Display the specified receipt
    public function show($id)
    {
        $receipt = PurchaseReceipt::with(['vendor', 'invoice', 'purchaseOrder'])->findOrFail($id);
        return view('receipts.show', compact('receipt'));
    }

    // Show the form for editing the specified receipt
    public function edit($id)
    {
        $receipt = PurchaseReceipt::findOrFail($id);
        $vendors = Vendor::all();
        $invoices = Invoice::all();
        $purchaseOrders = PurchaseOrder::all();
        return view('receipts.edit', compact('receipt', 'vendors', 'invoices', 'purchaseOrders'));
    }

    // Update the specified receipt in storage
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'vendor_id' => 'required|integer|exists:vendors,id',
            'invoice_id' => 'nullable|integer|exists:invoices,invoice_id',
            'po_id' => 'nullable|integer|exists:purchase_orders,po_id',
            'receipt_date' => 'required|date',
            'total_amount' => 'required|numeric',
            'tax_amount' => 'nullable|numeric',
            'payment_method' => 'nullable|string|max:50',
            'currency' => 'nullable|string|max:10',
            'status' => 'required|in:pending,completed,canceled',
            'notes' => 'nullable|string',
        ]);

        $receipt = PurchaseReceipt::findOrFail($id);
        $receipt->update($validatedData);

        return redirect()->route('receipts.index')->with('success', 'Receipt updated successfully.');
    }

    // Remove the specified receipt from storage
    public function destroy($id)
    {
        $receipt = PurchaseReceipt::findOrFail($id);
        $receipt->delete();

        return redirect()->route('receipts.index')->with('success', 'Receipt deleted successfully.');
    }
}
