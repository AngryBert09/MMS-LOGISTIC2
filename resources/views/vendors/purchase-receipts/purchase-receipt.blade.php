<!DOCTYPE html>
<html>

<head>
    <!-- Basic Page Info -->
    <meta charset="utf-8" />
    <title>Purchase Receipt</title>

    <!-- Site favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="images/gwa-touch-icon" />
    <link rel="icon" type="image/png" sizes="32x32" href="images/gwa-favicon-32x32.png" />
    <link rel="icon" type="image/png" sizes="16x16" href="vendors/images/favicon-16x16.png" />

    <!-- Mobile Specific Metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet" />
    <!-- CSS -->
    <link rel="stylesheet" type="text/css" href="css/core.css" />
    <link rel="stylesheet" type="text/css" href="css/icon-font.min.css" />
    <link rel="stylesheet" type="text/css" href="src/plugins/datatables/css/dataTables.bootstrap4.min.css" />
    <link rel="stylesheet" type="text/css" href="src/plugins/datatables/css/responsive.bootstrap4.min.css" />
    <link rel="stylesheet" type="text/css" href="css/style.css" />

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

    @include('layout.nav')
    @include('layout.right-sidebar')
    @include('layout.left-sidebar')


    <div class="mobile-menu-overlay"></div>

    <div class="main-container">
        <div class="pd-ltr-20 xs-pd-20-10">
            <div class="min-height-200px">
                <div class="page-header">
                    <div class="row">
                        <div class="col-md-6 col-sm-12">
                            <div class="title">
                                <h4>Purchase Receipt</h4>
                            </div>
                            <nav aria-label="breadcrumb" role="navigation">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item">
                                        <a href="{{ route('dashboard') }}">Home</a>
                                    </li>
                                    <li class="breadcrumb-item active" aria-current="page">
                                        Purchase Receipt
                                    </li>
                                </ol>
                            </nav>
                        </div>
                        <div class="col-md-6 col-sm-12 text-right">
                            <div class="dropdown">
                                <a class="btn btn-dark dropdown-toggle" href="#" role="button"
                                    data-toggle="dropdown">
                                    January 2018
                                </a>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <a class="dropdown-item" href="#">Export List</a>
                                    <a class="dropdown-item" href="#">Policies</a>
                                    <a class="dropdown-item" href="#">View Assets</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Simple Datatable start -->
                <div class="card-box mb-30">
                    <div class="pd-20">
                        <h4 class="text-dark h4">Purchase Receipt</h4>
                        <p class="mb-0">
                            All purchase receipts
                            are
                            for Great Wall Arts.

                        </p>
                    </div>
                    <div class="pb-20">
                        <table class="data-table table stripe hover nowrap">
                            <thead>
                                <tr>
                                    <th class="table-plus datatable-nosort">Name</th>
                                    <th>Age</th>
                                    <th>Office</th>
                                    <th>Address</th>
                                    <th>Start Date</th>
                                    <th class="datatable-nosort">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="table-plus">Gloria F. Mead</td>
                                    <td>25</td>
                                    <td>Sagittarius</td>
                                    <td>2829 Trainer Avenue Peoria, IL 61602</td>
                                    <td>29-03-2018</td>
                                    <td>
                                        <div class="dropdown">
                                            <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle"
                                                href="#" role="button" data-toggle="dropdown">
                                                <i class="dw dw-more"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                                                <a class="dropdown-item" href="#"><i class="dw dw-eye"></i>
                                                    View</a>
                                                <a class="dropdown-item" href="#"><i class="dw dw-edit2"></i>
                                                    Edit</a>
                                                <a class="dropdown-item" href="#"><i class="dw dw-delete-3"></i>
                                                    Delete</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="table-plus">Andrea J. Cagle</td>
                                    <td>30</td>
                                    <td>Gemini</td>
                                    <td>1280 Prospect Valley Road Long Beach, CA 90802</td>
                                    <td>29-03-2018</td>
                                    <td>
                                        <div class="dropdown">
                                            <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle"
                                                href="#" role="button" data-toggle="dropdown">
                                                <i class="dw dw-more"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                                                <a class="dropdown-item" href="#"><i class="dw dw-eye"></i>
                                                    View</a>
                                                <a class="dropdown-item" href="#"><i class="dw dw-edit2"></i>
                                                    Edit</a>
                                                <a class="dropdown-item" href="#"><i class="dw dw-delete-3"></i>
                                                    Delete</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="table-plus">Andrea J. Cagle</td>
                                    <td>20</td>
                                    <td>Gemini</td>
                                    <td>2829 Trainer Avenue Peoria, IL 61602</td>
                                    <td>29-03-2018</td>
                                    <td>
                                        <div class="dropdown">
                                            <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle"
                                                href="#" role="button" data-toggle="dropdown">
                                                <i class="dw dw-more"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                                                <a class="dropdown-item" href="#"><i class="dw dw-eye"></i>
                                                    View</a>
                                                <a class="dropdown-item" href="#"><i class="dw dw-edit2"></i>
                                                    Edit</a>
                                                <a class="dropdown-item" href="#"><i
                                                        class="dw dw-delete-3"></i> Delete</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="table-plus">Andrea J. Cagle</td>
                                    <td>30</td>
                                    <td>Sagittarius</td>
                                    <td>1280 Prospect Valley Road Long Beach, CA 90802</td>
                                    <td>29-03-2018</td>
                                    <td>
                                        <div class="dropdown">
                                            <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle"
                                                href="#" role="button" data-toggle="dropdown">
                                                <i class="dw dw-more"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                                                <a class="dropdown-item" href="#"><i class="dw dw-eye"></i>
                                                    View</a>
                                                <a class="dropdown-item" href="#"><i class="dw dw-edit2"></i>
                                                    Edit</a>
                                                <a class="dropdown-item" href="#"><i
                                                        class="dw dw-delete-3"></i> Delete</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="table-plus">Andrea J. Cagle</td>
                                    <td>25</td>
                                    <td>Gemini</td>
                                    <td>2829 Trainer Avenue Peoria, IL 61602</td>
                                    <td>29-03-2018</td>
                                    <td>
                                        <div class="dropdown">
                                            <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle"
                                                href="#" role="button" data-toggle="dropdown">
                                                <i class="dw dw-more"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                                                <a class="dropdown-item" href="#"><i class="dw dw-eye"></i>
                                                    View</a>
                                                <a class="dropdown-item" href="#"><i class="dw dw-edit2"></i>
                                                    Edit</a>
                                                <a class="dropdown-item" href="#"><i
                                                        class="dw dw-delete-3"></i> Delete</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="table-plus">Andrea J. Cagle</td>
                                    <td>20</td>
                                    <td>Sagittarius</td>
                                    <td>1280 Prospect Valley Road Long Beach, CA 90802</td>
                                    <td>29-03-2018</td>
                                    <td>
                                        <div class="dropdown">
                                            <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle"
                                                href="#" role="button" data-toggle="dropdown">
                                                <i class="dw dw-more"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                                                <a class="dropdown-item" href="#"><i class="dw dw-eye"></i>
                                                    View</a>
                                                <a class="dropdown-item" href="#"><i class="dw dw-edit2"></i>
                                                    Edit</a>
                                                <a class="dropdown-item" href="#"><i
                                                        class="dw dw-delete-3"></i> Delete</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="table-plus">Andrea J. Cagle</td>
                                    <td>18</td>
                                    <td>Gemini</td>
                                    <td>1280 Prospect Valley Road Long Beach, CA 90802</td>
                                    <td>29-03-2018</td>
                                    <td>
                                        <div class="dropdown">
                                            <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle"
                                                href="#" role="button" data-toggle="dropdown">
                                                <i class="dw dw-more"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                                                <a class="dropdown-item" href="#"><i class="dw dw-eye"></i>
                                                    View</a>
                                                <a class="dropdown-item" href="#"><i class="dw dw-edit2"></i>
                                                    Edit</a>
                                                <a class="dropdown-item" href="#"><i
                                                        class="dw dw-delete-3"></i> Delete</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="table-plus">Andrea J. Cagle</td>
                                    <td>30</td>
                                    <td>Sagittarius</td>
                                    <td>1280 Prospect Valley Road Long Beach, CA 90802</td>
                                    <td>29-03-2018</td>
                                    <td>
                                        <div class="dropdown">
                                            <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle"
                                                href="#" role="button" data-toggle="dropdown">
                                                <i class="dw dw-more"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                                                <a class="dropdown-item" href="#"><i class="dw dw-eye"></i>
                                                    View</a>
                                                <a class="dropdown-item" href="#"><i class="dw dw-edit2"></i>
                                                    Edit</a>
                                                <a class="dropdown-item" href="#"><i
                                                        class="dw dw-delete-3"></i> Delete</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="table-plus">Andrea J. Cagle</td>
                                    <td>30</td>
                                    <td>Sagittarius</td>
                                    <td>1280 Prospect Valley Road Long Beach, CA 90802</td>
                                    <td>29-03-2018</td>
                                    <td>
                                        <div class="dropdown">
                                            <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle"
                                                href="#" role="button" data-toggle="dropdown">
                                                <i class="dw dw-more"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                                                <a class="dropdown-item" href="#"><i class="dw dw-eye"></i>
                                                    View</a>
                                                <a class="dropdown-item" href="#"><i class="dw dw-edit2"></i>
                                                    Edit</a>
                                                <a class="dropdown-item" href="#"><i
                                                        class="dw dw-delete-3"></i> Delete</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="table-plus">Andrea J. Cagle</td>
                                    <td>30</td>
                                    <td>Gemini</td>
                                    <td>1280 Prospect Valley Road Long Beach, CA 90802</td>
                                    <td>29-03-2018</td>
                                    <td>
                                        <div class="dropdown">
                                            <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle"
                                                href="#" role="button" data-toggle="dropdown">
                                                <i class="dw dw-more"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                                                <a class="dropdown-item" href="#"><i class="dw dw-eye"></i>
                                                    View</a>
                                                <a class="dropdown-item" href="#"><i class="dw dw-edit2"></i>
                                                    Edit</a>
                                                <a class="dropdown-item" href="#"><i
                                                        class="dw dw-delete-3"></i> Delete</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="table-plus">Andrea J. Cagle</td>
                                    <td>30</td>
                                    <td>Gemini</td>
                                    <td>1280 Prospect Valley Road Long Beach, CA 90802</td>
                                    <td>29-03-2018</td>
                                    <td>
                                        <div class="dropdown">
                                            <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle"
                                                href="#" role="button" data-toggle="dropdown">
                                                <i class="dw dw-more"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                                                <a class="dropdown-item" href="#"><i class="dw dw-eye"></i>
                                                    View</a>
                                                <a class="dropdown-item" href="#"><i class="dw dw-edit2"></i>
                                                    Edit</a>
                                                <a class="dropdown-item" href="#"><i
                                                        class="dw dw-delete-3"></i> Delete</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="table-plus">Andrea J. Cagle</td>
                                    <td>30</td>
                                    <td>Gemini</td>
                                    <td>1280 Prospect Valley Road Long Beach, CA 90802</td>
                                    <td>29-03-2018</td>
                                    <td>
                                        <div class="dropdown">
                                            <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle"
                                                href="#" role="button" data-toggle="dropdown">
                                                <i class="dw dw-more"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                                                <a class="dropdown-item" href="#"><i class="dw dw-eye"></i>
                                                    View</a>
                                                <a class="dropdown-item" href="#"><i class="dw dw-edit2"></i>
                                                    Edit</a>
                                                <a class="dropdown-item" href="#"><i
                                                        class="dw dw-delete-3"></i> Delete</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- Simple Datatable End -->
                <!-- multiple select row Datatable start -->

                <!-- multiple select row Datatable End -->
                <!-- Checkbox select Datatable start -->

                <!-- Checkbox select Datatable End -->
                <!-- Export Datatable start -->

                <!-- Export Datatable End -->
            </div>
        </div>
    </div>
    <!-- welcome modal end -->
    <!-- js -->
    <script src="js/core.js"></script>
    <script src="js/script.min.js"></script>
    <script src="js/process.js"></script>
    <script src="js/layout-settings.js"></script>
    <script src="src/plugins/datatables/js/jquery.dataTables.min.js"></script>
    <script src="src/plugins/datatables/js/dataTables.bootstrap4.min.js"></script>
    <script src="src/plugins/datatables/js/dataTables.responsive.min.js"></script>
    <script src="src/plugins/datatables/js/responsive.bootstrap4.min.js"></script>
    <!-- buttons for Export datatable -->
    <script src="src/plugins/datatables/js/dataTables.buttons.min.js"></script>
    <script src="src/plugins/datatables/js/buttons.bootstrap4.min.js"></script>
    <script src="src/plugins/datatables/js/buttons.print.min.js"></script>
    <script src="src/plugins/datatables/js/buttons.html5.min.js"></script>
    <script src="src/plugins/datatables/js/buttons.flash.min.js"></script>
    <script src="src/plugins/datatables/js/pdfmake.min.js"></script>
    <script src="src/plugins/datatables/js/vfs_fonts.js"></script>
    <!-- Datatable Setting js -->
    <script src="js/datatable-setting.js"></script>
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-NXZMQSS" height="0" width="0"
            style="display: none; visibility: hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
</body>

</html>
