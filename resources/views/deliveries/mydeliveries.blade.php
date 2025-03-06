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
    @include('deliveries.layout.navbar')
    @include('deliveries.layout.left-sidebar')
    <div class="main-container">
        <div class="pd-ltr-20 xs-pd-20-10">
            <div class="min-height-200px">
                @include('layout.breadcrumb')
                <!-- Simple Datatable Start -->
                <div class="card-box mb-30">
                    <div class="pd-20">
                        <h4 class="text-warning h4"> My Deliveries</h4>
                        <p class="mb-0">Have a nice day! Delivery great!</p>
                    </div>
                    <div class="pb-20">
                        <table class="data-table table stripe hover nowrap">
                            <thead>
                                <tr>
                                    <th>PO ID</th>
                                    <th>Rider Name</th>
                                    <th>Tracking#</th>
                                    <th>Status</th>
                                    <th>Estimated Delivery</th>
                                    <th>Actual Delivery</th>
                                    <th>Address</th>
                                    <th>Shipment Method</th>
                                    <th>Shipping Cost</th>
                                    <th>Weight</th>
                                    <th>Action</th> <!-- New column for buttons -->
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($shipments as $shipment)
                                    <tr>
                                        <td>{{ $shipment->po_id }}</td>
                                        <td>{{ $shipment->rider_name }}</td>
                                        <td>{{ $shipment->tracking_number }}</td>

                                        <!-- Shipment Status with Badge -->
                                        <td>
                                            @php
                                                $statusClasses = [
                                                    'Pending' => 'badge badge-warning',
                                                    'Shipped' => 'badge badge-primary',
                                                    'Delivered' => 'badge badge-success',
                                                    'Canceled' => 'badge badge-danger',
                                                ];
                                                $statusClass =
                                                    $statusClasses[$shipment->shipment_status] ??
                                                    'badge badge-secondary';
                                            @endphp
                                            <span class="{{ $statusClass }}">{{ $shipment->shipment_status }}</span>
                                        </td>

                                        <td>{{ $shipment->estimated_delivery_date }}</td>
                                        <td>{{ $shipment->actual_delivery_date }}</td>
                                        <td>{{ $shipment->shipping_address }}</td>

                                        <!-- Shipment Method with Badge -->
                                        <td>
                                            @php
                                                $methodClasses = [
                                                    'Standard' => 'badge badge-info',
                                                    'Express' => 'badge badge-success',
                                                    'Same-day' => 'badge badge-danger',
                                                ];
                                                $methodClass =
                                                    $methodClasses[$shipment->shipment_method] ?? 'badge badge-dark';
                                            @endphp
                                            <span class="{{ $methodClass }}">{{ $shipment->shipment_method }}</span>
                                        </td>

                                        <td>{{ $shipment->shipping_cost }}</td>
                                        <td>{{ $shipment->weight }}</td>

                                        <!-- Action Buttons -->
                                        <td>
                                            <a class="btn btn-primary"
                                                href="{{ route('route.form', ['origin' => env('SHIPPING_ORIGIN'), 'destination' => $shipment->shipping_address]) }}">
                                                View Route
                                            </a>

                                            <!-- Button to trigger the modal -->
                                            <button type="button" class="btn btn-warning" data-toggle="modal"
                                                data-target="#updateStatusModal-{{ $shipment->shipment_id }}">
                                                Update Status
                                            </button>
                                        </td>
                                    </tr>

                                    <!-- Update Status Modal -->
                                    <div class="modal fade" id="updateStatusModal-{{ $shipment->shipment_id }}"
                                        tabindex="-1" role="dialog" aria-labelledby="updateStatusLabel"
                                        aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="updateStatusLabel">Update Shipment
                                                        Status</h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <form action="{{ route('deliveries.update', $shipment->shipment_id) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-body">
                                                        <div class="form-group">
                                                            <label for="shipment_status">Select New Status</label>
                                                            <select class="form-control" name="shipment_status"
                                                                id="shipment_status"
                                                                {{ in_array($shipment->shipment_status, ['Delivered', 'Completed']) ? 'disabled' : '' }}>
                                                                @php
                                                                    $statusOptions = [];

                                                                    switch ($shipment->shipment_status) {
                                                                        case 'Pending':
                                                                            $statusOptions = ['In Transit', 'Delayed'];
                                                                            break;
                                                                        case 'In Transit':
                                                                            $statusOptions = ['Delivered', 'Delayed'];
                                                                            break;
                                                                        case 'Delivered':
                                                                            $statusOptions = ['Completed'];
                                                                            break;
                                                                        case 'Delayed':
                                                                            $statusOptions = [
                                                                                'In Transit',
                                                                                'Pending',
                                                                                'Delivered',
                                                                            ];
                                                                            break;
                                                                    }
                                                                @endphp

                                                                @foreach ($statusOptions as $status)
                                                                    <option value="{{ $status }}"
                                                                        {{ $shipment->shipment_status == $status ? 'selected' : '' }}>
                                                                        {{ $status }}
                                                                    </option>
                                                                @endforeach
                                                            </select>

                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-dismiss="modal">Close</button>
                                                        <button type="submit" class="btn btn-success">Update
                                                            Status</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </tbody>
                        </table>


                        <!-- Modal -->
                        <!-- Modal -->
                        <div class="modal fade" id="mapModal" tabindex="-1" role="dialog"
                            aria-labelledby="mapModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="mapModalLabel">Shipment Route</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div id="map" style="width: 100%; height: 450px;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>



                    </div>
                </div>

                <!-- Load Google Maps API -->
                <script>
                    let map, directionsService, directionsRenderer;

                    function initMap() {
                        map = new google.maps.Map(document.getElementById('map'), {
                            center: {
                                lat: 14.5995,
                                lng: 120.9842
                            },
                            zoom: 12,
                        });

                        directionsService = new google.maps.DirectionsService();
                        directionsRenderer = new google.maps.DirectionsRenderer({
                            map: map,
                            suppressMarkers: true
                        });

                        // Add click event listener to the map
                        map.addListener('click', function(event) {
                            if (!pickupMarker) {
                                // Set pickup location
                                pickupMarker = new google.maps.Marker({
                                    position: event.latLng,
                                    map: map,
                                    label: 'P'
                                });
                                document.getElementById('pickup-location').value = event.latLng.lat() + ', ' + event.latLng
                                    .lng();
                            } else if (!dropoffMarker) {
                                // Set drop-off location
                                dropoffMarker = new google.maps.Marker({
                                    position: event.latLng,
                                    map: map,
                                    label: 'D'
                                });
                                document.getElementById('dropoff-location').value = event.latLng.lat() + ', ' + event.latLng
                                    .lng();
                                calculateRoute();
                            } else {
                                // Reset markers if both are already set
                                pickupMarker.setMap(null);
                                dropoffMarker.setMap(null);
                                pickupMarker = null;
                                dropoffMarker = null;
                                document.getElementById('pickup-location').value = '';
                                document.getElementById('dropoff-location').value = '';
                                directionsRenderer.set('directions', null);
                            }
                        });
                    }

                    function openMapModal(origin, destination) {
                        if (!map) initMap();

                        let request = {
                            origin: origin,
                            destination: destination,
                            travelMode: google.maps.TravelMode.DRIVING
                        };

                        directionsService.route(request, function(result, status) {
                            if (status === google.maps.DirectionsStatus.OK) {
                                directionsRenderer.setDirections(result);
                                $('#mapModal').modal('show'); // Open the modal
                            } else {
                                alert('Could not retrieve route: ' + status);
                            }
                        });
                    }

                    window.onload = initMap;
                </script>

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
