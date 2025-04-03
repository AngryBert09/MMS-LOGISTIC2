<!DOCTYPE html>
<html>
@include('layout.head')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

<body class="login-page">
    {{-- <div class="pre-loader">
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
    </div> --}}


    <div class="login-wrap d-flex justify-content-center align-items-center min-vh-100">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6 col-lg-5">
                    <div class="login-box bg-white box-shadow border-radius-10 p-4">
                        <h2 class="text-center text-primary text-dark">ADMIN</h2>
                        <form action="{{ route('admin.login') }}" method="POST" id="loginForm">
                            @csrf

                            <!-- Error Message Container -->
                            <div id="errorMessage" class="alert alert-danger d-none mt-3"></div>

                            @if (session('confirmation_message'))
                                <div class="alert alert-info mt-3">
                                    {{ session('confirmation_message') }}
                                </div>
                            @endif

                            <div class="input-group custom mt-3">
                                <input type="text" class="form-control form-control-lg" placeholder="Email"
                                    name="email" value="{{ old('email') }}" required />
                                <div class="input-group-append custom">
                                    <span class="input-group-text">
                                        <i class="icon-copy dw dw-user1"></i>
                                    </span>
                                </div>
                            </div>

                            <div class="input-group custom mt-3">
                                <input id="password-field" type="password" class="form-control form-control-lg"
                                    name="password" placeholder="Password" required />
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
                                <div class="col-6 text-right">
                                    <a href="{{ route('forgot.password.form') }}" class="text-warning">Forgot
                                        Password?</a>
                                </div>
                            </div>

                            <!-- Google reCAPTCHA -->

                            <div class="row">
                                <div class="col-sm-12">
                                    <button type="submit" class="btn btn-warning btn-lg btn-block" id="submitBtn">
                                        <span id="submitText">Sign In</span>
                                        <span id="submitSpinner" class="spinner-border spinner-border-sm d-none"></span>
                                    </button>
                                </div>
                            </div>

                            {{-- <div class="g-recaptcha ml-4 mt-3" data-sitekey="{{ env('RECAPTCHA_SITE_KEY') }}">
                            </div> --}}

                        </form>


                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- <!-- Include reCAPTCHA Script -->
    <script src="https://www.google.com/recaptcha/api.js" async defer></script> --}}

    <!-- jQuery & AJAX Form Submission -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#loginForm').on('submit', function(e) {
                e.preventDefault();

                $('#errorMessage').addClass('d-none').html('');
                $('.form-control').removeClass('is-invalid');

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
                        $('#submitText').removeClass('d-none');
                        $('#submitSpinner').addClass('d-none');
                        $('#submitBtn').prop('disabled', false);

                        if (error.status === 422) {
                            var errors = error.responseJSON.errors;
                            var errorMessage = '';

                            for (var key in errors) {
                                if (errors.hasOwnProperty(key)) {
                                    var $input = $('[name="' + key + '"]');
                                    $input.addClass('is-invalid');
                                    errorMessage += errors[key] + '<br>';
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


    <!-- New Features Modal -->
    {{-- @include('auth.new-features'); --}}
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
