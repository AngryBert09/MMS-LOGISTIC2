<!DOCTYPE html>
<html>

@include('layout.head')

<body>
    {{-- <div class="pre-loader">
        <div class="pre-loader-box">
            <div class="loader-logo">
                <img src="vendors/images/deskapp-logo.svg" alt="" />
            </div>
            <div class="loader-progress" id="progress_div">
                <div class="bar" id="bar1"></div>
            </div>
            <div class="percent" id="percent1">0%</div>
            <div class="loading-text">Loading...</div>
        </div>
    </div> --}}



    <div class="mobile-menu-overlay"></div>
    @include('layout.nav')
    @include('layout.left-sidebar')
    <div class="main-container">
        <div class="pd-ltr-20 xs-pd-20-10">
            <div class="min-height-200px">
                @include('layout.breadcrumb')
                <!-- Simple Datatable Start -->
                <div class="card-box mb-30">
                    <div class="pd-20">
                        <h4 class="text-warning h4">Deliveries</h4>
                        <p class="mb-0">All Deliveries are for Great Wall Arts.</p>
                    </div>
                    <div class="pb-20">
                        <!-- Assign Rider Modal -->


                        <!-- Data Table -->
                        <table class="data-table table stripe hover nowrap">
                            <thead>
                                <tr>
                                    <th>PO ID</th>
                                    {{-- <th>Rider Name</th> --}}
                                    <th>Tracking#</th>
                                    <th>Status</th>
                                    <th>Estimated Delivery</th>
                                    <th>Actual Delivery</th>
                                    <th>Address</th>
                                    <th>Shipment Method</th>
                                    <th>Shipping Cost</th>
                                    <th>Weight</th>
                                    <th>Action</th> <!-- New Action Column -->
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($shipments as $shipment)
                                    <tr>
                                        <td>{{ $shipment->po_id }}</td>
                                        {{-- <td>{{ $shipment->rider_name ?? 'Not Assigned' }}</td> --}}
                                        <td>{{ $shipment->tracking_number }}</td>

                                        <!-- Shipment Status with Badge -->
                                        <td>
                                            <span
                                                class="badge
                                                @if ($shipment->shipment_status == 'Delivered') bg-success
                                                @elseif($shipment->shipment_status == 'In Transit') bg-info
                                                @elseif($shipment->shipment_status == 'Pending') bg-warning
                                                @elseif($shipment->shipment_status == 'Cancelled') bg-danger
                                                @else bg-secondary @endif">
                                                {{ ucfirst($shipment->shipment_status) }}
                                            </span>
                                        </td>

                                        <td>{{ $shipment->estimated_delivery_date }}</td>
                                        <td>{{ $shipment->actual_delivery_date }}</td>
                                        <td>{{ $shipment->shipping_address }}</td>

                                        <!-- Shipment Method with Badge -->
                                        <td>
                                            <span
                                                class="badge
                                                @if ($shipment->shipment_method == 'Standard') bg-primary
                                                @elseif($shipment->shipment_method == 'Express') bg-secondary
                                                @elseif($shipment->shipment_method == 'Overnight') bg-dark
                                                @else bg-light text-dark @endif">
                                                {{ ucfirst($shipment->shipment_method) }}
                                            </span>
                                        </td>

                                        <td>{{ $shipment->shipping_cost }}</td>
                                        <td>{{ $shipment->weight }}</td>

                                        <!-- Assign Rider Action -->
                                        {{-- <td>
                                            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#assignRiderModal{{ $shipment->shipment_id }}">
                                                Assign Rider
                                            </button>
                                        </td> --}}
                                    </tr>

                                    <!-- Modal for Assigning Rider -->
                                    <div class="modal fade" id="assignRiderModal{{ $shipment->shipment_id }}"
                                        tabindex="-1"
                                        aria-labelledby="assignRiderModalLabel{{ $shipment->shipment_id }}"
                                        aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title"
                                                        id="assignRiderModalLabel{{ $shipment->shipment_id }}">Assign
                                                        Rider to
                                                        Shipment #{{ $shipment->tracking_number }}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form
                                                        action="{{ route('shipments.assign-rider', $shipment->shipment_id) }}"
                                                        method="POST">
                                                        @csrf
                                                        <div class="mb-3">
                                                            <label for="riderName{{ $shipment->shipment_id }}"
                                                                class="form-label">Rider Name</label>
                                                            <input type="text" class="form-control"
                                                                id="riderName{{ $shipment->shipment_id }}"
                                                                name="rider_name" required>
                                                        </div>
                                                        <button type="submit" class="btn btn-primary">Assign</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </tbody>
                        </table>


                    </div>
                </div>
                <!-- Modal -->
                <div class="modal fade" id="assignRiderModal" tabindex="-1" aria-labelledby="assignRiderModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="assignRiderModalLabel">Assign Rider</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form id="assignRiderForm">
                                    @csrf
                                    <input type="hidden" id="shipment_id" name="shipment_id">
                                    <div class="mb-3">
                                        <label for="rider_name" class="form-label">Rider Name</label>
                                        <input type="text" class="form-control" id="rider_name" name="rider_name"
                                            required>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary" id="saveRiderBtn">Save</button>
                            </div>
                        </div>
                    </div>
                </div>


                <!-- Simple Datatable End -->
                <!-- multiple select row Datatable start -->

                <!-- multiple select row Datatable End -->
                <!-- Checkbox select Datatable start -->

                <!-- Checkbox select Datatable End -->
                <!-- Export Datatable start -->

                <!-- Export Datatable End -->
            </div>
        </div>
    </div>
    <!-- welcome modal end -->
    <!-- js -->
    <!-- Core Scripts -->
    <script src="{{ asset('js/core.js') }}"></script>
    <script src="{{ asset('js/script.min.js') }}"></script>
    <script src="{{ asset('js/process.js') }}"></script>
    <script src="{{ asset('js/layout-settings.js') }}"></script>

    <!-- DataTables -->
    <script src="{{ asset('src/plugins/datatables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('src/plugins/datatables/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('src/plugins/datatables/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('src/plugins/datatables/js/responsive.bootstrap4.min.js') }}"></script>

    <!-- Export Buttons -->
    <script src="{{ asset('src/plugins/datatables/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('src/plugins/datatables/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('src/plugins/datatables/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('src/plugins/datatables/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('src/plugins/datatables/js/buttons.flash.min.js') }}"></script>
    <script src="{{ asset('src/plugins/datatables/js/pdfmake.min.js') }}"></script>
    <script src="{{ asset('src/plugins/datatables/js/vfs_fonts.js') }}"></script>

    <!-- Datatable Setting js -->
    <script src="js/datatable-setting.js"></script>
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-NXZMQSS" height="0" width="0"
            style="display: none; visibility: hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
</body>

</html>
