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
    @include('layout.right-sidebar')
    @include('layout.left-sidebar')
    <div class="mobile-menu-overlay"></div>

    <div class="main-container">
        <div class="pd-ltr-20 xs-pd-20-10">
            <div class="min-height-200px">
                @include('layout.breadcrumb')
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
                                    Date Issued: <strong
                                        class="weight-600">{{ $purchaseOrder->created_at->format('F j, Y') }}</strong>
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
                        <h4 class="text-center pb-20">INVOICE GENERATED</h4>
                        <div class="text-center">
                            <!-- Add margin to the Download Invoice button for spacing -->
                            <button id="download-btn" class="btn btn-warning" onclick="printInvoice()"
                                style="margin-bottom: 15px;">Download Invoice</button>

                            <form action="{{ route('invoices.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="purchase_order_id" value="{{ $purchaseOrder->po_id }}">

                                <!-- Create Invoice button -->
                                <button id="create-invoice-btn" type="submit" class="btn btn-success">Create
                                    Invoice</button>
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
                                    text-align: center;
                                    margin-left: -38px;
                                    margin-top: 100px;
                                }

                                .invoice-box {
                                    display: inline-block;
                                    margin: 0 auto;
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
            <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-NXZMQSS" height="0"
                    width="0" style="display: none; visibility: hidden"></iframe></noscript>
            <!-- End Google Tag Manager (noscript) -->
</body>

</html>
