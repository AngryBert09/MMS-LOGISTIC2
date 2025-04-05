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
    @include('admin.layout.navbar')
    @include('admin.layout.right-sidebar')

    @include('admin.layout.left-sidebar')
    <div class="mobile-menu-overlay"></div>

    <div class="main-container">
        <div class="xs-pd-20-10 pd-ltr-20">
            <div class="title pb-20">
                <h2 class="h3 mb-0">Admin Overview</h2>
            </div>

            <div class="row pb-10">
                <div class="col-xl-3 col-lg-3 col-md-6 mb-20">
                    <div class="card-box height-100-p widget-style3">
                        <div class="d-flex flex-wrap">
                            <div class="widget-data">
                                <div class="weight-700 font-24 text-dark purchase-order-count">0</div>
                                <div class="font-14 text-secondary weight-500">
                                    Total Purchase Orders
                                </div>
                            </div>
                            <div class="widget-icon">
                                <div class="icon" data-color="#00eccf">
                                    <i class="icon-copy fa fa-shopping-basket" aria-hidden="true"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                <script>
                    $(document).ready(function() {
                        function fetchTotalOrders() {
                            $.ajax({
                                url: '/admin/get-total-purchase-orders', // Update with your route
                                type: 'GET',
                                dataType: 'json',
                                success: function(response) {
                                    $('.purchase-order-count').text(response.total); // Update the card number
                                },
                                error: function(error) {
                                    console.error("Error fetching total purchase orders:", error);
                                }
                            });
                        }

                        fetchTotalOrders(); // Call the function on page load
                    });
                </script>
                <div class="col-xl-3 col-lg-3 col-md-6 mb-20">
                    <div class="card-box height-100-p widget-style3">
                        <div class="d-flex flex-wrap">
                            <div class="widget-data">
                                <div class="weight-700 font-24 text-dark vendor-count">0</div>
                                <div class="font-14 text-secondary weight-500">
                                    Total Vendors
                                </div>
                            </div>
                            <div class="widget-icon">
                                <div class="icon" data-color="#ff5b5b">
                                    <i class="icon-copy fa fa-user-o" aria-hidden="true"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <script>
                    $(document).ready(function() {
                        function fetchVendorCount() {
                            $.ajax({
                                url: '/admin/get-vendor-count',
                                type: 'GET',
                                dataType: 'json',
                                success: function(response) {
                                    $(".vendor-count").text(response.total);
                                },
                                error: function(error) {
                                    console.error("Error fetching vendor count:", error);
                                }
                            });
                        }

                        fetchVendorCount();
                        setInterval(fetchVendorCount, 10000); // Auto-refresh every 10 seconds
                    });
                </script>
                <div class="col-xl-3 col-lg-3 col-md-6 mb-20">
                    <div class="card-box height-100-p widget-style3">
                        <div class="d-flex flex-wrap">
                            <div class="widget-data">
                                <div class="weight-700 font-24 text-dark bidding-count">0</div>
                                <div class="font-14 text-secondary weight-500">
                                    Total Biddings
                                </div>
                            </div>
                            <div class="widget-icon">
                                <div class="icon">
                                    <i class="icon-copy fa fa-gavel" aria-hidden="true"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <script>
                    $(document).ready(function() {
                        function fetchTotalBiddings() {
                            $.ajax({
                                url: '/admin/get-total-biddings',
                                type: 'GET',
                                dataType: 'json',
                                success: function(response) {
                                    $(".bidding-count").text(response.total);
                                },
                                error: function(error) {
                                    console.error("Error fetching bidding count:", error);
                                }
                            });
                        }

                        fetchTotalBiddings();
                        setInterval(fetchTotalBiddings, 10000); // Auto-refresh every 10 seconds
                    });
                </script>
                <div class="col-xl-3 col-lg-3 col-md-6 mb-20">
                    <div class="card-box height-100-p widget-style3">
                        <div class="d-flex flex-wrap">
                            <div class="widget-data">
                                <div class="weight-700 font-24 text-dark earnings-amount">₱</div>
                                <div class="font-14 text-secondary weight-500">Invoice Amount</div>
                            </div>
                            <div class="widget-icon">
                                <div class="icon" data-color="#09cc06">
                                    <i class="icon-copy fa fa-money" aria-hidden="true"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <script>
                    $(document).ready(function() {
                        function fetchTotalEarnings() {
                            $.ajax({
                                url: '/admin/get-total-invoice-amount',
                                type: 'GET',
                                dataType: 'json',
                                success: function(response) {
                                    let formattedEarnings = new Intl.NumberFormat('en-US', {
                                        style: 'currency',
                                        currency: 'PHP'
                                    }).format(response.total);

                                    $(".earnings-amount").text(formattedEarnings);
                                },
                                error: function(error) {
                                    console.error("Error fetching earnings:", error);
                                }
                            });
                        }

                        fetchTotalEarnings();
                        setInterval(fetchTotalEarnings, 10000); // Auto-refresh every 10 seconds
                    });
                </script>
            </div>

            <div class="row pb-10">
                <div class="col-md-8 mb-20">
                    <div class="card-box pb-10">
                        <div class="h5 pd-20 mb-0">Recent Approved Vendors
                            <table class="data-table table nowrap">
                                <thead>
                                    <tr>
                                        <th class="table-plus">Company Name</th>
                                        <th>Email</th>
                                        <th>Address</th>
                                        <th>Status</th>
                                        <th>Approved At</th>
                                    </tr>
                                </thead>
                                <tbody id="vendorTableBody">
                                    <!-- Data will be dynamically inserted here -->
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <script>
                        document.addEventListener("DOMContentLoaded", function() {
                            fetch('/admin/recent-approved-vendors')
                                .then(response => response.json())
                                .then(data => {
                                    const tbody = document.getElementById("vendorTableBody");
                                    tbody.innerHTML = ""; // Clear existing rows

                                    data.vendors.forEach(vendor => {
                                        let profilePic = vendor.profile_pic ?
                                            `${vendor.profile_pic}` // Adjust according to your storage setup
                                            :
                                            '/images/default.jpg';

                                        const row = `
                    <tr>
                        <td class="table-plus">
                            <div class="name-avatar d-flex align-items-center">
                                <div class="avatar mr-2 flex-shrink-0">
                                    <img src="${profilePic}" class="border-radius-100 shadow" width="40" height="40" alt="" />
                                </div>
                                <div class="txt">
                                    <div class="weight-600">${vendor.company_name}</div>
                                </div>
                            </div>
                        </td>
                        <td>${vendor.email}</td> <!-- Placeholder for gender -->
                        <td>${vendor.address}</td> <!-- Placeholder for weight -->
                        <td>${vendor.status}</td>
                        <td>${new Date(vendor.created_at).toLocaleDateString()}</td>
                    </tr>
                `;
                                        tbody.innerHTML += row;
                                    });
                                })
                                .catch(error => console.error("Error fetching vendors:", error));
                        });
                    </script>
                </div>
                <div class="col-md-4 mb-20">
                    <!-- Card UI -->
                    <div class="card-box min-height-200px pd-20 mb-20" data-bgcolor="#455a64">
                        <div class="d-flex justify-content-between pb-20 text-white">
                            <div class="icon h1 text-white">
                                <i class="fa fa-calendar" aria-hidden="true"></i>
                            </div>
                            <div class="font-14 text-right">
                                <div id="percentage-change"><i class="icon-copy ion-arrow-up-c"></i> 0%</div>
                                <div class="font-12">Since last month</div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-end">
                            <div class="text-white">
                                <div class="font-14">Ongoing Biddings</div>
                                <div class="font-24 weight-500" id="ongoing-bidding-count">Loading...</div>
                            </div>
                            <div class="max-width-150">
                                <div id="appointment-chart"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Include ApexCharts -->
                    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

                    <script>
                        // Fetch ongoing bidding count from API
                        fetch("/admin/get-ongoing-biddings")
                            .then(response => response.json())
                            .then(data => {
                                var ongoingCount = data.ongoing; // Get current ongoing count
                                var lastMonthCount = 50; // Placeholder value, should be fetched dynamically

                                // Update the ongoing bidding count in the UI
                                document.getElementById("ongoing-bidding-count").textContent = ongoingCount;

                                // Calculate percentage change (ensure no division by zero)
                                var percentageChange = lastMonthCount > 0 ? ((ongoingCount - lastMonthCount) / lastMonthCount * 100)
                                    .toFixed(2) : "0.00";
                                var percentageDiv = document.getElementById("percentage-change");
                                percentageDiv.innerHTML =
                                    `<i class="icon-copy ion-${percentageChange >= 0 ? 'arrow-up-c' : 'arrow-down-c'}"></i> ${Math.abs(percentageChange)}%`;

                                // Chart configuration
                                var options2 = {
                                    series: [{
                                        name: "Week",
                                        data: [{
                                                x: "Monday",
                                                y: 21
                                            },
                                            {
                                                x: "Tuesday",
                                                y: 22
                                            },
                                            {
                                                x: "Wednesday",
                                                y: 10
                                            },
                                            {
                                                x: "Thursday",
                                                y: 28
                                            },
                                            {
                                                x: "Friday",
                                                y: 16
                                            },
                                            {
                                                x: "Saturday",
                                                y: 21
                                            },
                                            {
                                                x: "Sunday",
                                                y: 13
                                            },
                                            {
                                                x: "Ongoing Bids",
                                                y: ongoingCount
                                            } // API data
                                        ],
                                    }],
                                    chart: {
                                        height: 100,
                                        type: "bar",
                                        toolbar: {
                                            show: false
                                        },
                                        sparkline: {
                                            enabled: true
                                        },
                                    },
                                    plotOptions: {
                                        bar: {
                                            columnWidth: "25px",
                                            distributed: true,
                                            endingShape: "rounded",
                                        },
                                    },
                                    dataLabels: {
                                        enabled: false
                                    },
                                    legend: {
                                        show: false
                                    },
                                    xaxis: {
                                        type: "category",
                                        lines: {
                                            show: false
                                        },
                                        axisBorder: {
                                            show: false
                                        },
                                        labels: {
                                            show: false
                                        },
                                    },
                                    yaxis: [{
                                        y: 0,
                                        offsetX: 0,
                                        offsetY: 0,
                                        labels: {
                                            show: false
                                        },
                                        padding: {
                                            left: 0,
                                            right: 0
                                        },
                                    }],
                                };

                                // Render the chart
                                var chart2 = new ApexCharts(document.querySelector("#appointment-chart"), options2);
                                chart2.render();
                            })
                            .catch(error => {
                                console.error("Error fetching data:", error);
                                document.getElementById("ongoing-bidding-count").textContent = "N/A";
                                document.getElementById("percentage-change").innerHTML = `<i class="icon-copy ion-arrow-up-c"></i> N/A`;
                            });
                    </script>


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
