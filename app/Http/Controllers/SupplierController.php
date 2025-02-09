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
        // Retrieve supplier performance data from the database
        $suppliers = DB::table('supplier_performance')
            ->select('vendor_id', 'on_time_delivery_rate', 'avg_delivery_delay', 'defect_rate', 'return_rate', 'churn_risk')
            ->orderBy('churn_risk', 'DESC')
            ->get();

        // Log the retrieved supplier data for debugging
        Log::debug('Retrieved Supplier Data:', ['suppliers' => $suppliers]);

        // Convert the collection of suppliers into an array for easier handling by the AI service
        $supplierData = $suppliers->map(function ($supplier) {
            return [
                'vendor_id' => $supplier->vendor_id,
                'on_time_delivery_rate' => $supplier->on_time_delivery_rate,
                'avg_delivery_delay' => $supplier->avg_delivery_delay,
                'defect_rate' => $supplier->defect_rate,
                'return_rate' => $supplier->return_rate,
                'churn_risk' => $supplier->churn_risk,
            ];
        })->toArray();

        // Log the transformed supplier data for debugging
        Log::debug('Transformed Supplier Data:', ['supplierData' => $supplierData]);

        // Send the supplier data to the AI service for analysis
        $aiResponse = $this->aiService->analyzeSupplierPerformance($supplierData);

        // Log the AI response for debugging
        Log::debug('AI Response:', ['aiResponse' => $aiResponse]);

        // Return the suppliers' data and the AI's insights as a JSON response
        return response()->json([
            'suppliers' => $suppliers,
            'ai_insights' => $aiResponse
        ]);
    }
}
