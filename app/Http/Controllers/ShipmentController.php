<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

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
}
