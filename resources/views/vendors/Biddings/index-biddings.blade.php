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
                    <h4 class="font-weight-bold mb-20">Available Biddings</h4>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Item</th>
                                    <th>Starting Price</th>
                                    <th>Deadline</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($biddings as $bidding)
                                    <tr>
                                        <td>{{ $bidding->item_name }}</td>
                                        <td>${{ number_format($bidding->starting_price, 2) }}</td>
                                        <td>{{ \Carbon\Carbon::parse($bidding->deadline)->format('Y-m-d') }}</td>
                                        <td>
                                            <a href="{{ route('biddings.edit', $bidding->id) }}"
                                                class="btn btn-info">View</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
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
