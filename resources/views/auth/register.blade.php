<!DOCTYPE html>
<html>

<head>
    <!-- Basic Page Info -->
    <meta charset="utf-8" />
    <title>Register to greatwall</title>

    <!-- Site favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="images/gwa-touch-icon" />
    <link rel="icon" type="image/png" sizes="32x32" href="images/gwa-favicon-32x32.png" />
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/favicon-16x16.png') }}" />

    <!-- Mobile Specific Metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet" />
    <!-- CSS -->
    <link rel="stylesheet" type="text/css" href="{{ asset('css/core.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('css/icon-font.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="src/plugins/jquery-steps/jquery.steps.css" />
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
                    <li><a href="{{ route('login') }}">Login</a></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="register-page-wrap d-flex align-items-center flex-wrap justify-content-center">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6 col-lg-7">
                    <img src="{{ asset('images/register-page-img.png') }}" alt="" />
                </div>
                <div class="col-md-6 col-lg-5">
                    <h1 style="padding-left: 65px; border-line:solid">REGISTER VENDOR</h1>
                    <div class="register-box bg-white box-shadow border-radius-10">
                        <div class="wizard-content">
                            <!-- Vendor Registration Form -->
                            <form class="tab-wizard2 wizard-circle wizard" id="vendorForm" method="POST"
                                action="{{ route('register') }}" enctype="multipart/form-data">
                                @csrf
                                <!-- Step 1: Basic Account Credentials -->
                                <h5>Basic Account Credentials</h5>
                                <section>
                                    <div class="form-wrap max-width-600 mx-auto">
                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label">Company Name*</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" name="companyName"
                                                    id="companyName" value="{{ old('companyName') }}" required />
                                                @error('companyName')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label">Email*</label>
                                            <div class="col-sm-8">
                                                <input type="email" class="form-control" name="email" id="email"
                                                    value="{{ old('email') }}" required />
                                                @error('email')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label">Password*</label>
                                            <div class="col-sm-8">
                                                <input type="password" class="form-control" name="password"
                                                    id="password" required />
                                                @error('password')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label">Confirm Password*</label>
                                            <div class="col-sm-8">
                                                <input type="password" class="form-control" name="password_confirmation"
                                                    id="confirmPassword" required />
                                                @error('password_confirmation')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </section>

                                <!-- Step 2: Personal Information -->
                                <h5>Personal Information</h5>
                                <section>
                                    <div class="form-wrap max-width-600 mx-auto">
                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label">Full Name*</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" name="fullName"
                                                    id="fullName" value="{{ old('fullName') }}" required />
                                                @error('fullName')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row align-items-center">
                                            <label class="col-sm-4 col-form-label">Gender*</label>
                                            <div class="col-sm-8">
                                                <div class="custom-control custom-radio custom-control-inline pb-0">
                                                    <input type="radio" id="male" name="gender"
                                                        class="custom-control-input" value="Male" required
                                                        {{ old('gender') == 'Male' ? 'checked' : '' }} />
                                                    <label class="custom-control-label" for="male">Male</label>
                                                </div>
                                                <div class="custom-control custom-radio custom-control-inline pb-0">
                                                    <input type="radio" id="female" name="gender"
                                                        class="custom-control-input" value="Female" required
                                                        {{ old('gender') == 'Female' ? 'checked' : '' }} />
                                                    <label class="custom-control-label" for="female">Female</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label">City</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" name="city"
                                                    id="city" value="{{ old('city') }}" />
                                                @error('city')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label">State</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" name="state"
                                                    id="state" value="{{ old('state') }}" />
                                                @error('state')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </section>

                                <!-- Step 3: Upload Documents -->
                                <h5>Upload Documents</h5>
                                <section>
                                    <div class="form-wrap max-width-600 mx-auto">
                                        <h5>Upload Required Documents</h5>

                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label">Business Registration
                                                Document*</label>
                                            <div class="col-sm-8">
                                                <input type="file" class="form-control"
                                                    name="business_registration" required
                                                    accept=".pdf,.jpg,.jpeg,.png" />
                                                <small class="form-text text-muted">Upload your business registration
                                                    document (PDF or image format).</small>
                                                @error('business_registration')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label">Mayor's Permit*</label>
                                            <div class="col-sm-8">
                                                <input type="file" class="form-control" name="mayor_permit"
                                                    required accept=".pdf,.jpg,.jpeg,.png" />
                                                <small class="form-text text-muted">Upload your Mayor's Permit (PDF or
                                                    image format).</small>
                                                @error('mayor_permit')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label">Tax Identification Number
                                                (TIN)*</label>
                                            <div class="col-sm-8">
                                                <input type="file" class="form-control" name="tin" required
                                                    accept=".pdf,.jpg,.jpeg,.png" />
                                                <small class="form-text text-muted">Upload your TIN document (PDF or
                                                    image format).</small>
                                                @error('tin')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label">Proof of Identity*</label>
                                            <div class="col-sm-8">
                                                <input type="file" class="form-control" name="proof_of_identity"
                                                    required accept=".pdf,.jpg,.jpeg,.png" />
                                                <small class="form-text text-muted">Upload a valid government-issued ID
                                                    (PDF or image format).</small>
                                                @error('proof_of_identity')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </section>

                                <!-- Step 4: Overview Information -->
                                <h5>Overview Information</h5>
                                <section>
                                    <div class="form-wrap max-width-600 mx-auto">
                                        <ul class="register-info">
                                            <li>
                                                <div class="row">
                                                    <div class="col-sm-4 weight-600">Company Name</div>
                                                    <div class="col-sm-8" id="overviewCompanyName">
                                                        {{ old('companyName', 'example company') }}</div>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="row">
                                                    <div class="col-sm-4 weight-600">Email Address</div>
                                                    <div class="col-sm-8" id="overviewEmail">
                                                        {{ old('email', 'example@abc.com') }}</div>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="row">
                                                    <div class="col-sm-4 weight-600">Full Name</div>
                                                    <div class="col-sm-8" id="overviewFullName">
                                                        {{ old('fullName', 'John Smith') }}</div>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="row">
                                                    <div class="col-sm-4 weight-600">Location</div>
                                                    <div class="col-sm-8" id="overviewLocation">
                                                        {{ old('city', '123 Example City') }}</div>
                                                </div>
                                            </li>
                                        </ul>
                                        <div class="custom-control custom-checkbox mt-4">
                                            <input type="checkbox" class="custom-control-input" id="customCheck1"
                                                name="terms" required />
                                            <label class="custom-control-label" for="customCheck1">I have read and
                                                agreed to the terms of services and privacy policy</label>
                                        </div>
                                        @if ($errors->has('terms'))
                                            <div class="text-danger">{{ $errors->first('terms') }}</div>
                                        @else
                                            <!-- success Popup html Start -->
                                            <button type="button" id="success-modal-btn" hidden data-toggle="modal"
                                                data-target="#success-modal" data-backdrop="static">
                                                Launch modal
                                            </button>
                                            <div class="modal fade" id="success-modal" tabindex="-1" role="dialog"
                                                aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered max-width-400"
                                                    role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-body text-center font-18">
                                                            <h3 class="mb-20">Form Submitted!</h3>
                                                            <div class="mb-30 text-center">
                                                                <img src="images/success.png" alt="Success" />
                                                            </div>
                                                            Lorem ipsum dolor sit amet, consectetur adipisicing elit,
                                                            sed do eiusmod
                                                        </div>
                                                        <div class="modal-footer justify-content-center">
                                                            <button type="button"
                                                                class="btn btn-primary">Done</button>
                                                            <!-- Change link to button -->
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </section>
                            </form>


                            <!-- Error Popup HTML End -->



                            <!-- JavaScript for Modals and Form -->
                            <script>
                                document.addEventListener('DOMContentLoaded', function() {
                                    // Function to update the overview section with form data
                                    function updateOverview() {
                                        document.getElementById('overviewCompanyName').textContent = document.getElementById('companyName')
                                            .value;
                                        document.getElementById('overviewEmail').textContent = document.getElementById('email').value;
                                        document.getElementById('overviewFullName').textContent = document.getElementById('fullName').value;
                                        document.getElementById('overviewLocation').textContent = document.getElementById('city').value +
                                            ', ' + document.getElementById('state').value;
                                    }

                                    // Capture form data on input change
                                    document.querySelectorAll('#vendorForm input').forEach(input => {
                                        input.addEventListener('input', updateOverview);
                                    });

                                    // Validation function
                                    function validateForm() {
                                        // Example validation - adjust according to your fields
                                        const companyName = document.getElementById('companyName').value;
                                        const email = document.getElementById('email').value;
                                        const fullName = document.getElementById('fullName').value;
                                        const city = document.getElementById('city').value;
                                        const state = document.getElementById('state').value;

                                        // Check if required fields are filled
                                        if (!companyName || !email || !fullName || !city || !state) {
                                            return false; // Validation failed
                                        }
                                        return true; // Validation passed
                                    }

                                    // Handle form submission
                                    document.getElementById('vendorForm').addEventListener('submit', function(event) {
                                        event.preventDefault(); // Prevent the default form submission

                                        // Perform validation
                                        if (validateForm()) {
                                            // If successful, show the success modal
                                            $('#success-modal').modal('show');
                                        } else {
                                            // Handle validation failure and show error modal
                                            document.getElementById('error-message').textContent =
                                                'Please fill in all required fields.';
                                            $('#error-modal').modal('show'); // Show error modal
                                        }
                                    });

                                    // Submit the form when the "Done" button is clicked in the success modal
                                    document.getElementById('success-modal').addEventListener('click', function(event) {
                                        if (event.target.classList.contains('btn-primary')) {
                                            document.getElementById('vendorForm').submit(); // Submit the form
                                        }
                                    });
                                });

                                $(document).ready(function() {
                                    $('a[href="#finish"]').on('click', function(e) {
                                        e.preventDefault(); // Prevent the default anchor behavior

                                        // Perform validation checks here
                                        if (validateForm()) { // Call the same validation function
                                            $('#success-modal').modal('show'); // Show the modal instead of submitting immediately
                                        } else {
                                            // Handle validation failure and show error modal
                                            document.getElementById('error-message').textContent =
                                                'Please fill in all required fields.';
                                            $('#error-modal').modal('show'); // Show error modal
                                        }
                                    });
                                });
                            </script>


                            <!-- success Popup html End -->
                            <!-- welcome modal start -->

                            <!-- welcome modal end -->
                            <!-- js -->
                            <script src="{{ asset('js/core.js') }}"></script>
                            <script src="{{ asset('js/script.min.js') }}"></script>
                            <script src="{{ asset('js/process.js') }}"></script>
                            <script src="{{ asset('js/layout-settings.js') }}"></script>
                            <script src="src/plugins/jquery-steps/jquery.steps.js"></script>
                            <script src="{{ asset('js/steps-setting.js') }}"></script>
                            <!-- Google Tag Manager (noscript) -->
                            <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-NXZMQSS"
                                    height="0" width="0"
                                    style="display: none; visibility: hidden"></iframe></noscript>
                            <!-- End Google Tag Manager (noscript) -->
</body>

</html>
