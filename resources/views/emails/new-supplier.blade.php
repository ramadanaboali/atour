<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('mail.new_supplier_notification') }}</title>
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
            border-bottom: 2px solid #007bff;
            padding-bottom: 20px;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 10px;
        }
        .notification-box {
            background-color: #e3f2fd;
            border-left: 4px solid #2196f3;
            padding: 20px;
            margin: 20px 0;
            border-radius: 5px;
        }
        .action-button {
            display: inline-block;
            background-color: #007bff;
            color: white;
            padding: 12px 25px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            margin: 20px 0;
        }
        .supplier-info {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }
        .info-label {
            font-weight: bold;
            color: #495057;
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
            <h1 style="margin: 0; font-size: 24px; color: #333;">{{ __('mail.new_supplier_notification') }}</h1>
        </div>

        <p>{{ __('mail.hello') }} {{ $adminName }},</p>

        <div class="notification-box">
            <h3 style="margin-top: 0; color: #1976d2;">
                <i style="margin-right: 10px;">🔔</i>
                {{ __('mail.new_supplier_registered') }}
            </h3>
            <p>{{ __('mail.new_supplier_message') }}</p>
        </div>

        @if($supplierData)
        <div class="supplier-info">
            <h4 style="margin-top: 0; color: #495057;">{{ __('mail.supplier_details') }}:</h4>
            
            @if(isset($supplierData['name']))
            <div class="info-row">
                <span class="info-label">{{ __('mail.supplier_name') }}:</span>
                <span>{{ $supplierData['name'] }}</span>
            </div>
            @endif
            
            @if(isset($supplierData['email']))
            <div class="info-row">
                <span class="info-label">{{ __('mail.email') }}:</span>
                <span>{{ $supplierData['email'] }}</span>
            </div>
            @endif
            
            @if(isset($supplierData['phone']))
            <div class="info-row">
                <span class="info-label">{{ __('mail.phone') }}:</span>
                <span>{{ $supplierData['phone'] }}</span>
            </div>
            @endif
            
            @if(isset($supplierData['code']))
            <div class="info-row">
                <span class="info-label">{{ __('mail.code') }}:</span>
                <span>{{ $supplierData['code'] }}</span>
            </div>
            @endif
            
            <div class="info-row">
                <span class="info-label">{{ __('mail.registration_date') }}:</span>
                <span>{{ now()->format('Y-m-d H:i:s') }}</span>
            </div>
        </div>
        @endif

        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ route('admin.suppliers.index') }}" class="action-button">
                {{ __('mail.review_supplier') }}
            </a>
        </div>

        <div style="background-color: #fff3cd; border: 1px solid #ffeaa7; color: #856404; padding: 15px; border-radius: 5px; margin: 20px 0;">
            <strong>{{ __('mail.action_required') }}:</strong>
            <ul style="margin: 10px 0; padding-left: 20px;">
                <li>{{ __('mail.review_supplier_documents') }}</li>
                <li>{{ __('mail.verify_supplier_information') }}</li>
                <li>{{ __('mail.approve_or_reject') }}</li>
            </ul>
        </div>

        <p>{{ __('mail.supplier_approval_note') }}</p>

        <div class="footer">
            <p>{{ __('mail.automated_message') }}</p>
            <p style="margin: 0; color: #999; font-size: 12px;">{{ __('mail.copyright') }}</p>
        </div>
    </div>
</body>
</html>
