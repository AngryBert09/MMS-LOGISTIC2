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
                <!-- Simple Datatable Start -->
                <div class="card-box mb-30">
                    <div class="pd-20 d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="text-warning h4">Procurement Approval</h4>
                            <p class="text-muted">
                                Overview of all your procurement requests including item name, price, quantity, status,
                                deadline, and description. This section allows you to review and approve procurement
                                requests efficiently.
                            </p>
                        </div>
                    </div>
                    <div class="pb-20">
                        <table class="table table-striped table-hover nowrap dt-responsive" style="width:100%">
                            <thead>
                                <tr>
                                    <th class="table-plus">ID</th>
                                    <th>Item Name</th>
                                    <th>Starting Price</th>
                                    <th>Quantity</th>
                                    <th>Status</th>
                                    <th>Deadline</th>
                                    <th>Description</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($procurementRequests as $request)
                                    <tr>
                                        <td class="table-plus">{{ $request->id }}</td>
                                        <td>{{ $request->item_name }}</td>
                                        <td>{{ number_format($request->starting_price, 2) }}</td>
                                        <td>{{ $request->quantity }}</td>
                                        <td>
                                            @switch($request->status)
                                                @case('Approved')
                                                    <span class="badge badge-primary">Approved</span>
                                                @break

                                                @case('Under Review')
                                                    <span class="badge badge-info">Under Review</span>
                                                @break

                                                @case('Awarded')
                                                    <span class="badge badge-success">Awarded</span>
                                                @break

                                                @case('Cancelled')
                                                    <span class="badge badge-danger">Cancelled</span>
                                                @break

                                                @case('Completed')
                                                    <span class="badge badge-dark">Completed</span>
                                                @break

                                                @case('Rejected')
                                                    <span class="badge badge-warning">Rejected</span>
                                                @break

                                                @default
                                                    <span class="badge badge-secondary">Pending</span>
                                            @endswitch
                                        </td>



                                        <td>{{ \Carbon\Carbon::parse($request->deadline)->format('M d, Y') }}</td>
                                        <td>{{ Str::limit($request->description, 50) }}</td>

                                        <td>
                                            <!-- View Button -->
                                            <button type="button" class="btn btn-primary btn-sm viewRequestBtn"
                                                data-id="{{ $request->id }}" data-item="{{ $request->item_name }}"
                                                data-quantity="{{ $request->quantity }}"
                                                data-starting_price="{{ $request->starting_price }}"
                                                data-status="{{ $request->status }}"
                                                data-deadline="{{ $request->deadline }}"
                                                data-description="{{ $request->description }}" data-toggle="modal"
                                                data-target="#viewRequestModal">
                                                View
                                            </button>

                                            <!-- Update Status Button -->
                                            <button type="button" class="btn btn-warning btn-sm updateStatusBtn"
                                                data-id="{{ $request->id }}" data-status="{{ $request->status }}"
                                                data-toggle="modal" data-target="#updateStatusModal">
                                                Update Status
                                            </button>
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

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Update Procurement Request Status Modal -->
    <div class="modal fade" id="updateStatusModal" tabindex="-1" role="dialog"
        aria-labelledby="updateStatusModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateStatusModalLabel">Update Procurement Status</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="updateStatusForm">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <input type="hidden" id="updateRequestId" name="request_id">
                        <div class="form-group">
                            <label for="newStatus">Select Status</label>
                            <select class="form-control" id="newStatus" name="status">
                                <option value="" disabled selected>Select Status</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-warning">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('.updateStatusBtn').on('click', function() {
                let requestId = $(this).data('id');
                let currentStatus = $(this).data('status');

                $('#updateRequestId').val(requestId);
                let statusSelect = $('#newStatus');
                statusSelect.empty(); // Clear previous options
                statusSelect.append('<option value="" disabled selected>Select Status</option>');

                // Dynamically add options based on current status
                if (currentStatus === "Approved") {
                    statusSelect.append('<option value="Cancelled">Cancelled</option>');
                } else if (currentStatus === "Under Review") {
                    statusSelect.append('<option value="Approved">Approve</option>');
                    statusSelect.append('<option value="Rejected">Reject</option>');
                } else if (currentStatus === "Open") {
                    statusSelect.append('<option value="Under Review">Under Review</option>');
                }

                $('#updateStatusModal').modal('show');
            });

            $('#updateStatusForm').on('submit', function(e) {
                e.preventDefault();
                let requestId = $('#updateRequestId').val();
                let newStatus = $('#newStatus').val();

                $.ajax({
                    url: `/admin/procurement-request/${requestId}/update-status`,
                    type: 'PUT',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        status: newStatus
                    },
                    success: function(response) {
                        $('#updateStatusModal').modal('hide');

                        if (response.success) {
                            if (newStatus === "Approved") {
                                // Redirect to the bidding details page if approved
                                location.reload();
                            }
                        } else {
                            alert('Error updating status.');
                        }
                    },
                    error: function() {
                        alert('Error updating status.');
                    }
                });
            });
        });
    </script>



    <!-- View Procurement Request Modal -->
    <div class="modal fade" id="viewRequestModal" tabindex="-1" aria-labelledby="viewRequestModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content border-0 shadow-lg rounded-3">
                <div class="modal-header bg-warning text-white">
                    <h5 class="modal-title" id="viewRequestModalLabel">Procurement Request Details</h5>
                    <button type="button" class="btn-close text-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted">Item Name</h6>
                            <p class="fw-bold" id="modalItemName"></p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted">Quantity</h6>
                            <p class="fw-bold" id="modalQuantity"></p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted">Starting Price</h6>
                            <p class="fw-bold" id="modalStartingPrice"></p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted">Status</h6>
                            <span id="modalStatus" class="badge"></span>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted">Deadline</h6>
                            <p class="fw-bold" id="modalDeadline"></p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted">Requested By</h6>
                            <p class="fw-bold" id="modalEmployee"></p>
                        </div>
                        <div class="col-12">
                            <h6 class="text-muted">Description</h6>
                            <p class="fw-bold" id="modalDescription"></p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $('.viewRequestBtn').on('click', function() {
                $('#modalItemName').text($(this).data('item'));
                $('#modalQuantity').text($(this).data('quantity'));
                $('#modalStartingPrice').text($(this).data('starting_price'));
                $('#modalDeadline').text($(this).data('deadline'));
                $('#modalEmployee').text($(this).data('employee'));
                $('#modalDescription').text($(this).data('description'));

                let status = $(this).data('status');
                let statusBadge = $('#modalStatus');

                statusBadge.removeClass().addClass('badge');

                switch (status) {
                    case 'Under Review':
                        statusBadge.addClass('bg-info text-white').text('Under Review');
                        break;
                    case 'Approved':
                        statusBadge.addClass('bg-primary text-white').text('Approved');
                        break;
                    case 'Rejected':
                        statusBadge.addClass('bg-warning text-white').text('Rejected');
                        break;
                    case 'Cancelled':
                        statusBadge.addClass('bg-danger text-white').text('Cancelled');
                        break;
                    case 'Completed':
                        statusBadge.addClass('bg-dark text-white').text('Completed');
                        break;
                    default:
                        statusBadge.addClass('bg-warning text-dark').text('Pending');
                }

                $('#viewRequestModal').modal('show');
            });
        });
    </script>




    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('js/core.js') }}"></script>
    <script src="{{ asset('js/script.min.js') }}"></script>
    <script src="{{ asset('js/process.js') }}"></script>
    <script src="{{ asset('js/layout-settings.js') }}"></script>
    <script src="{{ asset('src/plugins/datatables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('src/plugins/datatables/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('src/plugins/datatables/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('src/plugins/datatables/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('src/plugins/datatables/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('src/plugins/datatables/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('src/plugins/datatables/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('src/plugins/datatables/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('src/plugins/datatables/js/buttons.flash.min.js') }}"></script>
    <script src="{{ asset('src/plugins/datatables/js/pdfmake.min.js') }}"></script>
    <script src="{{ asset('src/plugins/datatables/js/vfs_fonts.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('.table').DataTable({
                responsive: true,
                paging: true,
                searching: true,
                ordering: true,
                info: true,
                autoWidth: false
            });
        });
    </script>

</body>

</html>
