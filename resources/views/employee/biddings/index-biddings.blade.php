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
    @include('employee.layout.navbar')
    @include('employee.layout.right-sidebar')

    @include('employee.layout.left-sidebar')
    <div class="mobile-menu-overlay"></div>

    <div class="main-container">
        <div class="pd-ltr-20 xs-pd-20-10">
            <div class="min-height-200px">
                @include('layout.breadcrumb')

                <!-- Simple Datatable Start -->
                <div class="card-box mb-30">
                    <div class="pd-20">
                        <h4 class="text-warning h4">Current Biddings</h4>
                        <p class="text-muted">
                            This section provides an overview of all the biddings, including details such as item name,
                            starting price, quantity, status, deadline, description, comments, and bid amount. You can
                            use this table to monitor and manage the biddings effectively.
                        </p>
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
                                    <th>Bid Amount</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($biddings as $bidding)
                                    <tr>
                                        <td class="table-plus">{{ $bidding->id }}</td>
                                        <td>{{ $bidding->item_name }}</td>
                                        <td>{{ $bidding->starting_price }}</td>
                                        <td>{{ $bidding->quantity }}</td>
                                        <td>
                                            @if ($bidding->vendor_id)
                                                <span class="badge badge-success">Assigned</span>
                                            @else
                                                <span class="badge badge-warning">Unassigned</span>
                                            @endif
                                        </td>
                                        <td>{{ $bidding->deadline }}</td>
                                        <td>{{ $bidding->description }}</td>
                                        <td>{{ $bidding->bid_amount }}</td>
                                        <td>
                                            <a href="{{ route('employee.bidding.showVendors', $bidding->id) }}"
                                                class="btn btn-success btn-sm">
                                                Offers
                                            </a>
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


    <!-- welcome modal end -->
    <!-- js -->
    <script src="{{ asset('js/core.js') }}"></script>
    <script src="{{ asset('js/script.min.js') }}"></script>
    <script src="{{ asset('js/process.js') }}"></script>
    <script src="{{ asset('js/layout-settings.js') }}"></script>
    <script src="{{ asset('src/plugins/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ asset('src/plugins/datatables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('src/plugins/datatables/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('src/plugins/datatables/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('src/plugins/datatables/js/responsive.bootstrap4.min.js') }}"></script>



    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-NXZMQSS" height="0" width="0"
            style="display: none; visibility: hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
</body>

</html>
