<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700&display=swap');

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Poppins', sans-serif;
    }

    body {
        min-height: 100vh;
        width: 100%;
        background: white;
        display: flex;
        flex-direction: column;
    }

    /* Navbar */
    .navbar {
        width: 100%;
        height: 60px;
        background: #ffc107;
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 20px;
        box-shadow: 0px 5px 10px rgba(0, 0, 0, 0.1);
        position: fixed;
        top: 0;
        left: 0;
        z-index: 1000;
    }

    .navbar .logo {
        width: 80px;
        height: auto;
    }

    .navbar .nav-links {
        display: flex;
        gap: 20px;
    }

    .navbar .nav-links a {
        text-decoration: none;
        color: black;
        font-size: 16px;
        font-weight: 500;
    }

    .navbar .nav-links a:hover {
        text-decoration: underline;
    }

    .logout-btn {
        background: black;
        color: white;
        padding: 8px 15px;
        border-radius: 5px;
        text-decoration: none;
        font-weight: 500;
        transition: 0.3s;
    }

    .logout-btn:hover {
        background: red;
    }

    /* Sidebar */
    .sidebar {
        width: 250px;
        height: 100vh;
        background: #fff;
        position: fixed;
        top: 60px;
        /* Adjusted to start below navbar */
        left: 0;
        padding-top: 20px;
        box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
    }

    .sidebar a {
        display: block;
        padding: 12px 10px;
        color: black;
        text-decoration: none;
        font-size: 16px;
        margin-bottom: 10px;
        border-radius: 5px;
        transition: 0.3s;
    }

    .sidebar a:hover {
        background: #ffc107;
    }

    /* Main Content */
    .main-content {
        margin-left: 270px;
        margin-top: 80px;
        /* Adjusted so content is not hidden behind navbar */
        padding: 20px;
    }

    .main-content h1 {
        font-size: 24px;
        font-weight: 600;
        margin-bottom: 15px;
    }

    .card {
        background: white;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0px 5px 10px rgba(0, 0, 0, 0.1);
        margin-bottom: 20px;
    }
</style>

<body>

    <!-- Navbar -->
    <div class="navbar">
        <img class="logo" src="{{ asset('images/greatwall-logo.png') }}" alt="Logo">
        <div class="nav-links">
            <a href="#">Home</a>
            <a href="{{ route('api.docs') }}">API Docs</a>
            <a href="#">Settings</a>
        </div>
        <form id="logout-form" action="{{ route('api.logout') }}" method="POST" style="display: none">
            @csrf
        </form>
        <a href="#" class="logout-btn"
            onclick="document.getElementById('logout-form').submit(); return false;">Logout</a>
    </div>

    <!-- Sidebar -->
    <div class="sidebar">
        <a href="{{ route('api.dashboard') }}">Dashboard</a>

        <a href="#">API Logs</a>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <h1>Welcome to the Dashboard</h1>
        <p style="color: red; font-size: 14px;">
            ⚠️ Important: This API key will only be shown once. Please save it securely, as it cannot be retrieved
            again.
            If lost, you will need to generate a new key.
        </p>

        <div class="card">
            <h3><strong>API Key:</strong> <span id="apiKey" style="font-weight: normal;">*********</span></h3>

            <button onclick="generateToken()"
                style="padding: 10px 15px; background: #ffc107; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; margin-top:10px">
                Generate API Token
            </button>

            <button onclick="copyApiKey()"
                style="padding: 10px 15px; background: #28a745; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; margin-top:10px">
                Copy API Key
            </button>
        </div>
    </div>
    <script>
        function generateToken() {
            fetch("{{ route('api.generateToken') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    }
                })
                .then(response => response.json())
                .then(data => {
                    document.getElementById('apiKey').innerText = data.token;
                })
                .catch(error => console.error('Error:', error));
        }

        function copyToClipboard() {
            const apiKey = document.getElementById('apiKey').innerText;

            if (apiKey === '*********') {
                alert('No API token available to copy.');
                return;
            }

            navigator.clipboard.writeText(apiKey).then(() => {}).catch(err => {
                console.error('Failed to copy API key', err);
            });
        }
    </script>

</body>

</html>
