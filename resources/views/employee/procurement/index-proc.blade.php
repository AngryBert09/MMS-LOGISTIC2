<!DOCTYPE html>
<html>

@include('layout.head')

<body>
    @include('employee.layout.navbar')
    @include('employee.layout.left-sidebar')
    @include('employee.layout.right-sidebar')

    <div class="mobile-menu-overlay"></div>

    <div class="main-container">
        <div class="pd-ltr-20 xs-pd-20-10">
            <div class="min-height-200px">
                @include('layout.breadcrumb')
                <!-- Simple Datatable Start -->
                <div class="card-box mb-30">
                    <div class="pd-20 d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="text-warning h4">My Procurement</h4>
                            <p class="text-muted">
                                Overview of all your procurement requests including item name, price, quantity, status,
                                deadline, and description.
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
    <!-- Create Procurement Request Modal -->
    <div class="modal fade" id="createRequestModal" tabindex="-1" role="dialog"
        aria-labelledby="createRequestModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createRequestModalLabel">Create Procurement Request</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('employee.procurement.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="item_name">Item Name</label>
                            <input type="text" class="form-control" id="item_name" name="item_name" required>
                        </div>
                        <div class="form-group">
                            <label for="quantity">Quantity</label>
                            <input type="number" class="form-control" id="quantity" name="quantity" required>
                        </div>
                        <div class="form-group">
                            <label for="starting_price">Starting Price</label>
                            <input type="number" class="form-control" id="starting_price" name="starting_price"
                                required>
                        </div>
                        <div class="form-group">
                            <label for="deadline">Deadline</label>
                            <input type="date" class="form-control" id="deadline" name="deadline" required>
                        </div>
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
                        </div>
                        <input type="hidden" name="employee_id" value="{{ Auth::id() }}">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Submit Request</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

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
