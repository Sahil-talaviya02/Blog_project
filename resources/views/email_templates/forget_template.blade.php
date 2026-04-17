<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>

    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #f4f6f8;
            font-family: Arial, sans-serif;
        }

        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background: #ffffff;
            border-radius: 8px;
            overflow: hidden;
        }

        .email-header {
            background-color: #4CAF50;
            color: #ffffff;
            text-align: center;
            padding: 20px;
        }

        .email-header h1 {
            margin: 0;
            font-size: 24px;
        }

        .email-body {
            padding: 25px;
            color: #333333;
        }

        .email-body h1 {
            font-size: 20px;
            margin-bottom: 15px;
        }

        .email-body p {
            font-size: 15px;
            line-height: 1.6;
            margin: 10px 0;
        }

        .reset-button {
            display: inline-block;
            margin: 20px 0;
            padding: 12px 20px;
            background-color: #4CAF50;
            color: #ffffff !important;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
        }

        .email-footer {
            background-color: #f1f1f1;
            text-align: center;
            padding: 15px;
            font-size: 13px;
            color: #777777;
        }

        /* Mobile Responsive */
        @media only screen and (max-width: 600px) {
            .email-container {
                width: 100% !important;
                margin: 0 !important;
                border-radius: 0;
            }

            .email-body {
                padding: 15px;
            }

            .email-header h1 {
                font-size: 20px;
            }

            .email-body h1 {
                font-size: 18px;
            }

            .reset-button {
                display: block;
                width: 100%;
                text-align: center;
            }
        }
    </style>

</head>

<body>

    <div class="email-container">

        <!-- Header -->
        <div class="email-header">
            <h1>Reset Your Password</h1>
        </div>

        <!-- Body -->
        <div class="email-body">
            <h1>Hello, {{ $user->name }}</h1>

            <p>You requested to reset your password. Click the button below to proceed.</p>

            <a href="{{ $actionLink }}" target="_blank" class="reset-button">
                Reset Password
            </a>

            <p>This link is valid for 15 minutes.</p>

            <p>If you did not request a password reset, please ignore this email or contact support.</p>

            <p>Thank you,<br>
                The {{ env('APP_NAME') }} Team</p>
        </div>

        <!-- Footer -->
        <div class="email-footer">
            <p>&copy; {{ date('Y') }} {{ env('APP_NAME') }}. All rights reserved.</p>
        </div>

    </div>

</body>

</html>
