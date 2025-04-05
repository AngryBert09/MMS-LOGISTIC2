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
        <a class="dropdown-item" href="{{ route('invoices.edit', $invoice->invoice_id) }}">
            <i class="dw dw-edit2"></i> Edit
        </a>

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
