<!DOCTYPE html>
<html>
<head>
    <title>Welcome to Secure Portal</title>
</head>
<body>
    <h1>Welcome, {{ $user->name }}!</h1>
    <p>Thank you for registering at Secure Portal.</p>
    <p>Your email: {{ $user->email }}</p>
    <p>You can now log in and access your dashboard.</p>
    <p>Best regards,<br>Secure Portal Team</p>
</body>
</html>