<!DOCTYPE html>
<html>

@include('layout.head')

<body>


    @include('admin.layout.navbar')

    @include('admin.layout.left-sidebar')
    @include('admin.layout.right-sidebar')
    <div class="mobile-menu-overlay"></div>

    <div class="main-container">
        <div class="pd-ltr-20 xs-pd-20-10">
            <div class="min-height-200px">
                @include('layout.breadcrumb')

                <!-- horizontal Basic Forms Start -->
                <div class="pd-20 card-box mb-30">
                    <div class="clearfix">
                        <div class="pull-left">
                            <h4 class="text-warning h4">Vendor Account</h4>
                            <p class="mb-30">Make sure to read carefuly before proceeding</p>
                        </div>

                    </div>
                    <form action="" method="POST">
                        @csrf
                        @method('PUT') <!-- Indicating that this is an update request -->

                        <div class="form-group">
                            <label>ID</label>
                            <input class="form-control" type="text" name="id" value="{{ $vendor->id }}"
                                disabled />
                        </div>

                        <div class="form-group">
                            <label>Company Name</label>
                            <input class="form-control" type="text" name="company_name"
                                value="{{ $vendor->company_name }}" required />
                        </div>

                        <div class="form-group">
                            <label>Email</label>
                            <input class="form-control" type="email" name="email" value="{{ $vendor->email }}"
                                required />
                        </div>

                        <div class="form-group">
                            <label>Password</label>
                            <input class="form-control" type="password" name="password"
                                placeholder="Enter new password (leave blank to keep current)" />
                        </div>

                        <div class="form-group">
                            <label>Account Owner</label>
                            <input class="form-control" type="text" name="full_name" value="{{ $vendor->full_name }}"
                                required />
                        </div>

                        <div class="form-group">
                            <label>Gender</label>
                            <select class="form-control" name="gender" required>
                                <option value="Male" {{ $vendor->gender == 'Male' ? 'selected' : '' }}>Male</option>
                                <option value="Female" {{ $vendor->gender == 'Female' ? 'selected' : '' }}>Female
                                </option>
                                <option value="Other" {{ $vendor->gender == 'Other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Address</label>
                            <input class="form-control" type="text" name="address" value="{{ $vendor->address }}"
                                required />
                        </div>

                        <div class="form-group">
                            <label>Status</label>
                            <select class="form-control" name="status" required>
                                <option value="Active" {{ $vendor->status == 'Active' ? 'selected' : '' }}>Active
                                </option>
                                <option value="Inactive" {{ $vendor->status == 'Inactive' ? 'selected' : '' }}>Inactive
                                </option>
                            </select>
                        </div>

                        <!-- Cancel and Update buttons -->
                        <div class="form-group text-left">
                            <a href="{{ route('admin.vendors-list') }}" class="btn btn-danger">Cancel</a>
                            <button type="submit" class="btn btn-warning">Update</button>
                        </div>
                    </form>



                    <!-- Input Validation End -->
                </div>

            </div>
        </div>

        <!-- js -->
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
