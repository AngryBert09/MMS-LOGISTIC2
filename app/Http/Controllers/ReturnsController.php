<?php

namespace App\Http\Controllers;

use App\Models\Returns; // Assuming the model is named Return
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ReturnsController extends Controller
{
    // Display a listing of the returns.
    public function index()
    {
        try {
            $apiKey = env('LOGISTIC1_API_KEY'); // Get API Key from .env

            $response = Http::withToken($apiKey)->get('https://logistic1.gwamerchandise.com/api/returns');

            if ($response->successful()) {
                $returns = $response->json(); // Get full API response (data, links, meta)
            } else {
                Log::error('Failed to fetch returns from API', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                $returns = ['data' => []]; // Default structure to avoid errors
            }
        } catch (\Exception $e) {
            Log::error('API Request Failed', ['error' => $e->getMessage()]);
            $returns = ['data' => []]; // Default structure
        }

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
    public function update(Request $request, $returnId)
    {
        $request->validate([
            'status' => 'required|in:Approved,Rejected',
        ]);

        $response = Http::put("https://logistic1.gwamerchandise.com/api/returns/{$returnId}", [
            'return_status' => $request->status,
        ]);

        if ($response->successful()) {
            return response()->json(['message' => 'Return status updated successfully'], 200);
        }

        return response()->json(['message' => 'Failed to update return status'], $response->status());
    }




    // Remove the specified return from storage.
    public function destroy(Returns $return)
    {
        $return->delete();

        return redirect()->route('returns.index')->with('success', 'Return deleted successfully.');
    }
}
