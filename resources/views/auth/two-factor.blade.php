<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Two-Factor Authentication</title>
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/gwa-favicon-32x32.png') }}" />
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Custom styles for the OTP form */
        .otp-input {
            width: 3rem;
            height: 3rem;
            text-align: center;
            font-size: 1.5rem;
            margin: 0 0.2rem;
            border: 1px solid #ced4da;
            border-radius: 0.5rem;
        }

        .otp-input:focus {
            border-color: #ffc107;
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
            outline: none;
        }
    </style>
</head>

<body class="bg-light d-flex align-items-center justify-content-center" style="height: 100vh;">

    <div class="card shadow-sm p-4 rounded-4" style="max-width: 400px; width: 100%;">
        <!-- Header Section -->
        <div class="text-center mb-4">
            <h4 class="fw-bold text-warning">Two-Factor Authentication</h4>
            <p class="text-muted">Enter the 6-digit code sent to your email address.</p>
        </div>

        <!-- Message Display Section -->
        <div id="message-container">
            <!-- Success or Error messages will be inserted here -->
        </div>

        <!-- OTP Form -->
        <form id="otp-form">
            @csrf
            <div class="d-flex justify-content-center mb-3">
                <!-- OTP Inputs -->
                <input type="text" maxlength="1" class="otp-input form-control text-center" name="otp1"
                    required />
                <input type="text" maxlength="1" class="otp-input form-control text-center" name="otp2"
                    required />
                <input type="text" maxlength="1" class="otp-input form-control text-center" name="otp3"
                    required />
                <input type="text" maxlength="1" class="otp-input form-control text-center" name="otp4"
                    required />
                <input type="text" maxlength="1" class="otp-input form-control text-center" name="otp5"
                    required />
                <input type="text" maxlength="1" class="otp-input form-control text-center" name="otp6"
                    required />
            </div>

            <!-- Hidden input to store full OTP -->
            <input type="hidden" name="code" id="otp-code" />

            <!-- Submit Button -->
            <div class="d-grid">
                <button type="submit" class="btn btn-warning btn-lg">Verify Code</button>
            </div>
        </form>


        <!-- Resend OTP Section -->
        <!-- Resend OTP Section -->
        <div class="text-center mt-4">
            <p class="text-muted mb-1">Didn’t receive the code?</p>
            <button id="resend-otp-btn" class="btn btn-link text-primary p-0 m-0">Resend OTP</button>
        </div>

    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const otpInputs = document.querySelectorAll(".otp-input");
            const otpCodeInput = document.getElementById("otp-code");
            const otpForm = document.getElementById("otp-form");
            const messageContainer = document.getElementById("message-container");
            const resendOtpBtn = document.getElementById("resend-otp-btn");

            // Move focus to next input on key press
            otpInputs.forEach((input, index) => {
                input.addEventListener("input", (e) => {
                    if (e.target.value.length === 1 && index < otpInputs.length - 1) {
                        otpInputs[index + 1].focus();
                    }
                });

                input.addEventListener("keydown", (e) => {
                    if (e.key === "Backspace" && !e.target.value && index > 0) {
                        otpInputs[index - 1].focus();
                    }
                });
            });

            // On form submit, merge all OTP inputs into hidden input
            otpForm.addEventListener("submit", function(e) {
                e.preventDefault(); // Prevent the default form submission

                let otpCode = "";
                otpInputs.forEach(input => otpCode += input.value);
                otpCodeInput.value = otpCode;

                // Prevent submission if OTP is incomplete
                if (otpCode.length !== 6) {
                    alert("Please enter all 6 digits of the OTP.");
                    return;
                }

                // Send OTP data via AJAX
                $.ajax({
                    url: '{{ route('2fa.verify') }}',
                    type: 'POST',
                    data: {
                        _token: $('input[name="_token"]').val(),
                        code: otpCode
                    },
                    success: function(response) {
                        if (response.success) {
                            // Show success message
                            messageContainer.innerHTML =
                                '<div class="alert alert-success text-center">' + response
                                .message + '</div>';
                            window.location.href =
                                '{{ route('dashboard') }}'; // Redirect to dashboard on success
                        } else {
                            // Show error message
                            messageContainer.innerHTML =
                                '<div class="alert alert-danger text-center">' + response
                                .message + '</div>';
                        }
                    },
                    error: function(xhr, status, error) {
                        // Show error message
                        messageContainer.innerHTML =
                            '<div class="alert alert-danger text-center">Something went wrong. Please try again later.</div>';
                    }
                });
            });

            // Handle Resend OTP via AJAX
            resendOtpBtn.addEventListener("click", function() {
                $.ajax({
                    url: '{{ route('2fa.resend') }}',
                    type: 'POST',
                    data: {
                        _token: $('input[name="_token"]').val(),
                    },
                    success: function(response) {
                        if (response.success) {
                            // Show success message for OTP resend
                            messageContainer.innerHTML =
                                '<div class="alert alert-success text-center">' + response
                                .message + '</div>';
                        } else {
                            // Show error message for failure to resend OTP
                            messageContainer.innerHTML =
                                '<div class="alert alert-danger text-center">' + response
                                .message + '</div>';
                        }
                    },
                    error: function(xhr, status, error) {
                        // Show error message
                        messageContainer.innerHTML =
                            '<div class="alert alert-danger text-center">Something went wrong while resending the OTP. Please try again later.</div>';
                    }
                });
            });
        });
    </script>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

</body>

</html>
