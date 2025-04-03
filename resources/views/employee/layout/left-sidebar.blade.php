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
                        <li><a href="{{ route('employee.dashboard') }}">Dashboard</a></li>
                        <li><a href="{{ route('employee.biddings') }}">Bidding & Offers</a></li>
                        <li><a href="">Orders and Deliveries</a></li>
                        <li><a href="">Invoices</a></li>
                    </ul>
                </li>

                <li class="dropdown">
                    <a href="javascript:;" class="dropdown-toggle">
                        <span class="micon bi bi-cart-check"></span><span class="mtext">Procurement</span>
                    </a>
                    <ul class="submenu">
                        <li><a href="{{ route('employee.procurement.request') }}">Create Procurement Request</a></li>
                        <li><a href="{{ route('employee.procurements') }}">Procurements</a></li>
                    </ul>
                </li>


            </ul>
        </div>
    </div>
</div>
