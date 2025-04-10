<!DOCTYPE html>
<html>

@include('layout.head')

<body>
    {{-- <div class="pre-loader">
        <div class="pre-loader-box">
            <div class="loader-logo">
                <img src="vendors/images/deskapp-logo.svg" alt="" />
            </div>
            <div class="loader-progress" id="progress_div">
                <div class="bar" id="bar1"></div>
            </div>
            <div class="percent" id="percent1">0%</div>
            <div class="loading-text">Loading...</div>
        </div>
    </div> --}}


    @include('admin.layout.navbar')

    @include('admin.layout.left-sidebar')

    @include('admin.layout.right-sidebar')

    <div class="mobile-menu-overlay"></div>

    <div class="main-container">
        <div class="pd-ltr-20 xs-pd-20-10">
            <div class="min-height-200px">
                @include('layout.breadcrumb')
                <!-- Simple Datatable start -->
                <div class="card-box mb-30">
                    <div class="pd-20">
                        <h4 class="text-warning h4">Order Management</h4>
                        <p class="text-muted">All purchase orders are for Great Wall Arts. Below is the list of all
                            orders with their details including PO number, invoice number, order date, delivery date,
                            status, and total amount.</p>
                    </div>
                    <div class="pb-20">
                        <table class="table table-striped table-hover nowrap dt-responsive" style="width:100%">
                            <thead>
                                <tr>
                                    <th class="table-plus">PO #</th>
                                    <th>Invoice Number</th>
                                    <th>Order Date</th>
                                    <th>Delivery Date</th>
                                    <th>Order Status</th>
                                    <th>Total Amount</th>
                                    <th>Issued to</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($purchaseOrders as $order)
                                    <tr>
                                        <td class="table-plus">{{ $order->purchase_order_number }}</td>
                                        <td>
                                            @if ($order->invoices->isNotEmpty())
                                                {{ $order->invoices->first()->invoice_number }}
                                            @endif
                                        </td>
                                        <td>
                                            {{ $order->order_date }}
                                            <!-- Check if the order date is within the last 2 days -->
                                            @if (\Carbon\Carbon::parse($order->order_date)->diffInDays(now()) <= 2)
                                                <span class="badge badge-info ml-2">NEW</span>
                                            @endif
                                        </td>
                                        <td>{{ $order->delivery_date }}</td>
                                        <td>
                                            <!-- Status Badge -->
                                            @if ($order->order_status === 'Pending Approval')
                                                <span class="badge badge-warning">{{ $order->order_status }}</span>
                                            @elseif ($order->order_status === 'Approved')
                                                <span class="badge badge-success">{{ $order->order_status }}</span>
                                            @elseif ($order->order_status === 'Rejected')
                                                <span class="badge badge-danger">{{ $order->order_status }}</span>
                                            @elseif ($order->order_status === 'On hold')
                                                <span class="badge badge-secondary">{{ $order->order_status }}</span>
                                            @elseif ($order->order_status === 'In Transit')
                                                <span class="badge badge-primary">{{ $order->order_status }}</span>
                                            @elseif ($order->order_status === 'Completed')
                                                <span class="badge badge-info">{{ $order->order_status }}</span>
                                            @elseif ($order->order_status === 'In Progress')
                                                <span class="badge badge-light">{{ $order->order_status }}</span>
                                            @else
                                                <span class="badge badge-dark">{{ $order->order_status }}</span>
                                            @endif
                                        </td>
                                        <td>₱{{ number_format($order->total_amount, 2) }}
                                        <td>{{ $order->vendor->company_name }}</td>
                                        <td>
                                            <button class="btn btn-primary btn-sm viewOrderBtn" data-toggle="modal"
                                                data-target="#viewPurchaseOrderModal" data-id="{{ $order->po_id }}">
                                                <i class="icon-copy fa fa-eye" aria-hidden="true"></i> View
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>


                        </table>
                    </div>
                </div>
                <!-- Simple Datatable End -->
            </div>
        </div>
    </div>

    <!-- Ensure jQuery is loaded before this script -->
    <!-- View Order Modal -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

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
                            <a class="nav-link" id="order-items-tab" data-toggle="tab" href="#order-items"
                                role="tab" aria-controls="order-items" aria-selected="false">Order Items</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="timeline-tab" data-toggle="tab" href="#timeline" role="tab"
                                aria-controls="timeline" aria-selected="false">Timeline</a>
                        </li>
                    </ul>
                    <div class="tab-content mt-3" id="purchaseOrderTabContent">
                        <div class="tab-pane fade show active" id="po-info" role="tabpanel"
                            aria-labelledby="po-info-tab">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <tbody>
                                        <tr>
                                            <th scope="row">PO #</th>
                                            <td id="modal-po-number"></td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Invoice Number</th>
                                            <td id="modal-invoice-number"></td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Order Date</th>
                                            <td id="modal-order-date"></td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Delivery Date</th>
                                            <td id="modal-delivery-date"></td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Order Status</th>
                                            <td id="modal-order-status"></td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Total Amount</th>
                                            <td id="modal-total-amount"></td>
                                        </tr>

                                        <tr>
                                            <th scope="row">Delivery Location</th>
                                            <td id="modal-delivery-location"></td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Notes/Instructions</th>
                                            <td id="modal-notes"></td>
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
                <!-- Modal Footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <form id="viewInvoiceForm" action="{{ route('invoices.create') }}" method="GET"
                        style="display:inline;">
                        @csrf
                        <!-- Hidden fields for PO ID and Vendor ID -->
                        <input type="hidden" id="modal-po-id" name="po_id">
                        <input type="hidden" id="modal-vendor-id" name="vendor_id">
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // Handle the purchase order modal display
            $('#viewPurchaseOrderModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget); // Button that triggered the modal
                var po_id = button.data('id'); // Get the PO ID from the data attribute
                var vendorId = button.data('vendor-id'); // Extract Vendor ID from data-* attributes

                console.log("PO ID:", po_id); // Debugging line to check PO ID
                console.log("Form Vendor ID:", vendorId);

                // AJAX request to get purchase order details
                $.ajax({
                    url: '/admin/order-details/' + po_id, // Ensure the URL is correct
                    type: 'GET',
                    success: function(data) {
                        console.log("Data received:",
                            data); // Debugging line to check received data

                        // Fill modal fields with data
                        $('#modal-po-id').val(po_id); // Set PO ID as value
                        $('#modal-vendor-id').val(vendorId); // Set Vendor ID

                        // Populate modal fields
                        $('#modal-po-number').text(data.purchase_order_number || 'N/A');
                        $('#modal-invoice-number').text(data.invoice_number || 'N/A');
                        $('#modal-order-date').text(data.order_date || 'N/A');
                        $('#modal-delivery-date').text(data.delivery_date || 'N/A');
                        $('#modal-order-status').text(data.order_status || 'N/A');

                        // Ensure totalAmount is initialized as a number

                        $('#modal-total-amount').text(data.total_amount);


                        $('#modal-delivery-location').text(data.delivery_location || 'N/A');
                        $('#modal-notes').text(data.notes_instructions || 'N/A');


                        // Disable "Create Invoice" button if the status is "Rejected" or an invoice already exists
                        if (data.order_status === 'Rejected' || data.invoice_number) {
                            $('#createInvoiceButton').prop('disabled',
                                true); // Disable button by ID
                            $('#invoiceStatusMessage').text(
                                'Invoice has already been issued.'); // Display message
                        } else if (data.order_status === 'Pending Approval' || data
                            .invoice_number) {
                            $('#createInvoiceButton').prop('disabled',
                                true); // Disable button by ID
                            $('#invoiceStatusMessage').text(
                                'Invoice has already been issued.'); // Display message
                        } else {
                            $('#createInvoiceButton').prop('disabled',
                                false); // Enable button by ID
                            $('#invoiceStatusMessage').text(''); // Clear message
                        }

                        // Populate order items
                        $('#modal-order-items-body').empty(); // Clear previous items
                        if (data.order_items && data.order_items.length > 0) {
                            data.order_items.forEach(function(item) {
                                $('#modal-order-items-body').append(
                                    '<tr>' +
                                    '<td>' + item.item_description + '</td>' +
                                    '<td>' + item.quantity + '</td>' +

                                    '<td>' + item.total_price + '</td>' +
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
                                    '<div class="timeline-date">' +
                                    formattedDate +
                                    '</div>' +
                                    '<div class="timeline-desc card-box">' +
                                    '<div class="pd-20">' +
                                    '<h4 class="mb-10 h4">' + event
                                    .event_title +
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

            // Handle create invoice button click
            $('#createInvoiceButton').on('click', function() {
                // Get PO ID and Vendor ID from modal hidden inputs
                var poId = $('#modal-po-id').val(); // Use .val() to get the value from the hidden input
                var vendorId = $('#modal-vendor-id').val(); // This is already correct

                // Debugging: Log the values to the console
                console.log("PO ID: " + poId);
                console.log("Vendor ID: " + vendorId);

                // Check if both values exist before submitting the form
                if (poId && vendorId) {
                    $('#viewInvoiceForm').submit(); // Submit the form to the preview route
                } else {
                    alert('Error: PO ID or Vendor ID is missing!');
                }
            });
        });
    </script>


    <!-- Edit Purchase Order Modal
         Confirm Purchase Order Modal
         Reject Purchase Order Modal
         Resubmission Purchase Order Modal -->


    <!-- VIEW MODAL -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>




    <!-- welcome modal end -->
    <!-- js -->
    <!-- jQuery -->
    <script src="{{ asset('js/core.js') }}"></script>
    <script src="{{ asset('js/script.min.js') }}"></script>
    <script src="{{ asset('js/process.js') }}"></script>
    <script src="{{ asset('js/layout-settings.js') }}"></script>
    <script src="{{ asset('src/plugins/datatables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('src/plugins/datatables/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('src/plugins/datatables/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('src/plugins/datatables/js/responsive.bootstrap4.min.js') }}"></script>
    <!-- buttons for Export datatable -->
    <script src="{{ asset('src/plugins/datatables/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('src/plugins/datatables/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('src/plugins/datatables/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('src/plugins/datatables/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('src/plugins/datatables/js/buttons.flash.min.js') }}"></script>
    <script src="{{ asset('src/plugins/datatables/js/pdfmake.min.js') }}"></script>
    <script src="{{ asset('src/plugins/datatables/js/vfs_fonts.js') }}"></script>

    <script>
        $(document).ready(function() {
            $('.table').DataTable({
                order: [
                    [3, "desc"]
                ],

                responsive: true,
                paging: true,
                searching: true,
                ordering: true,
                info: true,
                autoWidth: false
            });
        });
    </script>

    <!-- Datatable Setting js -->
    <script src="{{ asset('js/datatable-setting.js') }}"></script>
    <!-- Google Tag Manager (noscript) -->

    <!-- End Google Tag Manager (noscript) -->
</body>

</html>
