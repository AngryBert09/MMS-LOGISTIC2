<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <style>
        /* General Reset */
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            color: #333;
        }

        a {
            text-decoration: none;
        }

        /* Email Container */
        .email-container {
            max-width: 600px;
            margin: 40px auto;
            background: #ffffff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        }

        /* Header */
        .email-header {
            background: linear-gradient(135deg, #333, #333333);
            color: #fff;
            padding: 20px;
            text-align: center;
        }

        .email-header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: bold;
        }

        /* Body */
        .email-body {
            padding: 30px 20px;
            text-align: center;
        }

        .email-body h2 {
            font-size: 20px;
            font-weight: bold;
            color: #333;
            margin-bottom: 15px;
        }

        .email-body p {
            font-size: 16px;
            line-height: 1.6;
            color: #555;
            margin-bottom: 25px;
        }

        /* Button */
        .reset-btn {
            display: inline-block;
            padding: 14px 30px;
            background: #FFC107;
            color: #333;
            font-size: 16px;
            font-weight: bold;
            border-radius: 5px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: background 0.3s ease, transform 0.2s ease;
        }

        .reset-btn:hover {
            background: #FFA000;
            transform: translateY(-2px);
        }

        /* Footer */
        .email-footer {
            background-color: #333;
            color: #fff;
            padding: 15px;
            text-align: center;
            font-size: 14px;
        }

        .email-footer p {
            margin: 0;
        }

        .email-footer a {
            color: #FFC107;
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="email-container">
        <!-- Header -->
        <div class="email-header">
            <h1>Password Reset Request</h1>
        </div>

        <!-- Body -->
        <div class="email-body">
            <h2>Hello,</h2>
            <p>
                We received a request to reset your password. To proceed, click the button below:
            </p>
            <a href="{{ $url }}" class="reset-btn">Reset Password</a>
            <p>If you did not request this, you can safely ignore this email.</p>
        </div>

        <!-- Footer -->
        <div class="email-footer">
            <p>&copy; {{ date('Y') }} Great Wall Arts. All rights reserved.</p>
            <p>Need help? Contact us at <a href="mailto:greatwallarts@gmail.com">greatwallarts@gmail.com
                </a></p>
        </div>
    </div>
</body>

</html>
