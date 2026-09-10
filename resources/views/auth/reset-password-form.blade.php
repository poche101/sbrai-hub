<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Reset Password — Sbrai Solutions</title>
<style>
  body { margin:0; font-family:'Helvetica Neue', Arial, sans-serif; background:#F5F5F7; display:flex; align-items:center; justify-content:center; min-height:100vh; padding:16px; }
  .card { background:#fff; border-radius:20px; padding:40px; max-width:400px; width:100%; box-shadow:0 4px 24px rgba(0,0,0,0.08); }
  h1 { font-size:20px; color:#1A1A1A; margin:0 0 8px; }
  p { font-size:14px; color:#666; margin:0 0 24px; }
  label { display:block; font-size:13px; font-weight:600; color:#333; margin-bottom:6px; }
  input { width:100%; padding:12px 14px; border-radius:10px; border:1px solid #E5E5E5; background:#F8F8F8; font-size:14px; margin-bottom:16px; box-sizing:border-box; }
  button { width:100%; padding:14px; border-radius:10px; border:none; background:#E8622A; color:#fff; font-weight:600; font-size:15px; cursor:pointer; }
  .msg { padding:12px; border-radius:10px; font-size:13px; margin-bottom:16px; }
  .msg.error { background:#FFEBEE; color:#E74C3C; }
  .msg.success { background:#E8F8EF; color:#27AE60; }
</style>
</head>
<body>
  <div class="card">
    <h1>Reset Your Password</h1>
    <p>Enter a new password for {{ $email }}.</p>

    @if(session('status'))
    <div class="mb-4 text-sm text-green-700 bg-green-50 border border-green-200 rounded-xl p-3 text-center">
        {{ session('status') }}
    </div>
@endif
    @if($errors->any())
      <div class="msg error">{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ url('/api/v1/auth/reset-password-form') }}">
      @csrf
      <input type="hidden" name="email" value="{{ $email }}">
      <input type="hidden" name="token" value="{{ $token }}">

      <label>New Password</label>
      <input type="password" name="password" required minlength="6" placeholder="Minimum 6 characters">

      <label>Confirm Password</label>
      <input type="password" name="password_confirmation" required minlength="6" placeholder="Re-enter password">

      <button type="submit">Reset Password</button>
    </form>
  </div>
</body>
</html>
