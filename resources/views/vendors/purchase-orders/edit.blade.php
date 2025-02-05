<!-- Edit Purchase Order Modal -->
<div class="modal fade" id="editPurchaseOrderModal" tabindex="-1" role="dialog"
    aria-labelledby="editPurchaseOrderModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editPurchaseOrderModalLabel">Edit Purchase Order</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editPurchaseOrderForm" method="POST" action="">
                    @csrf
                    @method('PUT')

                    <!-- Hidden input to specify the action -->
                    <input type="hidden" name="action" value="edit">

                    <!-- Total Amount Field -->
                    <div class="form-group">
                        <label for="edit_total_amount">Total Amount</label>
                        <input type="number" class="form-control" id="edit_total_amount" name="total_amount"
                            step="0.01" min="0" required>
                    </div>

                    <!-- Shipping Method Field -->
                    <div class="form-group">
                        <label for="edit_shipping_method">Shipping Method</label>
                        <input type="text" class="form-control" id="edit_shipping_method" name="shipping_method"
                            required>
                    </div>

                    <!-- Notes/Instructions Field -->
                    <div class="form-group">
                        <label for="edit_notes_instructions">Notes/Instructions</label>
                        <textarea class="form-control" id="edit_notes_instructions" name="notes_instructions" rows="3"></textarea>
                    </div>

                    <!-- Hidden input to store order ID -->
                    <input type="hidden" id="edit_order_id" name="order_id">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" form="editPurchaseOrderForm" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).on('click', '.edit-purchase-order', function() {
        var order = $(this).data('order');
        $('#edit_order_id').val(order.po_id); // Assuming your order object has a 'po_id' field
        $('#edit_total_amount').val(order.total_amount);
        $('#edit_shipping_method').val(order.shipping_method);
        $('#edit_notes_instructions').val(order.notes_instructions);

        // Set the form action URL for updating the order
        var actionUrl = "{{ route('purchase-orders.update', '') }}/" + order.po_id;
        $('#editPurchaseOrderForm').attr('action', actionUrl);
        console.log("Edit action URL:", actionUrl);
    });
</script>

