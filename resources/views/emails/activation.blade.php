<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>{{ __('emails.activation.title') }}</title>
</head>
<body style="font-family: Arial, sans-serif; background-color:#f0f8ff; margin:0; padding:0;">

    <div style="max-width:600px; margin:40px auto; background:#fff; border:1px solid #ccc; padding:20px; text-align:center;">

        <!-- Logo -->
        <div style="margin-bottom:20px;">
            <img src="{{ asset('atour.jpg') }}" alt="{{ __('emails.activation.logo_alt') }}" style="max-height:80px;">
        </div>

        <!-- Message -->
        <p style="font-size:16px; margin:0;">{{ __('emails.activation.greeting_ar', ['name' => $userName]) }}</p>
        <p style="font-size:16px; margin:5px 0;">{{ __('emails.activation.greeting_en', ['name' => $userName]) }}</p>
            <span>{{__('emails.activation.thank_you') }}</span>
            <br>
            <strong>جولة (ATOUR)</strong>

        <p style="font-size:15px; color:#333;">
            {{ __('emails.activation.use_code') }}
        </p>

        <!-- Activation Code -->
        <div style="background:#f8f8f8; border:1px dashed #345c76; padding:10px; margin:15px 0;">
            <strong style="font-size:18px; color:#345c76;">
                {!! nl2br(e($activationCode)) !!}
            </strong>
        </div>

        <p style="font-size:14px; color:#777;">
            {{ __('emails.activation.ignore') }}
        </p>

        <p style="font-size:15px; margin-top:20px;">
            {{ __('emails.activation.wish') }}
        </p>

        <p style="font-size:14px; font-weight:bold; margin-top:10px;">
            {{ __('emails.activation.team') }}
            <br>(ATOUR Team)

        </p>
    </div>

</body>
</html>
