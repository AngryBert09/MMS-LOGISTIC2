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
                <!-- AI-Generated Offer Analysis Section -->
                <div class="card-box bg-light p-3 mb-4">
                    <h5 class="text-primary">Offers</h5>
                    <p class="text-muted">
                        A quick summary of vendor bids to help you evaluate the best offer.
                    </p>

                    @php
                        $bidAmounts = $biddingDetail->vendorBids->pluck('bid_amount')->filter();
                        $highestBid = $bidAmounts->max();
                        $lowestBid = $bidAmounts->min();
                        $averageBid = $bidAmounts->avg();
                    @endphp

                    <div class="row">
                        <div class="col-md-4">
                            <div class="p-3 border rounded bg-white">
                                <h6 class="text-success">Highest Bid</h6>
                                <p class="h5">{{ $highestBid ? number_format($highestBid, 2) : 'N/A' }} USD</p>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="p-3 border rounded bg-white">
                                <h6 class="text-danger">Lowest Bid</h6>
                                <p class="h5">{{ $lowestBid ? number_format($lowestBid, 2) : 'N/A' }} USD</p>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="p-3 border rounded bg-white">
                                <h6 class="text-warning">Average Bid</h6>
                                <p class="h5">{{ $averageBid ? number_format($averageBid, 2) : 'N/A' }} USD</p>
                            </div>
                        </div>
                    </div>


                </div>
                <!-- End Offer Analysis Section -->

                <!-- Simple Datatable Start -->
                <div class="card-box mb-30">
                    <div class="pd-20">
                        <h4 class="text-warning h4">Current Offers</h4>
                        <p class="text-muted">
                            This section provides an overview of all the offers submitted by vendors, including details
                            such as vendor name, bid amount, comments, and submission date. Use this table to review and
                            compare vendor
                            offers before making a decision.
                        </p>
                    </div>


                    <div class="pb-20">
                        <table class="table table-striped table-hover nowrap dt-responsive" style="width:100%">
                            <thead>
                                <tr>
                                    <th class="table-plus">ID</th>
                                    <th>Vendor</th>
                                    <th>Bid Amount</th>
                                    <th>Comments</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($biddingDetail->vendorBids as $vendorBid)
                                    @php
                                        $isWinner = $biddingDetail->vendor_id === $vendorBid->vendor_id;
                                    @endphp
                                    <tr class="{{ $isWinner ? 'table-success' : '' }}">
                                        <td>{{ $vendorBid->id }}</td>
                                        <td>
                                            {{ $vendorBid->vendor->company_name ?? 'N/A' }}
                                            @if ($isWinner)
                                                <span class="badge badge-success ml-2">
                                                    <i class="icon-copy fa fa-trophy" aria-hidden="true"></i> Winner
                                                </span>
                                            @endif
                                        </td>
                                        <td>{{ number_format($vendorBid->bid_amount, 2) }}</td>

                                        <!-- Comment Modal Button -->
                                        <td>
                                            <button type="button" class="btn btn-info btn-sm" data-toggle="modal"
                                                data-target="#commentModal-{{ $vendorBid->id }}">
                                                View
                                            </button>

                                            <!-- Modal -->
                                            <div class="modal fade" id="commentModal-{{ $vendorBid->id }}"
                                                tabindex="-1" role="dialog"
                                                aria-labelledby="commentModalLabel-{{ $vendorBid->id }}"
                                                aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered modal-lg"
                                                    role="document">
                                                    <div class="modal-content shadow-lg border-0">
                                                        <div class="modal-header bg-warning text-white">
                                                            <h5 class="modal-title"
                                                                id="commentModalLabel-{{ $vendorBid->id }}">
                                                                <i class="fas fa-comment-dots"></i> Vendor Comment
                                                            </h5>
                                                            <button type="button" class="close text-white"
                                                                data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body p-4">
                                                            <div class="p-3 border rounded bg-light">
                                                                <p class="text-dark font-weight-bold">
                                                                    {{ $vendorBid->comments ?? 'No comments available' }}
                                                                </p>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer bg-light">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-dismiss="modal">
                                                                <i class="icon-copy fa fa-window-close"
                                                                    aria-hidden="true"></i> Close
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>

                                        <!-- Action Buttons -->
                                        <td>
                                            @if (!$isWinner && !$biddingDetail->vendor_id)
                                                <!-- Show the Accept button only if no vendor is assigned -->
                                                <form
                                                    action="{{ route('employee.bidding.updateBidStatus', $vendorBid->id) }}"
                                                    method="POST" class="d-inline">
                                                    @csrf
                                                    <input type="hidden" name="action" value="accept">
                                                    <button type="submit"
                                                        class="btn btn-success btn-sm">Accept</button>
                                                </form>
                                            @elseif ($biddingDetail->vendor_id == $vendorBid->vendor_id)
                                                <!-- Show Accepted status for the winning vendor -->
                                                <span class="text-success font-weight-bold">Accepted</span>
                                            @endif
                                        </td>


                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted">No offers available for this
                                            bidding.</td>
                                    </tr>
                                @endforelse
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
