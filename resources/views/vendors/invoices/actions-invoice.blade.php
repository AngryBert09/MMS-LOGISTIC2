<div class="dropdown">
    <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" href="#" role="button"
        data-toggle="dropdown">
        <i class="dw dw-more"></i>
    </a>
    <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
        <a class="dropdown-item" href="{{ route('invoices.show', $invoice->invoice_id) }}"><i class="dw dw-eye"></i>
            View</a>
        <a class="dropdown-item" href="{{ route('invoices.edit', $invoice->invoice_id) }}"><i class="dw dw-edit2"></i>
            Edit</a>
    </div>
</div>
