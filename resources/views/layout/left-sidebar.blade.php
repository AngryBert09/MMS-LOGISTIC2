<div class="left-side-bar">
    <div class="brand-logo">
        <a href="{{ route('dashboard') }}">
            <img src="{{ asset('images/greatwall-logo.png') }}" style="height:90px" />
            <img src="{{ asset('images/greatwallarts-logo.svg') }}" style="width:150px" />
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
                        <li><a href="{{ route('dashboard') }}">Dashboard</a></li>

                        @if (Auth::guard('vendor')->check())
                            @php
                                $vendorId = Auth::guard('vendor')->user()->id;
                                $verifiedVendor = \App\Models\VerifiedVendor::where('vendor_id', $vendorId)->first();
                            @endphp

                            @if ($verifiedVendor && $verifiedVendor->is_verified)
                                <li><a href="index3.html">Payments</a></li>
                                <li><a href="{{ route('invoices.index') }}">Invoices</a></li>
                                <li><a href="{{ route('purchase-orders.index') }}">Customer PO</a></li>
                                <li><a href="{{ route('receipts.index') }}">Customer RN</a></li>
                                <li><a href="#">Returns</a></li>
                            @else
                                <li><a href="{{ route('profiles.show', $vendorId) }}">Complete Profile</a></li>
                            @endif
                        @endif
                    </ul>
                </li>

                @if (Auth::guard('vendor')->check() && $verifiedVendor && $verifiedVendor->is_verified)
                    <li>
                        <a href="{{ route('bidding') }}" class="dropdown-toggle no-arrow">
                            <span class="micon bi bi-globe"></span><span class="mtext">Biddings</span>
                        </a>

                    </li>
                    <li>
                        <a href="{{ route('chat') }}" class="dropdown-toggle no-arrow">
                            <span class="micon bi bi-chat-right-dots"></span><span class="mtext">Chat</span>
                        </a>
                    </li>
                @endif
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
