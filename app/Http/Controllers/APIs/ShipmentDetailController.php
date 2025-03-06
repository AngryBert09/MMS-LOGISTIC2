<?php


namespace App\Http\Controllers\APIs;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;


class ShipmentDetailController extends Controller
{

    // public function __construct()
    // {
    //     // Apply Sanctum authentication to all methods
    //     $this->middleware('auth:sanctum');
    // }

    public function index()
    {
        $shipments = DB::table('shipment_details')->get();
        return $this->transformToCamelCase($shipments);
    }

    public function store(Request $request)
    {
        // Validate the request data
        $request->validate([
            'poId' => 'required|string|max:255',
            'shipmentStatus' => 'required|string|in:In Transit,Pending,Delivered,Delayed',
            'shippingAddress' => 'required|string|max:500',
            'shipmentMethod' => 'required|string|in:Standard,Express,Overnight',
            'weight' => 'required|numeric|min:0',
            'actualDeliveryDate' => 'nullable|date',
        ]);

        // Generate a tracking number
        $date = now()->format('Ymd'); // YYYYMMDD
        $randomSuffix = Str::upper(Str::random(4));
        $trackingNumber = 'TRK-' . $date . '-' . $randomSuffix;

        // Define the origin and destination

        $destination = $request->shippingAddress;

        // Call the distance matrix API
        try {
            $response = Http::get('https://maps.gomaps.pro/maps/api/distancematrix/json', [
                'origins' => env('SHIPPING_ORIGIN'),
                'destinations' => $destination,
                'key' => env('GOMAPS_API_KEY'),
            ]);

            if (!$response->successful()) {
                throw new \Exception('Failed to fetch distance data.');
            }

            $data = $response->json();
        } catch (\Exception $e) {
            Log::error('Distance Matrix API failed: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to calculate shipping cost.'], 500);
        }

        // Set defaults
        $estimatedDeliveryDate = now()->addDay();
        $shippingCost = 0;

        if ($response->successful()) {
            $data = $response->json();

            // Calculate estimated delivery date based on duration
            if (isset($data['rows'][0]['elements'][0]['duration']['value'])) {
                $durationSeconds = $data['rows'][0]['elements'][0]['duration']['value'];
                $estimatedDeliveryDate = now()->addSeconds($durationSeconds);
            }

            // Calculate shipping cost based on distance
            if (isset($data['rows'][0]['elements'][0]['distance']['value'])) {
                $distanceMeters = $data['rows'][0]['elements'][0]['distance']['value'];
                $distanceKm = $distanceMeters / 1000;

                // Determine the shipping rate based on shipping method
                $shippingMethod = $request->shipmentMethod;
                switch (strtolower($shippingMethod)) {
                    case 'standard':
                        $rate = 0.5;
                        break;
                    case 'express':
                        $rate = 1.0;
                        break;
                    case 'overnight':
                        $rate = 1.5;
                        break;
                    default:
                        $rate = 0.75;
                        break;
                }

                $shippingCost = $distanceKm * $rate;
            }
        }

        // Insert the shipment details into the database
        $id = DB::table('shipment_details')->insertGetId([
            'shipment_id' => $request->shipmentId, // Use your custom primary key column
            'po_id' => $request->poId,
            'carrier_name' => null,
            'rider_name' => null,
            'tracking_number' => $trackingNumber,
            'shipment_status' => $request->shipmentStatus,
            'estimated_delivery_date' => $estimatedDeliveryDate,
            'actual_delivery_date' => $request->actualDeliveryDate,
            'shipping_address' => $request->shippingAddress,
            'shipment_method' => $request->shipmentMethod,
            'shipping_cost' => $shippingCost,
            'weight' => $request->weight,
        ]);

        // Retrieve the newly created shipment
        $shipmentDetail = DB::table('shipment_details')
            ->where('shipment_id', $id) // Use your custom primary key column
            ->first();