<<!-- Confirm Purchase Order Modal -->
    <div class="modal fade" id="confirmPurchaseOrderModal" tabindex="-1" role="dialog"
        aria-labelledby="confirmPurchaseOrderModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmPurchaseOrderModalLabel">Confirm Purchase Order</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="confirmPurchaseOrderForm" method="POST" action="">
                        @csrf
                        @method('PUT')
                        <p>Are you sure you want to confirm this purchase order?</p>
                        <input type="hidden" id="confirm_order_id" name="order_id">
                        <input type="hidden" name="action" value="confirm"> <!-- Action field -->

                        <!-- Optional: Display order details for confirmation -->
                        <div id="orderDetails" class="mt-3">
                            <p><strong>PO #:</strong> <span id="poNumber"></span></p>
                            <p><strong>Total Amount:</strong> <span id="totalAmount"></span></p>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-dark" data-dismiss="modal">Close</button>
                    <button type="submit" form="confirmPurchaseOrderForm" class="btn btn-warning">Confirm</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        // For Confirm Purchase Order
        $(document).on('click', '.confirm-purchase-order', function() {
            var order = $(this).data('order');
            $('#confirm_order_id').val(order.po_id);
            $('#poNumber').text(order.purchase_order_number);
            $('#totalAmount').text(order.total_amount.toFixed(2)); // Format the amount as needed

            // Set the form action URL
            var actionUrl = "{{ route('purchase-orders.update', '') }}/" + order.po_id;
            $('#confirmPurchaseOrderForm').attr('action', actionUrl);
        });
    </script>


    <!-- Reject Purchase Order Modal -->
    <div class="modal fade" id="rejectPurchaseOrderModal" tabindex="-1" role="dialog"
        aria-labelledby="rejectPurchaseOrderModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="rejectPurchaseOrderModalLabel">Reject Purchase Order</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="rejectPurchaseOrderForm" method="POST"
                        action="{{ route('purchase-orders.update', '') }}">
                        @csrf
                        @method('PUT')
                        <p>Are you sure you want to reject this purchase order?</p>
                        <input type="hidden" id="reject_order_id" name="order_id">
                        <input type="hidden" name="action" value="reject"> <!-- Action field -->

                        <div class="form-group">
                            <label for="rejection-note">Rejection Note:</label>
                            <textarea id="rejection-note" name="rejection_note" class="form-control" rows="3" required maxlength="50"></textarea>
                            <small class="form-text text-muted">Please provide a reason for rejection (max 50
                                characters).</small>
                            <div class="character-count mt-2">0 / 50</div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" form="rejectPurchaseOrderForm" class="btn btn-danger">Reject</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).on('click', '.reject-purchase-order', function() {
            var orderId = $(this).data('order-id');
            $('#reject_order_id').val(orderId);

            // Update the action URL to include the order ID
            var actionUrl = "{{ route('purchase-orders.update', '') }}/" + orderId;
            $('#rejectPurchaseOrderForm').attr('action', actionUrl);
        });

        // Update character count
        $('#rejection-note').on('input', function() {
            var charCount = $(this).val().length;
            $('.character-count').text(charCount + ' / 50');
        });
    </script>


    <!-- Re-submit Purchase Order Modal -->
    <div class="modal fade" id="resubmitPurchaseOrderModal" tabindex="-1" role="dialog"
        aria-labelledby="resubmitPurchaseOrderModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="resubmitPurchaseOrderModalLabel">Re-submit Purchase Order</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="resubmitPurchaseOrderForm" method="POST"
                        action="{{ route('purchase-orders.update', '') }}">
                        @csrf
                        @method('PUT')
                        <p>Are you sure you want to re-submit this purchase order?</p>
                        <input type="hidden" id="resubmit_order_id" name="order_id">
                        <input type="hidden" name="action" value="resubmit"> <!-- Action field -->
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" form="resubmitPurchaseOrderForm"
                        class="btn btn-primary">Re-submit</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).on('click', '.resubmit-purchase-order', function() {
            var orderId = $(this).data('order-id');
            $('#resubmit_order_id').val(orderId);
            // Update the action URL to include the order ID
            var actionUrl = "{{ route('purchase-orders.update', '') }}/" + orderId;
            $('#resubmitPurchaseOrderForm').attr('action', actionUrl);
        });
    </script>

    <!-- Initiate Fulfillment Modal -->
    <div class="modal fade" id="initiateFulfillmentModal" tabindex="-1" role="dialog"
        aria-labelledby="initiateFulfillmentModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title" id="initiateFulfillmentModalLabel">Initiate Fulfillment</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to initiate fulfillment for the following purchase order?</p>
                    <strong>Purchase Order Number:</strong> <span id="fulfillment-po-number"></span><br>
                    <strong>Total Amount:</strong> <span id="fulfillment-total-amount"></span>
                    <form id="initiateFulfillmentForm" method="POST" action="">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="action" value="initiate_fulfillment">
                        <input type="hidden" name="po_id" id="fulfillment-po-id" value="">
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-warning">Initiate Fulfillment</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).on('click', '.initiate-fulfillment', function() {
            const poId = $(this).data('po-id');
            const poNumber = $(this).data('po-number');
            const totalAmount = $(this).data('total-amount');

            // Set the values in the modal
            $('#fulfillment-po-id').val(poId);
            $('#fulfillment-po-number').text(poNumber);
            $('#fulfillment-total-amount').text(totalAmount);

            // Update the form action to include the correct purchase order ID
            $('#initiateFulfillmentForm').attr('action', "{{ route('purchase-orders.update', '') }}" + '/' + poId);
        });
    </script>

    <!-- Hold Purchase Order Modal -->
    <div class="modal fade" id="holdPurchaseOrderModal" tabindex="-1" role="dialog"
        aria-labelledby="holdPurchaseOrderModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="holdPurchaseOrderModalLabel">Put Purchase Order on Hold</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="holdPurchaseOrderForm" method="POST"
                        action="{{ route('purchase-orders.update', '') }}">
                        @csrf
                        @method('PUT')
                        <p>Are you sure you want to put this purchase order on hold?</p>

                        <!-- Hidden field for order ID -->
                        <input type="hidden" id="hold_order_id" name="order_id">
                        <input type="hidden" name="action" value="hold"> <!-- Action field -->

                        <!-- Optional note for placing order on hold -->
                        <div class="form-group">
                            <label for="hold_note">Reason for Hold (optional):</label>
                            <textarea id="hold_note" name="hold_note" class="form-control" rows="3"
                                placeholder="Enter a reason for putting the order on hold (optional)"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" form="holdPurchaseOrderForm" class="btn btn-warning">Put on Hold</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).on('click', '.hold-purchase-order', function() {
            var orderId = $(this).data('order-id');
            $('#hold_order_id').val(orderId);

            // Update the action URL to include the order ID dynamically
            var actionUrl = "{{ route('purchase-orders.update', '') }}/" + orderId;
            $('#holdPurchaseOrderForm').attr('action', actionUrl);
        });
    </script>

    <!-- Modal for Marking Order as In Transit -->
    <div class="modal fade" id="markInTransitModal" tabindex="-1" role="dialog"
        aria-labelledby="markInTransitModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="markInTransitModalLabel">Mark Order as In Transit</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center">
                    <!-- Add GIF of a delivery truck -->
                    <img src="https://media1.giphy.com/media/cmCHuk53AiTmOwBXlw/giphy.gif?cid=6c09b952qpah58v18812891m28r3l8mv5qbvm42mxy72zxmi&ep=v1_internal_gif_by_id&rid=giphy.gif&ct=g"
                        alt="Delivery Truck" class="img-fluid mb-3" style="max-height: 200px;">

                    <form id="markInTransitForm" method="POST" action="{{ route('purchase-orders.update', '') }}">
                        @csrf
                        @method('PUT')

                        <input type="hidden" name="order_id" id="order_id" value="">
                        <input type="hidden" name="action" value="mark_in_transit">

                        <!-- Delivery Service Option -->
                        <div class="form-group">
                            <label for="delivery_service">Delivery Service:</label>
                            <select class="form-control" id="delivery_service" name="delivery_service" required>
                                <option value="">Select Delivery Service</option>
                                <option value="own">Own Service</option>
                                <option value="third_party">Third-Party</option>
                            </select>
                        </div>

                        <!-- Show Third-Party Options only if Third-Party is selected -->
                        <div class="form-group" id="thirdPartyOptions" style="display: none;">
                            <label for="carrier_name">Carrier Name:</label>
                            <select class="form-control" id="carrier_name" name="carrier_name">
                                <option value="lalamove">Lalamove</option>
                                <option value="other">Other</option>
                            </select>
                        </div>

                        <!-- Fields for Own Service only -->
                        <div class="form-group" id="ownServiceFields" style="display: none;">
                            <label for="estimated_delivery_date">Estimated Delivery Date:</label>
                            <input type="date" class="form-control" id="estimated_delivery_date"
                                name="estimated_delivery_date" required>

                            <label for="shipping_method">Shipping Method:</label>
                            <select class="form-control" id="shipping_method" name="shipment_method" required>
                                <option value="Ground">Ground</option>
                                <option value="Air">Air</option>
                                <option value="Expedited">Expedited</option>
                            </select>

                            <label for="shipping_cost">Shipping Cost:</label>
                            <input type="number" step="0.01" class="form-control" id="shipping_cost"
                                name="shipping_cost" required>

                            <label for="weight">Weight (kg):</label>
                            <input type="number" step="0.01" class="form-control" id="weight" name="weight"
                                required>

                        </div>

                        <p>Are you sure you want to mark this purchase order as <strong>In Transit</strong>?</p>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-warning">Yes, Mark as In Transit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        // Toggle fields based on the selected delivery service

        $(document).on('click', '.mark-in-transit', function() {
            var orderId = $(this).data('order-id');
            $('#order_id').val(orderId); // Set the order_id in the hidden input

            // Update the action URL to include the order ID dynamically
            var actionUrl = "{{ route('purchase-orders.update', '') }}/" + orderId;
            $('#markInTransitForm').attr('action', actionUrl);
        });

        document.getElementById('delivery_service').addEventListener('change', function() {
            var thirdPartyOptions = document.getElementById('thirdPartyOptions');
            var ownServiceFields = document.getElementById('ownServiceFields');

            // Hide all fields initially
            thirdPartyOptions.style.display = 'none';
            ownServiceFields.style.display = 'none';

            if (this.value === 'third_party') {
                thirdPartyOptions.style.display = 'block'; // Show third-party options
            } else if (this.value === 'own') {
                ownServiceFields.style.display = 'block'; // Show own service fields
            }
        });
    </script>


    <!-- Modal to view shipment details -->
    <div class="modal fade" id="viewShipmentDetailsModal" tabindex="-1" role="dialog"
        aria-labelledby="viewShipmentDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document"> <!-- Added 'modal-lg' for a larger modal -->
            <div class="modal-content shadow-lg rounded-lg"> <!-- Added shadow and rounded corners -->
                <div class="modal-header bg-warning text-white"> <!-- Modern background and white text -->
                    <h5 class="modal-title" id="viewShipmentDetailsModalLabel">Shipment Details</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="shipmentDetailsContent">
                        <!-- Timeline Design -->
                        <div class="container px-1 px-md-4 py-5 mx-auto">
                            <div class="card">
                                <div class="row d-flex justify-content-between px-3 top">
                                    <div class="d-flex">
                                        <h5>ORDER <span class="text-primary font-weight-bold"
                                                id="orderId">#Y34XDHR</span></h5>
                                    </div>
                                    <div class="d-flex flex-column text-sm-right">
                                        <p class="mb-0">Expected Arrival: <span id="expectedArrival">01/12/19</span>
                                        </p>
                                        <p>Carrier: <span id="carrier">USPS</span></p>
                                        <p>Tracking #: <span class="font-weight-bold"
                                                id="trackingNumberModal">234094567242423422898</span></p>
                                        <p><strong>Estimated Delivery Date:</strong> <span
                                                id="estimatedDeliveryDate"></span></p>
                                        <p><strong>Shipment Method:</strong> <span id="shipmentMethod"></span></p>
                                        <p><strong>Shipping Cost:</strong> <span id="shippingCost"></span></p>
                                        <p><strong>Weight:</strong> <span id="weight"></span> kg</p>
                                        <p><strong>Shipping Address:</strong> <span id="shippingAddress"></span></p>
                                        <p><strong>Actual Delivery Date:</strong> <span id="actualDeliveryDate"></span>
                                        </p>
                                    </div>
                                </div>

                                <!-- Progress bar for the shipping status -->
                                <div class="row d-flex justify-content-center">
                                    <div class="col-12">
                                        <ul id="progressbar" class="text-center">
                                            <li class="step0" id="stepProcessed"></li>
                                            <li class="step0" id="stepShipped"></li>
                                            <li class="step0" id="stepEnRoute"></li>
                                            <li class="step0" id="stepDelivered"></li>
                                        </ul>
                                    </div>
                                </div>

                                <!-- Shipping Status Icons with Labels -->
                                <div class="row justify-content-between top">
                                    <div class="row d-flex icon-content" id="statusProcessed">
                                        <img class="icon" src="images/package.gif">
                                        <div class="d-flex flex-column">
                                            <p class="font-weight-bold">Ready to<br>Transit</p>
                                        </div>
                                    </div>
                                    <div class="row d-flex icon-content" id="statusShipped">
                                        <img class="icon" src="images/Intransit.gif">
                                        <div class="d-flex flex-column">
                                            <p class="font-weight-bold">In<br>Transit</p>
                                        </div>
                                    </div>
                                    <div class="row d-flex icon-content" id="statusEnRoute">
                                        <img class="icon" src="images/Outfordelivery.gif">
                                        <div class="d-flex flex-column">
                                            <p class="font-weight-bold">Out for<br>Delivery</p>
                                        </div>
                                    </div>
                                    <div class="row d-flex icon-content" id="statusDelivered">
                                        <img class="icon"
                                            src="https://media1.giphy.com/media/Tj3gzRARAtYvJYXmb0/giphy.gif">
                                        <div class="d-flex flex-column">
                                            <p class="font-weight-bold">Order<br>Delivered</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        // Handle viewing shipment details when clicking the dropdown item
        // Handle viewing shipment details when clicking the dropdown item
        $(document).on('click', '.view-shipment-details', function() {
            var orderId = $(this).data('order-id'); // Get the order ID
            var modal = $('#viewShipmentDetailsModal'); // Modal to show details

            // Ensure modal is shown before starting the AJAX request
            modal.modal('show');

            // Add a small delay to make sure loading message is visible
            setTimeout(function() {
                // Make an AJAX request to fetch shipment details
                $.ajax({
                    url: '/shipment-details/' +
                        orderId, // URL to fetch shipment details (change to your route)
                    method: 'GET',
                    success: function(response) {
                        if (response.success) {
                            // Populate the modal with shipment data, handle null values
                            modal.find('#trackingNumber').text(response.data.tracking_number ||
                                'N/A');
                            modal.find('#carrierName').text(response.data.carrier_name ||
                                'N/A');
                            modal.find('#estimatedDeliveryDate').text(response.data
                                .estimated_delivery_date || 'N/A');
                            modal.find('#shipmentMethod').text(response.data.shipment_method ||
                                'N/A');
                            modal.find('#shippingCost').text(response.data.shipping_cost ||
                                'N/A');
                            modal.find('#weight').text(response.data.weight || 'N/A');
                            modal.find('#shippingAddress').text(response.data
                                .shipping_address || 'N/A');
                            modal.find('#actualDeliveryDate').text(response.data
                                .actual_delivery_date || 'N/A');
                            modal.find('#orderId').text('#' + response.data.po_id || 'N/A');
                            modal.find('#expectedArrival').text(response.data
                                .estimated_delivery_date || 'N/A');
                            modal.find('#carrier').text(response.data.carrier_name || 'N/A');
                            modal.find('#trackingNumberModal').text(response.data
                                .tracking_number || 'N/A');

                            // Handle timeline progress
                            var status = response.data.shipment_status;
                            var progressBar = $('#progressbar li');
                            var statusIcons = ['#statusProcessed', '#statusShipped',
                                '#statusEnRoute', '#statusDelivered'
                            ];

                            // Reset all progress bar steps before updating
                            progressBar.removeClass('active');

                            // Make all icons and text faint (inactive state)
                            statusIcons.forEach(id => {
                                $(id).find('img').css({
                                    "opacity": "0.4",
                                    "visibility": "visible"
                                }); // Faint images
                                $(id).find('p').css({
                                    "opacity": "0.4",
                                    "visibility": "visible"
                                }); // Faint text
                            });

                            // Apply styles for active status
                            switch (status) {
                                case 'Pending': // Order just received
                                    progressBar.eq(0).addClass('active');
                                    $('#statusProcessed').find('img, p').css({
                                        "opacity": "1"
                                    });
                                    break;
                                case 'In Transit': // Order is being shipped but not delivered yet
                                    progressBar.eq(0).addClass('active');
                                    progressBar.eq(1).addClass('active');
                                    $('#statusProcessed, #statusShipped').find('img, p').css({
                                        "opacity": "1"
                                    });
                                    break;
                                case 'Delayed': // Order is delayed, keep the first three steps lit
                                    progressBar.eq(0).addClass('active');
                                    progressBar.eq(1).addClass('active');
                                    progressBar.eq(2).addClass('active');
                                    $('#statusProcessed, #statusShipped, #statusEnRoute').find(
                                        'img, p').css({
                                        "opacity": "1"
                                    });
                                    break;
                                case 'Delivered': // Order is fully delivered
                                    progressBar.eq(0).addClass('active');
                                    progressBar.eq(1).addClass('active');
                                    progressBar.eq(2).addClass('active');
                                    progressBar.eq(3).addClass('active');
                                    statusIcons.forEach(id => {
                                        $(id).find('img, p').css({
                                            "opacity": "1"
                                        });
                                    });
                                    break;
                                default:
                                    // No progress
                                    break;
                            }

                            // Show the modal with the populated data after the AJAX request is successful
                            modal.modal('show');
                        } else {
                            // Handle error if data is not found
                            modal.find('.modal-body').html(
                                '<p>Shipment details not available.</p>');
                        }
                    },
                    error: function() {
                        // Handle any AJAX error
                        modal.find('.modal-body').html(
                            '<p>Error fetching shipment details.</p>');
                    }
                });
            }, 100); // Small delay before making the AJAX request
        });
    </script>
    <style>
        /* Basic Styling for Body */
        /* Styling for Card */
        .card {
            z-index: 0;
            background-color: #ECEFF1;
            padding-bottom: 20px;
            margin-top: -20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            /* Adding some shadow for a clean look */
        }

        /* Top Section inside Card */
        .top {
            padding-top: 40px;
            padding-left: 13% !important;
            padding-right: 13% !important;
        }

        .card .d-flex p {
            margin: 5px 0;
            /* Reduce spacing between p tags */
            font-size: 14px;
            line-height: 1.6;
        }

        /* Progress Bar Styling */
        #progressbar {
            margin-bottom: 30px;
            overflow: hidden;
            color: #455A64;
            padding-left: 0px;
            margin-top: 30px;
            display: flex;
            justify-content: space-between;
            position: relative;
        }

        #progressbar li {
            list-style-type: none;
            font-size: 13px;
            width: 24%;
            text-align: center;
            position: relative;
            font-weight: 400;
        }

        #progressbar .step0:before {
            font-family: FontAwesome;
            content: "\f10c";
            /* Initial icon */
            color: #fff;
        }

        #progressbar li:before {
            width: 40px;
            height: 40px;
            line-height: 45px;
            display: block;
            font-size: 20px;
            background: #C5CAE9;
            border-radius: 50%;
            margin: auto;
            padding: 0px;
        }

        /* Progress Bar Connectors */
        #progressbar li:after {
            content: '';
            width: 100%;
            height: 12px;
            background: #C5CAE9;
            position: absolute;
            left: 0;
            top: 16px;
            z-index: -1;
        }

        #progressbar li:last-child:after {
            border-top-right-radius: 10px;
            border-bottom-right-radius: 10px;
            position: absolute;
            left: -50%;
        }

        #progressbar li:nth-child(2):after,
        #progressbar li:nth-child(3):after {
            left: -50%;
        }

        #progressbar li:first-child:after {
            border-top-left-radius: 10px;
            border-bottom-left-radius: 10px;
            position: absolute;
            left: 50%;
        }

        /* Active Step and Connector Styles */
        #progressbar li.active:before,
        #progressbar li.active:after {
            background: #651FFF;
            /* Highlighted active color */
        }

        #progressbar li.active:before {
            font-family: FontAwesome;
            content: "\f00c";
            /* Checkmark for active steps */
        }

        /* Styling for Icons */
        .icon {
            width: 60px;
            height: 60px;
            margin-right: 2px;
            margin-left: -20px;
            display: inline-block;
        }

        /* Icon Content Alignment */
        .icon-content {
            padding-bottom: 20px;
            display: flex;
            align-items: center;
            justify-content: center;

        }

        .icon-content p {
            font-weight: bold;
            text-align: left;
        }

        /* Responsiveness for smaller screens */
        @media screen and (max-width: 992px) {
            .icon-content {
                width: 50%;
                text-align: center;
            }

            /* Adjust progress bar and icon size */
            #progressbar li {
                width: 22%;
            }

            .icon {
                width: 50px;
                height: 50px;
            }
        }


        /* Customizing the container inside modal */
        #shipmentDetailsContent p {
            font-size: 1rem;
            font-weight: 500;
            color: #333;
        }

        #shipmentDetailsContent span {
            font-weight: normal;
            color: #555;
        }
    </style>


    <!-- Modal for Error Message -->
    <div class="modal fade" id="errorModal" tabindex="-1" role="dialog" aria-labelledby="errorModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="errorModalLabel">Error</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @if (session('error_message'))
                        <p>{{ session('error_message') }}</p>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            @if (session('error_message'))
                $('#errorModal').modal('show');
            @endif
        });
    </script>
