<!DOCTYPE html>
<html>

@include('layout.head')

<body>

    @include('layout.nav')

    @include('layout.right-sidebar')

    @include('layout.left-sidebar')

    <div class="mobile-menu-overlay"></div>

    <div class="main-container">
        <div class="pd-ltr-20 xs-pd-20-10">
            <div class="min-height-200px">
                @include('layout.breadcrumb')
                <!-- Simple Datatable start -->
                <div class="card-box mb-30">
                    <div class="pd-20">
                        <h4 class="text-warning h4">Invoices</h4>
                        <p class="text-muted">All invoices are for Great Wall Arts.</p>
                    </div>
                    <div class="pb-20">
                        <table class="data-table table stripe hover nowrap">
                            <thead>
                                <tr>
                                    <th class="table-plus datatable-nosort">Invoice #</th>
                                    <th>Tax Amount</th>
                                    <th>Discount Amount</th>
                                    <th>Balance</th>
                                    <th>Invoice Date</th>
                                    <th>Due Date</th>
                                    <th>Status</th>
                                    <th class="datatable-nosort">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($invoices as $invoice)
                                    <tr>
                                        <td class="table-plus">{{ $invoice->invoice_number }}</td>
                                        <td>${{ number_format($invoice->tax_amount, 2) }}</td>
                                        <td>${{ number_format($invoice->discount_amount, 2) }}</td>
                                        <td>${{ number_format($invoice->total_amount, 2) }}</td>
                                        <td>{{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d-m-Y') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($invoice->due_date)->format('d-m-Y') }}</td>
                                        <td>
                                            <!-- Status Badge for Invoices -->
                                            @if ($invoice->status === 'paid')
                                                <span class="badge badge-success">{{ ucfirst($invoice->status) }}</span>
                                            @elseif ($invoice->status === 'unpaid')
                                                <span class="badge badge-danger">{{ ucfirst($invoice->status) }}</span>
                                            @elseif ($invoice->status === 'overdue')
                                                <span class="badge badge-warning">{{ ucfirst($invoice->status) }}</span>
                                            @else
                                                <span
                                                    class="badge badge-secondary">{{ ucfirst($invoice->status) }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @include('vendors.invoices.actions-invoice')
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>

                        </table>
                    </div>
                </div>

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
