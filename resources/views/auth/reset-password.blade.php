<!DOCTYPE html>
<html>

@include('layout.head')

<body>
    @include('layout.login-header')
    <div class="login-wrap d-flex align-items-center flex-wrap justify-content-center">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <img src="{{ asset('images/forgot-password.png') }}" alt="" />
                </div>
                <div class="col-md-6">
                    <div class="login-box bg-white box-shadow border-radius-10">
                        <div class="login-title">
                            <h2 class="text-center text-warning">Reset Password</h2>
                        </div>
                        <h6 class="mb-20">Enter your new password, confirm it, and submit</h6>
                        <form method="POST" action="{{ route('reset.password.update') }}">
                            @csrf
                            <input type="hidden" name="token" value="{{ $token }}">
                            <input type="hidden" name="email" value="{{ $email }}">

                            <!-- Password Field with Toggle -->
                            <div class="input-group custom">
                                <input type="password" id="password" name='password'
                                    class="form-control form-control-lg
                                    @error('password') is-invalid @enderror"
                                    placeholder="New Password" required />
                                <div class="input-group-append custom">
                                    <span
                                        class="input-group-text
                                        @error('password') d-none @enderror">
                                        <i class="dw dw-padlock1" id="togglePassword"
                                            onclick="togglePasswordVisibility()"></i>
                                    </span>
                                </div>
                                @error('password')
                                    <div class="text-danger small-error">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Password Confirmation Field with Toggle -->
                            <div class="input-group custom">
                                <input type="password" id="password_confirmation" name="password_confirmation"
                                    class="form-control form-control-lg
                                    @error('password_confirmation') is-invalid @enderror"
                                    placeholder="Confirm New Password" required />
                                @error('password_confirmation')
                                    <div class="text-danger small-error">{{ $message }}</div>
                                @enderror
                                <div class="input-group-append custom">
                                    <span
                                        class="input-group-text
                                        @error('password_confirmation') d-none @enderror">
                                        <i class="dw dw-padlock1" id="togglePasswordConfirm"
                                            onclick="togglePasswordConfirmationVisibility()"></i>
                                    </span>
                                </div>
                            </div>

                            <div class="row align-items-center">
                                <div class="col-12">
                                    <div class="input-group mb-0">
                                        <button type="submit" class="btn btn-warning btn-lg btn-block"
                                            style="width: 100%;">Submit</button>
                                    </div>
                                </div>
                            </div>
                        </form>

                        <script>
                            // Toggle password visibility
                            function togglePasswordVisibility() {
                                var passwordField = document.getElementById('password');
                                var icon = document.getElementById('togglePassword');

                                if (passwordField.type === "password") {
                                    passwordField.type = "text";
                                    icon.classList.add('dw-open-padlock'); // Change icon to "eye" for visible password
                                    icon.classList.remove('dw-padlock1'); // Remove lock icon
                                } else {
                                    passwordField.type = "password";
                                    icon.classList.add('dw-padlock1'); // Change back to lock icon
                                    icon.classList.remove('dw-open-padlock'); // Remove "eye" icon
                                }
                            }

                            // Toggle password confirmation visibility
                            function togglePasswordConfirmationVisibility() {
                                var passwordFieldConfirm = document.getElementById('password_confirmation');
                                var icon = document.getElementById('togglePasswordConfirm');

                                if (passwordFieldConfirm.type === "password") {
                                    passwordFieldConfirm.type = "text";
                                    icon.classList.add('dw-open-padlock'); // Change icon to "eye" for visible password
                                    icon.classList.remove('dw-padlock1'); // Remove lock icon
                                } else {
                                    passwordFieldConfirm.type = "password";
                                    icon.classList.add('dw-padlock1'); // Change back to lock icon
                                    icon.classList.remove('dw-open-padlock'); // Remove "eye" icon
                                }
                            }
                        </script>




                    </div>
                </div>
            </div>
        </div>
    </div>


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
