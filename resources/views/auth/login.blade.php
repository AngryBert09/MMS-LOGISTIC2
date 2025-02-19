<!DOCTYPE html>
<html>
@include('layout.head')

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
                        <form action="{{ route('login') }}" method="post" id="loginForm">
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
                                            <i id="toggle-password" class="dw dw-padlock1" style="cursor: pointer;"></i>
                                        </span>
                                    </div>
                                @endif
                            </div>

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
                                        <a href="{{ route('forgot.password.form') }}">Forgot Password</a>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="input-group mb-0">
                                        <!-- Submit Button with Spinner -->
                                        <button type="submit" class="btn btn-warning btn-lg btn-block" id="submitBtn">
                                            Sign In

                                        </button>
                                    </div>
                                </div>
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



                <script>
                    document.getElementById('loginForm').addEventListener('submit', function(e) {
                        // Get the submit button and spinner
                        var submitBtn = document.getElementById('submitBtn');
                        var preLoad = document.getElementById('.pre-loader');

                        preLoad.style.display = "none";


                        // Disable the submit button to prevent multiple submissions
                        submitBtn.disabled = true;



                        // Change the button text to indicate it's being processed
                        submitBtn.innerHTML = 'Signing In...';

                        // Optionally, you can prevent form submission in case of additional validation
                        // e.preventDefault(); // If needed
                    });
                </script>





            </div>
        </div>
    </div>
    </div>
    </div>
    <script>
        // Toggle password visibility
        document.getElementById('toggle-password')?.addEventListener('click', function() {
            var passwordField = document.getElementById('password-field');
            var icon = document.getElementById('toggle-password');

            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                icon.classList.remove('dw-padlock1');
                icon.classList.add('dw-open-padlock'); // Change to 'dw-open-padlock' (assuming it's available)
            } else {
                passwordField.type = 'password';
                icon.classList.remove('dw-open-padlock');
                icon.classList.add('dw-padlock1');
            }
        });


        document.querySelector('form').addEventListener('submit', function(e) {
            // Ensure the pre-loader is hidden just before the form is submitted
            document.querySelector('.pre-loader').style.display = 'none';
        });
    </script>

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
