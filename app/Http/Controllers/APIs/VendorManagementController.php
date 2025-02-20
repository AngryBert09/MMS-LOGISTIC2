<?php

namespace App\Http\Controllers\APIs;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VendorManagementController extends Controller
{
    public function __construct()
    {
        // Apply Sanctum authentication to all methods
        $this->middleware('auth:sanctum');
    }

    /**
     * Retrieve all vendors with pagination.
     */
    public function index(Request $request)
    {
        $vendors = Vendor::select(
            'id',
            'company_name',
            'email',
            'full_name',
            'gender',
            'address',
            'status',
            'business_registration',
            'mayor_permit',
            'tin',
            'proof_of_identity',
            'created_at'
        )->get();

        $vendors = $vendors->map(function ($vendor) {
            return [
                'id' => $vendor->id,
                'companyName' => $vendor->company_name,
                'email' => $vendor->email,
                'fullName' => $vendor->full_name,
                'gender' => $vendor->gender,
                'address' => $vendor->address,
                'status' => $vendor->status,
                'businessRegistration' => $vendor->business_registration,
                'mayorsPermit' => $vendor->mayor_permit,
                'taxIdentificationNumber' => $vendor->tin,
                'proofOfIdentity' => $vendor->proof_of_identity,
                'createdAt' => $vendor->created_at,
            ];
        });

        return response()->json($vendors);
    }

    /**
     * Retrieve a single vendor's data.
     */
    public function show(Request $request)
    {
        $vendorId = $request->query('id');

        if (!$vendorId) {
            return response()->json(['error' => 'Vendor ID is required'], 400);
        }

        $vendor = Vendor::select(
            'id',
            'company_name',
            'email',
            'full_name',
            'gender',
            'address',
            'status',
            'business_registration',
            'mayor_permit',
            'tin',
            'proof_of_identity',
            'created_at'
        )->find($vendorId);

        if (!$vendor) {
            return response()->json(['error' => 'Vendor not found'], 404);
        }

        return response()->json([
            'id' => $vendor->id,
            'companyName' => $vendor->company_name,
            'email' => $vendor->email,
            'fullName' => $vendor->full_name,
            'gender' => $vendor->gender,
            'address' => $vendor->address,
            'status' => $vendor->status,
            'businessRegistration' => $vendor->business_registration,
            'mayorsPermit' => $vendor->mayor_permit,
            'taxIdentificationNumber' => $vendor->tin,
            'proofOfIdentity' => $vendor->proof_of_identity,
            'createdAt' => $vendor->created_at,
        ]);
    }

    /**
     * Update a vendor's data.
     */
    public function update(Request $request, $id)
    {
        $vendor = Vendor::find($id);

        if (!$vendor) {
            return response()->json(['error' => 'Vendor not found'], 404);
        }

        $rules = [
            'company_name' => 'required|string|max:255',
            'full_name' => 'required|string|max:255',
            'gender' => 'required|string',
            'address' => 'required|string',
            'status' => 'required|string|in:Approved,Rejected,Pending',
            'business_registration' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'mayor_permit' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'tin' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'proof_of_identity' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ];

        $validated = $request->validate($rules);

        // Handle file uploads
        foreach (['business_registration', 'mayor_permit', 'tin', 'proof_of_identity'] as $file) {
            if ($request->hasFile($file)) {
                $validated[$file] = $request->file($file)->store('documents');
            }
        }

        $vendor->update($validated);

        return response()->json([
            'id' => $vendor->id,
            'companyName' => $vendor->company_name,
            'fullName' => $vendor->full_name,
            'gender' => $vendor->gender,
            'address' => $vendor->address,
            'status' => $vendor->status,
            'businessRegistration' => $vendor->business_registration,
            'mayorsPermit' => $vendor->mayor_permit,
            'taxIdentificationNumber' => $vendor->tin,
            'proofOfIdentity' => $vendor->proof_of_identity,
            'updatedAt' => $vendor->updated_at,
        ]);
    }

    /**
     * Partially update a vendor's data.
     */
    public function patch(Request $request, $id)
    {
        $vendor = Vendor::find($id);

        if (!$vendor) {
            return response()->json(['error' => 'Vendor not found'], 404);
        }

        $rules = [
            'company_name' => 'sometimes|string|max:255',
            'full_name' => 'sometimes|string|max:255',
            'gender' => 'sometimes|string',
            'address' => 'sometimes|string',
            'status' => 'sometimes|string|in:Approved,Rejected,Pending',
            'business_registration' => 'sometimes|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'mayor_permit' => 'sometimes|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'tin' => 'sometimes|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'proof_of_identity' => 'sometimes|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ];

        $validated = $request->validate($rules);

        foreach (['business_registration', 'mayor_permit', 'tin', 'proof_of_identity'] as $file) {
            if ($request->hasFile($file)) {
                $validated[$file] = $request->file($file)->store('documents');
            }
        }

        $vendor->update($validated);

        return response()->json([
            'id' => $vendor->id,
            'companyName' => $vendor->company_name,
            'fullName' => $vendor->full_name,
            'gender' => $vendor->gender,
            'address' => $vendor->address,
            'status' => $vendor->status,
            'businessRegistration' => $vendor->business_registration,
            'mayorsPermit' => $vendor->mayor_permit,
            'taxIdentificationNumber' => $vendor->tin,
            'proofOfIdentity' => $vendor->proof_of_identity,
            'updatedAt' => $vendor->updated_at,
        ]);
    }
}
