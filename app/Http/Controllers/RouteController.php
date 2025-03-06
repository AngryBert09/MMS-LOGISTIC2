<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class RouteController extends Controller
{
    // Show the form to input start and end locations
    public function showForm(Request $request)
    {
        $origin = $request->query('origin');
        $destination = $request->query('destination');

        return view('route-form', compact('origin', 'destination'));
    }


    // Handle the form submission and fetch the route
    public function getRoute(Request $request)
    {
        // Validate the input
        $request->validate([
            'start_lat' => 'required|numeric',
            'start_lng' => 'required|numeric',
            'end_lat' => 'required|numeric',
            'end_lng' => 'required|numeric',
        ]);

        // Get the coordinates from the form
        $startLat = $request->input('start_lat');
        $startLng = $request->input('start_lng');
        $endLat = $request->input('end_lat');
        $endLng = $request->input('end_lng');

        // Call the OpenRouteService API
        $apiKey = '5b3ce3597851110001cf62483b8096778a2d4ee3a369f14ba7d6e8f7';
        $url = "https://api.openrouteservice.org/v2/directions/driving-car?api_key=$apiKey&start=$startLng,$startLat&end=$endLng,$endLat";

        $response = Http::withHeaders([
            'Accept' => 'application/json, application/geo+json, application/gpx+xml, img/png; charset=utf-8',
        ])->get($url);

        // Decode the response
        $routeData = $response->json();

        // Pass the route data and coordinates to the view
        return view('route-result', [
            'routeData' => $routeData,
            'startLat' => $startLat,
            'startLng' => $startLng,
            'endLat' => $endLat,
            'endLng' => $endLng,
        ]);
    }
}
