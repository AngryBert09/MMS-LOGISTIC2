<!DOCTYPE html>
<html>

<head>
    <!-- Basic Page Info -->
    <meta charset="utf-8" />
    <title>{{ config('app.name') }}</title>

    <!-- Site favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="images/gwa-touch-icon" />
    <link rel="icon" type="image/png" sizes="32x32" href="images/gwa-favicon-32x32.png" />
    <link rel="icon" type="image/png" sizes="16x16" href="vendors/images/favicon-16x16.png" />

    <!-- Mobile Specific Metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet" />
    <!-- CSS -->
    <link rel="stylesheet" type="text/css" href="css/core.css" />
    <link rel="stylesheet" type="text/css" href="css/icon-font.min.css" />
    <link rel="stylesheet" type="text/css" href="css/style.css" />

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
    @include('layout.login-header')
    <div class="pre-loader">
        <div class="pre-loader-box">
            <!-- Welcome Message -->
            <div class="welcome-message">
                <div class="loader-logo">
                    <img src="images/greatwall-logo.png" alt="Great Wall Logo" />
                </div>
                <h2>Welcome to Great Wall Arts</h2>
                <p class="text-muted">Bringing creativity and craftsmanship together.</p>
            </div>
            <!-- Progress Bar -->
            <div class="loader-progress" id="progress_div">
                <div class="bar" id="bar1"></div>
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
                            <h2 class="text-center text-primary text-dark">LOGIN TO GREATWALL</h2>
                        </div>
                        <form action="{{ route('login') }}" method="post">
                            @csrf
                            @if (session('confirmation_message'))
                                <div class="alert alert-info mt-3">
                                    {{ session('confirmation_message') }}
                                </div>
                            @endif

                            @if ($errors->any())
                                <div class="alert alert-danger mt-3">
                                    <p>{{ $errors->all() ? implode(' | ', $errors->all()) : '' }}</p>
                                </div>
                            @endif


                            <div class="input-group custom">
                                <input type="text"
                                    class="form-control form-control-lg {{ $errors->has('email') ? 'is-invalid' : '' }}"
                                    placeholder="Email" name="email" value="{{ old('email') }}"
                                    title="{{ $errors->has('email') ? $errors->first('email') : '' }}" />
                                {{-- Show user icon only if there is no error --}}
                                @if (!$errors->has('email'))
                                    <div class="input-group-append custom">
                                        <span class="input-group-text">
                                            <i class="icon-copy dw dw-user1"></i>
                                        </span>
                                    </div>
                                @endif
                            </div>
                            <div class="input-group custom mt-3">
                                <input id="password-field" type="password"
                                    class="form-control form-control-lg {{ $errors->has('password') ? 'is-invalid' : '' }}"
                                    name="password" placeholder="Password"
                                    title="{{ $errors->has('password') ? $errors->first('password') : '' }}" />

                                {{-- Show padlock icon only if there is no error --}}
                                @if (!$errors->has('password'))
                                    <div class="input-group-append custom">
                                        <span class="input-group-text">
                                            <!-- Use ti-lock for closed and ti-lock-open for open padlock -->
                                            <i id="toggle-password" class="dw dw-padlock1" style="cursor: pointer;"></i>
                                        </span>
                                    </div>
                                @endif
                            </div>

                            <script>
                                document.getElementById('toggle-password').addEventListener('click', function() {
                                    var passwordField = document.getElementById('password-field');
                                    var icon = document.getElementById('toggle-password');

                                    if (passwordField.type === 'password') {
                                        passwordField.type = 'text';
                                        icon.classList.remove('ti-lock');
                                        icon.classList.add('dw-open-padlock'); // Changed to ti-lock-open
                                    } else {
                                        passwordField.type = 'password';
                                        icon.classList.remove('dw-open-padlock');
                                        icon.classList.add('dw-padlock1');
                                    }
                                });
                            </script>

                            <div class="row pb-30">
                                <div class="col-6">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="customCheck1"
                                            name="remember" {{ old('remember') ? 'checked' : '' }} />
                                        <label class="custom-control-label" for="customCheck1">Remember</label>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="forgot-password ">
                                        <a href="{{ route('forgot.password.form') }}">Forgot
                                            Password</a>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="input-group mb-0">
                                        <input class="btn btn-warning btn-lg btn-block " type="submit" value="Sign In">
                                    </div>
                        </form>
                        <div class="font-16 weight-600 pt-10 pb-10 text-center" data-color="#707373">
                            OR
                        </div>
                        <div class="input-group mb-0">
                            <a class="btn btn-outline-warning btn-lg btn-block" href="{{ route('register') }}">Register
                                To
                                Create Account</a>
                        </div>
                    </div>
                </div>

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
