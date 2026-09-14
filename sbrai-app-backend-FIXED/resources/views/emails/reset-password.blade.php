<x-mail::layout>

<h2>Reset Your Password</h2>

<p>
  Hi {{ $name }}, we received a request to reset the password for your
  Sbrai {{ ucfirst($role) }} account. Click the button below to choose a new password.
</p>

<div style="text-align:center">
  <a href="{{ $resetUrl }}" class="btn btn-navy">Reset My Password</a>
</div>

<p style="font-size:13px; color:#999;">
  Or copy and paste this link into your browser:<br>
  <span style="color:#1A2B4A; word-break:break-all;">{{ $resetUrl }}</span>
</p>

<div class="divider"></div>

<p style="font-size:13px; color:#999;">
  This link expires in 60 minutes. If you didn't request a password reset,
  no action is needed — your account is safe.
</p>

</x-mail::layout>
