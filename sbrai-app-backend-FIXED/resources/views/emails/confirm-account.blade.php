<x-mail::layout>

<h2>Welcome to Sbrai, {{ $name }}! 👋</h2>

<p>
  Thanks for subscribing to Sbrai Solutions. Before you can fully access your account,
  please confirm your email address by clicking the button below.
</p>

<div style="text-align:center">
  <a href="{{ $confirmUrl }}" class="btn">Confirm My Account</a>
</div>

<p style="font-size:13px; color:#999;">
  Or copy and paste this link into your browser:<br>
  <span style="color:#E8622A; word-break:break-all;">{{ $confirmUrl }}</span>
</p>

<div class="divider"></div>

<p>
  Once confirmed, you'll have full access to post listings, chat with buyers,
  and manage your vendor dashboard.
</p>

<p style="font-size:13px; color:#999;">
  This link expires in 24 hours. If you didn't create this account, please ignore this email.
</p>

</x-mail::layout>
