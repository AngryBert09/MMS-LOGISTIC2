<div class="login-header box-shadow">
    <div class="container-fluid d-flex justify-content-between align-items-center">
        <div class="brand-logo">
            <a href="{{ route('login') }}">
                <img src="images/greatwall-logo.png " style="height:90px" />
                <img id='logo_name' src="images/greatwallarts-logo.svg " style="height:100px " />
            </a>
        </div>
        <div class="login-menu">
            <ul>
                @if (Route::is('register'))
                    <li><a href="{{ route('login') }}">Login</a></li>
                @elseif(Route::is('login'))
                    <li><a href="{{ route('register') }}">Register</a></li>
                @endif
            </ul>
        </div>
    </div>
</div>
