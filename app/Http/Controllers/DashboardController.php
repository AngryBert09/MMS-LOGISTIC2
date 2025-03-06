<?php

namespace App\Http\Controllers;

use App\Mail\WelcomeEmail;
use App\Models\Comment;
use App\Models\Idea;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log; // Import Log facade
use Illuminate\Support\Facades\Cache;


class DashboardController extends Controller
{

   public function index()
    {
        // Retrieve the authenticated vendor
        $vendor = auth()->user(); // Get the authenticated user (vendor)

        // Fetch notifications associated with the authenticated vendor
        $notifications = $vendor->notifications; // Adjust this line if necessary

        // Pass both the notifications and the vendor's profile picture to the view
        return view('dashboard', [
            'notifications' => $notifications,
            'profile _pic' => $vendor->profile_pic // Include the profile picture
        ]);
    }

    public function getMyPerformance()
    {
        Log::info('Fetching supplier performance details for authenticated vendor.');

        // Get the authenticated user's ID
        $authVendorId = Auth::id();

        // Define a cache key for this vendor's performance data.
        $cacheKey = "vendor_{$authVendorId}_performance";

        // Retrieve the performance data from cache or process it if not cached.
        $supplierPerformance = Cache::remember($cacheKey, now()->addMinutes(15), function () use ($authVendorId) {
            // Fetch records where vendor_id matches the authenticated user's ID
            $data = DB::table('supplier_performance')
                ->where('vendor_id', $authVendorId)
                ->get();

            // Check if data exists
            if ($data->isEmpty()) {
                Log::warning("No supplier performance records found for vendor ID: " . $authVendorId);
                return collect(); // return an empty collection
            }

            Log::info("Successfully retrieved " . $data->count() . " records for vendor ID: " . $authVendorId);

            // Process data to format values properly
            return $data->map(function ($supplier) {
                // Ensure percentage values are correctly formatted
                $supplier->on_time_delivery_rate = $supplier->on_time_delivery_rate <= 1 ? round($supplier->on_time_delivery_rate * 100) : round($supplier->on_time_delivery_rate);
                $supplier->defect_rate = $supplier->defect_rate <= 1 ? round($supplier->defect_rate * 100) : round($supplier->defect_rate);
                $supplier->return_rate = $supplier->return_rate <= 1 ? round($supplier->return_rate * 100) : round($supplier->return_rate);
                $supplier->compliance_score = $supplier->compliance_score <= 1 ? round($supplier->compliance_score * 100) : round($supplier->compliance_score);
                $supplier->response_time = $supplier->response_time; // Assuming response time is in hours/minutes.
                $supplier->churn_risk = $supplier->churn_risk; // No modification

                // Calculate Overall Quality Metrics
                $supplier->overall_quality_metrics = round(
                    ($supplier->on_time_delivery_rate * 0.35) +  // 35% weight
                        ((100 - $supplier->defect_rate) * 0.2) +       // 20% weight (inverse of defect rate)
                        ((100 - $supplier->return_rate) * 0.15) +      // 15% weight (inverse of return rate)
                        ($supplier->compliance_score * 0.2) +          // 20% weight
                        ((100 - $supplier->response_time) * 0.1)       // 10% weight (inverse of response time)
                );

                // Calculate Overall Rating (out of 5 stars)
                $supplier->overall_rating = round(($supplier->overall_quality_metrics / 100) * 5, 1);
                return $supplier;
            });
        });

        // Return JSON response
        return response()->json([
            'success' => true,
            'data' => $supplierPerformance
        ]);
    }

    public function getTopSuppliers()
    {
        Log::info('Fetching and ranking top suppliers based on overall ratings.');

        // Define a cache key for top suppliers.
        $cacheKey = "top_suppliers";

        // Retrieve the top suppliers from cache or process if not cached.
        $supplierPerformance = Cache::remember($cacheKey, now()->addMinutes(15), function () {
            // Fetch all supplier performance records and join with vendors table
            $data = DB::table('supplier_performance')
                ->join('vendors', 'supplier_performance.vendor_id', '=', 'vendors.id')
                ->select(
                    'supplier_performance.*',
                    'vendors.company_name'
                )
                ->get();

            if ($data->isEmpty()) {
                Log::warning("No supplier performance records found.");
                return collect(); // Return an empty collection if no data exists
            }

            Log::info("Successfully retrieved " . $data->count() . " supplier records.");

            // Process and rank suppliers based on overall rating
            $processedData = $data->map(function ($supplier) {
                // Ensure percentage values are correctly formatted
                $supplier->on_time_delivery_rate = $supplier->on_time_delivery_rate <= 1 ? round($supplier->on_time_delivery_rate * 100) : round($supplier->on_time_delivery_rate);
                $supplier->defect_rate = $supplier->defect_rate <= 1 ? round($supplier->defect_rate * 100) : round($supplier->defect_rate);
                $supplier->return_rate = $supplier->return_rate <= 1 ? round($supplier->return_rate * 100) : round($supplier->return_rate);
                $supplier->compliance_score = $supplier->compliance_score <= 1 ? round($supplier->compliance_score * 100) : round($supplier->compliance_score);
                $supplier->response_time = $supplier->response_time; // Assuming it's already in a proper unit

                // Calculate Overall Quality Metrics
                $supplier->overall_quality_metrics = round(
                    ($supplier->on_time_delivery_rate * 0.35) +  // 35% weight
                        ((100 - $supplier->defect_rate) * 0.2) +       // 20% weight (inverse of defect rate)
                        ((100 - $supplier->return_rate) * 0.15) +      // 15% weight (inverse of return rate)
                        ($supplier->compliance_score * 0.2) +          // 20% weight
                        ((100 - $supplier->response_time) * 0.1)       // 10% weight (inverse of response time)
                );

                // Calculate Overall Rating (out of 5 stars)
                $supplier->overall_rating = round(($supplier->overall_quality_metrics / 100) * 5, 1);
                return $supplier;
            });

            // Sort suppliers by overall rating in descending order
            return $processedData->sortByDesc('overall_rating')->values();
        });

        // Return JSON response
        return response()->json([
            'success' => true,
            'data' => $supplierPerformance
        ]);
    }
}
