<?php


namespace App\Services;

use GuzzleHttp\Client;

class DeepSeekAIService
{
    protected $client;
    protected $apiKey;

    public function __construct()
    {
        $this->client = new Client();
        $this->apiKey = env('DEEPSEEK_AI_API_KEY');
    }

    public function predictDeliveryTime($data)
    {
        $url = "https://api.deepseek.ai/v1/predict";
        $response = $this->client->post($url, [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ],
            'json' => $data,
        ]);

        return json_decode($response->getBody(), true);
    }
}
