<!DOCTYPE html>
<html>

<head>
    <title>Email Verification</title>
</head>

<body>
    <p>Hello {{ $vendor->full_name }},</p>

    <p>Thank you for registering! Please click the button below to verify your email address:</p>

    <a href="{{ $verificationUrl }}"
        style="padding: 10px 20px; background-color: #28a745; color: white; text-decoration: none; border-radius: 5px;">
        Verify Your Email
    </a>

    <p>If you did not request this verification, please ignore this email.</p>
</body>

</html>
