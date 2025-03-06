<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class DeliveriesController extends Controller
{
    public function getDeliveryLogin()
    {

        return view('deliveries.auth.login-deliveries');
    }

    public function authenticate(Request $request)
    {
        // Validate input
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Send authentication request to the API
        $response = Http::post('https://admin.gwamerchandise.com/api/auth', [
            'email' => $request->email,
            'password' => $request->password,
        ]);

        // Handle response
        if ($response->successful()) {
            $data = $response->json(); // Decode API response

            // Check if the user's role is "delivery"
            if ($data['user']['role'] === 'delivery') {
                // Store user data in session
                Session::put('user', [
                    'id' => $data['user']['id'],
                    'name' => $data['user']['name'],
                    'email' => $data['user']['email'],
                    'role' => $data['user']['role'],
                    'status' => $data['user']['status'],
                ]);

                // Redirect to dashboard deliveries page
                return redirect()->route('dashboard.deliveries')->with('success', 'Login successful');
            } else {
                // If the user is not a delivery role, deny access
                return redirect()->back()->with('error', 'Access denied. Only delivery personnel can log in.');
            }
        }

        // If authentication fails, redirect back with error message
        return redirect()->back()->with('error', 'Invalid credentials');
    }

    public function index()
    {
        // Log the start of fetching shipment details
        Log::info('Fetching shipment details from database');

        // Fetch shipments from the database
        $shipments = DB::table('shipment_details')->get();
        Log::info('Fetched shipments', ['count' => $shipments->count()]);

        // Fetch users from API
        Log::info('Fetching users from API: https://admin.gwamerchandise.com/api/users');
        $response = Http::get('https://admin.gwamerchandise.com/api/users');

        if ($response->successful()) {
            Log::info('API request successful');

            $usersArray = json_decode($response->body(), true);
            $users = $usersArray['users'] ?? [];
            Log::info('Fetched users from API', ['count' => count($users)]);

            // Filter delivery riders
            $deliveryRiders = collect($users)
                ->filter(fn($user) => strtolower($user['role'] ?? '') === 'delivery')
                ->values(); // Reset array keys

            Log::info('Filtered delivery riders', [
                'count' => $deliveryRiders->count(),
                'riders' => $deliveryRiders->toArray()
            ]);

            if ($deliveryRiders->isNotEmpty()) {
                // Assign riders in a round-robin way
                $riderIndex = 0;
                $totalRiders = $deliveryRiders->count();

                foreach ($shipments as $shipment) {
                    // Only assign riders to shipments with "Pending" status
                    if ($shipment->shipment_status !== 'Pending' && $shipment->shipment_status == 'In Transit') {
                        Log::info('Skipping non-pending shipment', [
                            'shipment_id' => $shipment->shipment_id,
                            'status' => $shipment->shipment_status
                        ]);
                        continue;
                    }

                    // Skip if rider is already assigned
                    if (!is_null($shipment->rider_id)) {
                        Log::info('Skipping shipment as rider is already assigned', [
                            'shipment_id' => $shipment->shipment_id,
                            'existing_rider_id' => $shipment->rider_id
                        ]);
                        continue;
                    }

                    $rider = $deliveryRiders[$riderIndex];

                    // Update shipment with rider details in the database
                    DB::table('shipment_details')
                        ->where('shipment_id', $shipment->shipment_id)
                        ->update([
                            'rider_id' => $rider['id'],
                            'rider_name' => $rider['name']
                        ]);

                    Log::info('Assigned rider to pending shipment', [
                        'shipment_id' => $shipment->shipment_id,
                        'rider_id' => $rider['id'],
                        'rider_name' => $rider['name']
                    ]);

                    // Move to the next rider (round-robin)
                    $riderIndex = ($riderIndex + 1) % $totalRiders;
                }
            } else {
                Log::warning('No delivery riders found.');
            }
        } else {
            Log::error('API request failed', ['status' => $response->status()]);
        }

        return view('deliveries.index-deliveries', compact('shipments'));
    }



    public function myDeliveries()
    {
        // Get the logged-in user
        $user = Session::get('user');

        // // Ensure the user is a delivery rider
        // if (!$user || $user['role'] !== 'delivery') {
        //     return redirect()->route('deliveries.auth.login-deliveries')->with('error', 'Unauthorized access.');
        // }

        // Fetch only shipments assigned to the logged-in rider
        $shipments = DB::table('shipment_details')
            ->where('rider_id', $user['id'])
            ->get();

        // Return the view with assigned shipments
        return view('deliveries.mydeliveries', compact('shipments'));
    }

    public function updateStatus(Request $request, $id)
    {
        // Validate the input
        $request->validate([
            'shipment_status' => 'required|string|in:Pending,In Transit,Delivered, Delayed'
        ]);

        try {
            // Check if shipment exists
            $shipment = DB::table('shipment_details')->where('shipment_id', $id)->first();

            if (!$shipment) {
                Log::error("Shipment ID {$id} not found.");
                return redirect()->back()->with('error', 'Shipment not found.');
            }

            // Update the shipment status
            DB::table('shipment_details')
                ->where('shipment_id', $id)
                ->update(['shipment_status' => $request->shipment_status]);

            // Log the update
            Log::info("Shipment ID {$id} status updated to {$request->shipment_status}");

            return redirect()->back()->with('success', 'Shipment status updated successfully!');
        } catch (\Exception $e) {
            Log::error("Failed to update shipment ID {$id}. Error: " . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to update shipment status.');
        }
    }



    public function logout(Request $request)
    {
        // Clear the session data
        Session::forget('user');

        // Invalidate session
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Redirect to login page with a success message
        return redirect()->route('deliveries.show')->with('success', 'Logged out successfully.');
    }
}
