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
                    <div class="pd-20">
                        <h4 class="text-warning h4">Biddings Overview</h4>
                        <p class="text-muted">
                            This section provides an overview of all the biddings, including details such as item name,
                            starting price, quantity, status, deadline, description, comments, and bid amount. You can
                            use this table to monitor and manage the biddings effectively.
                        </p>
                    </div>
                    <div class="pb-20">
                        <table class="table table-striped table-hover nowrap" id="mainBiddingsTable" style="width:100%">
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
                                        <td>
                                            @if ($bidding->vendorBids->isNotEmpty())
                                                <!-- Display the winning bid amount, if available -->
                                                ₱{{ number_format($bidding->vendorBids->max('bid_amount'), 2) }}
                                            @else
                                                <!-- If no bids, display N/A or leave it empty -->
                                                N/A
                                            @endif
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-primary btn-sm viewBiddingBtn"
                                                data-id="{{ $bidding->id }}" data-vendor-id="{{ $bidding->vendor_id }}"
                                                data-toggle="modal" data-target="#viewBiddingModal">
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

    <!-- View Bidding Modal -->
    <div class="modal fade" id="viewBiddingModal" tabindex="-1" role="dialog" aria-labelledby="viewBiddingModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewBiddingModalLabel">Vendor Bids</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <!-- 🏆 Winning Vendor Section - Only shown when there's an actual winner -->
                    <div class="alert alert-success" id="winningVendor" style="display: none;">
                        <strong>🏆 Winning Vendor:</strong> <span id="winningVendorName"></span> -
                        <strong>Bid:</strong> ₱<span id="winningBidAmount"></span>
                    </div>

                    <!-- Vendor Bids Table -->
                    <table id="vendorBidsDataTable" class="table table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th>Vendor Name</th>
                                <th>Bid Amount</th>
                                <th>Comments</th>
                                <th>Shortname</th>
                            </tr>
                        </thead>
                        <tbody id="vendorBidsTable">
                            <!-- Vendor Bids will be populated here -->
                        </tbody>
                    </table>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

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

    <script>
        $(document).ready(function() {
            var mainTable = $('#mainBiddingsTable').DataTable({
                responsive: true,
                paging: true,
                searching: true,
                ordering: true,
                info: true,
                autoWidth: false
            });

            $(document).on('click', '.viewBiddingBtn', function() {
                let biddingId = $(this).data('id');
                let winningVendorId = $(this).data('vendor-id'); // Will be compared with bid.vendor_id

                if (!biddingId) {
                    alert("⚠️ Bidding ID is missing!");
                    return;
                }

                let url = "{{ url('/admin/bidding') }}/" + biddingId + "/vendor-bids";

                $.ajax({
                    url: url,
                    type: 'GET',
                    dataType: 'json',
                    beforeSend: function() {
                        $('#vendorBidsTable').html(
                            '<tr><td colspan="4" class="text-center text-primary">⏳ Loading...</td></tr>'
                        );
                        $('#winningVendor').hide();
                    },
                    success: function(response) {
                        console.log('AJAX Response:', response);

                        $('#vendorBidsTable').empty();

                        if (response.vendorBids && response.vendorBids.length > 0) {
                            let hasWinner = winningVendorId && winningVendorId !== null &&
                                winningVendorId !== '';

                            if (hasWinner) {
                                // Convert to number for safe comparison
                                let winningBid = response.vendorBids.find(bid => Number(bid
                                    .vendor_id) === Number(winningVendorId));

                                if (winningBid) {
                                    $('#winningVendorName').text(winningBid.company_name ||
                                        'N/A');
                                    $('#winningBidAmount').text(parseFloat(winningBid
                                        .bid_amount).toLocaleString('en-US', {
                                        style: 'currency',
                                        currency: 'USD'
                                    }));
                                    $('#winningVendor').show();
                                } else {
                                    $('#winningVendor').hide();
                                }
                            } else {
                                $('#winningVendor').hide();
                            }

                            // Populate all bids
                            response.vendorBids.forEach(function(bid) {
                                let row = `
                            <tr>
                                <td>${bid.company_name || 'Unknown'}</td>
                                <td>${parseFloat(bid.bid_amount).toLocaleString('en-US', {
                                    style: 'currency',
                                    currency: 'USD'
                                })}</td>
                                <td>${bid.comments || 'N/A'}</td>
                                <td>${bid.shortname || 'N/A'}</td>
                            </tr>`;
                                $('#vendorBidsTable').append(row);
                            });

                            // Reinit DataTable
                            if ($.fn.DataTable.isDataTable('#vendorBidsDataTable')) {
                                $('#vendorBidsDataTable').DataTable().destroy();
                            }

                            $('#vendorBidsDataTable').DataTable({
                                searching: false,
                                paging: false,
                                info: false,
                                ordering: true,
                                responsive: true
                            });
                        } else {
                            $('#vendorBidsTable').html(
                                '<tr><td colspan="4" class="text-center text-danger">No bids available</td></tr>'
                            );
                            $('#winningVendor').hide();
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("🚨 AJAX Error:", error, xhr.responseText);
                        $('#vendorBidsTable').html(
                            '<tr><td colspan="4" class="text-center text-danger">Error loading bids</td></tr>'
                        );
                        $('#winningVendor').hide();
                    }
                });
            });

            $('#viewBiddingModal').on('hidden.bs.modal', function() {
                if ($.fn.DataTable.isDataTable('#vendorBidsDataTable')) {
                    $('#vendorBidsDataTable').DataTable().destroy();
                }
                $('#vendorBidsTable').empty();
                $('#winningVendor').hide();
            });
        });
    </script>
</body>

</html>
