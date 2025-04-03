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
        <div class="xs-pd-20-10 pd-ltr-20">
            <div class="title pb-20">
                <h2 class="h3 mb-0">Employee Dashboard</h2>
            </div>

            <div class="row">
                <!-- Ongoing Bids Card -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Ongoing Bids</h5>
                            <p class="card-text">View and manage all ongoing bids.</p>
                            <a href="{{ route('employee.biddings') }}" class="btn btn-info">View Bids</a>
                        </div>
                    </div>
                </div>

                <!-- Requests Under Review Card -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Procurement Request</h5>
                            <p class="card-text">Check the status of requests currently under review.</p>
                            <a href="{{ route('employee.procurement.request') }}" class="btn btn-warning">View
                                Requests</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- welcome modal end -->
    <!-- js -->
    @include('admin.layout.footerjs')


    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-NXZMQSS" height="0" width="0"
            style="display: none; visibility: hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
</body>

</html>
