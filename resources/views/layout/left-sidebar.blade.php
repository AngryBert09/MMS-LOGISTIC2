<div class="left-side-bar">
    <div class="brand-logo">
        <a href="index.html">
            <img src="images/greatwall-logo.png " style="height:90px" />
            <img src="images/greatwallarts-logo.svg " style="width:150px " />
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
                        <li><a href="{{ route('dashboard') }}"> Dashboard</a>
                        </li>
                        <li><a href="index2.html">Customers</a></li>
                        <li><a href="index3.html">Credit Notes</a></li>
                        <li><a href="index3.html">Payments</a></li>
                        <li><a href="{{ route('invoice') }}">Invoices</a></li>
                        <li><a href="{{ route('purchase-orders.index') }}">Customer PO</a></li>
                        <li><a href="{{ route('customer_RN') }}">Customer RN</a></li>

                    </ul>
                </li>

                <li>
                    <div class="dropdown-divider"></div>
                </li>
                <li>
                    <div class="sidebar-small-cap">Extra</div>
                </li>

            </ul>
        </div>
    </div>
</div>
