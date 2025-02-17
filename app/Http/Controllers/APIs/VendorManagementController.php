<?php

namespace App\Http\Controllers\APIs;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VendorManagementController extends Controller
{
    /**
     * Retrieve all vendors with pagination.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Retrieve vendors with pagination (default per page is 10) and only the specified fields
        $vendors = Vendor::select(
            'id',
            'company_name',
            'email',
            'password',
            'full_name',
            'gender',
            'status',
            'business_registration',
            'mayor_permit',
            'tin',
            'proof_of_identity',
        )->get();

        $vendors = $vendors->map(function ($vendor) {
            return [
                'id' => $vendor->id,
                'companyName' => $vendor->company_name,
                'email' => $vendor->email,
                'fullName' => $vendor->full_name,
                'gender' => $vendor->gender,
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
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        // Retrieve the vendor ID from the query string (e.g., ?id=44)
        $vendorId = $request->query('id');

        // Validate the ID
        if (!$vendorId) {
            return response()->json(['error' => 'Vendor ID is required'], 400);
        }

        // Find the vendor by ID and select only the required columns
        $vendor = Vendor::select(
            'id',
            'company_name',
            'email',
            'full_name',
            'gender',
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

        // Map the vendor data to camelCase manually
        $vendorData = [
            'id' => $vendor->id,
            'companyName' => $vendor->company_name,
            'email' => $vendor->email,
            'fullName' => $vendor->full_name,
            'gender' => $vendor->gender,
            'status' => $vendor->status,
            'businessRegistration' => $vendor->business_registration,
            'mayorsPermit' => $vendor->mayor_permit,
            'taxIdentificationNumber' => $vendor->tin,
            'proofOfIdentity' => $vendor->proof_of_identity,
            'createdAt' => $vendor->created_at,
        ];

        // Return the vendor data in camelCase
        return response()->json($vendorData);
    }



    /**
     * Update a vendor's data.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Find the vendor
        $vendor = Vendor::find($id);

        if (!$vendor) {
            return response()->json(['error' => 'Vendor not found'], 404);
        }

        // Define validation rules (all fields nullable so they can be updated individually)
        $rules = [
            'company_name' => 'nullable|string|max:255',
            'full_name' => 'nullable|string|max:255',
            'gender' => 'nullable|string',
            'status' => 'nullable|string',
        ];

        // Validate input
        $validated = $request->validate($rules);

        // Remove null values (prevents overwriting existing data with null)
        $filteredData = array_filter($validated, fn($value) => !is_null($value));


        // Handle file uploads
        if ($request->hasFile('business_registration')) {
            $filteredData['business_registration'] = $request->file('business_registration')->store('documents');
        }

        if ($request->hasFile('mayor_permit')) {
            $filteredData['mayor_permit'] = $request->file('mayor_permit')->store('documents');
        }

        if ($request->hasFile('tax_identification_number')) {
            $filteredData['tax_identification_number'] = $request->file('tax_identification_number')->store('documents');
        }

        // Update the vendor
        $vendor->update($filteredData);

        // Return the **full** vendor details in the response
        return response()->json([
            'id' => $vendor->id,
            'companyName' => $vendor->company_name,
            'fullName' => $vendor->full_name,
            'gender' => $vendor->gender,
            'status' => $vendor->status,
            'businessRegistration' => $vendor->business_registration,
            'mayorsPermit' => $vendor->mayor_permit,
            'taxIdentificationNumber' => $vendor->tin,
            'proofOfIdentity' => $vendor->proof_of_identity,
            'updatedAt' => $vendor->updated_at,
        ]);
    }
}
