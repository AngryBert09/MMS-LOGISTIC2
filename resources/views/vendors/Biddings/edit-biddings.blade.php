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
                <div class="pd-20 bg-light border-radius-10 box-shadow mb-30">
                    <h4 class="font-weight-bold mb-20">Bidding Details</h4>
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <strong>Item Name:</strong> {{ $bidding->item_name }}
                        </div>
                        <div class="col-md-6">
                            <strong>Starting Price:</strong> ${{ number_format($bidding->starting_price, 2) }}
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <strong>Quantity:</strong> {{ $bidding->quantity }}
                        </div>
                        <div class="col-md-6">
                            <strong>Deadline:</strong> {{ \Carbon\Carbon::parse($bidding->deadline)->format('Y-m-d') }}
                        </div>

                        <div class="col-md-6">
                            <strong>Description:</strong> {{ $bidding->description }}
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-12">
                            <strong>Additional Information:</strong>
                            <p>{{ $bidding->additional_information }}</p>
                        </div>
                    </div>

                    <!-- Display the number of vendors who have submitted bids -->
                    @php
                        $vendorsSubmitted = \App\Models\VendorBid::where('bidding_id', $bidding->id)->count();
                    @endphp

                    <div class="mt-4">
                        <p><strong>{{ $vendorsSubmitted }}</strong> vendors have already submitted their bids for this
                            item.</p>
                    </div>

                    <!-- Check if vendor has already submitted a bid -->
                    <!-- Check if vendor has already submitted a bid -->
                    @php
                        $vendorBid = \App\Models\VendorBid::where('vendor_id', auth()->user()->id)
                            ->where('bidding_id', $bidding->id)
                            ->first();

                        // Retrieve the winning bid based on BiddingDetails vendor_id
                        $winningBid = \App\Models\VendorBid::where('bidding_id', $bidding->id)
                            ->where('vendor_id', $bidding->vendor_id) // Ensure the winning vendor is assigned in BiddingDetails
                            ->first();

                        // Check if the authenticated vendor is the winner
                        $isWinner = $winningBid && $winningBid->vendor_id == auth()->user()->id;
                    @endphp


                    @if ($isWinner)
                        <!-- If the vendor is the winner -->
                        <div class="mt-4">
                            <div class="alert alert-success p-4 border-radius-10">
                                <h5 class="font-weight-bold mb-4">🎉 Congratulations! You Won the Bid</h5>
                                <p>Congratulations! Your bid has been selected as the winning bid.</p>
                                <p><strong>Winning Bid Amount:</strong> ${{ number_format($winningBid->bid_amount, 2) }}
                                </p>
                                <p><strong>Your Comments:</strong> {{ $winningBid->comments }}</p>
                                <a href="{{ route('biddings.index') }}" class="btn btn-secondary">Back to Bidding
                                    List</a>
                            </div>
                        </div>
                    @elseif (!$vendorBid)
                        <!-- Show the form only if the vendor has not submitted a bid -->
                        <div class="mt-4">
                            <div class="alert alert-info p-4 border-radius-10">
                                <h5 class="font-weight-bold mb-4">Place Your Bid</h5>
                                <form action="{{ route('biddings.update', $bidding->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="form-group">
                                        <label for="bid_amount">Bid Amount</label>
                                        <input type="number" id="bid_amount" name="bid_amount" class="form-control"
                                            placeholder="Enter your bid amount" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="comments">Comments</label>
                                        <textarea id="comments" name="comments" class="form-control"
                                            placeholder="Provide any additional comments or clarification" rows="4"></textarea>
                                    </div>

                                    <button type="submit" class="btn btn-warning">Submit Bid</button>
                                    <a href="{{ route('biddings.index') }}" class="btn btn-secondary ml-2">Back to
                                        Bidding List</a>
                                </form>
                            </div>
                        </div>
                    @else
                        <!-- If vendor has already submitted a bid, show a message -->
                        <div class="mt-4">
                            <div class="alert alert-success p-4 border-radius-10">
                                <h5 class="font-weight-bold mb-4">You Have Already Submitted Your Bid</h5>
                                <p>Thank you for your submission! You can no longer make changes to your bid. Please
                                    wait for the results of the bidding process.</p>
                                <p><strong>Your Bid Amount:</strong> ${{ number_format($vendorBid->bid_amount, 2) }}
                                </p>
                                <p><strong>Your Comments:</strong> {{ $vendorBid->comments }}</p>
                            </div>
                            <a href="{{ route('biddings.index') }}" class="btn btn-secondary">Back to Bidding List</a>
                        </div>
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
