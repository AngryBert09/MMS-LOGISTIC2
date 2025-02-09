<?php

namespace App\Http\Controllers;

use App\Services\HereMapsService;
use App\Services\DeepSeekAIService;
use Illuminate\Http\Request;

class DeliveryController extends Controller
{
    protected $hereMapsService;
    protected $deepSeekAIService;

    public function __construct(HereMapsService $hereMapsService, DeepSeekAIService $deepSeekAIService)
    {
        $this->hereMapsService = $hereMapsService;
        $this->deepSeekAIService = $deepSeekAIService;
    }

    public function adjustDeliveryTime(Request $request)
    {
        // Get origin and destination from the request
        $origin = $request->input('origin');
        $destination = $request->input('destination');

        // Calculate route using HERE Maps
        $route = $this->hereMapsService->calculateRoute($origin, $destination);

        // Extract travel time from HERE Maps response
        $travelTime = $route['routes'][0]['sections'][0]['travelSummary']['duration'] ?? 0;

        // Prepare data for DeepSeek AI prediction
        $data = [
            'travel_time' => $travelTime,
            'traffic_conditions' => $request->input('traffic_conditions'),
            'weather_conditions' => $request->input('weather_conditions'),
        ];

        // Predict adjusted delivery time using DeepSeek AI
        $prediction = $this->deepSeekAIService->predictDeliveryTime($data);

        // Return the adjusted delivery time
        return response()->json([
            'original_travel_time' => $travelTime,
            'adjusted_delivery_time' => $prediction['predicted_time'],
        ]);
    }
}
