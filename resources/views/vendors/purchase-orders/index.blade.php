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


    @include('layout.nav')

    @include('layout.left-sidebar')

    @include('layout.right-sidebar')

    <div class="mobile-menu-overlay"></div>

    <div class="main-container">
        <div class="pd-ltr-20 xs-pd-20-10">
            <div class="min-height-200px">
                @include('layout.breadcrumb')
                <!-- Simple Datatable start -->
                <div class="card-box mb-30">
                    <div class="pd-20">
                        <h4 class="text-warning h4">Purchase Orders</h4>
                        <p class="text-muted">All purchase orders are for Great Wall Arts.</p>
                    </div>
                    <div class="pb-20">
                        <table class="data-table table stripe hover nowrap">
                            <thead>
                                <tr>
                                    <th class="table-plus">PO #</th>
                                    <th>Invoice Number</th>
                                    <th>Order Date</th>
                                    <th>Delivery Date</th>
                                    <th>Order Status</th>
                                    <th>Total Amount</th>

                                    {{-- <th>Delivery Location</th>
                                    <th>Notes/Instructions</th>
                                    <th>Shipping Method</th> --}}
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
                                        <td>{{ $order->order_date }}</td>
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
                                        <td>{{ number_format($order->orderItems->sum('total_price'), 2) }}</td>
                                        {{-- <td>{{ $order->delivery_location }}</td>
                                        <td>{{ $order->notes_instructions }}</td>
                                        <td>{{ $order->shipping_method }}</td> --}}
                                        <td>
                                            @include('vendors.purchase-orders.actions')
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Edit Purchase Order Modal
         Confirm Purchase Order Modal
         Reject Purchase Order Modal
         Resubmission Purchase Order Modal -->
    @include('vendors.purchase-orders.edit')

    <!-- VIEW MODAL -->
    @include('vendors.purchase-orders.show')





    <!-- welcome modal end -->
    <!-- js -->
    <!-- jQuery -->
    <script src="js/core.js"></script>
    <script src="js/script.min.js"></script>
    <script src="js/process.js"></script>
    <script src="js/layout-settings.js"></script>
    <script src="src/plugins/datatables/js/jquery.dataTables.min.js"></script>
    <script src="src/plugins/datatables/js/dataTables.bootstrap4.min.js"></script>
    <script src="src/plugins/datatables/js/dataTables.responsive.min.js"></script>
    <script src="src/plugins/datatables/js/responsive.bootstrap4.min.js"></script>
    <!-- buttons for Export datatable -->
    <script src="src/plugins/datatables/js/dataTables.buttons.min.js"></script>
    <script src="src/plugins/datatables/js/buttons.bootstrap4.min.js"></script>
    <script src="src/plugins/datatables/js/buttons.print.min.js"></script>
    <script src="src/plugins/datatables/js/buttons.html5.min.js"></script>
    <script src="src/plugins/datatables/js/buttons.flash.min.js"></script>
    <script src="src/plugins/datatables/js/pdfmake.min.js"></script>
    <script src="src/plugins/datatables/js/vfs_fonts.js"></script>



    <!-- Datatable Setting js -->
    <script src="js/datatable-setting.js"></script>
    <!-- Google Tag Manager (noscript) -->

    <!-- End Google Tag Manager (noscript) -->
</body>

</html>
