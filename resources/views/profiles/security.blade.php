<div class="pd-20 profile-task-wrap">
    <div class="container pd-0">
        <!-- Change Password Section Start -->
        <div class="billing-title row align-items-center">
            <div class="col-md-8 col-sm-12">
                <h5>Change Password</h5>
                <p class="text-muted small">For security reasons, please choose a strong password that you haven't used
                    before.</p>
            </div>
        </div>

        <div class="card-body">
            <form method="POST" action="{{ route('profiles.update', $vendor->id) }}">
                @csrf
                @method('PUT')

                <!-- Current Password -->
                <div class="form-group">
                    <label>Current Password</label>
                    <input type="password" name="current_password" class="form-control"
                        placeholder="Enter current password" required>
                </div>

                <!-- New Password -->
                <div class="form-group">
                    <label>New Password</label>
                    <input type="password" name="new_password" class="form-control" placeholder="Enter new password"
                        required>
                </div>

                <!-- Confirm Password -->
                <div class="form-group">
                    <label>Confirm Password</label>
                    <input type="password" name="new_password_confirmation" class="form-control"
                        placeholder="Confirm new password" required>
                </div>

                <div class="text-right">
                    <button type="submit" class="btn btn-warning">Update Password</button>
                </div>
            </form>
        </div>
    </div>
    <!-- Change Password Section End -->
</div>
