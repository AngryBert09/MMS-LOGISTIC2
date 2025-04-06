<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Vendor;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class AdminBiddingController extends Controller
{

    public function index()
    {
        // Fetch all bidding details from the database
        $biddings = \App\Models\BiddingDetail::all();

        // Return the view with bidding details data
        return view('admin.vendors.biddings-overview', compact('biddings'));
    }

    public function getVendorBids($id)
    {
        Log::info("📡 Fetching vendor bids for Bidding ID: $id");

        try {
            // Verify if the bidding exists
            $bidding = DB::table('bidding_details')->where('id', $id)->first();

            if (!$bidding) {
                Log::warning("⚠️ Bidding not found with ID: $id");
                return response()->json(['error' => 'Bidding not found'], 404);
            }

            Log::info("✅ Bidding found", ['bidding' => $bidding]);

            // Fetch vendor bids
            $vendorBids = DB::table('vendor_bids')
                ->join('vendors', 'vendor_bids.vendor_id', '=', 'vendors.id')
                ->where('vendor_bids.bidding_id', $id)
                ->select(
                    'vendor_bids.bid_amount',
                    'vendor_bids.comments',
                    'vendor_bids.shortname',
                    'vendor_bids.vendor_id',
                    'vendors.company_name'
                )
                ->get();

            if ($vendorBids->isEmpty()) {
                Log::info("⚠️ No vendor bids found for Bidding ID: $id");
            } else {
                Log::info("✅ Vendor bids found", ['vendorBids' => $vendorBids]);
            }

            return response()->json([
                'vendorBids' => $vendorBids
            ], 200);
        } catch (\Exception $e) {
            Log::error("🚨 Error fetching vendor bids for Bidding ID: $id", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => 'An unexpected error occurred'], 500);
        }
    }
}
