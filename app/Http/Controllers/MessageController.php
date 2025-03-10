<?php


namespace App\Http\Controllers;

use App\Models\Message;
use App\Events\MessageSent;
use Illuminate\Http\Request;
use App\Models\Vendor;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class MessageController extends Controller
{
    public function store(Request $request)
    {
        try {
            // Log the incoming request data for debugging
            Log::info('Incoming message request:', [
                'data' => $request->all(),
                'user' => auth()->user(),
            ]);

            // Validate the request data
            $validated = $request->validate([
                'message' => 'required|string',
                'receiver_id' => 'required|integer|exists:vendors,id',
                'sender_id' => 'required|integer|exists:vendors,id',
            ]);

            // Save the message
            $message = Message::create($validated);

            // Log the saved message for debugging
            Log::info('Message saved successfully:', $message->toArray());

            // Broadcast the message (optional)
            broadcast(new MessageSent($message))->toOthers();

            // Return a JSON response
            return response()->json([
                'sender_id' => $message->sender_id,
                'receiver_id' => $message->receiver_id,
                'message_id' => $message->message_id,
                'message' => is_string($message->message) ? $message->message : json_encode($message->message), // Ensure message is string
                'created_at' => now()->toDateTimeString(), // Use ISO format for consistency
            ]);
        } catch (\Exception $e) {
            // Log the error for debugging
            Log::error('Error while saving message:', [
                'error' => $e->getMessage(),
                'request' => $request->all(),
            ]);

            // Return an error response
            return response()->json([
                'success' => false,
                'error' => 'Failed to send the message. Please try again later.',
            ], 500);
        }
    }




    public function showChat(Request $request)
    {
        // Fetch all vendors
        $vendors = Vendor::all();

        // Get the selected vendor ID (default to null if not provided)
        $selectedVendorId = $request->input('vendor_id', null);

        // Retrieve messages if a vendor is selected
        $messages = [];
        if ($selectedVendorId) {
            $messages = Message::where(function ($query) use ($selectedVendorId) {
                $query->where('sender_id', auth()->id())
                    ->where('receiver_id', $selectedVendorId);
            })
                ->orWhere(function ($query) use ($selectedVendorId) {
                    $query->where('sender_id', $selectedVendorId)
                        ->where('receiver_id', auth()->id());
                })
                ->orderBy('created_at', 'asc')
                ->get();
        }

        // Pass vendors, messages, and selectedVendorId to the view
        return view('vendors.Chats.chats', [
            'vendors' => $vendors,
            'messages' => $messages,
            'selectedVendorId' => $selectedVendorId, // Explicitly pass null if no vendor is selected
        ]);
    }

    public function getMessages($vendorId)
    {
        // Get the authenticated vendor's ID
        $authVendorId = auth()->id();

        // Fetch the messages between the authenticated vendor and the selected vendor
        $messages = Message::where(function ($query) use ($authVendorId, $vendorId) {
            $query->where('sender_id', $authVendorId)
                ->where('receiver_id', $vendorId);
        })
            ->orWhere(function ($query) use ($authVendorId, $vendorId) {
                $query->where('sender_id', $vendorId)
                    ->where('receiver_id', $authVendorId);
            })
            ->orderBy('created_at', 'asc') // Order messages by timestamp
            ->get(['message_id', 'message', 'created_at', 'sender_id', 'receiver_id']); // Ensure you only return necessary fields

        // Check if messages were found
        if ($messages->isEmpty()) {
            return response()->json([
                'message' => 'No messages found.',
                'messages' => []
            ]);
        }

        // Return the messages as a JSON response
        return response()->json([
            'messages' => $messages
        ]);
    }

    public function markAsRead(Vendor $vendor)
    {
        Log::info('Mark as read request received', [
            'auth_user_id' => auth()->id(),
            'vendor_id' => $vendor->id,
        ]);

        try {
            $authUserId = auth()->id();
            $updatedRows = Message::where('receiver_id', $authUserId)
                ->where('sender_id', $vendor->id)
                ->where('is_read', false)
                ->update(['is_read' => true]);

            Log::info('Messages marked as read', [
                'updated_rows' => $updatedRows,
            ]);

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            Log::error('Error in markAsRead', [
                'error' => $e->getMessage(),
            ]);

            return response()->json(['success' => false], 500);
        }
    }



    public function getVendorsWithUnreadCount()
    {
        $authVendorId = auth()->id();  // Get the authenticated vendor's ID

        // Get all vendors with unread message count for the authenticated vendor
        $vendors = Vendor::all()->map(function ($vendor) use ($authVendorId) {
            // Count unread messages for the current vendor
            $unreadMessagesCount = Message::where('receiver_id', $authVendorId)
                ->where('sender_id', $vendor->id)
                ->where('is_read', false)
                ->count(); // Count only unread messages

            // Add unread message count to vendor data
            $vendor->unread_messages_count = $unreadMessagesCount;

            return $vendor;
        });

        return response()->json($vendors);  // Return vendors with unread message count
    }
}
