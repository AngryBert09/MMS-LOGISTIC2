<!-- VIEW Modal -->
<div class="modal fade" id="viewPurchaseOrderModal" tabindex="-1" role="dialog"
    aria-labelledby="viewPurchaseOrderModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewPurchaseOrderModalLabel">Purchase Order Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <ul class="nav nav-tabs" id="purchaseOrderTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="po-info-tab" data-toggle="tab" href="#po-info" role="tab"
                            aria-controls="po-info" aria-selected="true">PO Info</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="order-items-tab" data-toggle="tab" href="#order-items" role="tab"
                            aria-controls="order-items" aria-selected="false">Order Items</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="timeline-tab" data-toggle="tab" href="#timeline" role="tab"
                            aria-controls="timeline" aria-selected="false">Timeline</a>
                    </li>
                </ul>

                <div class="tab-content mt-3" id="purchaseOrderTabContent">
                    <div class="tab-pane fade show active" id="po-info" role="tabpanel" aria-labelledby="po-info-tab">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <th scope="row">PO #</th>
                                        <td id="modal-po-number">PO000011</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Invoice Number</th>
                                        <td id="modal-invoice-number">INV123456</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Order Date</th>
                                        <td id="modal-order-date">10/01/2023</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Delivery Date</th>
                                        <td id="modal-delivery-date">10/11/2023</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Order Status</th>
                                        <td id="modal-order-status">Approved</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Total Amount</th>
                                        <td id="modal-total-amount">IDR 35,000,000</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Payment Terms</th>
                                        <td id="modal-payment-terms">Net 30</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Delivery Location</th>
                                        <td id="modal-delivery-location">Jakarta, Indonesia</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Notes/Instructions</th>
                                        <td id="modal-notes">Handle with care</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Shipping Method</th>
                                        <td id="modal-shipping-method">Express Delivery</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="order-items" role="tabpanel" aria-labelledby="order-items-tab">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="modal-order-items-table">
                                <thead>
                                    <tr>
                                        <th>Item Description</th>
                                        <th>Quantity</th>
                                        <th>Unit Price</th>
                                        <th>Total Price</th>
                                    </tr>
                                </thead>
                                <tbody id="modal-order-items-body">
                                    <!-- Order items will be dynamically populated here -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="timeline" role="tabpanel" aria-labelledby="timeline-tab">
                        <div class="container pd-0">
                            <div class="timeline mb-30">
                                <ul id="modal-timeline-events" class="timeline">
                                    <!-- Timeline events will be dynamically populated here -->
                                </ul>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-success">Create Invoice</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#viewPurchaseOrderModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            var po_id = button.data('po-id'); // Get the PO ID from the data attribute

            console.log("PO ID:", po_id); // Debugging line to check PO ID

            // AJAX request to get purchase order details
            $.ajax({
                url: '/purchase-orders/' + po_id, // Ensure the URL is correct
                type: 'GET',
                success: function(data) {
                    console.log("Data received:",
                        data); // Debugging line to check received data

                    // Fill modal fields with data
                    $('#modal-po-number').text(data.purchase_order_number);
                    $('#modal-invoice-number').text(data.invoice_number);
                    $('#modal-order-date').text(data.order_date);
                    $('#modal-delivery-date').text(data.delivery_date);
                    $('#modal-order-status').text(data.order_status);
                    $('#modal-total-amount').text(data.total_amount);
                    $('#modal-payment-terms').text(data.payment_terms);
                    $('#modal-delivery-location').text(data.delivery_location);
                    $('#modal-notes').text(data.notes_instructions);
                    $('#modal-shipping-method').text(data.shipping_method);

                    // Disable "Create Invoice" button if the status is "Rejected"
                    if (data.order_status === 'Rejected') {
                        $('.btn-success').prop('disabled', true); // Disable button
                    } else {
                        $('.btn-success').prop('disabled', false); // Enable button
                    }

                    // Populate order items
                    $('#modal-order-items-body').empty(); // Clear previous items
                    if (data.order_items && data.order_items.length > 0) {
                        data.order_items.forEach(function(item) {
                            $('#modal-order-items-body').append(
                                '<tr>' +
                                '<td>' + item.item_description + '</td>' +
                                '<td>' + item.quantity + '</td>' +
                                '<td>' + item.unit_price + '</td>' +
                                '<td>' + (item.unit_price * item.quantity)
                                .toFixed(2) + '</td>' +
                                '</tr>'
                            );
                        });
                    } else {
                        $('#modal-order-items-body').append(
                            '<tr><td colspan="4" class="text-center">No items found</td></tr>'
                        );
                    }

                    // Populate timeline events
                    $('#modal-timeline-events').empty(); // Clear previous events
                    if (data.timeline_events && data.timeline_events.length > 0) {
                        console.log("Timeline Events:", data.timeline_events);

                        data.timeline_events.forEach(function(event) {
                            // Format the event date
                            var eventDate = new Date(event.event_date);
                            var options = {
                                year: 'numeric',
                                month: 'long',
                                day: 'numeric',
                                hour: '2-digit',
                                minute: '2-digit',
                                hour12: false
                            };
                            var formattedDate = eventDate.toLocaleDateString(
                                'en-US', options);

                            $('#modal-timeline-events').append(
                                '<li>' +
                                '<div class="timeline-date">' + formattedDate +
                                '</div>' +
                                '<div class="timeline-desc card-box">' +
                                '<div class="pd-20">' +
                                '<h4 class="mb-10 h4">' + event.event_title +
                                '</h4>' +
                                '<p>' + event.event_details + '</p>' +
                                '</div>' +
                                '</div>' +
                                '</li>'
                            );
                        });
                    } else {
                        $('#modal-timeline-events').append(
                            '<li><div class="text-center">No timeline events found</div></li>'
                        );
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error fetching data:", error);
                    alert(
                        'An error occurred while fetching the purchase order details. Please check the console for more details.'
                        );
                }
            });
        });
    });
</script>
