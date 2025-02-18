<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delivery Tracking</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <style>
        body {
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f8f9fa;
        }

        .search-container {
            width: 100%;
            max-width: 1000px;
            /* Adjust max width for responsiveness */
            text-align: center;
            margin-bottom: 500px;
        }

        .logo {
            max-width: 150px;
            /* Adjust logo size */
            margin-bottom: 20px;
        }

        .search-bar {
            width: 200%;
            border-radius: 25px;
            padding: 10px 20px;
        }

        .btn-custom {
            border-radius: 25px;
            /* Rounded button */
            display: flex;
            align-items: center;
            justify-content: center;
            padding-right: 20px;
            padding-left: 20px;
        }
    </style>
</head>

<body>

    <div class="search-container">
        <img src="{{ asset('images/greatwall-logo.png') }}" alt="Logo" class="logo">
        <!-- Replace with your logo -->
        <form>
            <div class="input-group">
                <input type="text" class="form-control search-bar" placeholder="Enter tracking #">
                <button class="btn btn-warning btn-custom" type="submit">
                    <i class="bi bi-search"></i> <!-- Bootstrap search icon -->
                </button>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
