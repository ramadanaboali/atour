<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('security.2fa_title') }}</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 10px;
        }
        .code-container {
            background-color: #f8f9fa;
            border: 2px dashed #007bff;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            margin: 20px 0;
        }
        .verification-code {
            font-size: 32px;
            font-weight: bold;
            color: #007bff;
            letter-spacing: 5px;
            margin: 10px 0;
        }
        .warning {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            color: #856404;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            text-align: center;
            color: #666;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">{{ config('app.name', 'Travel Agency') }}</div>
            <h1 style="margin: 0; font-size: 24px; color: #333; margin-bottom: 20px;">{{ __('security.2fa_title') }}</h1>
            <p style="margin: 0; color: #666; line-height: 1.6; margin-bottom: 20px;">{{ __('security.hello') }} {{ $user->name }},</p>
        </div>

        <p>{{ __('security.2fa_email_message') }}</p>

        <div class="code-container">
            <p><strong>{{ __('security.2fa_your_code') }}:</strong></p>
            <div class="verification-code">{{ $code }}</div>
            <p><small>{{ __('security.2fa_expires_at') }} {{ $expiresAt->format('Y-m-d H:i:s') }}</small></p>
        </div>

        <div class="warning">
            <strong>{{ __('security.security_notice') }}:</strong>
            <ul style="margin: 10px 0; padding-left: 20px;">
                <li>{{ __('security.2fa_code_expires') }}</li>
                <li>{{ __('security.2fa_never_share') }}</li>
                <li>{{ __('security.2fa_contact_support') }}</li>
            </ul>
            <p style="margin: 0; color: #666; line-height: 1.6; margin-bottom: 20px;">{{ __('security.2fa_email_disclaimer') }}</p>
        </div>

        <p>{{ __('security.2fa_trouble_login') }}</p>

        <div class="footer">
            <p>{{ __('security.automated_message') }}</p>
            <p style="margin: 0; color: #999; font-size: 12px; text-align: center;">{{ __('security.copyright') }}</p>
        </div>
    </div>
</body>
</html>
