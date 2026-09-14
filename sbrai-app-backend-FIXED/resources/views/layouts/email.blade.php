<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ $subject ?? 'Sbrai Solutions' }}</title>
<style>
  body { margin:0; padding:0; background-color:#F5F5F7; font-family:'Helvetica Neue', Arial, sans-serif; }
  .wrapper { width:100%; padding:32px 0; background-color:#F5F5F7; }
  .container { max-width:560px; margin:0 auto; background:#ffffff; border-radius:16px; overflow:hidden; box-shadow:0 2px 12px rgba(0,0,0,0.06); }
  .header { background:linear-gradient(135deg,#E8622A,#C4501F); padding:32px 40px; text-align:center; }
  .header img { width:56px; height:56px; border-radius:12px; }
  .header h1 { color:#ffffff; font-size:20px; margin:12px 0 0; font-weight:700; }
  .body { padding:40px; color:#1A1A1A; }
  .body h2 { font-size:22px; margin:0 0 16px; color:#1A1A1A; }
  .body p { font-size:15px; line-height:1.6; color:#444444; margin:0 0 16px; }
  .btn { display:inline-block; background-color:#E8622A; color:#ffffff !important; text-decoration:none; padding:14px 32px; border-radius:10px; font-weight:600; font-size:15px; margin:8px 0 24px; }
  .btn-navy { background-color:#1A2B4A; }
  .info-box { background-color:#F8F8F8; border-radius:12px; padding:20px 24px; margin:24px 0; border:1px solid #E5E5E5; }
  .info-row { display:flex; justify-content:space-between; padding:6px 0; font-size:14px; }
  .info-label { color:#999999; }
  .info-value { color:#1A1A1A; font-weight:600; }
  .footer { padding:24px 40px; text-align:center; background-color:#FAFAFA; border-top:1px solid #EEEEEE; }
  .footer p { font-size:12px; color:#999999; margin:4px 0; }
  .divider { height:1px; background-color:#EEEEEE; margin:24px 0; }
  .badge-success { display:inline-block; background-color:#E8F8EF; color:#27AE60; font-size:12px; font-weight:700; padding:4px 12px; border-radius:99px; margin-bottom:16px; }
</style>
</head>
<body>
<div class="wrapper">
  <div class="container">
    <div class="header">
      <img src="https://sbraisolutions.com/logo-white.png" alt="Sbrai Solutions" onerror="this.style.display='none'">
      <h1>Sbrai Solutions</h1>
    </div>
    <div class="body">
      {{ $slot }}
    </div>
    <div class="footer">
      <p><strong>Sbrai Solutions Limited</strong></p>
      <p>Building Materials · Furniture · Professional Services</p>
      <p>&copy; {{ date('Y') }} Sbrai Solutions. All rights reserved.</p>
      <p>If you didn't request this email, you can safely ignore it.</p>
    </div>
  </div>
</div>
</body>
</html>
