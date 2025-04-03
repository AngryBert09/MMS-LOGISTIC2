<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Invoice;
use Illuminate\Support\Facades\Log;

class AdminInvoiceController extends Controller
{
    public function index()
    {
        // Fetch all invoices with related data (e.g., purchase orders, vendors)
        $invoices = Invoice::all();


        // Pass invoices to the view
        return view('admin.vendors.invoices.index-invoice', compact('invoices'));
    }

    public function show($id)
    {

        $invoice = Invoice::findOrFail($id);

        return view('admin.vendors.invoices.view-invoice', compact('invoice'));
    }
}
