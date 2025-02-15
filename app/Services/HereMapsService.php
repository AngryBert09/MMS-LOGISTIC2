<?php

namespace App\Services;

use GuzzleHttp\Client;

class HereMapsService
{
    protected $client;
    protected $apiKey;

    public function __construct()
    {
        $this->client = new Client();
        $this->apiKey = env('HERE_MAPS_API_KEY');
    }

    /**
     * Geocode the address to get latitude and longitude
     *
     * @param string $address
     * @return array|null
     */
    public function geocodeAddress($address)
    {
        $url = "https://geocode.search.hereapi.com/v1/geocode";
        $response = $this->client->get($url, [
            'query' => [
                'q' => $address,
                'apiKey' => $this->apiKey,
            ],
        ]);

        $data = json_decode($response->getBody(), true);

        // Check if there are results and return the latitude and longitude of the first result
        if (!empty($data['items'][0]['position'])) {
            return $data['items'][0]['position']; // ['lat' => latitude, 'lng' => longitude]
        }

        return null; // No valid address found
    }

    /**
     * Calculate the route from the origin address to the destination address
     *
     * @param string $origin
     * @param string $destination
     * @return array|null
     */
    public function calculateRoute($origin, $destination)
    {
        // Geocode the origin and destination addresses to get latitude and longitude
        $originCoordinates = $this->geocodeAddress($origin);
        $destinationCoordinates = $this->geocodeAddress($destination);

        if ($originCoordinates === null || $destinationCoordinates === null) {
            return null; // Unable to geocode one or both addresses
        }

        $originLatLng = $originCoordinates['lat'] . ',' . $originCoordinates['lng'];
        $destinationLatLng = $destinationCoordinates['lat'] . ',' . $destinationCoordinates['lng'];

        // Calculate the route using HERE Maps Router API
        $url = "https://router.hereapi.com/v8/routes";
        $response = $this->client->get($url, [
            'query' => [
                'transportMode' => 'car',
                'origin' => $originLatLng,
                'destination' => $destinationLatLng,
                'return' => 'travelSummary',
                'apiKey' => $this->apiKey,
            ],
        ]);

        return json_decode($response->getBody(), true);
    }
}
