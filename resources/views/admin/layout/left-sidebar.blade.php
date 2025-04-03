<div class="left-side-bar">
    <div class="brand-logo">
        <a href="{{ route('admin.dashboard') }}">
            <img src="{{ asset('images/greatwall-logo.png') }}" style="height:90px" />
        </a>
        <div class="close-sidebar" data-toggle="left-sidebar-close">
            <i class="ion-close-round"></i>
        </div>
    </div>
    <div class="menu-block customscroll">
        <div class="sidebar-menu">
            <ul id="accordion-menu">
                <li class="dropdown">
                    <a href="javascript:;" class="dropdown-toggle">
                        <span class="micon bi bi-house"></span><span class="mtext">Home</span>
                    </a>
                    <ul class="submenu">
                        <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li><a href="{{ route('admin.biddings') }}">Biddings Overview</a></li>
                        <li><a href="{{ route('admin.orders') }}">Order Management</a></li>
                        <li><a href="{{ route('admin.invoices') }}">Payments and Invoices</a></li>
                        <li><a href="{{ route('admin.procurements') }}">Procurement Approval</a></li>
                    </ul>
                </li>

                <li class="dropdown">
                    <a href="javascript:;" class="dropdown-toggle">
                        <span class="micon bi bi-people"></span><span class="mtext">Vendor Management</span>
                    </a>
                    <ul class="submenu">
                        <li><a href="{{ route('admin.vendors') }}">Vendor Applications</a></li>
                        <li><a href="{{ route('admin.vendors-list') }}">Vendor List</a></li>


                    </ul>
                </li>

            </ul>
        </div>
    </div>
</div>
