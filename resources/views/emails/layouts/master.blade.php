<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@800;900&family=Open+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Open Sans', 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; margin: 0; padding: 0; background-color: #F3F4F6; color: #111827; }
        .wrapper { width: 100%; max-width: 600px; margin: 20px auto; background-color: #ffffff; border-radius: 24px; overflow: hidden; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05); }
        .header { padding: 40px; text-align: center; background-color: #ea5f06; background: linear-gradient(135deg, #ea5f06, #FF7A1A); border-bottom: 4px solid #1A1A1A; }
        .logo-img { height: 40px; width: auto; max-width: 100%; display: inline-block; vertical-align: middle; }
        .content { padding: 40px; }
        .h1 { font-family: 'Montserrat', 'Inter', sans-serif; font-size: 26px; font-weight: 900; color: #1A1A1A; margin-top: 0; margin-bottom: 20px; text-transform: uppercase; letter-spacing: -0.5px; }
        .p { font-size: 15px; color: #374151; line-height: 1.6; margin-bottom: 20px; }
        .order-summary { background-color: #F8F9FB; border-radius: 20px; padding: 25px; margin: 30px 0; border: 1px solid #E5E7EB; }
        .item { display: flex; align-items: center; padding: 15px 0; border-bottom: 1px solid #E5E7EB; }
        .item:last-child { border-bottom: 0; }
        .item-info { flex: 1; }
        .item-name { font-size: 13px; font-weight: 800; color: #111827; text-transform: uppercase; }
        .item-qty { font-size: 11px; font-weight: 700; color: #6B7280; text-transform: uppercase; margin-top: 4px; }
        .item-price { font-size: 13px; font-weight: 800; color: #111827; }
        .totals { margin-top: 20px; }
        .total-row { display: flex; justify-content: space-between; margin-bottom: 10px; }
        .total-label { font-size: 11px; font-weight: 700; color: #6B7280; text-transform: uppercase; letter-spacing: 0.5px; }
        .total-value { font-size: 12px; font-weight: 800; color: #111827; }
        .grand-total { font-size: 18px; font-weight: 900; color: #ea5f06; margin-top: 15px; border-top: 2px solid #E5E7EB; padding-top: 15px; }
        .footer { padding: 40px; background-color: #1A1A1A; text-align: center; color: #ffffff; }
        .footer-text { font-family: 'Montserrat', sans-serif; font-size: 10px; font-weight: 900; color: #ea5f06; text-transform: uppercase; letter-spacing: 2px; margin: 0; }
        .btn { display: inline-block; padding: 16px 35px; background-color: #ea5f06; background: linear-gradient(135deg, #ea5f06, #FF7A1A); color: #ffffff !important; text-decoration: none; border-radius: 50px; font-size: 11px; font-weight: 900; text-transform: uppercase; letter-spacing: 1.5px; margin-top: 20px; box-shadow: 0 6px 15px rgba(234, 95, 6, 0.25); text-align: center; border-bottom: 3px solid #cf5305; transition: all 0.3s ease; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="header">
            <a href="{{ config('app.url') }}" style="text-decoration: none; display: inline-block;">
                <img src="{{ \App\Helpers\ImageHelper::getUrl('logo/remenant-health-logo.png', 'images') }}" alt="REMENANT HEALTH" class="logo-img">
            </a>
        </div>
        <div class="content">
            @yield('content')
        </div>
        <div class="footer">
            <p class="footer-text">Where Science Meets Engineering</p>
            <p style="margin-top: 15px; font-size: 9px; color: #9CA3AF; letter-spacing: 0.5px; text-transform: uppercase; font-weight: 600; margin-bottom: 0;">© {{ date('Y') }} REMENANT HEALTH. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
