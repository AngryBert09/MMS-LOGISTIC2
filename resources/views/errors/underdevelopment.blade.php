<!DOCTYPE html>
<html>

@include('layout.head')

<body>
    <div class="error-page d-flex align-items-center flex-wrap justify-content-center pd-20">
        <div class="pd-10">
            <div class="error-page-wrap text-center">
                <h1>OOPS!</h1>
                <h3>THIS PAGE IS UNDER DEVELOPMENT</h3>
                <p>Sorry! but this page currently under development you can check it later.</p>
                <div class="pt-20 mx-auto max-width-200">
                    <a href="{{ route('api.login')) }}" class="btn btn-primary btn-block btn-lg">Back To Home</a>
                </div>
            </div>
        </div>
    </div>

    <!-- welcome modal end -->
    <!-- js -->
    <script src="js/core.js"></script>
    <script src="js/script.min.js"></script>
    <script src="js/process.js"></script>
    <script src="js/layout-settings.js"></script>
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-NXZMQSS" height="0" width="0"
            style="display: none; visibility: hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
</body>

</html>
