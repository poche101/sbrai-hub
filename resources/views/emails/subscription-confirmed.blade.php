<x-mail::layout>

<span class="badge-success">✓ Payment Successful</span>

<h2>You're all set, {{ $name }}! 🎉</h2>

<p>
  Great news — your <strong>Sbrai Solutions Annual Subscription</strong> has been activated successfully.
  You can now post unlimited listings and start reaching thousands of buyers across Nigeria.
</p>

<div class="info-box">
  <div class="info-row">
    <span class="info-label">Amount Paid</span>
    <span class="info-value">
      @if($method === 'espees')
        {{ $amount }} Espees
      @else
        ₦{{ number_format($amount) }}
      @endif
    </span>
  </div>
  <div class="info-row">
    <span class="info-label">Payment Method</span>
    <span class="info-value">{{ $method === 'espees' ? 'Espees Gateway' : 'Stripe (Card)' }}</span>
  </div>
  <div class="info-row">
    <span class="info-label">Subscription Start</span>
    <span class="info-value">{{ $startDate }}</span>
  </div>
  <div class="info-row">
    <span class="info-label">Renews On</span>
    <span class="info-value">{{ $endDate }}</span>
  </div>
  <div class="info-row">
    <span class="info-label">Transaction ID</span>
    <span class="info-value">{{ $transactionId }}</span>
  </div>
</div>

<p>As a welcome gift, we've also added a <strong>₦5,000 promotional voucher</strong> to your account — redeemable when our promotion feature launches.</p>

<div style="text-align:center">
  <a href="https://app.sbraisolutions.com/dashboard" class="btn">Go to My Dashboard</a>
</div>

<div class="divider"></div>

<p style="font-size:13px; color:#999;">
  Need help? Reply to this email or visit our Help &amp; Support page in the app. We're here for you.
</p>

</x-mail::layout>
