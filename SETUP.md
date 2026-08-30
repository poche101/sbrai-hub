# Complete site recovery — every file from this whole build, in one zip

This replaces every individual round-by-round zip from this entire
project (60+ files) with a single, complete, verified copy of the
current state — including the Espees rebuild. Use this instead of
trying to re-apply the smaller zips one at a time.

## Setup — one shot

```bash
cd ~/Downloads
unzip -o full-recovery.zip -d full-recovery
cp -r full-recovery/. ~/Downloads/sbrai-app-backend-FIXED/
```

⚠️ **This will overwrite `.env.example`, not `.env`** — your real `.env`
with live keys is untouched (deliberately never included in any zip
from this project). If you'd previously added real values for
`AGORA_APP_ID`, `GOOGLE_TRANSLATE_KEY`, etc. directly into `.env`,
those are safe. Compare the two afterward if unsure:
```bash
diff .env .env.example
```

Then:
```bash
cd ~/Downloads/sbrai-app-backend-FIXED
composer require taylanunutmaz/agora-token-builder   # if not already installed
php artisan view:clear
npm run build
```

## What's in here (everything, end to end)

### Every page
`/`, `/browse`, `/listing/{id}`, `/auth`, `/post-ad`, `/messages`,
`/kyc`, `/pricing`, `/favourites`, `/profile`, `/settings`, `/terms`,
`/privacy` — controllers, Blade views, and JS for all of them.

### Espees — the real, corrected integration
`EspeesService.php` (rebuilt against Espees' actual documented API —
hosted checkout, not the old fictional wallet+PIN debit),
`SubscriptionController.php` (`espeesCheckout()`/`espeesVerify()`
replacing the old `payWithEspees()`), `routes/api.php`
(`GET espees/checkout` + `POST espees/verify`), `config/services.php`
(correct `base_url`/`payment_portal_url`), and the `/pricing` page's
Espees UI (checkout button + verify button, no wallet/PIN form).

### The mail bug fix
`app/Providers/AppServiceProvider.php` +
`resources/views/components/mail/layout.blade.php` — fixes
`<x-mail::layout>`, which was never registered anywhere and broke
every styled email (OTP, account confirmation, password reset,
subscription confirmation) with "No hint path defined for [mail]"
the moment any of them actually tried to send.

### Translation — two systems working together
`i18n.js` (curated dictionary, ported from your Flutter app's exact
wording) + `translate.js` (real Google Translate via your backend,
covers everything else, cached). `routes/api.php` also has the fix
making `/translate/*` public (it required login before, which would
have blocked every guest).

### Design
`tailwind.config.js`, `app.css`, and every Blade view use your actual
`orange-600`/`gray-*` house style — not a separate design system.
Category-color gradient cards for listings without photos
(`gradient.js` + `Category::gradientFor()` — same hash, same colors,
whichever renders it).

### Header & account menu
`site-layout.blade.php` — notification bell (real, polls
`/notifications/unread-count`), avatar circle, reordered dropdown
(Favourites, Messages, Profile, Verification, Settings, Sign out),
language selector, Tabler icons throughout.

### Auth page
Forgot password (inline request form), terms & conditions checkbox
(required, links to the new `/terms`/`/privacy` pages), Google sign-in
fixed to render into plain containers (not custom buttons), vendor
business fields restored (your backend requires `business_name` for
vendor signup), darker input borders.

### KYC page — business/CAC verification is now vendor-only
Buyers no longer see the "Business verification" section at all —
buyers don't have a business to verify, so it made no sense to show
them a CAC input. Hidden by default in the markup, only revealed once
the page confirms the signed-in user's role is `vendor`. Worth knowing:
your backend's `/kyc/business/cac` endpoint itself has no role
restriction (any authenticated user could technically still call it
directly) — this fix is frontend-only, matching what you asked for; say
the word if you also want the backend endpoint locked to vendors.

## Known outstanding items (unchanged from before)

- Agora calling needs the Composer package + real `AGORA_APP_ID`/`AGORA_APP_CERTIFICATE`
- `MONO_SECRET_KEY`/`TERMII_API_KEY` needed for KYC identity/SMS steps
- `GOOGLE_TRANSLATE_KEY` needed for the dynamic translation layer
- `GOOGLE_CLIENT_ID`/`FACEBOOK_APP_ID` needed for social login buttons
- Terms/Privacy pages are placeholder text, clearly flagged — not
  reviewed by a lawyer
- ⚠️ Your `.env` still has live Paystack secret keys committed — rotate
  independently of this work
