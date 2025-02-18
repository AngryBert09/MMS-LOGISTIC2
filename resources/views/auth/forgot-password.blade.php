<!DOCTYPE html>
<html>

@include('layout.head')

<body>
    @include('layout.login-header')
    <div class="login-wrap d-flex align-items-center flex-wrap justify-content-center">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <img src="images/forgot-password.png" alt="" />
                </div>
                <div class="col-md-6">
                    <div class="login-box bg-white box-shadow border-radius-10">
                        <div class="login-title">
                            <h2 class="text-center text-dark">Forgot Password</h2>
                        </div>
                        <h6 class="mb-20">
                            Enter your email address to reset your password
                        </h6>
                        <form method="POST" action="{{ route('forgot.password.send') }}">
                            @csrf

                            <div class="input-group custom">
                                <input type="email" name="email"
                                    class="form-control form-control-lg {{ $errors->has('email') ? 'is-invalid' : '' }}"
                                    placeholder="Email" required />
                                <div class="input-group-append custom">
                                    <span class="input-group-text">
                                        @if ($errors->has('email'))
                                            <i class="fa fa-exclamation-circle" aria-hidden="true"
                                                style="color: red; "></i>
                                        @else
                                            <i class="fa fa-envelope-o" aria-hidden="true"></i>
                                        @endif
                                    </span>
                                </div>

                            </div>
                            @if ($errors->has('email'))
                                <div class="text-danger small-error mt-n4 mb-2">{{ $errors->first('email') }}</div>
                            @endif
                            <div class="row align-items-center">
                                <div class="col-5">
                                    <div class="input-group mb-0">
                                        <button type="submit" class="btn btn-warning btn-lg btn-block">Submit</button>
                                    </div>
                                </div>
                                <div class="col-2">
                                    <div class="font-16 weight-600 text-center" data-color="#707373">
                                        OR
                                    </div>
                                </div>
                                <div class="col-5">
                                    <div class="input-group mb-0">
                                        <a class="btn btn-outline-warning btn-lg btn-block"
                                            href="{{ route('login') }}">Login</a>
                                    </div>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- welcome modal start -->
    <!-- welcome modal end -->
    <!-- js -->
    <script src="js/core.js"></script>
    <script src="js/script.min.js"></script>
    <script src="js/process.js"></script>
    <script src="js/layout-settings.js"></script>
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-NXZMQSS" height="0" width="0"
            style="display: none; visibility: hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
</body>

</html>
