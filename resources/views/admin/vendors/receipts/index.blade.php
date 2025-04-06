<!DOCTYPE html>
<html>
@include('layout.head')

<body>


    @include('employee.layout.navbar')

    @include('employee.layout.left-sidebar')

    @include('employee.layout.right-sidebar')
    <div class="mobile-menu-overlay"></div>

    <div class="main-container">
        <div class="pd-ltr-20 xs-pd-20-10">
            <div class="min-height-200px">
                @include('layout.breadcrumb')
                <div class="invoice-wrap">
                    <div class="invoice-box">
                        <div class="invoice-header">
                            <div class="logo text-center">
                                <img src="{{ asset('images/greatwall-logo.png') }}" alt="Logo" width="100"
                                    height="40" />

                            </div>
                        </div>
                        <h4 class="text-center mb-30 weight-600">RECEIPT</h4>
                        <div class="row pb-30">
                            <div class="col-md-6">
                                <h5 class="mb-15">{{ $receipt->vendor->company_name ?? 'N/A' }}</h5>
                                <p class="font-14 mb-5">
                                    Date Issued: <strong
                                        class="weight-600">{{ date('F j, Y', strtotime($receipt->receipt_date)) }}</strong>
                                </p>
                                <p class="font-14 mb-5">
                                    Receipt No: <strong class="weight-600">{{ $receipt->receipt_number }}</strong>
                                </p>
                                <p class="font-14 mb-5">
                                    PO Number: <strong
                                        class="weight-600">{{ $receipt->purchaseOrder->purchase_order_number ?? 'N/A' }}</strong>
                                </p>
                                <p class="font-14 mb-5">
                                    Invoice ID: <strong class="weight-600">{{ $receipt->invoice_id ?? 'N/A' }}</strong>
                                </p>
                            </div>
                        </div>

                        <div class="invoice-desc pb-30">
                            <div class="invoice-desc-head clearfix">
                                <div class="invoice-sub">Item Description</div>
                                <div class="invoice-hours">Quantity</div>
                                <div class="invoice-subtotal">Total Price (₱)</div>
                            </div>
                            <div class="invoice-desc-body">
                                <ul>
                                    @foreach ($receipt->orderItems as $item)
                                        <li class="clearfix">
                                            <div class="invoice-sub">{{ $item->item_description }}</div>
                                            <div class="invoice-hours">{{ $item->quantity }}</div>
                                            <div class="invoice-subtotal">
                                                <span
                                                    class="weight-600">₱{{ $item->purchaseOrder->total_amount }}</span>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>

                            <div class="invoice-desc-footer">
                                <div class="invoice-desc-head clearfix">
                                    <div class="invoice-sub">Payment Details</div>
                                    <div class="invoice-rate">Paid On</div>
                                    <div class="invoice-subtotal">Total Amount (₱)</div>
                                </div>
                                <div class="invoice-desc-body">
                                    <ul>
                                        <li class="clearfix">
                                            <div class="invoice-sub">
                                                <p class="font-14 mb-5">
                                                    Payment Method:
                                                    <strong
                                                        class="weight-600">{{ ucfirst($receipt->payment_method) ?? 'N/A' }}</strong>
                                                </p>
                                                <p class="font-14 mb-5">
                                                    Tax Amount:
                                                    <strong
                                                        class="weight-600">{{ number_format($receipt->tax_amount, 2) }}
                                                        {{ $receipt->currency }}</strong>
                                                </p>
                                                <p class="font-14 mb-5">
                                                    Status:
                                                    <strong
                                                        class="weight-600 text-uppercase">{{ $receipt->status }}</strong>
                                                </p>
                                            </div>
                                            <div class="invoice-rate font-20 weight-600">
                                                {{ date('F j, Y', strtotime($receipt->updated_at)) }}
                                            </div>
                                            <div class="invoice-subtotal">
                                                <span class="weight-600 font-24 text-success">
                                                    {{ $item->purchaseOrder->total_amount }}
                                                    {{ $receipt->currency }}
                                                </span>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        @if (!empty($receipt->notes))
                            <div class="invoice-desc-footer">
                                <div class="invoice-desc-head clearfix">
                                    <div class="invoice-sub">Notes</div>
                                </div>
                                <div class="invoice-desc-body">
                                    <p class="font-14">{{ $receipt->notes }}</p>
                                </div>
                            </div>
                        @endif

                        <div class="text-center">
                            <button id="download-btn" class="btn btn-success" onclick="printReceipt()">Download
                                Receipt</button>
                        </div>

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>
                </div>

                <script>
                    function printReceipt() {
                        window.print();
                    }
                </script>


                <script>
                    function printReceipt() {
                        window.print();
                    }
                </script>

            </div>
        </div>
        <script>
            function printInvoice() {
                window.print();
            }
        </script>

        <style>
            @media print {

                /* Hide everything except the invoice */
                body * {
                    visibility: hidden;
                }

                .invoice-wrap,
                .invoice-wrap * {
                    visibility: visible;
                }

                #download-btn,
                #create-invoice-btn {
                    display: none;
                }

                .invoice-wrap {
                    position: absolute;
                    left: 0;
                    top: 0;
                    width: 100%;
                    /* Full width for centering */
                    text-align: center;
                    /* Center the text */
                    margin-left: -38px;
                    margin-top: 100px
                        /* Center the content */
                }

                .invoice-box {
                    display: inline-block;
                    /* Center the invoice box */
                    margin: 0 auto;
                    /* Center the box */
                }
            }
        </style>


        <!-- js -->
        <script src="{{ asset('js/core.js') }}"></script>
        <script src="{{ asset('js/script.min.js') }}"></script>
        <script src="{{ asset('js/process.js') }}"></script>
        <script src="{{ asset('js/layout-settings.js') }}"></script>

        <!-- Google Tag Manager (noscript) -->
        <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-NXZMQSS" height="0" width="0"
                style="display: none; visibility: hidden"></iframe></noscript>
        <!-- End Google Tag Manager (noscript) -->
</body>

</html>
