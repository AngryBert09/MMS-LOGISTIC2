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
                 <div class="table-responsive">
                     <table class="table">
                         <tbody>
                             <tr>
                                 <th>PO #</th>
                                 <td id="modal-po-number">PO000011</td>
                             </tr>
                             <tr>
                                 <th>Invoice Number</th>
                                 <td id="modal-invoice-number">INV123456</td>
                             </tr>
                             <tr>
                                 <th>Order Date</th>
                                 <td id="modal-order-date">10/01/2023</td>
                             </tr>
                             <tr>
                                 <th>Delivery Date</th>
                                 <td id="modal-delivery-date">10/11/2023</td>
                             </tr>
                             <tr>
                                 <th>Order Status</th>
                                 <td id="modal-order-status">Approved</td>
                             </tr>
                             <tr>
                                 <th>Total Amount</th>
                                 <td id="modal-total-amount">IDR 35,000,000</td>
                             </tr>
                             <tr>
                                 <th>Payment Terms</th>
                                 <td id="modal-payment-terms">Net 30</td>
                             </tr>
                             <tr>
                                 <th>Delivery Location</th>
                                 <td id="modal-delivery-location">Jakarta, Indonesia</td>
                             </tr>
                             <tr>
                                 <th>Notes/Instructions</th>
                                 <td id="modal-notes">Handle with care</td>
                             </tr>
                             <tr>
                                 <th>Shipping Method</th>
                                 <td id="modal-shipping-method">Express Delivery</td>
                             </tr>
                         </tbody>
                     </table>
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
                     // Fill modal with data
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
