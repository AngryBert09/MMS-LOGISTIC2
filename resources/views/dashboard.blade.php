<!DOCTYPE html>
<html>

@include('layout.head')

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
                    My Performance Analysis <span style="font-size: 10px;">powered by AI</span>
                    <span style="color:blue; cursor: pointer;" data-toggle="tooltip" data-placement="right"
                        title="This feature is under development and improvement. Expect changes and enhancements.">
                        [BETA]
                    </span>
                </h5>
                <div id="aiResponse" class="pd-20">
                    <p>Loading insights...</p>
                </div>
                <div class="pd-20 text-right">
                    <button id="downloadPDF" class="btn btn-primary" style="display: none;">Download as PDF</button>
                </div>
            </div>

            <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

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
                            let aiResponseDiv = document.getElementById("aiResponse");
                            let downloadButton = document.getElementById("downloadPDF");

                            if (data.error) {
                                aiResponseDiv.innerHTML = `<p class="text-danger">${data.error}</p>`;
                            } else {
                                aiResponseDiv.innerHTML = `<p>${data.ai_insights}</p>`;
                                downloadButton.style.display = "inline-block"; // Show the download button
                            }
                        })
                        .catch(error => {
                            console.error("Error fetching AI response:", error);
                            document.getElementById("aiResponse").innerHTML =
                                `<p class="text-danger">No available data to analyze.</p>`;
                        });
                });

                document.getElementById("downloadPDF").addEventListener("click", function() {
                    const {
                        jsPDF
                    } = window.jspdf;
                    const doc = new jsPDF();

                    // Get the AI response text
                    let content = document.getElementById("aiResponse").innerText;

                    // Add title and content to PDF
                    doc.setFont("helvetica", "bold");
                    doc.setFontSize(16);
                    doc.text("AI Supplier Performance Analysis", 10, 20);

                    doc.setFont("helvetica", "normal");
                    doc.setFontSize(12);
                    doc.text(content, 10, 40, {
                        maxWidth: 180
                    });

                    // Save the PDF
                    doc.save("Supplier_Performance_Analysis.pdf");
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
