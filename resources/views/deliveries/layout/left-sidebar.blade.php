<div class="left-side-bar">
    <div class="brand-logo">

        <a href="{{ route('dashboard.deliveries') }}">
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
                        <li><a href="{{ route('dashboard.deliveries') }}">Deliveries</a></li>
                        <li><a href="{{ route('deliveries.mydeliveries') }}">My Deliveries</a></li>
                    </ul>
                </li>






            </ul>
        </div>
    </div>
</div>
