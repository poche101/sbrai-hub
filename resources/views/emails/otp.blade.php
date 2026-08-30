<x-mail::layout>

<h2>Verify your email</h2>

<p>
  Hi {{ $name }}, use the code below to verify your email address on Sbrai Solutions.
</p>

<div style="text-align:center; margin: 24px 0;">
  <span style="display:inline-block; font-size:28px; font-weight:bold; letter-spacing:6px; color:#E8622A;">
    {{ $otp }}
  </span>
</div>

<p style="font-size:13px; color:#999;">
  This code expires in 10 minutes. If you didn't request this, you can safely ignore this email.
</p>

</x-mail::layout>
