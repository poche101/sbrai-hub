<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Account Confirmation — Sbrai Solutions</title>
<style>
  body { margin:0; font-family:'Helvetica Neue', Arial, sans-serif; background:#F5F5F7; display:flex; align-items:center; justify-content:center; min-height:100vh; }
  .card { background:#fff; border-radius:20px; padding:48px 40px; max-width:420px; text-align:center; box-shadow:0 4px 24px rgba(0,0,0,0.08); }
  .icon { width:72px; height:72px; border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 24px; font-size:36px; }
  .icon.success { background:#E8F8EF; color:#27AE60; }
  .icon.error { background:#FFEBEE; color:#E74C3C; }
  h1 { font-size:22px; color:#1A1A1A; margin:0 0 12px; }
  p { font-size:15px; color:#666; line-height:1.6; margin:0 0 24px; }
  .btn { display:inline-block; background:#E8622A; color:#fff; text-decoration:none; padding:14px 32px; border-radius:10px; font-weight:600; font-size:15px; }
</style>
</head>
<body>
  <div class="card">
    <div class="icon {{ $success ? 'success' : 'error' }}">{{ $success ? '✓' : '✕' }}</div>
    <h1>{{ $success ? 'Account Confirmed!' : 'Confirmation Failed' }}</h1>
    <p>{{ $message }}</p>
    <a href="https://app.sbraisolutions.com" class="btn">Open Sbrai App</a>
  </div>
</body>
</html>
