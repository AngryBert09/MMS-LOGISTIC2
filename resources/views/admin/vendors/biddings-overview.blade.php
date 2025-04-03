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
                                    <th>Comments</th>
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
                                        <td>{{ $bidding->comments }}</td>
                                        <td>{{ $bidding->bid_amount }}</td>
                                        <td>
                                            <button type="button" class="btn btn-primary btn-sm viewBiddingBtn"
                                                data-id="{{ $bidding->id }}" data-toggle="modal"
                                                data-target="#viewBiddingModal">
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
                    <table class="table table-bordered">
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
    <script src="{{ asset('src/plugins/datatables/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('src/plugins/datatables/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('src/plugins/datatables/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('src/plugins/datatables/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('src/plugins/datatables/js/buttons.flash.min.js') }}"></script>
    <script src="{{ asset('src/plugins/datatables/js/pdfmake.min.js') }}"></script>
    <script src="{{ asset('src/plugins/datatables/js/vfs_fonts.js') }}"></script>
    {{-- <script>
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
    </script> --}}
    <script>
        $(document).ready(function() {
            // Ensure DataTables initializes only when needed
            let dataTableInitialized = false;

            $('.viewBiddingBtn').click(function() {
                let biddingId = $(this).data('id');

                console.log("🔍 Button Clicked. Bidding ID:", biddingId);

                if (!biddingId) {
                    alert("⚠️ Bidding ID is missing!");
                    return;
                }

                // Fix: Laravel route in JavaScript
                let url = "{{ url('/admin/bidding') }}/" + biddingId + "/vendor-bids";

                console.log("🔗 API URL:", url);

                $.ajax({
                    url: url,
                    type: 'GET',
                    dataType: 'json',
                    beforeSend: function() {
                        console.log(`📡 Fetching data from: ${url}`);
                        $('#vendorBidsTable').html(
                            '<tr><td colspan="4" class="text-center text-primary">⏳ Loading...</td></tr>'
                        );
                    },
                    success: function(response) {
                        console.log("✅ Received Response:", response);

                        if (!response || typeof response !== 'object') {
                            console.error("❌ Invalid response format:", response);
                            alert('Unexpected server response. Please try again.');
                            return;
                        }

                        $('#vendorBidsTable').empty();

                        if (response.vendorBids && response.vendorBids.length > 0) {
                            console.log(
                                `📊 Populating ${response.vendorBids.length} vendor bids...`
                            );
                            $.each(response.vendorBids, function(index, bid) {
                                console.log(`🔹 Bid #${index + 1}:`, bid);
                                let row = `
                            <tr>
                                <td>${bid.company_name || 'Unknown'}</td>
                                <td>${bid.bid_amount || 'N/A'}</td>
                                <td>${bid.comments || 'N/A'}</td>
                                <td>${bid.shortname || 'N/A'}</td>
                            </tr>`;
                                $('#vendorBidsTable').append(row);
                            });

                            // Initialize DataTable only once
                            if (!dataTableInitialized) {
                                $('.table').DataTable();
                                dataTableInitialized = true;
                            }
                        } else {
                            console.warn("⚠️ No vendor bids available.");
                            $('#vendorBidsTable').html(
                                '<tr><td colspan="4" class="text-center text-danger">No bids available</td></tr>'
                            );
                        }

                        console.log("📌 Opening modal...");
                        $('#viewBiddingModal').modal('show');
                    },
                    error: function(xhr, status, error) {
                        console.error("🚨 AJAX Error:", error);
                        console.log("🛑 XHR Status:", status);
                        console.log("📄 Full Response:", xhr.responseText);
                        alert('❌ Failed to load vendor bids. Check console for details.');
                    }
                });
            });
        });
    </script>
</body>

</html>
