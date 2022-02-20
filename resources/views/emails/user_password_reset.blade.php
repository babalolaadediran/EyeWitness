<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Password Reset</title>
</head>
<body>
    <h4>Password Reset Successful</h4>
    <h4>Hello {{ $fullname }},</h4>
    <p>
        Your request to reset your password was successful. <br>        
        Kindy use the password below to login and change your pasword to a more secure one only known to you. <br>
    </p>
    <h4>{{ $password }}</h4>
    <p>
        Feel free to reach out to our support team if you didn't request for a password reset. <br>        
        <b>Support</b>: support@hotspotreporter.com
    </p>
</body>
</html>