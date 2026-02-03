<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Your Email</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .card {
            background-color: white;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #007bff;
            margin: 0;
            font-size: 24px;
        }
        .content {
            margin-bottom: 30px;
        }
        .content p {
            margin: 10px 0;
        }
        .btn {
            display: inline-block;
            padding: 12px 30px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            text-align: center;
            margin: 20px 0;
            font-weight: bold;
        }
        .btn:hover {
            background-color: #0056b3;
        }
        .footer {
            text-align: center;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            font-size: 12px;
            color: #666;
        }
        .link-text {
            word-break: break-all;
            font-size: 12px;
            color: #666;
            padding: 10px;
            background-color: #f9f9f9;
            border-radius: 4px;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="header">
                <h1>Secure Portal</h1>
                <p>Email Verification</p>
            </div>

            <div class="content">
                <p>Hi {{ $user->name }},</p>

                <p>Thank you for registering with Secure Portal. To complete your registration, please verify your email address by clicking the button below:</p>

                <div style="text-align: center;">
                    <a href="{{ $verifyUrl }}" class="btn">Verify Email Address</a>
                </div>

                <p>Or copy and paste this link in your browser:</p>
                <div class="link-text">
                    {{ $verifyUrl }}
                </div>

                <p>This link will expire in 24 hours.</p>

                <p>If you did not create this account, please ignore this email.</p>
            </div>

            <div class="footer">
                <p>&copy; {{ date('Y') }} Secure Portal. All rights reserved.</p>
            </div>
        </div>
    </div>
</body>
</html>
