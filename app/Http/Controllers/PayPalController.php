<?php

namespace App\Http\Controllers;

use Srmklive\PayPal\Services\PayPal as PayPalClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PayPalController extends Controller
{
    protected $paypal;

    public function __construct()
    {
        $this->paypal = new PayPalClient();

        // Attempt to set API credentials and log the output
        $credentialsResult = $this->paypal->setApiCredentials(config('paypal'));
        Log::debug("setApiCredentials() returned: ", ['result' => $credentialsResult]);

        try {
            // Attempt to retrieve access token and log the response
            $accessTokenResponse = $this->paypal->getAccessToken();
            Log::debug("getAccessToken() response: ", ['response' => $accessTokenResponse]);

            // Extract access token
            $accessToken = $accessTokenResponse['access_token'] ?? null;

            if ($accessToken) {
                Log::info("Access Token Retrieved: " . $accessToken);
            } else {
                Log::error("Failed to retrieve access token: access_token key not found.");
                return redirect()->route('login')->with('error', 'Failed to retrieve PayPal access token.');
            }
        } catch (\Exception $e) {
            Log::error("Failed to retrieve access token: " . $e->getMessage());
            return redirect()->route('login')->with('error', 'Failed to connect to PayPal.');
        }
    }

    // Initiate PayPal Payment
    public function checkout()
    {
        // Use the pre-initialized $this->paypal
        $order = $this->paypal->createOrder([
            "intent" => "CAPTURE",
            "purchase_units" => [
                [
                    "amount" => [
                        "currency_code" => "USD",
                        "value" => "25.00"
                    ]
                ]
            ],
            "application_context" => [
                "cancel_url" => route('paypal.cancel'),
                "return_url" => route('paypal.confirm')
            ]
        ]);

        if (isset($order['id']) && $order['status'] === 'CREATED') {
            // Redirect to PayPal approval URL
            foreach ($order['links'] as $link) {
                if ($link['rel'] === 'approve') {
                    return redirect()->away($link['href']);
                }
            }
        }

        return redirect()->route('paypal.checkout')->with('error', 'Something went wrong.');
    }

    // Confirm Payment
    public function confirm(Request $request)
    {
        $orderID = $request->query('token'); // PayPal returns 'token' as the order ID

        $result = $this->paypal->capturePaymentOrder($orderID);

        if (isset($result['status']) && $result['status'] === 'COMPLETED') {
            // Handle successful payment
            return redirect()->route('home')->with('success', 'Payment successful.');
        } else {
            return redirect()->route('paypal.checkout')->with('error', 'Payment failed.');
        }
    }
}
