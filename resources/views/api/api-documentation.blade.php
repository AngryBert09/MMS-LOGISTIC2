<!doctype html>

<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/gwa-favicon-32x32.png') }}" />
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
                Documentation 1.4.x</a>
            <a class="right" href="mailto:logistic2.devs.gwamerchandise.com" target="_blank"><i
                    class="fas fa-paper-plane"></i>&nbsp;
                logistic2.devs.gwamerchandise.com</a>

            @guest
                <!-- Show only if the user is NOT authenticated -->
                <a href="{{ route('api.login') }}" class="btn btn-outline-primary me-2 right">Login</a>
                <a href="{{ route('api.register') }}" class="btn btn-primary right">Register</a>
            @else
                <!-- Show Logout button if user is authenticated -->
                <a href="{{ route('api.dashboard') }}" class="btn btn-danger right">
                    dashboard
                </a>
            @endguest
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

                <a class="section" href="#getallvendor">Get All Vendors</a>
                <a class="section" href="#getvendor">Get Specific Vendor</a>
                <a class="section" href="#updatevendor">Update Vendor</a>

                <div class="divider left"></div>

                <a class="title" href="#moreinfo">More Info</a>

            </div>

            <div class="right-col">

                <h1>Introduction</h1>
                <p>Welcome to the LOGISTIC2 API documentation. This API provides a secure and efficient way to manage
                    vendors within your system, supporting CRUD operations (Create, Read, Update, and Delete) for vendor
                    data.</p>

                <p>This API is <strong>no longer open-source</strong>. To access and use it, you must
                    <strong>register</strong> and obtain an API key. We are continuously working on improvements and
                    expanding its capabilities to better serve your needs.
                </p>
                <p>🔹 <strong>Key Features:</strong></p>
                <ul>
                    <li>✔️ Secure Vendor Management (CRUD Operations)</li>
                    <li>✔️ Authentication & API Key Access</li>
                    <li>✔️ Scalable and Structured API Design</li>
                </ul>
                <p>📌 <strong>To get started, register for access and obtain your API key.</strong> 🚛💨</p>


                <h2>Getting Started</h2>
                <p>To start using the LOGISTIC2 API, you must first register and obtain an API key. Authentication is
                    <strong>required</strong> to interact with the endpoints securely.
                </p>

                <p>Follow these steps to get started:</p>
                <ol>
                    <li>🔹 <strong>Register</strong> for an account.</li>
                    <li>🔹 <strong>Generate your API key</strong> from the dashboard.</li>
                    <li>🔹 Use your API key in requests by including it in the <code>Authorization</code> header as a
                        Bearer token.</li>
                </ol>

                <p>Future updates will continue improving security and expanding API capabilities. Stay tuned! 🚀</p>



                <h2 id="apiendpoints">API Endpoints</h2>
                <p>The following API endpoints allow you to manage vendor data. Authentication is required for all
                    requests.</p>

                <ul>
                    <li><b>GET /api/vendors</b> - Retrieve a list of all vendors.</li>
                    <li><b>GET /api/vendor/{id}</b> - Retrieve a specific vendor's data by ID.</li>
                    <li><b>PUT /api/vendor/{id}</b> - Update a vendor's information by ID.</li>
                    <li><b>PATCH /api/vendor/{id}</b> - Partially update a vendor's data.</li>
                </ul>

                <h5>📌 Sample Endpoint:</h5>
                <p><code>https://logistic2.gwamerchandise.com/api/vendors</code></p>


                <h2 id="authentication">Authentication</h2>
                <p>Authentication is required to access the LOGISTIC2 API. You must first register and obtain an API
                    key.
                    All requests must include a valid API key in the header using the Bearer token method.</p>

                <h5>📌 Authentication Header:</h5>
                <pre><code>Authorization: Bearer YOUR_API_KEY
                     Accept: application/json</code></pre>

                <h5>🔹 Example cURL Request:</h5>
                <pre><code>curl -X GET "https://logistic2.gwamerchandise.com/api/vendors" \
                          -H "Authorization: Bearer YOUR_API_KEY" \
                          -H "Accept: application/json"</code></pre>



                <h2 id="features">API Features</h2>



                <h3 id="getallvendors">Get All Vendors</h3>
                <p>To retrieve a list of all vendors, send a <b>GET</b> request to the following endpoint:</p>

                <pre><code>GET /api/vendors</code></pre>

                <h5>📌 Sample Response:</h5>
                <pre><code>
                [
                    {
                        "id": 44,
                        "companyName": "MY COMPANY",
                        "email": "yourmom@gmail.com",
                        "fullName": "John Doe",
                        "gender": "Male",
                        "status": "Approved"
                    },
                    {
                        "id": 45,
                        "companyName": "XYZ Logistics",
                        "email": "xyz@email.com",
                        "fullName": "Jane Smith",
                        "gender": "Female",
                        "status": "Pending"
                    }
                ]
                </code></pre>


                <div class="divider" style="width:100%; margin:30px 0;"></div>

                <h3 id="getvendor">Get a Specific Vendor</h3>
                <p>To retrieve details of a specific vendor, send a <b>GET</b> request to the following endpoint,
                    replacing <code>{id}</code> with the vendor's ID:</p>

                <pre><code>GET /api/vendor/{id}</code></pre>

                <h5>📌 Sample Response:</h5>
                <pre><code>
{
    "id": 44,
    "companyName": "MY COMPANY",
    "email": "yourmom@gmail.com",
    "fullName": "John Doe",
    "gender": "Male",
    "status": "Approved"
}
</code></pre>


                <h3 id="updatevendor">Update a Vendor</h3>
                <p>To update a vendor's information, send a <b>PUT</b> request to the following endpoint, replacing
                    <code>{id}</code> with the vendor's ID:
                </p>

                <pre><code>PUT /api/vendor/{id}</code></pre>
                <h5>📌 Sample Response:</h5>
                <pre><code>
{

        "id": 44,
        "companyName": "Updated Company",
        "email": "updated.email@example.com",
        "fullName": "John Doe",
        "gender": "Male",
        "status": "Approved"

}
</code></pre>

                <h3 id="updatevendorpartial">Update Vendor (Partial)</h3>
                <p>To partially update a vendor's data, send a <b>PATCH</b> request to the following endpoint:</p>

                <pre><code>PATCH /api/vendor/{id}</code></pre>



                <h5>📌 Sample Response:</h5>
                <pre><code>
{
    "id": 44,
    "companyName": "Updated Company Name",
    "email": "newemail@example.com",
    "fullName": "John Doe",
    "gender": "Male",
    "status": "Approved"
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
