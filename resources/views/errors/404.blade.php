<!DOCTYPE html>
<html>
@include('layout.head')

<body>
    <div class="error-page d-flex align-items-center flex-wrap justify-content-center pd-20">
        <div class="pd-10">
            <div class="error-page-wrap text-center">
                <h1>404</h1>
                <h3>Error: 404 Page Not Found</h3>
                <p>
                    Sorry, the page you’re looking for cannot be accessed.<br />Either
                    check the URL
                </p>
                <div class="pt-20 mx-auto max-width-200">
                    <a href="{{ route('dashboard') }}" class="btn btn-warning btn-block btn-lg">Back To Home</a>
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
