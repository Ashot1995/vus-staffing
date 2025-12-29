<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Contact Form Submission</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #000000;
            color: #ffffff;
            padding: 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }
        .content {
            background-color: #f9f9f9;
            padding: 20px;
            border: 1px solid #ddd;
            border-top: none;
            border-radius: 0 0 5px 5px;
        }
        .field {
            margin-bottom: 15px;
        }
        .label {
            font-weight: bold;
            color: #000000;
            display: block;
            margin-bottom: 5px;
        }
        .value {
            background-color: #ffffff;
            padding: 10px;
            border-radius: 4px;
            border: 1px solid #ddd;
        }
        .message-box {
            background-color: #ffffff;
            padding: 15px;
            border-radius: 4px;
            border: 1px solid #ddd;
            white-space: pre-wrap;
        }
        .footer {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            font-size: 12px;
            color: #666;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>New Contact Form Submission</h1>
    </div>
    
    <div class="content">
        <div class="field">
            <span class="label">Type:</span>
            <div class="value">{{ ucfirst($data['type']) }}</div>
        </div>

        <div class="field">
            <span class="label">Name:</span>
            <div class="value">{{ $data['name'] }}</div>
        </div>

        <div class="field">
            <span class="label">Email:</span>
            <div class="value">
                <a href="mailto:{{ $data['email'] }}">{{ $data['email'] }}</a>
            </div>
        </div>

        @if(!empty($data['phone']))
        <div class="field">
            <span class="label">Phone:</span>
            <div class="value">
                <a href="tel:{{ $data['phone'] }}">{{ $data['phone'] }}</a>
            </div>
        </div>
        @endif

        <div class="field">
            <span class="label">Subject:</span>
            <div class="value">{{ $data['subject'] }}</div>
        </div>

        <div class="field">
            <span class="label">Message:</span>
            <div class="message-box">{{ $data['message'] }}</div>
        </div>
    </div>

    <div class="footer">
        <p>This email was sent from the VUS Bemanning contact form.</p>
        <p>You can reply directly to this email to contact the sender.</p>
    </div>
</body>
</html>

