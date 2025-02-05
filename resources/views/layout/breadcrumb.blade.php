<div class="page-header">
    <div class="row">
        <div class="col-md-6 col-sm-12">
            <nav aria-label="breadcrumb" role="navigation">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        @if (Request::is('purchase-orders*'))
                            <a href="{{ route('purchase-orders.index') }}">Purchase Orders</a>
                        @elseif (Request::is('receipts*'))
                            <a href="{{ route('receipts.index') }}">Purchase Receipts</a>
                        @elseif (Request::is('invoices*'))
                            <a href="{{ route('invoices.index') }}">Invoices</a>
                        @elseif (Request::is('chat*'))
                            <a href="{{ route('chat') }}">Chats</a>
                        @elseif (Request::is('Biddings*'))
                            <a href="{{ route('chat') }}">Biddings</a>
                        @elseif (Request::is('returns*'))
                            <a href="{{ route('returns.index') }}">Return Management</a>
                        @else
                            {{ $breadcrumbTitle ?? 'Page' }}
                        @endif
                    </li>
                    @if (Request::is('invoices/*/edit'))
                        <li class="breadcrumb-item active" aria-current="page">
                            Edit Invoice
                        </li>
                    @elseif (Request::is('invoices/*'))
                        <li class="breadcrumb-item active" aria-current="page">
                            Show Invoice
                        </li>
                    @endif
                </ol>

            </nav>
        </div>
        <div class="col-md-6 col-sm-12 text-right">
            <div class="dropdown">
                <a class="btn btn-warning dropdown-toggle" href="#" role="button" data-toggle="dropdown">
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
