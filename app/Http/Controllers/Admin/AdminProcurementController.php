<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ProcurementRequest;
use App\Models\BiddingDetail;
use App\Notifications\ProcurementStatusUpdated;
use App\Models\User;

class AdminProcurementController extends Controller
{
    public function index()
    {
        // Fetch all procurement requests
        $procurementRequests = ProcurementRequest::orderBy('created_at', 'desc')->get(); // Paginate results

        return view('admin.procurement.index-proc', compact('procurementRequests'));
    }


    public function updateStatus(Request $request, $id)
    {
        $procurement = ProcurementRequest::findOrFail($id);
        $newStatus = $request->status;

        // Update procurement request status
        $procurement->status = $newStatus;
        $procurement->save();

        $biddingDetails = null;

        // If status is "Approved," move details to BiddingDetails
        if ($newStatus === 'Approved') {
            $biddingDetails = BiddingDetail::create([
                'procurement_id' => $procurement->id,
                'item_name' => $procurement->item_name,
                'starting_price' => $procurement->starting_price,
                'quantity' => $procurement->quantity,
                'deadline' => $procurement->deadline,
                'description' => $procurement->description,
            ]);
        }

        // Fetch all bidding details for response
        $biddingList = BiddingDetail::orderBy('created_at', 'desc')->get();

        // Notify the employee about the status update
        $employee = User::find($procurement->employee_id);
        if ($employee) {
            $employee->notify(new ProcurementStatusUpdated($procurement, $newStatus));
        }

        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully',
            'biddingDetails' => $biddingDetails,
            'biddingList' => $biddingList
        ]);
    }
}
