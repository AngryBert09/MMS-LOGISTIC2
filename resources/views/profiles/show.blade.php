<!DOCTYPE html>
<html>

<head>
    <!-- Basic Page Info -->
    <meta charset="utf-8" />
    <title>Vendor Profile</title>

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
    <link rel="stylesheet" type="text/css" href="{{ asset('src/plugins/cropperjs/dist/cropper.css') }}" />
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
        <div class="pd-ltr-20 xs-pd-20-10">
            <div class="min-height-200px">
                @include('layout.breadcrumb')
                <div class="row">
                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 mb-30">
                        <div class="pd-20 card-box height-100-p">
                            <div class="profile-photo">
                                <a href="modal" data-toggle="modal" data-target="#modal" class="edit-avatar"><i
                                        class="fa fa-pencil"></i></a>
                                <img src="{{ $vendor->profile_pic ? asset($vendor->profile_pic) : asset('images/default.jpg') }}"
                                    alt="Vendor Profile Picture" class="avatar-photo" />

                                <!-- Modal for Picture Update -->
                                <div class="modal fade" id="modal" tabindex="-1" role="dialog"
                                    aria-labelledby="modalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <form action="{{ route('profiles.update', $vendor->id) }}" method="POST"
                                                enctype="multipart/form-data">
                                                @csrf
                                                @method('PUT')

                                                <div class="modal-body pd-5">
                                                    <div class="img-container text-center mb-3">
                                                        <img id="imagePreview"
                                                            src="{{ $vendor->profile_pic ? asset($vendor->profile_pic) : asset('images/photo2.jpg') }}"
                                                            alt="Profile Picture" class="img-fluid rounded-circle"
                                                            style="width: 150px; height: 150px; object-fit: cover;" />
                                                    </div>
                                                    <input type="file" name="profile_pic" class="form-control"
                                                        accept="image/*" onchange="previewImage(event)">
                                                </div>

                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-warning">Update</button>
                                                    <button type="button" class="btn btn-default="
                                                        data-dismiss="modal">Close</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <script>
                                    function previewImage(event) {
                                        const imagePreview = document.getElementById('imagePreview');
                                        const file = event.target.files[0];

                                        if (file) {
                                            const reader = new FileReader();
                                            reader.onload = function(e) {
                                                imagePreview.src = e.target.result;
                                            }
                                            reader.readAsDataURL(file);
                                        }
                                    }
                                </script>



                            </div>
                            <h5 class="text-center h5 mb-0">VENDOR : {{ $vendor->company_name }}</h5>
                            <p class="text-center text-muted font-14">
                                Account Admin : {{ $vendor->full_name }}
                            </p>
                            <div class="profile-info">
                                <h5 class="mb-20 h5 text-warning">Contact Information</h5>
                                <ul>
                                    <li>
                                        <span>Email Address:</span>
                                        {{ $vendor->email }}
                                    </li>
                                    <li>
                                        <span>Phone Number:</span>
                                        {{ $vendor->phone_number }}
                                    </li>
                                    <li>
                                        <span>Address:</span>
                                        {{ $vendor->address }}<br />
                                        {{ $vendor->city }} {{ $vendor->state }} {{ $vendor->zip }}
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-8 col-lg-8 col-md-8 col-sm-12 mb-30">
                        <div class="card-box height-100-p overflow-hidden">
                            <div class="profile-tab height-100-p">
                                <div class="tab height-100-p">
                                    <ul class="nav nav-tabs customtab" role="tablist">
                                        <li class="nav-item">

                                            <a class="nav-link active" data-toggle="tab" href="#account"
                                                role="tab">Account Information</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" data-toggle="tab" href="#security"
                                                role="tab">Security</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" data-toggle="tab" href="#personal"
                                                role="tab">Personal</a>
                                        </li>
                                    </ul>

                                    <div class="tab-content">
                                        <div class="tab-pane fade show active" id="account" role="tabpanel">
                                            <!-- Flash message for success or error -->
                                            @include('profiles.message')
                                            @include('profiles.update')
                                        </div>

                                        <!-- Security Tab start -->
                                        <div class="tab-pane fade" id="security" role="tabpanel">
                                            @include('profiles.security')
                                        </div>


                                        <!-- Setting Tab start -->
                                        <div class="tab-pane fade height-100-p" id="personal" role="tabpanel">
                                            <div class="pd-20 profile-task-wrap">
                                                <div class="container pd-0">
                                                    <!-- Personal Details Section Start -->
                                                    <div class="billing-title row align-items-center">
                                                        <div class="col-md-8 col-sm-12">
                                                            <h5>Personal Details</h5>
                                                            <p class="text-muted small">
                                                                View and manage your personal details. Some information
                                                                may be non-editable for security reasons.
                                                            </p>
                                                        </div>
                                                    </div>

                                                    <div class="card-body">
                                                        <!-- Account Owner -->
                                                        <div class="form-group">
                                                            <label>Account Owner</label>
                                                            <input type="text" class="form-control"
                                                                value="{{ $vendor->full_name }}" disabled>
                                                        </div>

                                                        <!-- Gender -->
                                                        <div class="form-group">
                                                            <label>Gender</label>
                                                            <input type="text" class="form-control"
                                                                value="{{ $vendor->gender }}" disabled>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- Personal Details Section End -->
                                            </div>
                                        </div>



                                        <!-- Setting Tab End -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>


    <!-- welcome modal end -->
    <!-- js -->
    <script src="{{ asset('js/core.js') }}"></script>
    <script src="{{ asset('js/script.min.js') }}"></script>
    <script src="{{ asset('js/process.js') }}"></script>
    <script src="{{ asset('js/layout-settings.js') }}"></script>
    <script src="{{ asset('src/plugins/cropperjs/dist/cropper.js') }}"></script>
    <script>
        window.addEventListener("DOMContentLoaded", function() {
            var image = document.getElementById("image");
            var cropBoxData;
            var canvasData;
            var cropper;

            $("#modal")
                .on("shown.bs.modal", function() {
                    cropper = new Cropper(image, {
                        autoCropArea: 0.5,
                        dragMode: "move",
                        aspectRatio: 3 / 3,
                        restore: false,
                        guides: false,
                        center: false,
                        highlight: false,
                        cropBoxMovable: false,
                        cropBoxResizable: false,
                        toggleDragModeOnDblclick: false,
                        ready: function() {
                            cropper.setCropBoxData(cropBoxData).setCanvasData(canvasData);
                        },
                    });
                })
                .on("hidden.bs.modal", function() {
                    cropBoxData = cropper.getCropBoxData();
                    canvasData = cropper.getCanvasData();
                    cropper.destroy();
                });
        });
    </script>
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-NXZMQSS" height="0" width="0"
            style="display: none; visibility: hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
</body>

</html>
