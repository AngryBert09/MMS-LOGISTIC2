<!DOCTYPE html>
<html>

<head>
    <!-- Basic Page Info -->
    <meta charset="utf-8" />
    <title>Vendor</title>

    <!-- Site favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/gwa-touch-icon') }}" />
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/gwa-favicon-32x32.png') }}" />
    <link rel="icon" type="image/png" sizes="16x16" href="images/favicon-16x16.png" />

    <!-- Mobile Specific Metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet" />
    <!-- CSS -->
    <link rel="stylesheet" type="text/css" href="{{ asset('css/core.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('css/icon-font.min.css') }}" />
    <link rel="stylesheet" type="text/css"
        href="{{ asset('src/plugins/datatables/css/dataTables.bootstrap4.min.css') }}" />
    <link rel="stylesheet" type="text/css"
        href="{{ asset('src/plugins/datatables/css/responsive.bootstrap4.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('css/style.css') }}" />


    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-GBZ3SGGX85"></script>
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-2973766580778258"
        crossorigin="anonymous"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag("js", new Date());

        gtag("config", "G-GBZ3SGGX85");
    </script>
    <!-- Google Tag Manager -->
    <script>
        (function(w, d, s, l, i) {
            w[l] = w[l] || [];
            w[l].push({
                "gtm.start": new Date().getTime(),
                event: "gtm.js"
            });
            var f = d.getElementsByTagName(s)[0],
                j = d.createElement(s),
                dl = l != "dataLayer" ? "&l=" + l : "";
            j.async = true;
            j.src = "https://www.googletagmanager.com/gtm.js?id=" + i + dl;
            f.parentNode.insertBefore(j, f);
        })(window, document, "script", "dataLayer", "GTM-NXZMQSS");
    </script>
    <!-- End Google Tag Manager -->
</head>

<body>


    @include('layout.nav')

    @include('layout.right-sidebar')

    @include('layout.left-sidebar')

    <div class="mobile-menu-overlay"></div>

    <div class="main-container">
        <div class="pd-ltr-20">
            <div class="card-box pd-20 height-100-p mb-30">
                <div class="row align-items-center">
                    <div class="col-md-4">
                        <img src="images/banner-img.png" alt="" />
                    </div>
                    <div class="col-md-8">
                        <h4 class="font-20 weight-500 mb-10 text-capitalize">
                            Welcome back
                            <div class="weight-600 font-30 text-warning">{{ Auth::user()->company_name }}</div>
                        </h4>
                        <p class="font-18 max-width-600">
                            TEST
                        </p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-3 mb-30">
                    <div class="card-box height-100-p widget-style1">
                        <div class="d-flex flex-wrap align-items-center">
                            <div class="progress-data">
                                <div id="chart"></div>
                            </div>
                            <div class="widget-data">
                                <div id="performanceRating" class="h4 mb-0 text-uppercase"></div>
                                <div class="weight-600 font-14" id="metricType">On-Time Delivery</div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="col-xl-3 mb-30">
                    <div class="card-box height-100-p widget-style1">
                        <div class="d-flex flex-wrap align-items-center">
                            <div class="progress-data">
                                <div id="chart2"></div>
                            </div>
                            <div class="widget-data">
                                <div id="churnRate" class="h4 mb-0 text-uppercase"></div>
                                <div class="weight-600 font-14">Churn Rate</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 mb-30">
                    <div class="card-box height-100-p widget-style1">
                        <div class="d-flex flex-wrap align-items-center">
                            <div class="progress-data">
                                <div id="chart3"></div>
                            </div>
                            <div class="widget-data">
                                <div id="qualityRate" class="h4 mb-0 text-uppercase"></div>
                                <div class="weight-600 font-14">Quality</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 mb-30">
                    <div class="card-box height-100-p widget-style1">
                        <div class="d-flex flex-wrap align-items-center">
                            <div class="progress-data">
                                <div id="chart4"></div>
                            </div>
                            <div class="widget-data">
                                <div id="responseTime" class="h4 mb-0 text-uppercase"></div>
                                <div class="weight-600 font-14">Communications</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-4 mb-30">
                    <div class="card-box height-100-p pd-20">
                        <h2 class="h4 mb-20">Overall Ratings</h2>
                        <div id="chart5"></div>
                    </div>
                </div>
                <div class="col-xl-8 mb-30">
                    <div class="card-box height-100-p pd-20">
                        <h2 class="h4 mb-20">Top Suppliers </h2>
                        <div id="chart6"></div>
                    </div>
                </div>

            </div>
            <div class="card-box mb-30">
                <h5 class="h5 pd-20">
                    AI Supplier Performance Analysis
                    <span style="color:blue; cursor: pointer;" data-toggle="tooltip" data-placement="right" title="This feature is under development and improvement. Expect changes and enhancements.">
                        [BETA]
                    </span>
                </h5>
                <div id="aiResponse" class="pd-20">
                    <p>Loading insights...</p>
                </div>
            </div>





            <script>
                document.addEventListener("DOMContentLoaded", function() {
                    fetch("{{ route('analyze.suppliers') }}", {
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": "{{ csrf_token() }}"
                            },
                            credentials: "include"
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.error) {
                                document.getElementById("aiResponse").innerHTML =
                                    `<p class="text-danger">${data.error}</p>`;
                            } else {
                                document.getElementById("aiResponse").innerHTML = `<p>${data.ai_insights}</p>`;
                            }
                        })
                        .catch(error => {
                            console.error("Error fetching AI response:", error);
                            document.getElementById("aiResponse").innerHTML =
                                `<p class="text-danger">Failed to load insights.</p>`;
                        });
                });
            </script>


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
    <script src="{{ asset('js/dashboard.js') }}"></script>

    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-NXZMQSS" height="0" width="0"
            style="display: none; visibility: hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
</body>

</html>