        return $this->transformToCamelCase($shipmentDetail);
    }

    public function show($id)
    {
        $shipmentDetail = DB::table('shipment_details')
            ->where('shipment_id', $id) // Use your custom primary key column
            ->first();
        return $this->transformToCamelCase($shipmentDetail);
    }

    public function update(Request $request, $id)
    {
        // Validate the request data
        $request->validate([
            'poId' => 'required|string',
            'carrierName' => 'nullable|string',
            'riderName' => 'nullable|string',
            'trackingNumber' => 'required|string',
            'shipmentStatus' => 'required|string',
            'estimatedDeliveryDate' => 'required|date',
            'actualDeliveryDate' => 'nullable|date',
            'shippingAddress' => 'required|string',
            'shipmentMethod' => 'required|string',
            'shippingCost' => 'required|numeric',
            'weight' => 'required|numeric',
        ]);

        // Update the shipment details
        DB::table('shipment_details')
            ->where('shipment_id', $id) // Use shipment_id as the primary key
            ->update([
                'po_id' => $request->poId,
                'carrier_name' => $request->carrierName,
                'rider_name' => $request->riderName,
                'tracking_number' => $request->trackingNumber,
                'shipment_status' => $request->shipmentStatus,
                'estimated_delivery_date' => $request->estimatedDeliveryDate,
                'actual_delivery_date' => $request->actualDeliveryDate,
                'shipping_address' => $request->shippingAddress,
                'shipment_method' => $request->shipmentMethod,
                'shipping_cost' => $request->shippingCost,
                'weight' => $request->weight,
            ]);

        // Retrieve the updated shipment
        $shipmentDetail = DB::table('shipment_details')
            ->where('shipment_id', $id) // Use shipment_id as the primary key
            ->first();

        return $this->transformToCamelCase($shipmentDetail);
    }

    public function destroy($id)
    {
        DB::table('shipment_details')->where('shipment_id', $id)->delete();
        return response()->json(null, 204);
    }

    public function calculateShippingCost(Request $request)
    {
        $request->validate([
            'destination' => 'required|string',
        ]);


        $destination = $request->input('destination');
        $apiKey = env('GOMAPS_API_KEY');

        // Call Distance Matrix API
        try {
            $response = Http::get('https://maps.gomaps.pro/maps/api/distancematrix/json', [
                'origins' => env('SHIPPING_ORIGIN'),
                'destinations' => $destination,
                'key' => env('GOMAPS_API_KEY'),
            ]);

            if (!$response->successful()) {
                throw new \Exception('Failed to fetch distance data.');
            }

            $data = $response->json();
        } catch (\Exception $e) {
            Log::error('Distance Matrix API failed: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to calculate shipping cost.'], 500);
        }

        $data = $response->json();

        // Check if API response is valid
        if ($response->successful() && isset($data['rows'][0]['elements'][0]['distance']['value'])) {
            $distanceInMeters = $data['rows'][0]['elements'][0]['distance']['value'];
            $distanceInKm = $distanceInMeters / 1000; // Convert meters to km

            // Define cost per km
            $costPerKm = 2;
            $shippingCost = $distanceInKm * $costPerKm;

            return response()->json([
                'success' => true,
                'origin' => $origin,
                'destination' => $destination,
                'distanceKm' => round($distanceInKm, 2),
                'shippingCost' => round($shippingCost, 2)
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Unable to fetch distance data'
        ], 400);
    }

    private function transformToCamelCase($data)
    {
        $transform = function ($item) {
            return [
                'shipmentId' => $item->shipment_id,
                'poId' => $item->po_id,
                'carrierName' => $item->carrier_name,
                'riderName' => $item->rider_name,
                'trackingNumber' => $item->tracking_number,
                'shipmentStatus' => $item->shipment_status,
                'estimatedDeliveryDate' => $item->estimated_delivery_date,
                'actualDeliveryDate' => $item->actual_delivery_date,
                'shippingAddress' => $item->shipping_address,
                'shipmentMethod' => $item->shipment_method,
                'shippingCost' => $item->shipping_cost,
                'weight' => $item->weight,
            ];
        };

        if ($data instanceof \Illuminate\Support\Collection) {
            return $data->map($transform);
        } elseif (is_array($data)) {
            return array_map($transform, $data);
        } else {
            return $transform($data);
        }
    }
}
