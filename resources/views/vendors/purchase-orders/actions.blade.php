<div class="dropdown">
    <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" href="#" role="button"
        data-toggle="dropdown">
        <i class="dw dw-more"></i>
    </a>
    <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
        <!-- View Action: Trigger Modal -->
        <a class="dropdown-item view-purchase-order" href="#" data-po-id="{{ $order->po_id }}" data-toggle="modal"
            data-target="#viewPurchaseOrderModal">
            <i class="dw dw-eye"></i> View
        </a>

        <!-- Actions based on order status -->
        @if ($order->order_status === 'Pending Approval')
            <a class="dropdown-item confirm-purchase-order" href="#"
                data-order='{"po_id": "{{ $order->po_id }}", "purchase_order_number": "{{ $order->purchase_order_number }}", "total_amount": {{ $order->total_amount }} }'
                data-toggle="modal" data-target="#confirmPurchaseOrderModal">
                <i class="dw dw-check"></i> Confirm
            </a>
            <a class="dropdown-item reject-purchase-order" href="#" data-order-id="{{ $order->po_id }}"
                data-toggle="modal" data-target="#rejectPurchaseOrderModal">
                <i class="dw dw-trash"></i> Reject
            </a>
            <a class="dropdown-item hold-purchase-order" href="#" data-order-id="{{ $order->po_id }}"
                data-toggle="modal" data-target="#holdPurchaseOrderModal">
                <i class="dw dw-pause"></i> Put on Hold
            </a>
        @elseif ($order->order_status === 'Approved')
            <a class="dropdown-item generate-invoice" href="#" data-order-id="{{ $order->po_id }}"
                data-toggle="modal" data-target="#generateInvoiceModal">
                <i class="dw dw-invoice"></i> Generate Invoice
            </a>
            <a class="dropdown-item initiate-fulfillment" href="#" data-po-id="{{ $order->po_id }}"
                data-po-number="{{ $order->purchase_order_number }}" data-total-amount="{{ $order->total_amount }}"
                data-toggle="modal" data-target="#initiateFulfillmentModal">
                <i class="dw dw-check"></i> Initiate Fulfillment
            </a>

            <a class="dropdown-item view-shipment-details" href="#" data-order-id="{{ $order->po_id }}"
                data-toggle="modal" data-target="#viewShipmentDetailsModal">
                <i class="dw dw-truck"></i> View Shipment Details
            </a>

            <!-- Adding option to put the order on hold -->
            <a class="dropdown-item hold-purchase-order" href="#" data-order-id="{{ $order->po_id }}"
                data-toggle="modal" data-target="#holdPurchaseOrderModal">
                <i class="dw dw-right-arrow-1"></i> Put on Hold
            </a>
        @elseif ($order->order_status === 'In Progress' || $order->order_status === 'On Hold')
            <span class="dropdown-item disabled">No actions available</span>
        @endif

        <!-- Delete Action: Only show if order is cancellable -->
        @include('vendors.purchase-orders.destroy')
    </div>
</div>
