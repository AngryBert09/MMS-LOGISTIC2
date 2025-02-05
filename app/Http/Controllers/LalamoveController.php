<?php

namespace App\Http\Controllers;

use App\Services\LalamoveService;
use Illuminate\Http\Request;

class LalamoveController extends Controller
{
    protected $lalamoveService;

    public function __construct(LalamoveService $lalamoveService)
    {
        $this->lalamoveService = $lalamoveService;
    }

    public function createOrderForm()
    {
        return view('lalamove.create-order');
    }

    public function createOrder(Request $request)
    {
        $data = $request->validate([
            'pickup_location' => 'required|string',
            'dropoff_location' => 'required|string',
            'item_description' => 'required|string',
        ]);

        $orderData = [
            'serviceType' => 'MOTORCYCLE',
            'specialRequests' => [],
            'stops' => [
                [
                    'location' => $data['pickup_location'],
                    'type' => 'PICKUP',
                ],
                [
                    'location' => $data['dropoff_location'],
                    'type' => 'DROPOFF',
                ],
            ],
            'itemDescription' => $data['item_description'],
        ];

        $response = $this->lalamoveService->createOrder($orderData);

        if (isset($response['error'])) {
            return back()->withErrors(['error' => $response['error']]);
        }

        return redirect()->route('lalamove.order.status', ['orderId' => $response['orderId']]);
    }

    public function getOrderStatus($orderId)
    {
        $status = $this->lalamoveService->getOrderStatus($orderId);

        return view('lalamove.order-status', compact('status'));
    }
}
