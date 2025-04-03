<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BiddingDetail;

class EmployeeBiddingController extends Controller
{
    public function index()
    {
        // Fetch all bidding details where vendor_id is NULL
        $biddings = BiddingDetail::whereNull('vendor_id')->get();

        // Pass data to view
        return view('employee.biddings.index-biddings', compact('biddings'));
    }

    public function showVendors($id)
    {
        // Fetch the BiddingDetail along with only related VendorBids
        $biddingDetail = BiddingDetail::with('vendorBids')->findOrFail($id);

        return view('employee.biddings.offers', compact('biddingDetail'));
    }
}
