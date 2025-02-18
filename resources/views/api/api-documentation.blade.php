<!doctype html>

<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>API Documentation</title>
    <meta name="description" content="API Documentation for managing vendors">
    <meta name="author" content="Your Name">
    <link
        href="https://fonts.googleapis.com/css2?family=Noto+Serif:wght@400;700&family=Open+Sans:ital,wght@0,400;0,700;1,600&display=swap"
        rel="stylesheet">

    <script defer src="https://use.fontawesome.com/releases/v5.7.2/js/all.js"
        integrity="sha384-0pzryjIRos8mFBWMzSSZApWtPl/5++eIfzYmTgBBmXYdhvxPc+XcFEk+zJwDgWbP" crossorigin="anonymous">
    </script>
</head>


<body>

    <div class="navbar clear nav-top">
        <div class="row content">
            <a href="#"><img class="logo" src="{{ asset('images/greatwall-logo.png') }}" alt="Logo"></a>
            <a class="right" style="text-decoration: underline;" href="#"><i class="fas fa-book"></i>&nbsp;
                Documentation</a>
            <a class="right" href="mailto: logistic2.devs.gwamerchandise.com" target="_blank"><i
                    class="fas fa-paper-plane"></i>&nbsp;
                logistic2.devs.gwamerchandise.com</a>
            <!-- Login & Register Buttons -->
            <a href="{{ route('login') }}" class="btn btn-outline-primary me-2 right">Login</a>
            <a href="{{ route('register') }}" class="btn btn-primary right">Register</a>
        </div>
    </div>

    <div class="container clear">
        <div class="row wrapper">

            <div class="sidepanel">

                <a class="title" href="#">Introduction</a>
                <a class="section" href="#gettingstarted">Getting Started</a>
                <a class="section" href="#apiendpoints">API Endpoints</a>
                <a class="section" href="#authentication">Authentication</a>

                <div class="divider left"></div>

                <a class="title" href="#features">API Features</a>

                <a class="section" href="#getvendor">Get Vendor</a>
                <a class="section" href="#updatevendor">Update Vendor</a>

                <div class="divider left"></div>

                <a class="title" href="#moreinfo">More Info</a>

            </div>

            <div class="right-col">

                <h1>Introduction</h1>
                <p>Welcome to the LOGISTIC2 API documentation. This API is open-source and allows you to manage vendors
                    in your system, enabling CRUD operations for vendor data. It is still evolving, and we plan to
                    improve and expand its capabilities in the future.</p>


                <h2>Getting Started</h2>
                <p>To get started, you can begin making requests to the LOGISTIC2 API. Currently, authentication is not
                    required, so you can freely interact with the endpoints. However, future versions may introduce
                    authentication for added security.</p>


                <h2 id="apiendpoints">API Endpoints</h2>
                <p>The following API endpoints are available for interacting with vendor data:</p>

                <ul>

                    <li><b>GET /api/vendors</b> - Retrieve a list of all vendors</li>
                    <li><b>GET /api/vendor?id={id}</b> - Retrieve a specific vendor's data by ID</li>
                    <li><b>PUT /api/vendors/{id}</b> - Update a vendor's information by ID</li>
                    <h5>SAMPLE ENDPOINT : logistic2.gwamerchandise.com/api/vendors</h5>
                </ul>

                <h2 id="authentication">Authentication</h2>
                <p>Currently, no authentication is required to access the API. All endpoints are open for use, but this
                    may change in the future as security measures are implemented.</p>


                <h2 id="features">API Features</h2>



                <h3 id="getvendor">Get Vendor</h3>
                <p>To retrieve a vendor's details, send a GET request to <code>/api/vendor?id={id}</code>:</p>


                <p>Sample Output:</p>
                <pre><code>
{
 "id": 44,
"companyName": "MY COMPANY",
"email": "yourmom@gmail.com",
"fullName": "asd",
"gender": "Male",
"status": "Approved",
}
                </code></pre>

                <div class="divider" style="width:100%; margin:30px 0;"></div>

                <h3 id="updatevendor">Update Vendor</h3>
                <p>To update a vendor's information, send a PUT request to <code>/api/vendor/{id}</code> with the data
                    you want to update (e.g., vendor name, contact info, etc.):</p>

                <pre><code>
PUT /api/vendor/{id}
                </code></pre>

                <p>Example data to update:</p>
                <pre><code>
{
 "companyName": "VendorCompany",
 "status": "Approved",
}
                </code></pre>


                <div class="divider" style="width:100%; margin:30px 0;"></div>

                <h2 id="moreinfo">More Info</h2>
                <p>If you need more detailed information on how to interact with the API, please refer to our full
                    documentation or contact our support team at <a href="mailto:yourapp@email.com">
                        logistic2.devs.gwamerchandise.com</a>.</p>

            </div>

        </div>

    </div>

</body>

</html>



