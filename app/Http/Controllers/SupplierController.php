<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log; // Make sure to include this for logging
use App\Services\SupplierAIService;

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
                'supplier_performance.compliance_score',
            )
            ->first(); // ✅ Retrieves a single row instead of a collection

        // Check if the vendor has performance data
        if (!$supplier) {
            return response()->json(['error' => 'No performance data found'], 404);
        }

        // Prepare supplier data for AI analysis
        $supplierData = [
            'company_name' => $supplier->company_name,
            'on_time_delivery_rate' => $supplier->on_time_delivery_rate,
            'avg_delivery_delay' => $supplier->avg_delivery_delay,
            'defect_rate' => $supplier->defect_rate,
            'return_rate' => $supplier->return_rate,
            'churn_risk' => $supplier->churn_risk,
            'cost_variance' => $supplier->cost_variance,
            'response_time' => $supplier->response_time,
            'issue_resolution_time' => $supplier->issue_resolution_time,
            'compliance_score' => $supplier->compliance_score,
        ];

        // Custom prompt for AI analysis
        $customPrompt = "Analyze the performance metrics for {$supplier->company_name}. Provide insights on improving efficiency, reliability, and customer satisfaction. Strategize how to outperform competitors in key areas. Ensure the response is a well-structured paragraph with human-like explanations. Additionally, based on current trends, predict the company's future performance and suggest proactive strategies to stay competitive. Do not mention any numbers ";

        // Send data to AI service
        $aiResponse = $this->aiService->analyzeSupplierPerformance($supplierData, $customPrompt);

        // Return response
        return response()->json([
            'supplier' => $supplierData,
            'ai_insights' => $aiResponse
        ]);
    }
}
