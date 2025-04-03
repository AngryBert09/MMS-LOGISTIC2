<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Vendor;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;
use App\Mail\VendorInvitationMail;
use App\Models\BiddingDetail;
use App\Models\VendorBid;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;


class AdminVendorController extends Controller
{

    public function index()
    {
        // Fetch all vendors from the database
        $vendors = Vendor::all();

        // Return the view with vendors data
        return view('admin.vendors.index', compact('vendors'));
    }


    public function inviteVendor(Request $request)
    {
        Log::info('Inviting vendor with data:', $request->all());

        $validated = $request->validate([
            'email' => 'required|email',
            'name' => 'required|string|max:255',
        ]);

        $combinedData = $validated['email'] . '|' . $validated['name'];
        $encryptedData = Crypt::encryptString($combinedData);
        $inviteLink = url('/register?data=' . urlencode($encryptedData));

        Log::debug('Generated invite link:', ['link' => $inviteLink]);

        try {
            Log::info('Attempting to send invitation email to:', ['email' => $validated['email']]);
            Mail::to($validated['email'])->send(new VendorInvitationMail($validated['name'], $inviteLink));

            Log::info('Invitation email sent successfully to:', ['email' => $validated['email']]);

            // Return a JSON response
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            Log::error('Failed to send invitation email:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    public function updateVendorStatus(Request $request, $id)
    {
        Log::info("Updating vendor status for ID: $id", ['new_status' => $request->status]);

        // Validate request
        $request->validate([
            'status' => 'required|in:Approved,Pending,Rejected,Banned',
        ]);

        try {
            // Find the vendor
            $vendor = Vendor::findOrFail($id);

            // Update the status
            $vendor->status = $request->status;
            $vendor->save();

            Log::info("Vendor ID $id status updated to: " . $vendor->status);

            return redirect()->back()->with('success', 'Vendor status updated successfully.');
        } catch (\Exception $e) {
            Log::error("Error updating vendor status: " . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to update vendor status.');
        }
    }

    public function getVendorList()
    {
        // Fetch only approved vendors with their supplier performance data
        $vendors = Vendor::leftJoin('supplier_performance', 'vendors.id', '=', 'supplier_performance.vendor_id')
            ->select(
                'vendors.*',
                'supplier_performance.on_time_delivery_rate',
                'supplier_performance.avg_delivery_delay',
                'supplier_performance.defect_rate',
                'supplier_performance.compliance_score'
            )
            ->where('vendors.status', 'Approved') // ✅ Only fetch approved vendors
            ->get();

        // Return the view with approved vendors
        return view('admin.vendors.vendor-list', compact('vendors'));
    }


    public function analyzeSupplierPerformance(Request $request)
    {
        $question = trim($request->input('question', ''));
        Log::info("🔍 Analyzing supplier performance for question: $question");

        // Fetch supplier performance data
        $suppliers = DB::table('supplier_performance')
            ->join('vendors', 'supplier_performance.vendor_id', '=', 'vendors.id')
            ->select(
                'vendors.company_name',
                'supplier_performance.on_time_delivery_rate',
                'supplier_performance.defect_rate',
                'supplier_performance.return_rate',
                'supplier_performance.cost_variance',
                'supplier_performance.response_time',
                'supplier_performance.issue_resolution_time',
                'supplier_performance.compliance_score',
                'supplier_performance.churn_risk'
            )
            ->get();

        if ($suppliers->isEmpty()) {
            return response()->json(['report' => 'No supplier performance data available.'], 404);
        }

        // Validate Question Relevance
        // $allowedKeywords = ['supplier', 'vendor', 'delivery', 'cost', 'compliance', 'quality', 'performance', 'efficiency', 'risk'];
        // $questionWords = explode(' ', strtolower($question));
        // $isRelevant = false;
        // foreach ($questionWords as $word) {
        //     if (in_array($word, $allowedKeywords)) {
        //         $isRelevant = true;
        //         break;
        //     }
        // }

        // if (!$isRelevant && !empty($question)) {
        //     return response()->json(['report' => '❌ The question is unrelated to supplier performance. Please ask a relevant question.'], 400);
        // }

        // Construct AI Prompt (Avoid Markdown Formatting)
        if (!empty($question)) {
            $prompt = "Provide a clear, structured business analysis based on the following supplier performance data.
        Answer this specific question: \"$question\".
        Ensure the response is written in plain text, avoiding Markdown symbols such as *, **, -, or lists.
        Maintain a professional and formal tone, using well-formed sentences and structured paragraphs.
        Here is the relevant supplier data for analysis: " . json_encode($suppliers, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        } else {
            $prompt = "Analyze the supplier performance data provided below.
        Identify key insights regarding supplier efficiency, reliability, cost-effectiveness, and compliance.
        Ensure the response is formatted in plain text with no Markdown symbols such as *, **, -, or numbered lists.
        The response should use well-formed sentences and structured paragraphs.
        Here is the data for analysis: " . json_encode($suppliers, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        }

        // Call Gemini AI API
        $geminiApiKey = env('GEMINI_API_KEY');
        if (!$geminiApiKey) {
            return response()->json(['report' => '❌ Gemini API key is missing.'], 500);
        }

        try {
            $client = new \GuzzleHttp\Client();
            $aiResponse = $client->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent", [
                'query' => ['key' => $geminiApiKey],
                'json' => [
                    'contents' => [['parts' => [['text' => $prompt]]]]
                ]
            ]);

            $aiData = json_decode($aiResponse->getBody(), true);
            $analysisText = $aiData['candidates'][0]['content']['parts'][0]['text'] ?? 'No analysis available.';

            // Ensure output is clean (extra safeguard)
            $analysisText = strip_tags($analysisText); // Removes unwanted HTML tags
            $analysisText = preg_replace('/\*\*(.*?)\*\*/', '$1', $analysisText); // Removes bold markdown
            $analysisText = preg_replace('/\*(.*?)\*/', '$1', $analysisText); // Removes italic markdown

            return response()->json(['report' => nl2br($analysisText)]);
        } catch (\Exception $e) {
            return response()->json(['report' => '❌ Error: ' . $e->getMessage()], 500);
        }
    }


    public function getVendorDetails($vendorId)
    {
        Log::info("Fetching vendor profile and performance details for vendor ID: {$vendorId}");

        // Fetch vendor profile
        $vendor = Vendor::find($vendorId);

        if (!$vendor) {
            Log::warning("Vendor not found for ID: {$vendorId}");

            if (request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vendor not found'
                ], 404);
            }

            return redirect()->back()->with('error', 'Vendor not found');
        }

        // Define a cache key
        $cacheKey = "vendor_{$vendorId}_performance";

        // Retrieve performance data from cache or database
        $supplierPerformance = Cache::remember($cacheKey, now()->addMinutes(15), function () use ($vendorId) {
            $data = DB::table('supplier_performance')
                ->where('vendor_id', $vendorId)
                ->get();

            if ($data->isEmpty()) {
                Log::warning("No supplier performance records found for vendor ID: {$vendorId}");
                return collect();
            }

            Log::info("Successfully retrieved " . $data->count() . " records for vendor ID: {$vendorId}");

            return $data->map(function ($supplier) {
                $supplier->on_time_delivery_rate = $supplier->on_time_delivery_rate <= 1 ? round($supplier->on_time_delivery_rate * 100) : round($supplier->on_time_delivery_rate);
                $supplier->defect_rate = $supplier->defect_rate <= 1 ? round($supplier->defect_rate * 100) : round($supplier->defect_rate);
                $supplier->return_rate = $supplier->return_rate <= 1 ? round($supplier->return_rate * 100) : round($supplier->return_rate);
                $supplier->compliance_score = $supplier->compliance_score <= 1 ? round($supplier->compliance_score * 100) : round($supplier->compliance_score);
                $supplier->response_time = $supplier->response_time;
                $supplier->churn_risk = $supplier->churn_risk;

                $supplier->overall_quality_metrics = round(
                    ($supplier->on_time_delivery_rate * 0.35) +
                        ((100 - $supplier->defect_rate) * 0.2) +
                        ((100 - $supplier->return_rate) * 0.15) +
                        ($supplier->compliance_score * 0.2) +
                        ((100 - $supplier->response_time) * 0.1)
                );

                $supplier->overall_rating = round(($supplier->overall_quality_metrics / 100) * 5, 1);
                return $supplier;
            });
        });

        // Return JSON response for API requests
        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'vendor' => $vendor,
                'performance' => $supplierPerformance
            ], 200, ['Content-Type' => 'application/json']);
        }

        // Return view for normal web requests
        return view('admin.vendors.vendor-profile', [
            'vendor' => $vendor,
            'performance' => $supplierPerformance
        ]);
    }
}