<style>
    html {
        scroll-behavior: smooth;
    }


    body {
        font-family: 'Open Sans', sans-serif;
        letter-spacing: 0.2px;
        color: #333;
        margin: 0;
    }

    h1 {
        font-family: 'Noto Serif', serif;
        font-size: 39px;
        line-height: 42px;
        margin-bottom: 50px;

    }

    h2 {
        font-family: 'Noto Serif', serif;
        font-size: 28px;
        line-height: 30px;
        text-align: center;
        color: #333;
        margin-block-start: 40px;
        margin-block-end: 30px;
        margin-inline-start: 0px;
        margin-inline-end: 0px;
    }

    p {
        font-size: 20px;
        line-height: 26px;
    }

    a {
        text-decoration: none;
    }

    a:hover {
        text-decoration: underline;
    }

    .navbar {
        overflow: hidden;
        background-color: #f;
        color: #333;
        top: 0;
        width: 100%;
        height: 70px;
        font-size: 15px;
        text-align: right;
        padding: 12px 0 0 0;
        position: fixed;
        z-index: 1;
    }

    .navbar a {
        margin-right: 40px;
        color: #333;
    }

    .navbar a:active {
        text-decoration: underline;
    }

    .right a {
        float: right;
    }

    .container {
        width: 100%;
    }

    .top {
        background-color: #0098f1;
        height: 650px;
    }

    .clear {
        background-color: #fff;
        height: auto;
    }

    .nav-top {
        border-top: 8px solid #ffc107;
    }

    .row {
        max-width: 63rem;
        margin: auto;
        display: block;
    }

    .content {
        padding: 22px;
    }

    .wrapper {
        display: flex;
        padding: 22px;
    }

    .sidepanel {
        width: 200px;
        margin-top: 120px;
        background-color: #fff;
        overflow-x: hidden;
        position: fixed;
        height: 80%;

    }

    .sidepanel a.title {
        font-family: 'Noto Serif', serif;
        font-size: 18px;
        font-weight: 600;
        text-align: left;
        text-decoration: none;
        color: #333;
        display: block;
        padding-bottom: 12px;
    }

    .sidepanel a.section {
        font-family: 'Open Sans', sans-serif;
        font-size: 15px;
        text-align: left;
        line-height: 30px;
        display: block;
        text-decoration: none;
        color: #333;
    }

    .sidepanel a.sub-section {
        font-family: 'Open Sans', sans-serif;
        font-size: 15px;
        text-align: left;
        line-height: 25px;
        display: block;
        text-decoration: none;
        color: #333;
        padding-left: 20px;

    }

    .sidepanel a.title:hover,
    .sidepanel a.section:hover,
    .sidepanel a.sub-section:hover {
        text-decoration: underline;
    }

    .main-col {
        width: 70%;
        margin: auto;
        text-align: center;
    }

    .main-col p {
        font-size: 15px;
        line-height: 24px;
        margin-bottom: 36px;
    }

    .right-col {
        margin-top: 100px;
        text-align: left;
        width: 100%;
        margin-left: 220px;
    }

    .right-col h1 {
        font-size: 24px;
        line-height: 28px;
        margin-bottom: 24px;
    }

    .right-col h2 {
        font-size: 21px;
        line-height: 24px;
        margin-bottom: 30px;
        text-align: left;
        margin-block-start: 20px;
        margin-block-end: 10px;
    }

    .right-col p {
        font-size: 16px;
        line-height: 22px;
        margin-bottom: 36px;
    }

    .right-col a {
        color: #ffc107;
        text-decoration: underline;
    }

    .right-col ol {
        margin-block-start: 10px;
    }

    .logo {
        width: 100px;
        float: left;
        margin: 10px 30px;
    }

    .col {
        width: 50%;
        float: left;
    }

    .top-info {
        font-family: 'Open Sans', sans-serif;
        font-size: 17px;
        text-align: center;

    }

    .divider {
        width: 40px;
        height: 4px;
        background: #ffc107;
        border-radius: 30px;
        margin: auto;
        margin-top: 8px;
        margin-bottom: 8px;
    }

    .left {
        margin: 20px 0;
    }


    .footer {
        float: left;
        width: 100%;
        padding: 90px 0 20px;
        font-size: 15px;
        text-align: center;
        background-color: #fff;
    }

    .footer a {
        color: #333;
    }

    .resp-break {
        display: none;
    }

    .break {
        display: block;
    }

    .space {
        height: 30px;
        width: 100%;
        float: left;
    }

    .double {
        height: 60px;
    }

    .links {
        float: left;
        padding-bottom: 8px;
    }



    /* Break Points */


    /* Extra small-small devices (phones, 420px and down) */
    @media only screen and (max-width: 420px) {

        .logo {
            width: aut;
        }

        .navbar {
            height: auto;
        }

        h2 {
            font-size: 24px;
        }

        .links {
            float: none;
        }

    }



    /* Extra small devices (phones, 650px and down) */
    @media only screen and (max-width: 650px) {

        .logo {
            width: 115px;
            margin: -10px 0px;
        }

        .navbar a {
            padding-bottom: 5px;
            margin-left: 40px;
            margin-right: 0;
        }

        .resp-break {
            display: block;
            margin: 17px;
        }

        .main-col {
            width: 100%;
        }


        .wrapper {
            display: block;
        }

        .sidepanel {
            width: 100%;
            text-align: center;
            position: relative;
            height: auto;
        }

        .sidepanel a.title,
        .sidepanel a.section,
        .sidepanel a.sub-section {
            text-align: center;
        }

        .left {
            margin: 20px auto;
        }

        .right-col {
            position: relative;
            margin-top: 20px;
            margin-left: 0px;

        }
    }

    /* Small devices (portrait tablets and large phones, 650px and up) */
    @media only screen and (min-width: 650px) {

        .logo {
            margin: -10px 0px;
        }

        .right a {
            float: right;
            padding-bottom: 5px;
        }



    }

    /* Medium devices (landscape tablets, 768px and up) */
    @media only screen and (min-width: 768px) .logo {
        margin: -10px 0px;
    }
    }

    /* Large devices (laptops/desktops, 992px and up) */
    @media only screen and (min-width: 992px) {}


    /* End Break Points */
</style>
