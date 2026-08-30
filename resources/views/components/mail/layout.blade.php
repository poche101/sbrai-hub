<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Sbrai Solutions</title>
<style>
    body {
        margin: 0;
        padding: 0;
        background: #F5F5F7;
        font-family: 'Helvetica Neue', Arial, sans-serif;
        color: #1A1A1A;
    }
    .wrapper {
        width: 100%;
        padding: 32px 16px;
    }
    .container {
        max-width: 480px;
        margin: 0 auto;
        background: #ffffff;
        border-radius: 16px;
        padding: 40px 32px;
        box-shadow: 0 4px 24px rgba(0, 0, 0, 0.06);
    }
    .brand {
        text-align: center;
        margin-bottom: 24px;
    }
    .brand span {
        font-size: 18px;
        font-weight: 700;
        color: #E8622A;
    }
    h2 {
        font-size: 20px;
        color: #1A1A1A;
        margin: 0 0 16px;
    }
    p {
        font-size: 15px;
        color: #666;
        line-height: 1.6;
        margin: 0 0 16px;
    }
    a {
        color: #E8622A;
    }
    .btn {
        display: inline-block;
        background: #E8622A;
        color: #ffffff !important;
        text-decoration: none;
        padding: 14px 32px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 15px;
        margin: 8px 0 16px;
    }
    .btn-navy {
        background: #1A2B4A;
    }
    .divider {
        height: 1px;
        background: #EEEEEE;
        border: none;
        margin: 24px 0;
    }
    .badge-success {
        display: inline-block;
        background: #E8F8EF;
        color: #27AE60;
        font-size: 13px;
        font-weight: 600;
        padding: 6px 14px;
        border-radius: 999px;
        margin-bottom: 16px;
    }
    .info-box {
        background: #FAFAFA;
        border: 1px solid #EEEEEE;
        border-radius: 12px;
        padding: 16px 20px;
        margin: 16px 0;
    }
    .info-row {
        display: flex;
        justify-content: space-between;
        padding: 8px 0;
        border-bottom: 1px solid #EEEEEE;
    }
    .info-row:last-child {
        border-bottom: none;
    }
    .info-label {
        font-size: 13px;
        color: #999999;
    }
    .info-value {
        font-size: 13px;
        color: #1A1A1A;
        font-weight: 600;
        text-align: right;
    }
    .footer {
        text-align: center;
        font-size: 12px;
        color: #AAAAAA;
        margin-top: 24px;
    }
</style>
</head>
<body>
    <div class="wrapper">
        <div class="container">
            <div class="brand"><span>Sbrai Solutions</span></div>
            {{ $slot }}
        </div>
        <p class="footer">&copy; {{ date('Y') }} Sbrai Solutions Limited &middot; sbraisolutions.com</p>
    </div>
</body>
</html>
