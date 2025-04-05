<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BiddingDetail;
use App\Models\VendorBid;
use App\Notifications\BidStatusUpdated;
use Illuminate\Support\Facades\Log;
use App\Notifications\VendorChosenNotification;
use App\Models\PurchaseOrder;
use App\Models\OrderItem;
use App\Models\Vendor;
use Carbon\Carbon;
use Illuminate\Support\Str;


class EmployeeBiddingController extends Controller
{
    public function index()
    {
        // Get biddings where vendor_id is NULL or vendors assigned 3 days ago
        $biddings = BiddingDetail::whereNull('vendor_id')
            ->orWhere('updated_at', '>=', now()->subDays(3))
            ->get();

        // Pass data to the view
        return view('employee.biddings.index-biddings', compact('biddings'));
    }

    public function showVendors($id)
    {
        // Fetch the BiddingDetail along with only related VendorBids
        $biddingDetail = BiddingDetail::with('vendorBids')->findOrFail($id);

        return view('employee.biddings.offers', compact('biddingDetail'));
    }

    public function updateBidStatus(Request $request, $bidId)
    {
        Log::info('Bid status update initiated', [
            'bidId' => $bidId,
            'action' => $request->action,
        ]);

        // Fetch the VendorBid and associated BiddingDetail
        $vendorBid = VendorBid::with('bidding')->findOrFail($bidId);
        $bidding = $vendorBid->bidding;

        Log::info('Fetched VendorBid and BiddingDetail', [
            'vendorBid_id' => $vendorBid->id,
            'bidding_id' => $bidding->id,
            'vendor_id' => $bidding->vendor_id,
            'bid_amount' => $vendorBid->bid_amount,
        ]);

        // Handle the accept action
        if ($request->action === 'accept') {
            // Check if the vendor is already assigned
            if ($bidding->vendor_id) {
                Log::warning('A vendor is already assigned to this bidding', [
                    'bidding_id' => $bidding->id,
                    'existing_vendor_id' => $bidding->vendor_id,
                ]);
                $message = 'This bidding has already been accepted by another vendor.';
            } else {
                // Assign the vendor to the bidding
                $bidding->vendor_id = $vendorBid->vendor_id;
                $bidding->save();

                // Reload the bidding instance to get the latest data, especially the vendor
                $bidding->refresh();  // Refresh to get the updated vendor data

                Log::info('Bid accepted, vendor_id updated', [
                    'new_vendor_id' => $bidding->vendor_id,
                    'bidding_id' => $bidding->id,
                ]);

                // Create Purchase Order
                Log::info('Creating Purchase Order for vendor', [
                    'vendor_id' => $vendorBid->vendor_id,
                    'bidding_id' => $bidding->id,
                    'bid_amount' => $vendorBid->bid_amount,
                ]);

                $purchaseOrder = PurchaseOrder::create([
                    'purchase_order_number' => 'PO-' . strtoupper(Str::random(8)),
                    'order_date' => Carbon::now(),
                    'delivery_date' => Carbon::parse($bidding->deadline),
                    'order_status' => 'Approved',
                    'total_amount' => $vendorBid->bid_amount,
                    'delivery_location' => env('SHIPPING_ORIGIN', 'Default Location'),
                    'notes_instructions' => 'Follow standard delivery process.',
                    'vendor_id' => $vendorBid->vendor_id,
                    'shipping_method' => 'Standard',
                    'status_history' => json_encode([['status' => 'Pending', 'timestamp' => Carbon::now()]]),
                    'attachments' => null,
                ]);

                Log::info('Purchase Order created', [
                    'purchase_order_number' => $purchaseOrder->purchase_order_number,
                    'purchase_order_id' => $purchaseOrder->po_id,
                ]);

                // Store Order Item
                $orderItem = OrderItem::create([
                    'po_id' => $purchaseOrder->po_id,
                    'invoice_id' => null, // Assuming this links to invoice
                    'receipt_id' => null, // Update if you have receipts later
                    'item_description' => $bidding->item_name,
                    'quantity' => $bidding->quantity,
                    'total_price' => $vendorBid->bid_amount,
                ]);

                Log::info('Order Item created', [
                    'order_item_id' => $orderItem->id,
                    'purchase_order_id' => $purchaseOrder->po_id,
                ]);

                // Store the Purchase Order ID on BiddingDetail
                $bidding->po_id = $purchaseOrder->po_id;
                $bidding->save();

                Log::info('Purchase Order ID saved to BiddingDetail', [
                    'purchase_order_id' => $purchaseOrder->po_id,
                    'bidding_id' => $bidding->id,
                ]);

                // Notify the selected vendor
                $vendor = Vendor::find($vendorBid->vendor_id);
                $vendor->notify(new VendorChosenNotification($bidding));

                Log::info('Notification sent to vendor', [
                    'vendor_id' => $vendorBid->vendor_id,
                    'bidding_id' => $bidding->id,
                ]);

                $message = 'Vendor bid has been accepted successfully. Purchase Order and Order Items created. Notification has been sent to the winning vendor.';
            }
        } else {
            Log::error('Invalid action provided', [
                'bid_id' => $bidId,
                'action' => $request->action,
            ]);
            $message = 'Invalid action provided.';
        }

        // Return the view with the updated message and purchase order details
        Log::info('Returning view with message', [
            'message' => $message,
            'bidding_id' => $bidding->id,
        ]);

        return view('employee.biddings.offers', [
            'biddingDetail' => $bidding,
            'message' => $message
        ]);
    }
}
