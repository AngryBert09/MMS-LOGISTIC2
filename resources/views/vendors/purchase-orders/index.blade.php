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
                                        <td>
                                            @if ($order->invoices->isNotEmpty())
                                                <!-- Check if the invoices collection is not empty -->
                                                {{ $order->invoices->first()->invoice_number }}
                                                <!-- Use the correct property name -->
                                            @else
                                            @endif
                                        </td>
                                        <td>{{ $order->order_date }}</td>
                                        <td>{{ $order->delivery_date }}</td>
                                        <td>{{ $order->order_status }}</td>
                                        <td>{{ number_format($order->orderItems->sum('total_price'), 2) }}</td>
                                        <td>{{ $order->payment_terms }}</td>
                                        <td>{{ $order->delivery_location }}</td>
                                        <td>{{ $order->notes_instructions }}</td>
                                        <td>{{ $order->shipping_method }}</td>
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
