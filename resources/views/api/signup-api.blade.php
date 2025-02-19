<!DOCTYPE html>
<!-- Coding By CodingNepal - codingnepalweb.com -->
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/gwa-favicon-32x32.png') }}" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Developers API</title>
    <!---Custom CSS File--->
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
    }

    .container {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        max-width: 430px;
        width: 100%;
        background: #fff;
        border-radius: 7px;
        box-shadow: 0 5px 10px rgba(0, 0, 0, 0.3);
    }

    .container .registration {
        display: block;
    }


    #check {
        display: none;
    }

    .container .form {
        padding: 2rem;
    }

    .form header {
        font-size: 2rem;
        font-weight: 500;
        text-align: center;
        margin-bottom: 1.5rem;
    }

    .form input {
        height: 60px;
        width: 100%;
        padding: 0 15px;
        font-size: 17px;
        margin-bottom: 1.3rem;
        border: 1px solid #ffc107;
        border-radius: 6px;
        outline: none;
    }

    .form input:focus {
        box-shadow: 0 1px 0 rgba(0, 0, 0, 0.2);
    }

    .form a {
        font-size: 16px;
        color: #ffc107;
        text-decoration: none;
    }

    .form a:hover {
        text-decoration: underline;
    }

    .form input.button {
        color: #fff;
        background: #ffc107;
        font-size: 1.2rem;
        font-weight: 500;
        letter-spacing: 1px;
        margin-top: 1.7rem;
        cursor: pointer;
        transition: 0.4s;
    }

    .form input.button:hover {
        background: #f0b60b;
    }

    .signup {
        font-size: 17px;
        text-align: center;
    }

    .signup label {
        color: #ffc107;
        cursor: pointer;
    }

    .signup label:hover {
        text-decoration: underline;
    }

    .error-container {
        background-color: #ffebee;
        /* Light red background */
        color: #d32f2f;
        /* Dark red text */
        border-left: 5px solid #d32f2f;
        /* Accent border */
        padding: 12px;
        margin-top: 15px;
        margin-bottom: 10px;
        border-radius: 5px;
        font-size: 14px;
    }

    .error-list {
        list-style-type: none;
        padding: 0;
        margin: 0;
    }

    .error-list li {
        margin-bottom: 5px;
        font-weight: bold;
    }
</style>

<body>
    <div class="container">
        <input type="checkbox" id="check">

        <div class="registration form">
            <img class="logo" src="{{ asset('images/greatwall-logo.png') }}" alt="Logo"
                style="width: 150px; height: auto; display: block; margin: 0 auto;">
            <header>Signup</header>
            @if ($errors->any())
                <div class="error-container">
                    <ul class="error-list">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('api.store') }}" method="POST">
                @csrf
                <input type="text" name="email" placeholder="Enter your email" required>
                <input type="password" name="password" placeholder="Create a password" required>
                <input type="password" name="password_confirmation" placeholder="Confirm your password" required>
                <input type="submit" class="button" value="Signup">
            </form>

            <div class="signup">
                <span class="signup">Already have an account?
                    <label for="check"><a href="{{ route('api.login') }}">Login</a></label>
                </span>
            </div>
        </div>
    </div>
</body>

</html>
