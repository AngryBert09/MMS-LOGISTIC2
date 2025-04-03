<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProcurementRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class ProcurementController extends Controller
{
    public function index()
    {
        // Fetch all procurement requests with status "Under Review"
        $procurementRequests = ProcurementRequest::where('status', 'Under Review')
            ->orderBy('created_at', 'desc')
            ->paginate(10); // Paginate results

        return view('employee.procurement.request-proc', compact('procurementRequests'));
    }


    public function store(Request $request)
    {
        Log::info('Store method called with request data: ', $request->all());

        $request->validate([
            'item_name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'starting_price' => 'required|numeric|min:0',
            'deadline' => 'required|date|after:today',
            'description' => 'required|string',
        ]);

        Log::info('Validation passed for request data.');

        ProcurementRequest::create([
            'item_name' => $request->item_name,
            'quantity' => $request->quantity,
            'starting_price' => $request->starting_price,
            'deadline' => $request->deadline,
            'description' => $request->description,
        ]);

        Log::info('Procurement request created successfully.');

        return redirect()->back()->with('success', 'Procurement request created successfully!');
    }

    public function myProcurements()
    {
        // Fetch all procurement requests
        $procurementRequests = ProcurementRequest::orderBy('created_at', 'desc')
            ->paginate(10); // Paginate results

        return view('employee.procurement.index-proc', compact('procurementRequests'));
    }
}
