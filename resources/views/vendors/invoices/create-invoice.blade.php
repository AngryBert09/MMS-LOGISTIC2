<!DOCTYPE html>
<html>

<head>
    <!-- Basic Page Info -->
    <meta charset="utf-8" />
    <title>Create Invoice</title>

    <!-- Site favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/gwa-touch-icon') }}" />
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/gwa-favicon-32x32.png') }}" />
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('vendors/images/favicon-16x16.png') }}" />

    <!-- Mobile Specific Metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet" />
    <!-- CSS -->
    <link rel="stylesheet" type="text/css" href="{{ asset('css/core.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('css/icon-font.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('css/style.css') }}" />


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
    <script>
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
    @include('layout.right-sidebar')
    @include('layout.left-sidebar')
    <div class="mobile-menu-overlay"></div>

    <div class="main-container">
        <div class="pd-ltr-20 xs-pd-20-10">
            <div class="min-height-200px">
                <div class="page-header">
                    <div class="row">
                        <div class="col-md-6 col-sm-12">
                            <div class="title">
                                <h4>Create Invoice</h4>
                            </div>
                            <nav aria-label="breadcrumb" role="navigation">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item">
                                        <a href="{{ route('dashboard') }}">Home</a>
                                    </li>
                                    <li class="breadcrumb-item active" aria-current="page">
                                        Create Invoice
                                    </li>
                                </ol>
                            </nav>
                        </div>
                        <div class="col-md-6 col-sm-12 text-right">
                            <div class="dropdown">
                                <a class="btn btn-primary dropdown-toggle" href="#" role="button"
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
                <div class="invoice-wrap">
                    <div class="invoice-box">
                        <div class="invoice-header">
                            <div class="logo text-center">
                                <img src="{{ asset('vendors/images/deskapp-logo.png') }}" alt="" />
                            </div>
                        </div>
                        <h4 class="text-center mb-30 weight-600">INVOICE</h4>
                        <div class="row pb-30">
                            <div class="col-md-6">
                                <h5 class="mb-15">Great Wall Arts</h5>
                                <p class="font-14 mb-5">
                                    Date Issued: <strong class="weight-600">{{ $purchaseOrder->created_at }}</strong>
                                </p>
                                <p class="font-14 mb-5">
                                    Invoice No: <strong class="weight-600">{{ $purchaseOrder->invoice_id }}</strong>
                                </p>
                            </div>
                            {{-- <div class="col-md-6">
                                <div class="text-right">
                                    <p class="font-14 mb-5">{{ $vendor->company_name }}</p>
                                    <p class="font-14 mb-5">{{ $vendor->address }}</p>
                                    <p class="font-14 mb-5">{{ $vendor->city }}</p>
                                    <p class="font-14 mb-5">{{ $vendor->postcode }}</p>
                                </div>
                            </div> --}}
                        </div>
                        <div class="invoice-desc pb-30">
                            <div class="invoice-desc-head clearfix">
                                <div class="invoice-sub">Item Description</div>
                                <div class="invoice-rate">Unit Price</div>
                                <div class="invoice-hours">Quantity</div>
                                <div class="invoice-subtotal">Total Price</div>
                            </div>
                            <div class="invoice-desc-body">
                                <ul>
                                    @foreach ($orderItems as $item)
                                        <li class="clearfix">
                                            <div class="invoice-sub">{{ $item->item_description }}</div>
                                            <div class="invoice-rate">${{ number_format($item->unit_price, 2) }}</div>
                                            <div class="invoice-hours">{{ $item->quantity }}</div>
                                            <div class="invoice-subtotal">
                                                <span
                                                    class="weight-600">${{ number_format($item->total_price, 2) }}</span>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                            <div class="invoice-desc-footer">
                                <div class="invoice-desc-head clearfix">
                                    <div class="invoice-sub">Bank Info</div>
                                    <div class="invoice-rate">Due By</div>
                                    <div class="invoice-subtotal">Total Due</div>
                                </div>
                                <div class="invoice-desc-body">
                                    <ul>
                                        <li class="clearfix">
                                            <div class="invoice-sub">
                                                <p class="font-14 mb-5">
                                                    Account No:
                                                    <strong class="weight-600">123 456 789</strong>
                                                </p>
                                                <p class="font-14 mb-5">
                                                    Code: <strong class="weight-600">4556</strong>
                                                </p>
                                            </div>
                                            <div class="invoice-rate font-20 weight-600">
                                                {{ $purchaseOrder->due_date }}
                                            </div>
                                            <div class="invoice-subtotal">
                                                <span
                                                    class="weight-600 font-24 text-danger">${{ number_format($orderItems->sum('total_price'), 2) }}</span>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <h4 class="text-center pb-20">Thank You!!</h4>
                        <div class="text-center">
                            <button id="download-btn" class="btn btn-primary" onclick="printInvoice()">Download
                                Invoice</button>
                            <form action="{{ route('invoices.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="purchase_order_id" value="{{ $purchaseOrder->po_id }}">
                                <button type="submit" class="btn btn-success">Create Invoice</button>
                            </form>
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

            </div>
        </div>
    </div>


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