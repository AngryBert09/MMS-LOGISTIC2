<?php

namespace App\Http\Controllers\APIs;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BiddingDetail;
use App\Models\VendorBid;
use Illuminate\Support\Facades\Auth;
use App\Notifications\VendorChosenNotification;
use App\Models\PurchaseOrder;
use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Models\OrderItem;
use App\Models\Vendor;

class BiddingController extends Controller
{
    /**
     * Get all bidding details.
     */
    public function index()
    {
        $biddings = BiddingDetail::all()->map(function ($bidding) {
            return [
                'biddingId' => $bidding->id,
                'itemName' => $bidding->item_name,
                'startingPrice' => $bidding->starting_price,
                'deadline' => $bidding->deadline,
                'description' => $bidding->description,
                'additionalInformation' => $bidding->additional_information,
            ];
        });

        return response()->json(['biddings' => $biddings], 200);
    }


    /**
     * Get details of a specific bidding.
     */
    public function show($id)
    {
        $bidding = BiddingDetail::find($id);
        if (!$bidding) {
            return response()->json(['message' => 'Bidding not found.'], 404);
        }

        return response()->json([
            'bidding' => [
                'biddingId' => $bidding->id,
                'itemName' => $bidding->item_name,
                'startingPrice' => $bidding->starting_price,
                'deadline' => $bidding->deadline,
                'description' => $bidding->description,
                'additionalInformation' => $bidding->additional_information,
            ]
        ], 200);
    }

    /**
     * Update an existing bid.
     */


    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'vendorId' => 'required|exists:vendors,id'
        ]);

        $bidding = BiddingDetail::findOrFail($id);

        // Check if the vendor has placed a bid
        $existingBid = VendorBid::where('bidding_id', $id)
            ->where('vendor_id', $validatedData['vendorId'])
            ->first();

        if (!$existingBid) {
            return response()->json(['message' => 'This vendor has not placed a bid for this item.'], 404);
        }

        // Generate Purchase Order Data
        $purchaseOrder = PurchaseOrder::create([
            'purchase_order_number' => 'PO-' . strtoupper(Str::random(8)),
            'order_date' => Carbon::now(),
            'delivery_date' => Carbon::parse($bidding->deadline),
            'order_status' => 'Approved',
            'total_amount' => $existingBid->bid_amount,
            'delivery_location' => env('SHIPPING_ORIGIN', 'Default Location'),
            'notes_instructions' => 'Follow standard delivery process.',
            'vendor_id' => $validatedData['vendorId'],
            'shipping_method' => 'Standard',
            'status_history' => json_encode([['status' => 'Pending', 'timestamp' => Carbon::now()]]),
            'attachments' => null,
        ]);

        // Store Order Items
        $orderItems = [];

        $orderItem = OrderItem::create([
            'po_id' => $purchaseOrder->po_id,
            'invoice_id' => null, // Assuming this links to invoice
            'receipt_id' => null, // Update if you have receipts later
            'item_description' => $bidding->item_name,
            'quantity' => $bidding->quantity,
            'total_price' => $existingBid->bid_amount,
        ]);

        $orderItems[] = $orderItem;


        // Notify the selected vendor
        $vendor = Vendor::find($validatedData['vendorId']);
        $vendor->notify(new VendorChosenNotification($bidding));

        return response()->json([
            'message' => 'Bid vendor updated successfully, vendor notified, Purchase Order and Order Items created!',
            'purchaseOrder' => [
                'id' => $purchaseOrder->po_id,
                'purchaseOrderNumber' => $purchaseOrder->purchase_order_number,
                'invoiceNumber' => $purchaseOrder->invoice_number,
                'orderDate' => Carbon::parse($purchaseOrder->order_date)->toDateString(),
                'deliveryDate' => Carbon::parse($purchaseOrder->delivery_date)->toDateString(),
                'orderStatus' => $purchaseOrder->order_status,
                'totalAmount' => $purchaseOrder->total_amount,
                'vendorId' => $purchaseOrder->vendor_id,
            ],
            'orderItems' => $orderItems, // Return all items
        ], 200);
    }
}
