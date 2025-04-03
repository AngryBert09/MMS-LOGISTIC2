<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Vendor;
use App\Models\PurchaseOrder;

class AdminDashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard');
    }


    public function getRecentApprovedVendors()
    {
        // Fetch the 5 most recent vendors where 'status' is 'approved'
        $vendors = Vendor::where('status', 'approved')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return response()->json(['vendors' => $vendors]);
    }

    public function getTotalPurchaseOrders()
    {
        $totalOrders = PurchaseOrder::count(); // Get total number of purchase orders
        return response()->json(['total' => $totalOrders]); // Return JSON response
    }

    public function getVendorCount()
    {
        $vendorCount = \App\Models\Vendor::count();
        return response()->json(['total' => $vendorCount]);
    }

    public function getTotalBiddings()
    {
        $totalBiddings = \App\Models\BiddingDetail::count();
        return response()->json(['total' => $totalBiddings]);
    }

    public function getTotalEarnings()
    {
        $totalEarnings = \App\Models\Invoice::sum('total_amount'); // Adjust column name if needed
        return response()->json(['total' => $totalEarnings]);
    }

    public function countOngoingBiddings()
    {
        $ongoingCount = \App\Models\BiddingDetail::whereNotNull('vendor_id')
            ->where('vendor_id', '!=', 0)
            ->count();

        return response()->json(['ongoing' => $ongoingCount]);
    }
}
