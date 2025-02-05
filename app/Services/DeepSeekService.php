<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class DeepSeekService
{
    public function getAdjustedDeliveryTime($deliveryData)
    {
        // Extract user input
        $distance = $deliveryData['distance'];
        $trafficConditions = $deliveryData['traffic_conditions'];
        $weatherConditions = $deliveryData['weather_conditions'];

        // Simulate delivery time adjustment logic
        $baseTime = 30; // Base delivery time in minutes
        $adjustedTime = $baseTime;

        // Adjust based on distance
        $adjustedTime += $distance * 2; // 2 minutes per mile

        // Adjust based on traffic conditions
        if ($trafficConditions === 'moderate') {
            $adjustedTime += 10;
        } elseif ($trafficConditions === 'high') {
            $adjustedTime += 20;
        }

        // Adjust based on weather conditions
        if ($weatherConditions === 'rainy') {
            $adjustedTime += 15;
        } elseif ($weatherConditions === 'snowy') {
            $adjustedTime += 30;
        }

        // Simulate API call delay
        sleep(2); // Simulate a 2-second delay

        return $adjustedTime . ' minutes';
    }
}
