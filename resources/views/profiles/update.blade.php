<div class="profile-setting">
    <form action="{{ route('profiles.update', $vendor->id) }}" method="POST">
        @csrf
        @method('PUT')

        <!-- Account Information Section -->
        <ul class="profile-edit-list row">
            <li class="weight-500 col-md-6">
                <h4 class="text-dark h3 mb-2">Account Information</h4>
                <p class="text-muted small">
                    Below is your account information. Some details, such as your name and email, are read-only for
                    security reasons.
                    Ensure that your contact details are accurate to receive important updates.
                </p>

                <!-- Full Name (Always readonly) -->
                <div class="form-group">
                    <label>Full Name</label>
                    <input class="form-control form-control-lg" type="text" value="{{ $vendor->full_name }}"
                        disabled />
                </div>

                <!-- Company Name (Always readonly) -->
                <div class="form-group">
                    <label>Company Name</label>
                    <input class="form-control form-control-lg" type="text" value="{{ $vendor->company_name }}"
                        disabled />
                </div>

                <!-- Email (Always readonly) -->
                <div class="form-group">
                    <label>Email</label>
                    <div class="d-flex align-items-center position-relative">
                        <input
                            class="form-control form-control-lg @if ($vendor->verifiedVendor && $vendor->verifiedVendor->verification_token === 'verified') border-success @endif"
                            type="email" value="{{ $vendor->email }}" disabled />
                        @if ($vendor->verifiedVendor && $vendor->verifiedVendor->verification_token === 'verified')
                            <span class="position-absolute text-success"
                                style="right: 10px; top: 50%; transform: translateY(-50%);">
                                ✔️
                            </span>
                        @endif
                    </div>
                </div>


                <!-- Email Verification Section -->
                @if (!$vendor->verifiedVendor || $vendor->verifiedVendor->verification_token === null)
                    <div class="form-group mb-3">
                        <p>If you haven't verified your email yet, please click the button below to send the
                            verification email.</p>
                        <button id="resend-verification-btn" class="btn btn-success">Send Verification Email</button>
                    </div>
                @endif


                <!-- Postal Code -->
                <div class="form-group">
                    <label>Postal Code</label>
                    <input class="form-control form-control-lg" name="postal_code" type="text"
                        value="{{ old('postal_code', $vendor->postal_code) }}"
                        {{ $vendor->postal_code ? 'disabled' : '' }} />
                    @if ($errors->has('postal_code'))
                        <div class="text-danger">{{ $errors->first('postal_code') }}</div>
                    @endif
                </div>

                <!-- Phone Number -->
                <div class="form-group">
                    <label>Phone Number</label>
                    <input class="form-control form-control-lg" name="phone_number" type="text"
                        value="{{ old('phone_number', $vendor->phone_number) }}"
                        {{ $vendor->phone_number ? 'disabled' : '' }} />
                    @if ($errors->has('phone_number'))
                        <div class="text-danger">{{ $errors->first('phone_number') }}</div>
                    @endif
                </div>

                <!-- Address -->
                <div class="form-group">
                    <label>Address</label>
                    <textarea class="form-control" name="address" {{ $vendor->address ? 'disabled' : '' }}>{{ old('address', $vendor->address) }}</textarea>
                    @if ($errors->has('address'))
                        <div class="text-danger">{{ $errors->first('address') }}</div>
                    @endif
                </div>

                <!-- Notifications Checkbox -->
                <div class="form-group">
                    @if (!$vendor->verifiedVendor || !$vendor->verifiedVendor->is_verified)
                        <div class="custom-control custom-checkbox mb-5">
                            <input type="hidden" name="notifications_enabled" value="0">
                            <input type="checkbox" name="notifications_enabled" class="custom-control-input"
                                id="customCheck1-1" value="1"
                                {{ $vendor->notifications_enabled ? 'checked' : '' }}>
                            <label class="custom-control-label weight-400" for="customCheck1-1">
                                I agree to receive notification emails (Optional)
                            </label>
                        </div>
                    @else
                        @if ($vendor->notifications_enabled)
                            <p class="text-muted">You are verified and will receive notification emails automatically.
                            </p>
                        @else
                            <p class="text-muted">You are verified but have opted out of receiving notification emails.
                            </p>
                        @endif
                    @endif
                </div>

                <!-- Submit Button -->
                <div class="form-group mb-0">
                    <input type="submit" class="btn btn-dark" value="Update Information"
                        {{ $vendor->verifiedVendor && $vendor->verifiedVendor->is_verified ? 'disabled' : '' }} />
                </div>
            </li>
        </ul>
    </form>
</div>


<script>
    document.getElementById('resend-verification-btn').addEventListener('click', function(event) {
        event.preventDefault();

        // Send the request to resend the email via AJAX or redirect with the form
        var form = document.createElement('form');
        form.method = 'POST';
        form.action = "{{ route('profiles.verifyEmail', $vendor->id) }}";
        var csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        form.appendChild(csrfToken);
        document.body.appendChild(form);
        form.submit();
    });
</script>
