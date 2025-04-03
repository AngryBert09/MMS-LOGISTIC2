<!DOCTYPE html>
<html>
@include('layout.head')

<body class="login-page">
    @include('layout.login-header')

    <div class="register-page-wrap d-flex align-items-center flex-wrap justify-content-center">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6 col-lg-7">
                    <img src="images/register-page-img.png" alt="" />
                </div>
                <div class="col-md-6 col-lg-5">
                    <div class="register-box bg-white box-shadow border-radius-10">
                        <div class="wizard-content">
                            <form id="registrationForm" class="tab-wizard2 wizard-circle wizard" method="POST"
                                action="{{ route('register') }}" enctype="multipart/form-data">
                                @csrf
                                <!-- Step 1: Basic Account Credentials -->
                                <h5>Basic Account Credentials</h5>
                                <section>
                                    <input type="hidden" name="data" value="{{ request()->input('data') }}">
                                    <div class="form-wrap max-width-600 mx-auto">
                                        <!-- Email Input -->
                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label">Email</label>
                                            <div class="col-sm-8">

                                                <input type="email" name="email"
                                                    class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                                                    value="{{ old(
                                                        'email',
                                                        $email ??
                                                            (request()->has('data')
                                                                ? (function () {
                                                                    try {
                                                                        $data = Crypt::decryptString(request()->query('data'));
                                                                        $email = explode('|', $data)[0] ?? '';
                                                                        return $email;
                                                                    } catch (\Exception $e) {
                                                                        return '';
                                                                    }
                                                                })()
                                                                : ''),
                                                    ) }}"
                                                    {{ isset($email) || request()->has('data') ? 'readonly' : '' }} />
                                                @error('email')
                                                    <div class="text-danger small-error">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <!-- Company Name Input -->
                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label">Company Name</label>
                                            <div class="col-sm-8">
                                                <input type="text" name="companyName"
                                                    class="form-control {{ $errors->has('companyName') ? 'is-invalid' : '' }}"
                                                    value="{{ old(
                                                        'companyName',
                                                        $companyName ??
                                                            (request()->has('data')
                                                                ? (function () {
                                                                    try {
                                                                        $data = Crypt::decryptString(request()->query('data'));
                                                                        $companyName = explode('|', $data)[1] ?? '';
                                                                        return $companyName;
                                                                    } catch (\Exception $e) {
                                                                        return '';
                                                                    }
                                                                })()
                                                                : ''),
                                                    ) }}"
                                                    {{ isset($companyName) || request()->has('data') ? 'readonly' : '' }} />
                                                @error('companyName')
                                                    <div class="text-danger small-error">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>


                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label">Password*</label>
                                            <div class="col-sm-8">
                                                <input type="password" name="password"
                                                    class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}"
                                                    required />
                                                @error('password')
                                                    <div class="text-danger small-error">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label">Confirm Password*</label>
                                            <div class="col-sm-8">
                                                <input type="password" name="password_confirmation"
                                                    class="form-control {{ $errors->has('password_confirmation') ? 'is-invalid' : '' }}"
                                                    required />
                                                @error('password_confirmation')
                                                    <div class="text-danger small-error">{{ $message }}</div>
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
                                                <input type="text" name="fullName"
                                                    class="form-control {{ $errors->has('fullName') ? 'is-invalid' : '' }}"
                                                    value="{{ old('fullName') }}" required />
                                                @error('fullName')
                                                    <div class="text-danger small-error">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row align-items-center">
                                            <label class="col-sm-4 col-form-label">Gender*</label>
                                            <div class="col-sm-8">
                                                <div class="custom-control custom-radio custom-control-inline pb-0">
                                                    <input type="radio" id="male" name="gender" value="male"
                                                        class="custom-control-input" required />
                                                    <label class="custom-control-label" for="male">Male</label>
                                                </div>
                                                <div class="custom-control custom-radio custom-control-inline pb-0">
                                                    <input type="radio" id="female" name="gender" value="female"
                                                        class="custom-control-input" required />
                                                    <label class="custom-control-label" for="female">Female</label>
                                                </div>
                                                @error('gender')
                                                    <div class="text-danger small-error">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label">Address</label>
                                            <div class="col-sm-8">
                                                <input type="text" name="address"
                                                    class="form-control {{ $errors->has('address') ? 'is-invalid' : '' }}"
                                                    value="{{ old('address') }}" />
                                                @error('address')
                                                    <div class="text-danger small-error">{{ $message }}</div>
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
                                                <input type="file" name="business_registration"
                                                    class="form-control {{ $errors->has('business_registration') ? 'is-invalid' : '' }}"
                                                    required accept=".pdf,.jpg,.jpeg,.png" />
                                                <small class="form-text text-muted">Upload your business registration
                                                    document (PDF or image format).</small>
                                                @error('business_registration')
                                                    <div class="text-danger small-error">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label">Mayor's Permit*</label>
                                            <div class="col-sm-8">
                                                <input type="file" name="mayor_permit"
                                                    class="form-control {{ $errors->has('mayor_permit') ? 'is-invalid' : '' }}"
                                                    required accept=".pdf,.jpg,.jpeg,.png" />
                                                <small class="form-text text-muted">Upload your Mayor's Permit (PDF or
                                                    image format).</small>
                                                @error('mayor_permit')
                                                    <div class="text-danger small-error">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label">Tax Identification Number
                                                (TIN)*</label>
                                            <div class="col-sm-8">
                                                <input type="file" name="tin"
                                                    class="form-control {{ $errors->has('tin') ? 'is-invalid' : '' }}"
                                                    required accept=".pdf,.jpg,.jpeg,.png" />
                                                <small class="form-text text-muted">Upload your TIN document (PDF or
                                                    image format).</small>
                                                @error('tin')
                                                    <div class="text-danger small-error">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label">Proof of Identity*</label>
                                            <div class="col-sm-8">
                                                <input type="file" name="proof_of_identity"
                                                    class="form-control {{ $errors->has('proof_of_identity') ? 'is-invalid' : '' }}"
                                                    required accept=".pdf,.jpg,.jpeg,.png" />
                                                <small class="form-text text-muted">Upload a valid government-issued ID
                                                    (PDF or image format).</small>
                                                @error('proof_of_identity')
                                                    <div class="text-danger small-error">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </section>
                            </form>


                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Success Popup HTML Start -->
    <button type="button" id="success-modal-btn" hidden data-toggle="modal" data-target="#success-modal"
        data-backdrop="static">
        Launch modal
    </button>
    <div class="modal fade" id="success-modal" tabindex="-1" role="dialog"
        aria-labelledby="successModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-body text-center font-18" style="max-height: 70vh; overflow-y: auto;">
                    <h3 class="mb-4">Success!</h3>
                    <div class="mb-3 text-center">
                        <img src="images/success.png" alt="Success" class="img-fluid" />
                    </div>
                    <p class="mb-4">
                        Your form has been successfully submitted! To continue using our services, please review the
                        terms and conditions below.
                    </p>

                    <!-- Centering the terms and conditions -->
                    <div class="terms-and-conditions mt-4 mb-4 text-center">
                        <h5 class="mb-3">Terms and Conditions</h5>
                        <p>Please read the following terms and conditions carefully:</p>
                        <ul class="list-unstyled" style="text-align: left; display: inline-block;">
                            <li><strong>1. Acceptance of Terms:</strong> By accessing and using our services, you agree
                                to comply with these terms and conditions as well as all applicable laws in the
                                Philippines.</li>
                            <li><strong>2. Service Usage:</strong> You agree to use our services only for lawful
                                purposes and in a manner that does not infringe the rights of, or restrict or inhibit
                                the use of this service by any third party.</li>
                            <li><strong>3. Data Privacy:</strong> We respect your privacy and are committed to
                                protecting your personal data in accordance with the Data Privacy Act of 2012 (Republic
                                Act No. 10173). For more details, please review our Privacy Policy.</li>
                            <li><strong>4. Intellectual Property:</strong> All content, trademarks, and other
                                intellectual property rights associated with our services are owned by or licensed to
                                us. You may not use any of these materials without our prior written consent.</li>
                            <li><strong>5. Limitation of Liability:</strong> In no event shall we be liable for any
                                direct, indirect, incidental, special, consequential, or punitive damages arising from
                                your use of our services.</li>
                            <li><strong>6. Modification of Terms:</strong> We reserve the right to amend or modify these
                                terms at any time. Changes will be effective immediately upon posting on our website.
                            </li>
                            <li><strong>7. Governing Law:</strong> These terms and conditions are governed by the laws
                                of the Republic of the Philippines. Any disputes arising under these terms shall be
                                subject to the exclusive jurisdiction of the courts of the Philippines.</li>
                        </ul>
                    </div>

                    <div class="custom-control custom-checkbox mt-4 mb-3">
                        <input type="checkbox" class="custom-control-input" id="termsAgreement" required />
                        <label class="custom-control-label" for="termsAgreement">
                            I have read and agree to the terms and conditions.
                        </label>
                    </div>
                    <p>
                        Once you agree to the terms and conditions, you can proceed with your next steps.
                    </p>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-warning" id="termsAgreeBtn" disabled>Done</button>
                </div>
            </div>
        </div>
    </div>





    <script>
        document.getElementById('termsAgreement').addEventListener('change', function() {
            document.getElementById('termsAgreeBtn').disabled = !this.checked;
        });

        document.getElementById('termsAgreeBtn').addEventListener('click', function() {
            // If the button is enabled, submit the form
            if (!this.disabled) {
                document.getElementById('registrationForm').submit();
            }
        });
    </script>






    <!-- js -->
    <script src="{{ asset('js/core.js') }}"></script>
    <script src="{{ asset('js/script.min.js') }}"></script>
    <script src="{{ asset('js/process.js') }}"></script>
    <script src="{{ asset('js/layout-settings.js') }}"></script>
    <script src="src/plugins/jquery-steps/jquery.steps.js"></script>
    <script src="{{ asset('js/steps-setting.js') }}"></script>
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-NXZMQSS" height="0" width="0"
            style="display: none; visibility: hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
</body>

</html>
