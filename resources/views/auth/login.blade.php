<!DOCTYPE html>
<html>

<head>
    <!-- Basic Page Info -->
    <meta charset="utf-8" />
    <title>Login to Great Wall</title>

    <!-- Site favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="images/greatwall-logo.ico'" />
    <link rel="icon" type="image/png" sizes="32x32" href="images/greatwall-logo.ico" />
    <link rel="icon" type="image/png" sizes="16x16"
        href="images/greatwall-logo.ico />

    <!-- Mobile Specific Metas -->
    <meta name="viewport"
        content="width=device-width, initial-scale=1, maximum-scale=1" />

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet" />
    <!-- CSS -->
    <link rel="stylesheet" type="text/css" href="{{ asset('css/core.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('css/icon-font.min.css') }}" />
    <!-- Fix the broken link tag by closing the quotes properly -->
    <link rel="stylesheet" type="text/css" href="{{ asset('css/style.css') }}" />






    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-GBZ3SGGX85"></script>
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-2973766580778258"
        crossorigin="anonymous"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag("js", new Date());

        gtag("config", "G-GBZ3SGGX85");
    </script>
    <!-- Google Tag Manager -->
    <script>
        (function(w, d, s, l, i) {
            w[l] = w[l] || [];
            w[l].push({
                "gtm.start": new Date().getTime(),
                event: "gtm.js"
            });
            var f = d.getElementsByTagName(s)[0],
                j = d.createElement(s),
                dl = l != "dataLayer" ? "&l=" + l : "";
            j.async = true;
            j.src = "https://www.googletagmanager.com/gtm.js?id=" + i + dl;
            f.parentNode.insertBefore(j, f);
        })(window, document, "script", "dataLayer", "GTM-NXZMQSS");
    </script>
    <!-- End Google Tag Manager -->
</head>

<body class="login-page">
    <div class="login-header box-shadow">
        <div class="container-fluid d-flex justify-content-between align-items-center">
            <div class="brand-logo">
                <a href="{{ route('login') }}">
                    <img src="images/greatwall-logo.png " style="height:90px" />
                    <img src="images/greatwallarts-logo.svg " style="height:100px " />
                </a>
            </div>
            <div class="login-menu">
                <ul>
                    <li><a href="{{ route('register') }}">Register</a></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="login-wrap d-flex align-items-center flex-wrap justify-content-center">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6 col-lg-7">
                    <img src="{{ asset('images/login-page-img.png') }}" alt="try" />

                </div>
                <div class="col-md-6 col-lg-5">
                    <div class="login-box bg-white box-shadow border-radius-10">
                        <div class="login-title">
                            <h2 class="text-center text-primary">Login to Greatwall</h2>
                        </div>
                        <div class="select-role">
                            <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                <label class="btn active">
                                    <input type="radio" name="options" id="admin" />
                                    <div class="icon">
                                        <img src="{{ asset('images/briefcase.svg') }}" class="svg" alt="" />
                                    </div>
                                    <span>I'm</span>
                                    Supplier
                                </label>
                                <label class="btn">
                                    <input type="radio" name="options" id="user" />
                                    <div class="icon">
                                        <img src="{{ asset('images/person.svg') }}" class="svg" alt="" />
                                    </div>
                                    <span>I'm</span>
                                    Employee
                                </label>
                            </div>
                        </div>
                        <form action="{{ route('login') }}" method="post">
                            @csrf
                            <div class="input-group custom">
                                <input type="text" class="form-control form-control-lg " placeholder="Email"
                                    name="email" value="{{ old('email') }}" />

                                {{-- Show warning icon if there is an error --}}
                                <div class="input-group-append custom">
                                    <span class="input-group-text" data-toggle="tooltip" data-placement="top"
                                        title="{{ $errors->has('email') ? $errors->first('email') : '' }}">
                                        @if ($errors->has('email'))
                                            <i class="icon-copy dw dw-warning text-danger"></i>
                                        @else
                                            <i class="icon-copy dw dw-user1"></i>
                                        @endif
                                    </span>
                                </div>
                            </div>

                            <div class="input-group custom mt-3">
                                <input id="password-field" type="password"
                                    class="form-control form-control-lg {{ $errors->has('password') ? 'is-invalid' : '' }}"
                                    name="password" placeholder="Password" />

                                {{-- Show warning icon if there is an error --}}
                                <div class="input-group-append custom">
                                    <span class="input-group-text" data-toggle="tooltip" data-placement="top"
                                        title="{{ $errors->has('password') ? $errors->first('password') : '' }}">
                                        @if ($errors->has('password'))
                                            <i class="icon-copy dw dw-warning text-danger"></i>
                                        @else
                                            <i id="toggle-password" class="dw dw-padlock1" style="cursor: pointer;"></i>
                                        @endif
                                    </span>
                                </div>
                            </div>

                            <script>
                                // Enable Bootstrap tooltips once for all elements
                                $(function() {
                                    $('[data-toggle="tooltip"]').tooltip();
                                });

                                document.addEventListener('DOMContentLoaded', function() {
                                    var togglePassword = document.getElementById('toggle-password');
                                    var passwordField = document.getElementById('password-field');


                                    if (togglePassword && passwordField) {
                                        togglePassword.addEventListener('click', function() {
                                            if (passwordField.type === 'password') {
                                                passwordField.type = 'text';
                                                togglePassword.classList.remove('dw-padlock1');

                                                togglePassword.classList.add('dw-eye');
                                            } else {
                                                passwordField.type = 'password';
                                                togglePassword.classList.remove('dw-eye');
                                                togglePassword.classList.add('dw-padlock1');
                                            }
                                        });
                                    }
                                });
                            </script>


                            <div class="row pb-30">
                                <div class="col-6">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="customCheck1" />
                                        <label class="custom-control-label" for="customCheck1">Remember</label>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="forgot-password">
                                        <a href="forgot-password.html">Forgot Password</a>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="input-group mb-0">
                                        <input class="btn btn-primary btn-lg btn-block" type="submit"
                                            value="Sign In">
                                        {{-- <a class="btn btn-primary btn-lg btn-block" href="index.html">Sigsn In</a> --}}
                                    </div>
                        </form>
                        <div class="font-16 weight-600 pt-10 pb-10 text-center" data-color="#707373">
                            OR
                        </div>
                        <div class="input-group mb-0">
                            <a class="btn btn-outline-primary btn-lg btn-block"
                                href="{{ route('register') }}">Register
                                To
                                Create Account</a>

                        </div>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>
    </div>
    </div>

    <script src="{{ asset('js/core.js') }}"></script>
    <script src="{{ asset('js/script.min.js') }}"></script>
    <script src="{{ asset('js/process.js') }}"></script>
    <script src="{{ asset('js/layout-settings.js') }}"></script>
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-NXZMQSS" height="0" width="0"
            style="display: none; visibility: hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
</body>

</html>
