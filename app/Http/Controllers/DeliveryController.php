<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\DeepSeekService;

class DeliveryController extends Controller
{
    protected $deepSeekService;

    public function __construct(DeepSeekService $deepSeekService)
    {
        $this->deepSeekService = $deepSeekService;
    }

    // Render the Blade view
    public function showDeliveryPage()
    {
        return view('deliver'); // Render the Blade view
    }

    // Adjust delivery time (for AJAX)
    public function adjustDeliveryTime(Request $request)
    {
        // Get user input
        $distance = $request->input('distance');
        $trafficConditions = $request->input('traffic_conditions');
        $weatherConditions = $request->input('weather_conditions');

        // Simulate DeepSeek AI integration
        $deliveryData = [
            'distance' => $distance,
            'traffic_conditions' => $trafficConditions,
            'weather_conditions' => $weatherConditions,
        ];

        // Get adjusted delivery time from DeepSeek AI (or dummy data)
        $adjustedTime = $this->deepSeekService->getAdjustedDeliveryTime($deliveryData);

        if ($adjustedTime) {
            return response()->json(['adjusted_time' => $adjustedTime]);
        } else {
            return response()->json(['error' => 'Failed to adjust delivery time'], 500);
        }
    }
}
