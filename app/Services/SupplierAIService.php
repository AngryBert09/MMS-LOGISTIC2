<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class SupplierAIService
{
    protected $client;
    protected $apiKey;
    protected $baseUrl;

    public function __construct()
    {
        $this->client = new Client();
        $this->apiKey = env('GEMINI_API_KEY'); // Ensure this is set in your .env file
        $this->baseUrl = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent'; // Correct Gemini API URL
    }

    public function analyzeSupplierPerformance($supplierData)
    {
        // Prepare the request payload for Gemini AI
        $payload = [
            'contents' => [
                [
                    'parts' => [
                        ['text' => 'Please analyze the following data about our suppliers and provide any insights or recommendations for improving their performance summarize each vendor: ' . json_encode($supplierData)]
                    ]
                ]
            ]
        ];


        // Log the request payload for debugging
        Log::debug('Sending request to Gemini AI:', ['payload' => $payload]);

        try {
            // Make the API request to Gemini AI
            $response = $this->client->post($this->baseUrl . '?key=' . $this->apiKey, [
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
                'json' => $payload,
            ]);

            // Log the full response for debugging
            $responseBody = $response->getBody()->getContents();
            Log::debug('Gemini AI Response:', ['response' => $responseBody]);

            // Decode the response
            $responseData = json_decode($responseBody, true);

            // Check if the response contains the expected structure
            if (isset($responseData['candidates'][0]['content']['parts'][0]['text'])) {
                return $responseData['candidates'][0]['content']['parts'][0]['text'];
            } else {
                Log::error('Malformed AI response: ' . $responseBody);
                return 'No insights available from Gemini AI';
            }
        } catch (\Exception $e) {
            // Log any errors for debugging
            Log::error('Error while communicating with Gemini AI: ' . $e->getMessage());
            return 'Error: ' . $e->getMessage();
        }
    }
}
