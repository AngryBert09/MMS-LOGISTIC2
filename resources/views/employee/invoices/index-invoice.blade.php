<!DOCTYPE html>
<html>

@include('layout.head')

<body>

    @include('employee.layout.navbar')

    @include('employee.layout.right-sidebar')

    @include('employee.layout.left-sidebar')

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
                                    <th>Total Amount</th>
                                    <th>Invoice Date</th>
                                    <th>Due Date</th>
                                    <th>Status</th>
                                    <th>Issued By</th>
                                    <th class="datatable-nosort">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($invoices as $invoice)
                                    <tr>
                                        <td class="table-plus">{{ $invoice->invoice_number }}</td>
                                        <td>₱{{ number_format($invoice->tax_amount, 2) }}</td>
                                        <td>₱{{ number_format($invoice->discount_amount, 2) }}</td>
                                        <td>₱{{ number_format($invoice->total_amount, 2) }}</td>
                                        <td>₱{{ number_format($invoice->purchaseOrder->total_amount, 2) }}</td>
                                        <td>{{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d-m-Y') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($invoice->due_date)->format('d-m-Y') }}</td>
                                        <td>
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
                                        <td>{{ $invoice->vendor->company_name }}</td>
                                        <td>
                                            <div class="dropdown">
                                                <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle"
                                                    href="#" role="button" data-toggle="dropdown">
                                                    <i class="dw dw-more"></i>
                                                </a>

                                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                                                    <a class="dropdown-item"
                                                        href="{{ route('employee.invoice.show', $invoice->invoice_id) }}">
                                                        <i class="dw dw-eye"></i> Invoice
                                                    </a>

                                                    @if ($invoice->status === 'unpaid' || $invoice->status === 'partial')
                                                        <a class="dropdown-item" href="#" data-toggle="modal"
                                                            data-target="#payModal{{ $invoice->invoice_id }}">
                                                            <i class="dw dw-money"></i> Pay Now
                                                        </a>
                                                    @endif

                                                    @if ($invoice->status === 'paid')
                                                        <a class="dropdown-item"
                                                            href="{{ route('employee.receipt', $invoice->invoice_id) }}">
                                                            <i class="icon-copy ion-ios-list-outline"></i> Receipt
                                                        </a>
                                                    @endif

                                                    <a class="dropdown-item" href="#" data-toggle="modal"
                                                        data-target="#historyModal{{ $invoice->invoice_id }}">
                                                        <i class="icon-copy ion-clock"></i> Transaction History
                                                    </a>
                                                </div>
                                            </div>


                                            <div class="modal fade" id="payModal{{ $invoice->invoice_id }}"
                                                tabindex="-1" role="dialog"
                                                aria-labelledby="payModalLabel{{ $invoice->invoice_id }}"
                                                aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <form method="POST"
                                                        action="{{ route('employee.invoice.update', $invoice->invoice_id) }}">
                                                        @csrf
                                                        @method('PUT')

                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title"
                                                                    id="payModalLabel{{ $invoice->invoice_id }}">
                                                                    Pay Invoice - {{ $invoice->invoice_number }}</h5>
                                                                <button type="button" class="close"
                                                                    data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>

                                                            <div class="modal-body">
                                                                <div class="form-group">
                                                                    <label>Amount to Pay</label>
                                                                    <input type="number" name="paymentAmount"
                                                                        class="form-control" step="0.01"
                                                                        max="{{ $invoice->total_amount }}"
                                                                        value="{{ $invoice->total_amount }}" required>
                                                                </div>

                                                                <div class="form-group">
                                                                    <label>Payment Method</label>
                                                                    <input type="text" name="paymentMethod"
                                                                        class="form-control"
                                                                        placeholder="e.g. Bank Transfer, PayPal, Cash"
                                                                        required>
                                                                </div>
                                                            </div>

                                                            <div class="modal-footer">
                                                                <button type="submit" class="btn btn-primary">Submit
                                                                    Payment</button>
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-dismiss="modal">Cancel</button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>

                                            <!-- Transaction History Modal -->
                                            <div class="modal fade" id="historyModal{{ $invoice->invoice_id }}"
                                                tabindex="-1" role="dialog"
                                                aria-labelledby="historyModalLabel{{ $invoice->invoice_id }}"
                                                aria-hidden="true">
                                                <div class="modal-dialog modal-lg" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title"
                                                                id="historyModalLabel{{ $invoice->invoice_id }}">
                                                                Transaction History -
                                                                {{ $invoice->invoice_number }}
                                                            </h5>
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>

                                                        <div class="modal-body">
                                                            @if ($invoice->transactionHistories->count())
                                                                <table class="table table-bordered">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>#</th>
                                                                            <th>Amount Paid</th>
                                                                            <th>Payment Method</th>
                                                                            <th>REF #</th>
                                                                            <!-- NEW COLUMN -->
                                                                            <th>Date & Time</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        @foreach ($invoice->transactionHistories as $index => $history)
                                                                            <tr>
                                                                                <td>{{ $index + 1 }}</td>
                                                                                <td>₱{{ number_format($history->amount_paid, 2) }}
                                                                                </td>
                                                                                <td>{{ $history->payment_method }}
                                                                                </td>
                                                                                <td>{{ $history->reference_number ?? 'N/A' }}
                                                                                </td>
                                                                                <!-- Display reference or 'N/A' if empty -->
                                                                                <td>{{ \Carbon\Carbon::parse($history->paid_at)->format('d-m-Y h:i A') }}
                                                                                </td>
                                                                            </tr>
                                                                        @endforeach
                                                                    </tbody>
                                                                </table>
                                                            @else
                                                                <p class="text-muted">No transaction history
                                                                    available.</p>
                                                            @endif
                                                        </div>

                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-dismiss="modal">Close</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
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
    <!-- Datatable Setting js -->
    <script src="{{ asset('js/datatable-setting.js') }}"></script>
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-NXZMQSS" height="0" width="0"
            style="display: none; visibility: hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
</body>

</html>
