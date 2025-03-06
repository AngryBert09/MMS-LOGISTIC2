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

    @include('layout.nav')
    @include('layout.right-sidebar')
    @include('layout.left-sidebar')


    <div class="mobile-menu-overlay"></div>

    <div class="main-container">
        <div class="pd-ltr-20 xs-pd-20-10">
            <div class="min-height-200px">
                @include('layout.breadcrumb')
                <!-- Simple Datatable Start -->
                <div class="card-box mb-30">
                    <div class="pd-20">
                        <h4 class="text-warning h4">Return Management</h4>
                        <p class="mb-0">All returns come from Great Wall Arts.</p>
                    </div>
                    <div class="pb-20">
                        <table class="data-table table stripe hover nowrap">
                            <thead>
                                <tr>
                                    <th class="table-plus datatable-nosort">Return ID</th>
                                    <th>Quantity Returned</th>
                                    <th>Return Reason</th>
                                    <th>Return Status</th>
                                    <th>Return Date</th>
                                    <th class="datatable-nosort">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- @foreach ($returns as $return)
                                    <tr>
                                        <td class="table-plus">{{ $return->return_id }}</td>
                                        <td>{{ $return->quantity_return }}</td>
                                        <td>{{ $return->return_reason }}</td>

                                        <!-- Status Column with Unique ID -->
                                        <td>
                                            <span id="status-{{ $return->return_id }}"
                                                class="badge
                                                @if ($return->return_status == 'Approved') badge-success
                                                @elseif ($return->return_status == 'Processed') badge-primary
                                                @elseif ($return->return_status == 'Rejected') badge-danger
                                                @else badge-warning @endif">
                                                {{ ucfirst($return->return_status) }}
                                            </span>
                                        </td>

                                        <td>{{ date('Y-m-d', strtotime($return->return_date)) }}</td>
                                        <td>
                                            @include('vendors.Returns.actions-returns') <!-- Dropdown Actions -->
                                        </td>
                                    </tr>
                                @endforeach --}}
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
