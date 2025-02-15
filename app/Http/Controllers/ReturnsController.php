<?php

namespace App\Http\Controllers;

use App\Models\Returns; // Assuming the model is named Return
use Illuminate\Http\Request;

class ReturnsController extends Controller
{
    // Display a listing of the returns.
    public function index()
    {
        $returns = Returns::all();
        return view('vendors.Returns.index-returns', compact('returns'));
    }

    // Show the form for creating a new return.
    public function create()
    {
        return view('returns.create');
    }

    // Store a newly created return in storage.
    public function store(Request $request)
    {
        $request->validate([
            'quantity_return' => 'required|integer|min:1',
            'return_reason' => 'required|string|max:255',
            'return_status' => 'required|in:Pending,Approved,Rejected,Processed',
            'return_date' => 'required|date',
        ]);

        Returns::create($request->all());

        return redirect()->route('returns.index')->with('success', 'Return created successfully.');
    }

    // Display the specified return.
    public function show(Returns $return)
    {
        return view('returns.show', compact('return'));
    }

    // Show the form for editing the specified return.
    public function edit(Returns $return)
    {
        return view('returns.edit', compact('return'));
    }

    // Update the specified return in storage.
    public function update(Request $request, Returns $return)
    {
        $request->validate([
            'return_status' => 'required|in:Pending,Approved,Rejected,Processed',
        ]);

        $return->update(['return_status' => $request->return_status]);

        return response()->json(['message' => 'Return status updated successfully.']); // ✅ Ensure JSON response
    }




    // Remove the specified return from storage.
    public function destroy(Returns $return)
    {
        $return->delete();

        return redirect()->route('returns.index')->with('success', 'Return deleted successfully.');
    }
}
