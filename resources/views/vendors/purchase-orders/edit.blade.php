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
                    <button type="submit" form="confirmPurchaseOrderForm" class="btn btn-primary">Confirm</button>
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
                <div class="modal-header">
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
                            <button type="submit" class="btn btn-primary">Initiate Fulfillment</button>
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
