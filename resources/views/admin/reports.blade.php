<!DOCTYPE html>
<html>

@include('layout.head')

<body>
    @include('admin.layout.navbar')
    @include('admin.layout.left-sidebar')
    @include('admin.layout.right-sidebar')

    <div class="mobile-menu-overlay"></div>

    <div class="main-container">
        <div class="pd-ltr-20 xs-pd-20-10">
            <div class="min-height-200px">
                @include('layout.breadcrumb')
                <!-- Simple Datatable Start -->
                <div class="card-box mb-30">
                    <div class="pd-20 d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="text-primary h4">Vendor Reports</h4>
                            <p class="text-muted">
                                Overview of all vendors, including company name, service type, total bids won,
                                performance rating,
                                and contact details. This section allows you to monitor and evaluate vendor performance.
                            </p>
                        </div>
                    </div>
                    <div class="pb-20">
                        <table class="table table-striped table-hover nowrap dt-responsive" style="width:100%">
                            <thead>
                                <tr>
                                    <th class="table-plus">ID</th>
                                    <th>Company Name</th>
                                    <th>Service Type</th>
                                    <th>Total Bids Won</th>
                                    <th>Performance Rating</th>
                                    <th>Contact Person</th>
                                    <th>Phone</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="table-plus">1</td>
                                    <td>Acme Supplies Ltd.</td>
                                    <td>Office Equipment</td>
                                    <td>12</td>
                                    <td><span class="badge badge-success">4.5/5</span></td>
                                    <td>Jane Doe</td>
                                    <td>+1 555-123-4567</td>
                                    <td>
                                        <button type="button" class="btn btn-info btn-sm viewVendorBtn"
                                            data-company="Acme Supplies Ltd." data-type="Office Equipment"
                                            data-rating="4.5" data-contact="Jane Doe" data-phone="+1 555-123-4567"
                                            data-email="jane.doe@acmesupplies.com" data-toggle="modal"
                                            data-target="#viewVendorModal">
                                            View
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="table-plus">2</td>
                                    <td>Global IT Services</td>
                                    <td>IT Solutions</td>
                                    <td>8</td>
                                    <td><span class="badge badge-warning">3.2/5</span></td>
                                    <td>Michael Smith</td>
                                    <td>+1 555-987-6543</td>
                                    <td>
                                        <button type="button" class="btn btn-info btn-sm viewVendorBtn"
                                            data-company="Global IT Services" data-type="IT Solutions" data-rating="3.2"
                                            data-contact="Michael Smith" data-phone="+1 555-987-6543"
                                            data-email="michael@globalit.com" data-toggle="modal"
                                            data-target="#viewVendorModal">
                                            View
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="table-plus">3</td>
                                    <td>Fresh Farms Co.</td>
                                    <td>Catering</td>
                                    <td>5</td>
                                    <td><span class="badge badge-danger">2.7/5</span></td>
                                    <td>Aisha Khan</td>
                                    <td>+1 555-222-3344</td>
                                    <td>
                                        <button type="button" class="btn btn-info btn-sm viewVendorBtn"
                                            data-company="Fresh Farms Co." data-type="Catering" data-rating="2.7"
                                            data-contact="Aisha Khan" data-phone="+1 555-222-3344"
                                            data-email="aisha@freshfarms.com" data-toggle="modal"
                                            data-target="#viewVendorModal">
                                            View
                                        </button>
                                    </td>
                                </tr>
                            </tbody>

                        </table>
                    </div>
                </div>

                <!-- Simple Datatable End -->
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- View Vendor Modal -->
    <div class="modal fade" id="viewVendorModal" tabindex="-1" role="dialog" aria-labelledby="viewVendorModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content border-0 shadow-lg rounded-3">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="viewVendorModalLabel">Vendor Details</h5>
                    <button type="button" class="btn-close text-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted">Company Name</h6>
                            <p class="fw-bold" id="vendorCompany"></p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted">Service Type</h6>
                            <p class="fw-bold" id="vendorType"></p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted">Performance Rating</h6>
                            <p class="fw-bold" id="vendorRating"></p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted">Contact Person</h6>
                            <p class="fw-bold" id="vendorContact"></p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted">Phone</h6>
                            <p class="fw-bold" id="vendorPhone"></p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted">Email</h6>
                            <p class="fw-bold" id="vendorEmail"></p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Vendor Modal JS -->
    <script>
        $(document).ready(function() {
            $('.viewVendorBtn').on('click', function() {
                $('#vendorCompany').text($(this).data('company'));
                $('#vendorType').text($(this).data('type'));
                $('#vendorRating').text($(this).data('rating') + ' / 5');
                $('#vendorContact').text($(this).data('contact'));
                $('#vendorPhone').text($(this).data('phone'));
                $('#vendorEmail').text($(this).data('email'));
            });
        });
    </script>




    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('js/core.js') }}"></script>
    <script src="{{ asset('js/script.min.js') }}"></script>
    <script src="{{ asset('js/process.js') }}"></script>
    <script src="{{ asset('js/layout-settings.js') }}"></script>
    <script src="{{ asset('src/plugins/datatables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('src/plugins/datatables/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('src/plugins/datatables/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('src/plugins/datatables/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('src/plugins/datatables/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('src/plugins/datatables/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('src/plugins/datatables/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('src/plugins/datatables/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('src/plugins/datatables/js/buttons.flash.min.js') }}"></script>
    <script src="{{ asset('src/plugins/datatables/js/pdfmake.min.js') }}"></script>
    <script src="{{ asset('src/plugins/datatables/js/vfs_fonts.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('.table').DataTable({
                responsive: true,
                paging: true,
                searching: true,
                ordering: true,
                info: true,
                autoWidth: false
            });
        });
    </script>

</body>

</html>
