<!DOCTYPE html>
<html>

<head>
    <!-- Basic Page Info -->
    <meta charset="utf-8" />
    <title>Purchase Orders</title>

    <!-- Site favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="images/gwa-touch-icon" />
    <link rel="icon" type="image/png" sizes="32x32" href="images/gwa-favicon-32x32.png" />
    <link rel="icon" type="image/png" sizes="16x16" href="vendors/images/favicon-16x16.png" />

    <!-- Mobile Specific Metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet" />
    <!-- CSS -->
    <link rel="stylesheet" type="text/css" href="css/core.css" />
    <link rel="stylesheet" type="text/css" href="css/icon-font.min.css" />
    <link rel="stylesheet" type="text/css" href="src/plugins/datatables/css/dataTables.bootstrap4.min.css" />
    <link rel="stylesheet" type="text/css" href="src/plugins/datatables/css/responsive.bootstrap4.min.css" />
    <link rel="stylesheet" type="text/css" href="css/style.css" />

    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-GBZ3SGGX85"></script>
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-2973766580778258"
        crossorigin="anonymous"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag("js", new Date());

        gtag("config", "G-GBZ3SGGX85");
    </script>
    <!-- Google Tag Manager -->
    <scrip>
        (function(w, d, s, l, i) {
        w[l] = w[l] || [];
        w[l].push({
        "gtm.start": new Date().getTime(),
        event: "gtm.js"
        });
        var f = d.getElementsByTagName(s)[0],
        j = d.createElement(s),
        dl = l != "dataLayer" ? "&l=" + l : "";
        j.async = true;
        j.src = "https://www.googletagmanager.com/gtm.js?id=" + i + dl;
        f.parentNode.insertBefore(j, f);
        })(window, document, "script", "dataLayer", "GTM-NXZMQSS");
        </script>
        <!-- End Google Tag Manager -->
</head>

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
                <div class="page-header">
                    <div class="row">
                        <div class="col-md-6 col-sm-12">
                            <div class="title">
                                <h4>Purchase Orders</h4>
                            </div>
                            <nav aria-label="breadcrumb" role="navigation">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item">
                                        <a href="index.html">Home</a>
                                    </li>
                                    <li class="breadcrumb-item active" aria-current="page">
                                        DataTable
                                    </li>
                                </ol>
                            </nav>
                        </div>
                        <div class="col-md-6 col-sm-12 text-right">
                            <div class="dropdown">
                                <a class="btn btn-dark dropdown-toggle" href="#" role="button"
                                    data-toggle="dropdown">
                                    January 2018
                                </a>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <a class="dropdown-item" href="#">Export List</a>
                                    <a class="dropdown-item" href="#">Policies</a>
                                    <a class="dropdown-item" href="#">View Assets</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Simple Datatable start -->
                <div class="card-box mb-30">
                    <div class="pd-20">
                        <h4 class="text-dark h4">Purchase Orders</h4>
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
                                    <th>Payment Terms</th>
                                    <th>Delivery Location</th>
                                    <th>Notes/Instructions</th>
                                    <th>Shipping Method</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($purchaseOrders as $order)
                                    <tr>
                                        <td class="table-plus">{{ $order->purchase_order_number }}</td>
                                        <td>{{ $order->invoice_number }}</td>
                                        <td>{{ $order->order_date }}</td>
                                        <td>{{ $order->delivery_date }}</td>
                                        <td>{{ $order->order_status }}</td>
                                        <td>{{ number_format($order->total_amount, 2) }}</td>
                                        <td>{{ $order->payment_terms }}</td>
                                        <td>{{ $order->delivery_location }}</td>
                                        <td>{{ $order->notes_instructions }}</td>
                                        <td>{{ $order->shipping_method }}</td>
                                        <td>
                                            <div class="dropdown">
                                                <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle"
                                                    href="#" role="button" data-toggle="dropdown">
                                                    <i class="dw dw-more"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                                                    <!-- View Action: Trigger Modal -->
                                                    <a class="dropdown-item view-purchase-order" href="#"
                                                        data-po-id="{{ $order->po_id }}" data-toggle="modal"
                                                        data-target="#viewPurchaseOrderModal">
                                                        <i class="dw dw-eye"></i> View
                                                    </a>


                                                    <!-- Edit Action: Display if order is in Draft status -->
                                                    @if ($order->order_status === 'Draft')
                                                        <a class="dropdown-item edit-purchase-order" href="#"
                                                            data-order='{{ json_encode($order) }}' data-toggle="modal"
                                                            data-target="#editPurchaseOrderModal">
                                                            <i class="dw dw-edit2"></i> Edit
                                                        </a>

                                                        <a class="dropdown-item confirm-purchase-order" href="#"
                                                            data-order='{"po_id": "{{ $order->po_id }}", "purchase_order_number": "{{ $order->purchase_order_number }}", "total_amount": {{ $order->total_amount }} }'
                                                            data-toggle="modal"
                                                            data-target="#confirmPurchaseOrderModal">
                                                            <i class="dw dw-check"></i> Confirm
                                                        </a>


                                                        <a class="dropdown-item reject-purchase-order" href="#"
                                                            data-order-id="{{ $order->po_id }}" data-toggle="modal"
                                                            data-target="#rejectPurchaseOrderModal">
                                                            <i class="dw dw-trash"></i> Reject
                                                        </a>
                                                    @elseif ($order->order_status === 'Confirmed')
                                                        <a class="dropdown-item cancel-purchase-order" href="#"
                                                            data-order-id="{{ $order->po_id }}" data-toggle="modal"
                                                            data-target="#cancelPurchaseOrderModal">
                                                            <i class="dw dw-cancel"></i> Cancel
                                                        </a>
                                                    @elseif ($order->order_status === 'Rejected')
                                                        <a class="dropdown-item resubmit-purchase-order"
                                                            href="#" data-order-id="{{ $order->po_id }}"
                                                            data-toggle="modal"
                                                            data-target="#resubmitPurchaseOrderModal">
                                                            <i class="dw dw-refresh"></i> Re-submit
                                                        </a>
                                                    @endif

                                                    <!-- Delete Action: Delete only if order is in a cancellable state -->
                                                    @include('vendors.purchase-orders.destroy');
                                                </div>
                                            </div>
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

    <!-- CANCEL MODAL -->
    <div class="modal fade" id="cancelPurchaseOrderModal" tabindex="-1" role="dialog"
        aria-labelledby="cancelPurchaseOrderModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cancelPurchaseOrderModalLabel">Cancel Purchase Order</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="cancelPurchaseOrderForm" method="POST" action="">
                        @csrf
                        @method('PUT')

                        <!-- Hidden input to specify the action -->
                        <input type="hidden" name="action" value="cancel">

                        <!-- Hidden input to store order ID -->
                        <input type="hidden" id="cancel_order_id" name="order_id">

                        <p>Are you sure you want to cancel this purchase order?</p>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">No, Keep Order</button>
                    <button type="submit" form="cancelPurchaseOrderForm" class="btn btn-danger">Yes, Cancel
                        Order</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).on('click', '.cancel-purchase-order', function() {
            var orderId = $(this).data('order-id');

            // Set the hidden input value for the order ID
            $('#cancel_order_id').val(orderId);

            // Set the form action URL for canceling the order
            var actionUrl = "{{ route('purchase-orders.update', '') }}/" + orderId;
            $('#cancelPurchaseOrderForm').attr('action', actionUrl);

            console.log("Cancel action URL:", actionUrl);
        });
    </script>



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
