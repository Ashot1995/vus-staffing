<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Job Application</title>
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
        <h1>New Job Application</h1>
    </div>

    <div class="content">
        <div class="field">
            <span class="label">Position:</span>
            <div class="value">
                @if($application->is_spontaneous)
                    Spontaneous Application
                @else
                    {{ $application->job?->title ?? '—' }}
                @endif
            </div>
        </div>

        <div class="field">
            <span class="label">Applicant:</span>
            <div class="value">{{ $application->first_name }} {{ $application->surname }}</div>
        </div>

        @if($application->user?->email)
        <div class="field">
            <span class="label">Email:</span>
            <div class="value">
                <a href="mailto:{{ $application->user->email }}">{{ $application->user->email }}</a>
            </div>
        </div>
        @endif

        @if($application->phone)
        <div class="field">
            <span class="label">Phone:</span>
            <div class="value">
                <a href="tel:{{ $application->phone }}">{{ $application->phone }}</a>
            </div>
        </div>
        @endif

        @if($application->address)
        <div class="field">
            <span class="label">Address:</span>
            <div class="value">{{ $application->address }}</div>
        </div>
        @endif

        @if($application->start_date)
        <div class="field">
            <span class="label">Available from:</span>
            <div class="value">{{ \Carbon\Carbon::parse($application->start_date)->format('Y-m-d') }}</div>
        </div>
        @endif

        @if($application->cover_letter)
        <div class="field">
            <span class="label">Cover Letter:</span>
            <div class="message-box">{{ $application->cover_letter }}</div>
        </div>
        @endif

        @if($application->additional_information)
        <div class="field">
            <span class="label">Additional Information:</span>
            <div class="message-box">{{ $application->additional_information }}</div>
        </div>
        @endif
    </div>

    <div class="footer">
        <p>This email was sent from the VUS Bemanning job application form.</p>
        <p>Log in to the admin panel to view the full application and download attached files.</p>
    </div>
</body>
</html>
