<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Changed</title>

    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #f4f6f8;
            font-family: Arial, sans-serif;
        }

        .container {
            max-width: 600px;
            margin: 20px auto;
            background: #ffffff;
            border-radius: 8px;
            overflow: hidden;
        }

        .header {
            background: #4CAF50;
            color: #fff;
            text-align: center;
            padding: 20px;
        }

        .body {
            padding: 25px;
            color: #333;
        }

        .body h2 {
            margin-top: 0;
        }

        .info-box {
            background: #f1f1f1;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }

        .info-box p {
            margin: 5px 0;
            font-size: 14px;
        }

        .footer {
            text-align: center;
            padding: 15px;
            font-size: 12px;
            color: #777;
            background: #f1f1f1;
        }

        @media (max-width: 600px) {
            .container {
                width: 100%;
                margin: 0;
                border-radius: 0;
            }

            .body {
                padding: 15px;
            }
        }
    </style>
</head>

<body>

    <div class="container">

        <!-- Header -->
        <div class="header">
            <h1>Password Changed Successfully</h1>
        </div>

        <!-- Body -->
        <div class="body">
            <h2>Hello, {{ $user->name }}</h2>

            <p>Your password has been successfully updated. Here are your login details:</p>

            <div class="info-box">
                <p><strong>Email:</strong> {{ $user->email }}</p>
                <p><strong>Password:</strong> {{ $new_password }}</p>
            </div>

            <p>If you did not perform this action, please contact support immediately.</p>

            <p>Thanks,<br>
                {{ env('APP_NAME') }} Team
            </p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>&copy; {{ date('Y') }} {{ env('APP_NAME') }}. All rights reserved.</p>
        </div>

    </div>

</body>

</html>