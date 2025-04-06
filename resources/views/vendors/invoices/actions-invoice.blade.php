<div class="dropdown">
    {{-- <a class="dropdown-item" href="{{ route('paypal.createOrder', $invoice->invoice_id) }}">
        <i class="dw dw-money"></i> Pay Now
    </a> --}} <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" href="#"
        role="button" data-toggle="dropdown">
        <i class="dw dw-more"></i>
    </a>

    <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
        <a class="dropdown-item" href="{{ route('invoices.show', $invoice->invoice_id) }}">
            <i class="dw dw-eye"></i> View
        </a>

        @if (!in_array($invoice->status, ['paid', 'partial', 'overdue']))
            <a class="dropdown-item" href="{{ route('invoices.edit', $invoice->invoice_id) }}">
                <i class="dw dw-edit2"></i> Edit
            </a>
        @endif



        <a class="dropdown-item" href="#" data-toggle="modal"
            data-target="#historyModal{{ $invoice->invoice_id }}">
            <i class="icon-copy ion-clock"></i> Transaction History
        </a>
    </div>




    <div class="modal fade" id="historyModal{{ $invoice->invoice_id }}" tabindex="-1" role="dialog"
        aria-labelledby="historyModalLabel{{ $invoice->invoice_id }}" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="historyModalLabel{{ $invoice->invoice_id }}">
                        Transaction History -
                        {{ $invoice->invoice_number }}
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
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
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    {{-- </div>class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" --}}

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


    <script>
        $(document).ready(function() {
            $('.pay-now-btn').click(function(e) {
                e.preventDefault();
                let invoiceId = $(this).data('invoice-id');
                let payUrl = "{{ route('paypal.createOrder', ':id') }}".replace(':id', invoiceId);
                window.location.href = payUrl; // Redirect to PayPal
            });
        });
    </script>
