<!DOCTYPE html>
<html>
<head>
    <title>New Contact Form Submission</title>
</head>
<body style="font-family: Arial, sans-serif; color: #333; line-height: 1.6;">
    <div style="max-w-2xl mx-auto p-4">
        <h2 style="color: #074D3D;">New Contact Submission</h2>
        <p>A new contact form has been submitted on the Remenant Health website.</p>
        
        <table style="width: 100%; border-collapse: collapse; margin-top: 20px;">
            <tr>
                <td style="padding: 8px; border-bottom: 1px solid #eee;"><strong>Name:</strong></td>
                <td style="padding: 8px; border-bottom: 1px solid #eee;">{{ $contactMessage->name }}</td>
            </tr>
            <tr>
                <td style="padding: 8px; border-bottom: 1px solid #eee;"><strong>Email:</strong></td>
                <td style="padding: 8px; border-bottom: 1px solid #eee;"><a href="mailto:{{ $contactMessage->email }}">{{ $contactMessage->email }}</a></td>
            </tr>
            <tr>
                <td style="padding: 8px; border-bottom: 1px solid #eee;"><strong>Phone:</strong></td>
                <td style="padding: 8px; border-bottom: 1px solid #eee;">{{ $contactMessage->phone ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td style="padding: 8px; border-bottom: 1px solid #eee;"><strong>Subject:</strong></td>
                <td style="padding: 8px; border-bottom: 1px solid #eee;">{{ $contactMessage->subject ?? 'N/A' }}</td>
            </tr>
        </table>

        <h3 style="margin-top: 30px; color: #074D3D;">Message Content:</h3>
        <div style="background-color: #f9f9f9; padding: 15px; border-left: 4px solid #FF6B00; white-space: pre-wrap;">{{ $contactMessage->message }}</div>
        
        <p style="margin-top: 30px;"><a href="{{ route('admin.contact-messages.show', $contactMessage->id) }}" style="display: inline-block; padding: 10px 20px; background-color: #074D3D; color: #fff; text-decoration: none; border-radius: 5px;">View in Admin Panel</a></p>
    </div>
</body>
</html>
