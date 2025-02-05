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

        // Get the authenticated vendor ID (assuming the vendor is logged in)
        $vendorId = auth()->user()->id; // Modify based on how the authenticated vendor's ID is stored

        // Validate the input fields
        $request->validate([
            'bid_amount' => 'required|numeric|min:1', // Ensure the bid amount is valid
            'comments' => 'nullable|string|max:1000', // Allow empty comments but limit length
        ]);

        // Check if a record for this vendor and bidding already exists in the vendor_bids table
        $existingBid = VendorBid::where('vendor_id', $vendorId)
            ->where('bidding_id', $bidding->id)
            ->first();

        if ($existingBid) {
            // If the vendor has already placed a bid, update it
            $existingBid->bid_amount = $request->input('bid_amount');
            $existingBid->comments = $request->input('comments');
            $existingBid->save();
        } else {
            // If no bid exists, create a new record in the vendor_bids table
            VendorBid::create([
                'vendor_id' => $vendorId,
                'bidding_id' => $bidding->id,
                'bid_amount' => $request->input('bid_amount'),
                'comments' => $request->input('comments'),
                'shortname' => 'Vendor' . $vendorId . '_Bid_' . $bidding->id, // Generate shortname based on vendor and bidding IDs
            ]);
        }

        // Redirect back to the bidding details page with a success message
        return redirect()->route('biddings.index')->with('success', 'Your bid has been updated successfully!');
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
