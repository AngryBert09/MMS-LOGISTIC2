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
                <!-- Simple Datatable Start -->
                <div class="card-box mb-30">
                    <div class="pd-20">
                        <h4 class="text-warning h4">Purchase Receipts</h4>
                        <p class="mb-0">All purchase receipts are for Great Wall Arts.</p>
                    </div>
                    <div class="pb-20">
                        <table class="data-table table stripe hover nowrap">
                            <thead>
                                <tr>
                                    <th class="table-plus datatable-nosort">Receipt No</th>
                                    <th>Vendor Name</th> <!-- Adjusted the column header -->
                                    <th>PO #</th>
                                    <!-- If you want to show PO number, make sure to have a field for it -->
                                    <th>Total Amount</th>
                                    <th>Date Issue</th>
                                    <th>Status</th>
                                    <th class="datatable-nosort">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($receipts as $receipt)
                                    <tr>
                                        <td class="table-plus">{{ $receipt->receipt_number }}</td>
                                        <td>{{ $receipt->vendor->company_name ?? 'N/A' }}</td>
                                        <td>{{ $receipt->purchaseOrder->purchase_order_number }}</td>
                                        <td>${{ number_format($receipt->total_amount, 2) }}</td>
                                        <td>{{ date('Y-m-d', strtotime($receipt->receipt_date)) }}</td>

                                        <td>{{ ucfirst($receipt->status) }}</td> <!-- Displaying status -->
                                        <td>
                                            <div class="dropdown">
                                                <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle"
                                                    href="#" role="button" data-toggle="dropdown">
                                                    <i class="dw dw-more"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                                                    {{-- <a class="dropdown-item"
                                                        href="{{ route('receipts.show', $receipt->receipt_id) }}">
                                                        <i class="dw dw-eye"></i> View
                                                    </a>
                                                    <a class="dropdown-item"
                                                        href="{{ route('receipts.edit', $receipt->receipt_id) }}">
                                                        <i class="dw dw-edit2"></i> Edit
                                                    </a> --}}
                                                    <form action="{{ route('receipts.destroy', $receipt->receipt_id) }}"
                                                        method="POST" style="display:inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="dropdown-item"
                                                            onclick="return confirm('Are you sure you want to delete this receipt?');">
                                                            <i class="dw dw-delete-3"></i> Delete
                                                        </button>
                                                    </form>
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
                <!-- multiple select row Datatable start -->

                <!-- multiple select row Datatable End -->
                <!-- Checkbox select Datatable start -->

                <!-- Checkbox select Datatable End -->
                <!-- Export Datatable start -->

                <!-- Export Datatable End -->
            </div>
        </div>
    </div>
    <!-- welcome modal end -->
    <!-- js -->
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
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-NXZMQSS" height="0" width="0"
            style="display: none; visibility: hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
</body>

</html>
