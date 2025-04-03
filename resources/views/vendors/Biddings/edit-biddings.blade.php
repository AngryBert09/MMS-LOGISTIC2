<!DOCTYPE html>
<html>
@include('layout.head')

<body>
    <!-- <div class="pre-loader">
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
  </div> -->

    @include('layout.nav')
    @include('layout.right-sidebar')
    @include('layout.left-sidebar')



    <div class="mobile-menu-overlay"></div>

    <div class="main-container">
        <div class="pd-ltr-20 xs-pd-20-10">
            <div class="min-height-200px">
                @include('layout.breadcrumb')

                <!-- Bidding Items Section -->
                <div class="p-4 bg-white border-radius-10 shadow-sm mb-4">
                    <h4 class="font-weight-bold text-primary mb-3">Bidding Details</h4>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <strong>Item Name:</strong> <span class="text-dark">{{ $bidding->item_name }}</span>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>Starting Price:</strong>
                            <span
                                class="text-success font-weight-bold">${{ number_format($bidding->starting_price, 2) }}</span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <strong>Quantity:</strong> <span class="text-dark">{{ $bidding->quantity }}</span>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>Deadline:</strong>
                            <span
                                class="badge badge-danger p-2">{{ \Carbon\Carbon::parse($bidding->deadline)->format('Y-m-d') }}</span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <strong>Description:</strong>
                            <p class="text-muted">{{ $bidding->description }}</p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <strong>Additional Information:</strong>
                            <p class="text-muted">{{ $bidding->additional_information }}</p>
                        </div>
                    </div>

                    @php
                        $vendorsSubmitted = \App\Models\VendorBid::where('bidding_id', $bidding->id)->count();
                    @endphp

                    <div class="alert alert-info border-radius-10 mt-3">
                        <strong>{{ $vendorsSubmitted }}</strong> vendors have already submitted their bids for this
                        item.
                    </div>

                    @php
                        $vendorBid = \App\Models\VendorBid::where('vendor_id', auth()->user()->id)
                            ->where('bidding_id', $bidding->id)
                            ->first();

                        $winningBid = \App\Models\VendorBid::where('bidding_id', $bidding->id)
                            ->where('vendor_id', $bidding->vendor_id)
                            ->first();

                        $isWinner = $winningBid && $winningBid->vendor_id == auth()->user()->id;
                    @endphp

                    @if ($isWinner)
                        <div class="alert alert-success p-4 border-radius-10 mt-4">
                            <h5 class="font-weight-bold text-success">🎉 Congratulations! You Won the Bid</h5>
                            <p>Your bid has been selected as the winning bid.</p>
                            <p><strong>Winning Bid Amount:</strong>
                                <span
                                    class="text-success font-weight-bold">${{ number_format($winningBid->bid_amount, 2) }}</span>
                            </p>
                            <p><strong>Your Comments:</strong> {{ $winningBid->comments }}</p>
                            <a href="{{ route('biddings.index') }}" class="btn btn-secondary">Back to Bidding List</a>
                        </div>
                    @elseif (!$vendorBid)
                        <div class="alert alert-warning p-4 border-radius-10 mt-4">
                            <h5 class="font-weight-bold text-warning">Place Your Bid</h5>

                            <!-- ✅ Display validation errors -->
                            @if ($errors->any())
                                <div class="alert alert-danger border-radius-10 alert-dismissible fade show mt-2"
                                    role="alert">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif


                            <form action="{{ route('biddings.update', $bidding->id) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="form-group">
                                    <label for="bid_amount" class="font-weight-bold">Bid Amount</label>
                                    <input type="number" id="bid_amount" name="bid_amount" class="form-control"
                                        placeholder="Enter your bid amount" required>
                                </div>

                                <div class="form-group">
                                    <label for="comments" class="font-weight-bold">Comments</label>
                                    <textarea id="comments" name="comments" class="form-control"
                                        placeholder="Provide any additional comments or clarification" rows="4"></textarea>
                                </div>

                                <button type="submit" class="btn btn-warning">Submit Bid</button>
                                <a href="{{ route('biddings.index') }}" class="btn btn-secondary ml-2">Back to Bidding
                                    List</a>
                            </form>
                        </div>
                    @else
                        <div class="alert alert-success p-4 border-radius-10 mt-4">
                            <h5 class="font-weight-bold text-success">You Have Already Submitted Your Bid</h5>
                            <p>Thank you for your submission! You can no longer make changes to your bid. Please wait
                                for the results.</p>
                            <p><strong>Your Bid Amount:</strong>
                                <span
                                    class="text-success font-weight-bold">${{ number_format($vendorBid->bid_amount, 2) }}</span>
                            </p>
                            <p><strong>Your Comments:</strong> {{ $vendorBid->comments }}</p>
                        </div>
                        <a href="{{ route('biddings.index') }}" class="btn btn-secondary">Back to Bidding List</a>
                    @endif
                </div>











            </div>

        </div>
    </div>


    <!-- Core JavaScript -->
    <script src="{{ asset('js/core.js') }}"></script>
    <script src="{{ asset('js/script.min.js') }}"></script>
    <script src="{{ asset('js/process.js') }}"></script>
    <script src="{{ asset('js/layout-settings.js') }}"></script>

    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-NXZMQSS" height="0" width="0"
            style="display: none; visibility: hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
</body>

</html>
