<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;


class ShipmentController extends Controller
{
    public function getShipmentDetails($orderId)
    {
        // Fetch shipment details using DB raw query
        $shipment = DB::table('shipment_details')
            ->where('po_id', $orderId)
            ->first(); // Get the first matching record

        if ($shipment) {
            return response()->json([
                'success' => true,
                'data' => $shipment
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Shipment details not found'
            ]);
        }
    }

    public function index()
    {
        // Get the authenticated vendor's ID using the vendor guard
        $vendorId = Auth::guard('vendor')->id();

        // Fetch only shipments that belong to the authenticated vendor
        $shipments = DB::table('shipment_details')
            ->where('vendor_id', $vendorId)
            ->get();

        return view('vendors.Deliveries.index', compact('shipments'));
    }

    public function assignRider(Request $request)
    {
        // Validate input
        $request->validate([
            'shipment_id' => 'required|exists:shipment_details,shipment_id',
            'rider_name' => 'required|string|max:255',
        ]);

        try {
            // Update shipment with assigned rider name
            DB::table('shipment_details')
                ->where('shipment_id', $request->shipment_id)
                ->update([
                    'rider_name' => $request->rider_name
                ]);

            return response()->json(['success' => true, 'message' => 'Rider assigned successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to assign rider'], 500);
        }
    }
}
