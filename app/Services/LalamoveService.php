<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class LalamoveService
{
    protected $client;
    protected $apiKey;
    protected $apiSecret;
    protected $baseUrl;

    public function __construct()
    {
        $this->apiKey = config('services.lalamove.api_key');
        $this->apiSecret = config('services.lalamove.api_secret');
        $this->baseUrl = config('services.lalamove.base_url');

        // Debugging: Log the base URL
        Log::info('Lalamove Base URL: ' . $this->baseUrl);

        $this->client = new Client([
            'base_uri' => $this->baseUrl,
            'headers' => [
                'Authorization' => 'Bearer ' . $this->generateToken(),
                'Content-Type' => 'application/json',
            ],
        ]);
    }

    protected function generateToken()
    {
        // Implement token generation logic here (if required by Lalamove API)
        return $this->apiSecret; // Placeholder
    }

    public function createOrder($data)
    {
        try {
            $url = '/v3/orders';
            Log::info('Lalamove Full URL: ' . $this->baseUrl . $url);

            $response = $this->client->post($url, [
                'json' => $data,
            ]);

            Log::info('Lalamove API Response: ' . $response->getBody());
            return json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            Log::error('Lalamove API Error: ' . $e->getMessage());
            return ['error' => $e->getMessage()];
        }
    }
}
