<!DOCTYPE html>
<html>
<head>
    <title>Thank You for Contacting Us</title>
</head>
<body style="font-family: Arial, sans-serif; color: #333; line-height: 1.6;">
    <div style="max-w-2xl mx-auto p-4">
        <h2 style="color: #074D3D;">Hello {{ $contactMessage->name }},</h2>
        <p>Thank you for reaching out to Remenant Health!</p>
        <p>We have received your message regarding "<strong>{{ $contactMessage->subject ?? 'General Inquiry' }}</strong>" and our team is currently reviewing it.</p>
        <p>We aim to respond to all inquiries within 24-48 hours. If your request is urgent, please feel free to call our support line.</p>
        <br>
        <p>Here is a copy of your message for your records:</p>
        <div style="background-color: #f9f9f9; padding: 15px; border-left: 4px solid #FF6B00; margin-bottom: 20px;">
            <em>{{ $contactMessage->message }}</em>
        </div>
        <p>Best regards,<br>The Remenant Health Team</p>
    </div>
</body>
</html>
