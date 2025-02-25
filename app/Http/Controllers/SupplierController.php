<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log; // Make sure to include this for logging
use App\Services\SupplierAIService;
use Illuminate\Support\Facades\Cache;

class SupplierController extends Controller
{
    protected $aiService;

    public function __construct(SupplierAIService $aiService)
    {
        $this->aiService = $aiService;
    }

    public function analyzeSuppliers()
    {
        // Get the authenticated vendor
        $vendor = auth('vendor')->user();

        // Check if vendor is authenticated
        if (!$vendor) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Build a cache key based on the vendor ID.
        // You might choose to include a version number if your logic changes.
        $cacheKey = "vendor_{$vendor->id}_supplier_analysis";

        // Retrieve the analysis from cache or generate it if not present.
        $analysis = Cache::remember($cacheKey, now()->addMinutes(30), function () use ($vendor) {
            // Retrieve supplier performance data only for the authenticated vendor
            $supplier = DB::table('supplier_performance')
                ->join('vendors', 'supplier_performance.vendor_id', '=', 'vendors.id')
                ->where('supplier_performance.vendor_id', $vendor->id)
                ->select(
                    'vendors.company_name',
                    'supplier_performance.on_time_delivery_rate',
                    'supplier_performance.avg_delivery_delay',
                    'supplier_performance.defect_rate',
                    'supplier_performance.return_rate',
                    'supplier_performance.churn_risk',
                    'supplier_performance.cost_variance',
                    'supplier_performance.response_time',
                    'supplier_performance.issue_resolution_time',
                    'supplier_performance.compliance_score'
                )
                ->first();

            // Check if the vendor has performance data
            if (!$supplier) {
                abort(404, 'No performance data found');
            }

            // Prepare supplier data for AI analysis
            $supplierData = [
                'company_name'          => $supplier->company_name,
                'on_time_delivery_rate' => $supplier->on_time_delivery_rate,
                'avg_delivery_delay'    => $supplier->avg_delivery_delay,
                'defect_rate'           => $supplier->defect_rate,
                'return_rate'           => $supplier->return_rate,
                'churn_risk'            => $supplier->churn_risk,
                'cost_variance'         => $supplier->cost_variance,
                'response_time'         => $supplier->response_time,
                'issue_resolution_time' => $supplier->issue_resolution_time,
                'compliance_score'      => $supplier->compliance_score,
            ];

            // Custom prompt for AI analysis
            $customPrompt = "Analyze the performance metrics for {$supplier->company_name}. Provide insights on improving efficiency, reliability, and customer satisfaction. Strategize how to outperform competitors in key areas. Ensure the response is a well-structured paragraph with human-like explanations. Additionally, based on current trends, predict the company's future performance and suggest proactive strategies to stay competitive. Do not mention any numbers ";

            // Call the AI service to analyze the supplier performance data
            $aiResponse = $this->aiService->analyzeSupplierPerformance($supplierData, $customPrompt);

            // Return the combined analysis data
            return [
                'supplier'   => $supplierData,
                'ai_insights' => $aiResponse,
            ];
        });

        // Return the cached (or freshly generated) analysis result
        return response()->json($analysis);
    }
}
