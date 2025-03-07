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
                            <!-- Error Message Container -->
                            <div id="errorMessage" class="alert alert-danger d-none mt-3"></div>

                            @if (session('confirmation_message'))
                                <div class="alert alert-info mt-3">
                                    {{ session('confirmation_message') }}
                                </div>
                            @endif

                            <div class="input-group custom">
                                <input type="text" class="form-control form-control-lg" placeholder="Email"
                                    name="email" value="{{ old('email') }}" />
                                <div class="input-group-append custom">
                                    <span class="input-group-text">
                                        <i class="icon-copy dw dw-user1"></i>
                                    </span>
                                </div>
                            </div>

                            <div class="input-group custom mt-3">
                                <input id="password-field" type="password" class="form-control form-control-lg"
                                    name="password" placeholder="Password" />
                                <div class="input-group-append custom">
                                    <span class="input-group-text">
                                        <i id="toggle-password" class="dw dw-padlock1" style="cursor: pointer;"></i>
                                    </span>
                                </div>
                            </div>

                            <div class="row pb-30 mt-3">
                                <div class="col-6">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="customCheck1"
                                            name="remember" {{ old('remember') ? 'checked' : '' }} />
                                        <label class="custom-control-label" for="customCheck1">Remember</label>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="forgot-password">
                                        <a href="{{ route('forgot.password.form') }}">Forgot Password</a>
                                    </div>
                                </div>
                            </div>


                            <div class="g-recaptcha mt-3 ml-4" name="g-recaptcha-response"
                                data-sitekey="{{ env('RECAPTCHA_SITE_KEY') }}">
                            </div>
                            <div class="row mt-3">
                                <div class="col-sm-12">
                                    <div class="input-group mb-0">
                                        <!-- Submit Button with Spinner -->
                                        <button type="submit" class="btn btn-warning btn-lg btn-block" id="submitBtn">
                                            <span id="submitText">Sign In</span>
                                            <span id="submitSpinner" class="spinner-border spinner-border-sm d-none"
                                                role="status" aria-hidden="true"></span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <!-- Google reCAPTCHA Widget -->


                        <!-- Include reCAPTCHA Script -->
                        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
                        <div class="font-16 weight-600 pt-10 pb-10 text-center" data-color="#707373">
                            OR
                        </div>
                        <div class="input-group mb-0">
                            <a class="btn btn-outline-warning btn-lg btn-block" href="{{ route('register') }}">Register
                                To
                                Create Account</a>
                        </div>

                        <!-- Include reCAPTCHA Script -->
                        <script src="https://www.google.com/recaptcha/api.js" async defer></script>


                    </div>
                </div>



                <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                <script>
                    $(document).ready(function() {
                        $('#loginForm').on('submit', function(e) {
                            e.preventDefault();

                            // Clear previous error messages, invalid classes, and restore icons if necessary
                            $('#errorMessage').addClass('d-none').html('');
                            $('.form-control').removeClass('is-invalid');
                            // Optionally, you can restore the icons if you saved them before removing

                            // Show loading spinner
                            $('#submitText').addClass('d-none');
                            $('#submitSpinner').removeClass('d-none');
                            $('#submitBtn').prop('disabled', true);

                            $.ajax({
                                url: $(this).attr('action'),
                                method: $(this).attr('method'),
                                data: $(this).serialize(),
                                success: function(response) {
                                    if (response.redirect) {
                                        window.location.href = response.redirect;
                                    } else {
                                        console.log('Login successful!');
                                    }
                                },
                                error: function(error) {
                                    // Hide loading spinner
                                    $('#submitText').removeClass('d-none');
                                    $('#submitSpinner').addClass('d-none');
                                    $('#submitBtn').prop('disabled', false);

                                    if (error.status === 422) {
                                        var errors = error.responseJSON && error.responseJSON.errors ? error
                                            .responseJSON.errors : error.errors;
                                        var errorMessage = '';

                                        // Loop through errors
                                        for (var key in errors) {
                                            if (errors.hasOwnProperty(key)) {
                                                // Add invalid class to the input
                                                var $input = $('[name="' + key + '"]');
                                                $input.addClass('is-invalid');

                                                // Remove icon if exists in the input-group-append element
                                                $input.closest('.input-group').find('.input-group-append')
                                                    .remove();

                                                // Append error messages (handle both array or string)
                                                if (Array.isArray(errors[key])) {
                                                    errorMessage += errors[key].join('<br>') + '<br>';
                                                } else {
                                                    errorMessage += errors[key] + '<br>';
                                                }
                                            }
                                        }
                                        $('#errorMessage').removeClass('d-none').html(errorMessage);
                                    } else if (error.status === 401 || error.status === 403) {
                                        $('#errorMessage').removeClass('d-none').html(
                                            'Invalid credentials. Please try again.');
                                    } else {
                                        $('#errorMessage').removeClass('d-none').html(
                                            'An error occurred. Please try again.');
                                    }
                                }
                            });
                        });
                    });
                </script>


            </div>
        </div>
    </div>
    </div>
    </div>


    <!-- New Features Modal -->
    @include('auth.new-features');
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
