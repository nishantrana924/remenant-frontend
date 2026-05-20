<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <style>
        body { font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; margin: 0; padding: 0; background-color: #FDFCFB; }
        .wrapper { width: 100%; max-width: 600px; margin: 0 auto; background-color: #ffffff; }
        .header { padding: 40px; text-align: center; background-color: #0f172a; }
        .logo { font-size: 24px; font-weight: 900; color: #ffffff; letter-spacing: -1px; text-transform: uppercase; font-style: italic; }
        .content { padding: 40px; }
        .h1 { font-size: 28px; font-weight: 900; color: #0f172a; margin-bottom: 20px; text-transform: uppercase; letter-spacing: -0.5px; }
        .p { font-size: 16px; color: #475569; line-height: 1.6; margin-bottom: 20px; }
        .order-summary { background-color: #f8fafc; border-radius: 24px; padding: 30px; margin: 30px 0; }
        .item { display: flex; align-items: center; padding: 15px 0; border-bottom: 1px solid #e2e8f0; }
        .item:last-child { border-bottom: 0; }
        .item-info { flex: 1; }
        .item-name { font-size: 14px; font-weight: 800; color: #0f172a; text-transform: uppercase; }
        .item-qty { font-size: 11px; font-weight: 700; color: #94a3b8; text-transform: uppercase; margin-top: 4px; }
        .item-price { font-size: 14px; font-weight: 800; color: #0f172a; }
        .totals { margin-top: 20px; }
        .total-row { display: flex; justify-content: space-between; margin-bottom: 10px; }
        .total-label { font-size: 12px; font-weight: 700; color: #94a3b8; text-transform: uppercase; }
        .total-value { font-size: 12px; font-weight: 800; color: #0f172a; }
        .grand-total { font-size: 20px; font-weight: 900; color: #ff6b00; margin-top: 10px; border-top: 2px solid #e2e8f0; pt: 15px; }
        .footer { padding: 40px; background-color: #f8fafc; text-align: center; }
        .footer-text { font-size: 10px; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 2px; }
        .btn { display: inline-block; padding: 18px 40px; background-color: #ff6b00; color: #ffffff; text-decoration: none; border-radius: 16px; font-size: 12px; font-weight: 900; text-transform: uppercase; letter-spacing: 2px; margin-top: 20px; box-shadow: 0 10px 20px rgba(255, 107, 0, 0.2); }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="header">
            <div class="logo">REMENANT</div>
        </div>
        <div class="content">
            @yield('content')
        </div>
        <div class="footer">
            <p class="footer-text">Where Science Meets Engineering</p>
            <p style="margin-top: 10px; font-size: 9px; color: #cbd5e1;">© {{ date('Y') }} REMENANT HEALTH. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
