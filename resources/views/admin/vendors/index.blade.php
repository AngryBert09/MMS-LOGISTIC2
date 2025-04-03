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
                <!-- Simple Datatable start -->
                <div class="card-box mb-30">
                    <div class="pd-20 d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="text-warning h4">Vendor management</h4>
                            <p class="text-muted">Vendors Application</p>
                        </div>
                        <div>
                            <button type="button" class="btn btn-warning" data-bs-toggle="modal"
                                data-bs-target="#inviteVendorModal">
                                Invite Vendors
                            </button>
                        </div>
                    </div>
                    <div class="pb-20">
                        <table class="table table-striped table-hover nowrap dt-responsive" style="width:100%">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Company Name</th>
                                    <th>Email</th>
                                    <th>Address</th>
                                    <th>Contact#</th>
                                    <th>Status</th>
                                    <th>Documents</th>
                                    <th>Created At</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($vendors as $vendor)
                                    <tr>
                                        <td>{{ $vendor->id }}</td>
                                        <td>{{ $vendor->company_name }}</td>
                                        <td>{{ $vendor->email }}</td>
                                        <td>{{ $vendor->address }}</td>
                                        <td>{{ $vendor->phone_number }}</td>
                                        <td>
                                            @if ($vendor->status === 'Approved')
                                                <span class="badge badge-success">Approved</span>
                                            @elseif ($vendor->status === 'Pending')
                                                <span class="badge badge-warning">Pending</span>
                                            @elseif ($vendor->status === 'Rejected')
                                                <span class="badge badge-danger">Rejected</span>
                                            @elseif ($vendor->status === 'Banned')
                                                <span class="badge badge-dark">Banned</span>
                                            @else
                                                <span class="badge badge-secondary">Unknown</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($vendor->business_registration)
                                                <a href="{{ asset('storage/app/' . $vendor->business_registration) }}"
                                                    target="_blank">Business Reg.</a><br>
                                            @endif
                                            @if ($vendor->mayor_permit)
                                                <a href="{{ asset('storage/app/' . $vendor->mayor_permit) }}"
                                                    target="_blank">Mayor's Permit</a><br>
                                            @endif
                                            @if ($vendor->tin)
                                                <a href="{{ asset('storage/app/' . $vendor->tin) }}"
                                                    target="_blank">TIN</a>
                                                <br>
                                            @endif
                                            @if ($vendor->proof_of_identity)
                                                <a href="{{ asset('storage/app/' . $vendor->proof_of_identity) }}"
                                                    target="_blank">Proof of ID</a>
                                            @endif
                                        </td>
                                        <td>{{ $vendor->created_at->format('d-m-Y') }}</td>
                                        <td>
                                            @if ($vendor->status === 'Pending')
                                                <form action="{{ route('admin.vendors.updateStatus', $vendor->id) }}"
                                                    method="POST" style="display:inline;">
                                                    @csrf
                                                    <input type="hidden" name="status" value="Approved">
                                                    <button type="submit"
                                                        class="btn btn-sm btn-success">Approve</button>
                                                </form>

                                                <form action="{{ route('admin.vendors.updateStatus', $vendor->id) }}"
                                                    method="POST" style="display:inline;">
                                                    @csrf
                                                    <input type="hidden" name="status" value="Rejected">
                                                    <button type="submit" class="btn btn-sm btn-danger">Reject</button>
                                                </form>
                                            @elseif ($vendor->status === 'Approved')
                                                <form action="{{ route('admin.vendors.updateStatus', $vendor->id) }}"
                                                    method="POST" style="display:inline;">
                                                    @csrf
                                                    <input type="hidden" name="status" value="Banned">
                                                    <button type="submit" class="btn btn-sm btn-dark">Ban</button>
                                                </form>
                                            @elseif ($vendor->status === 'Banned')
                                                <form action="{{ route('admin.vendors.updateStatus', $vendor->id) }}"
                                                    method="POST" style="display:inline;">
                                                    @csrf
                                                    <input type="hidden" name="status" value="Approved">
                                                    <button type="submit"
                                                        class="btn btn-sm btn-success">Activate</button>
                                                </form>
                                            @endif
                                        </td>

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- Simple Datatable End -->
            </div>
        </div>
    </div>

    <!-- Invite Vendor Modal -->

    <!-- Invite Vendor Modal -->
    <div class="modal fade" id="inviteVendorModal" tabindex="-1" aria-labelledby="inviteVendorModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="inviteVendorModalLabel">Invite Vendor</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="inviteVendorForm">
                        @csrf
                        <div class="mb-3">
                            <label for="companyName" class="form-label">Company Name</label>
                            <input type="text" class="form-control" id="companyName" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" form="inviteVendorForm">Send Invitation</button>
                </div>
            </div>
        </div>
    </div>


    <!-- AJAX Form Submission -->
    <script>
        document.getElementById('inviteVendorForm').addEventListener('submit', function(event) {
            event.preventDefault();

            let formData = new FormData(this);

            fetch('{{ route('admin.vendors-invite') }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Invitation sent successfully!');
                        this.reset();

                        // Properly close the modal
                        var modalElement = document.getElementById('inviteVendorModal');
                        var modalInstance = bootstrap.Modal.getInstance(modalElement); // Get existing instance
                        if (modalInstance) {
                            modalInstance.hide();
                        } else {
                            modalInstance = new bootstrap.Modal(modalElement);
                            modalInstance.hide();
                        }
                    } else {
                        alert('Error sending invitation: ' + data.error);
                    }
                })
                .catch(error => console.error('Error:', error));
        });
    </script>



    <!-- welcome modal end -->
    <!-- js -->
    <!-- jQuery -->
    <script src="{{ asset('js/core.js') }}"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/script.min.js') }}"></script>
    <script src="{{ asset('js/process.js') }}"></script>
    <script src="{{ asset('js/layout-settings.js') }}"></script>
    <script src="{{ asset('src/plugins/datatables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('src/plugins/datatables/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('src/plugins/datatables/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('src/plugins/datatables/js/responsive.bootstrap4.min.js') }}"></script>
    <!-- buttons for Export datatable -->
    <script src="{{ asset('src/plugins/datatables/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('src/plugins/datatables/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('src/plugins/datatables/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('src/plugins/datatables/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('src/plugins/datatables/js/buttons.flash.min.js') }}"></script>
    <script src="{{ asset('src/plugins/datatables/js/pdfmake.min.js') }}"></script>
    <script src="{{ asset('src/plugins/datatables/js/vfs_fonts.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('.table').DataTable();
        });
    </script>



    <!-- Datatable Setting js -->
    <script src="js/datatable-setting.js"></script>
    <!-- Google Tag Manager (noscript) -->

    <!-- End Google Tag Manager (noscript) -->
</body>

</html>
