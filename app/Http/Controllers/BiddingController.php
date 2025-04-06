<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BiddingDetail;
use App\Models\VendorBid;

class BiddingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Fetch all bidding details
        $biddings = BiddingDetail::all();

        // Return the view with bidding details
        return view('vendors.Biddings.index-biddings', compact('biddings'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id) {}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Fetch the bidding details by its ID
        $bidding = BiddingDetail::findOrFail($id);

        // Return the view with the bidding details
        return view('vendors.Biddings.edit-biddings', compact('bidding'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Find the bidding by ID
        $bidding = BiddingDetail::findOrFail($id);

        // Get the authenticated vendor ID
        $vendorId = auth()->user()->id;

        // Validate the input fields
        $request->validate([
            'bid_amount' => 'required|numeric|min:0.01', // Must be at least 1 cent
            'comments' => 'nullable|string|max:1000',
        ]);

        // Check if bidding is still open (no winner selected yet)
        if ($bidding->vendor_id) {
            return back()->withErrors(['bid_closed' => 'This bidding is already closed.']);
        }

        // Check if a record for this vendor and bidding already exists
        $existingBid = VendorBid::where('vendor_id', $vendorId)
            ->where('bidding_id', $bidding->id)
            ->first();

        try {
            if ($existingBid) {
                // Update existing bid
                $existingBid->update([
                    'bid_amount' => $request->input('bid_amount'),
                    'comments' => $request->input('comments'),
                    'updated_at' => now()
                ]);
            } else {
                // Create a new bid
                VendorBid::create([
                    'vendor_id' => $vendorId,
                    'bidding_id' => $bidding->id,
                    'bid_amount' => $request->input('bid_amount'),
                    'comments' => $request->input('comments'),
                    'shortname' => 'Vendor' . $vendorId . '_Bid_' . $bidding->id,
                ]);
            }

            return redirect()->route('biddings.index')
                ->with('success', 'Your bid has been ' . ($existingBid ? 'updated' : 'submitted') . ' successfully!');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to process your bid. Please try again.']);
        }
    }




    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
