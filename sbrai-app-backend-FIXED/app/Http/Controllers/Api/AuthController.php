<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\OtpService;
use App\Services\NotificationService;
use App\Mail\ResetPasswordMail;
use App\Mail\ConfirmAccountMail;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    // ─── Register ─────────────────────────────────────────────────────
    public function register(Request $request): JsonResponse
    {
        $request->validate([
            'full_name'        => 'required|string|max:255',
            'email'            => 'required|email|unique:users,email',
            'phone'            => 'required|string|max:20',
            'password'         => 'required|string|min:6|confirmed',
            'role'             => 'required|in:buyer,vendor',
            'business_name'    => 'required_if:role,vendor|string|max:255|nullable',
            'business_address' => 'nullable|string|max:500',
            'cac_number'       => 'nullable|string|max:50',
        ]);

        $user = User::create([
            'full_name'        => $request->full_name,
            'email'            => $request->email,
            'phone'            => $request->phone,
            'password'         => Hash::make($request->password),
            'role'             => $request->role,
            'kyc_status'       => 'not_submitted',
            'is_verified'      => false,
            'business_name'    => $request->business_name,
            'business_address' => $request->business_address,
            'cac_number'       => $request->cac_number,
        ]);

        // Give new vendors a ₦5,000 welcome voucher
        if ($user->role === 'vendor') {
            $user->voucher_balance = 5000;
            $user->save();

            // Log the voucher transaction
            $user->transactions()->create([
                'type'        => 'credit',
                'amount'      => 5000,
                'description' => 'Welcome bonus - Sign up voucher',
                'balance'     => 5000,
            ]);
        }

        // Send welcome notification
        NotificationService::sendWelcome($user);

        $token = $user->createToken('sbrai-app')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Account created successfully',
            'token'   => $token,
            'user'    => $this->formatUser($user),
        ], 201);
    }

    // ─── Login ────────────────────────────────────────────────────────
    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Invalid email or password.'],
            ]);
        }

        // Revoke old tokens (single device per user)
        $user->tokens()->delete();

        $token = $user->createToken('sbrai-app')->plainTextToken;

        // Update last login
        $user->update(['last_login_at' => now()]);

        // Load subscription
        $user->load('activeSubscription');

        return response()->json([
            'success' => true,
            'message' => 'Signed in successfully',
            'token'   => $token,
            'user'    => $this->formatUser($user),
        ]);
    }

    // ─── Google Sign-In ─────────────────────────────────────────────────
    // Verifies the ID token directly against Google's tokeninfo endpoint
    // (no Socialite dependency needed) and finds-or-creates a matching
    // user by email, linking google_id for future logins.
    public function googleAuth(Request $request): JsonResponse
    {
        $request->validate([
            'id_token' => 'required|string',
            'role'     => 'required|in:buyer,vendor',
        ]);

        $response = \Illuminate\Support\Facades\Http::get('https://oauth2.googleapis.com/tokeninfo', [
            'id_token' => $request->id_token,
        ]);

        if (!$response->successful()) {
            throw ValidationException::withMessages([
                'id_token' => ['Could not verify this Google account. Please try again.'],
            ]);
        }

        $payload = $response->json();
        $email = $payload['email'] ?? null;
        $googleId = $payload['sub'] ?? null;

        if (!$email || !$googleId) {
            throw ValidationException::withMessages([
                'id_token' => ['Google did not return the expected account details.'],
            ]);
        }

        $user = User::where('google_id', $googleId)->orWhere('email', $email)->first();

        if ($user) {
            if (!$user->google_id) {
                $user->update(['google_id' => $googleId]);
            }
        } else {
            $user = User::create([
                'full_name'            => $payload['name'] ?? explode('@', $email)[0],
                'email'                => $email,
                'phone'                => null,
                'password'             => Hash::make(Str::random(32)),
                'role'                 => $request->role,
                'avatar_url'           => $payload['picture'] ?? null,
                'kyc_status'           => 'not_submitted',
                'is_verified'          => false,
                'google_id'            => $googleId,
                // Google has already verified this email address for us.
                'email_verified_at'    => now(),
                'account_confirmed_at' => now(),
            ]);

            if ($user->role === 'vendor') {
                $user->voucher_balance = 5000;
                $user->save();
                $user->transactions()->create([
                    'type'        => 'credit',
                    'amount'      => 5000,
                    'description' => 'Welcome bonus - Sign up voucher',
                    'balance'     => 5000,
                ]);
            }

            NotificationService::sendWelcome($user);
        }

        $user->tokens()->delete();
        $token = $user->createToken('sbrai-app')->plainTextToken;
        $user->update(['last_login_at' => now()]);
        $user->load('activeSubscription');

        return response()->json([
            'success' => true,
            'message' => 'Signed in successfully',
            'token'   => $token,
            'user'    => $this->formatUser($user),
        ]);
    }

    // ─── Facebook Sign-In ───────────────────────────────────────────────
    public function facebookAuth(Request $request): JsonResponse
    {
        $request->validate([
            'access_token' => 'required|string',
            'role'         => 'required|in:buyer,vendor',
        ]);

        $response = \Illuminate\Support\Facades\Http::get('https://graph.facebook.com/me', [
            'fields'       => 'id,name,email,picture.type(large)',
            'access_token' => $request->access_token,
        ]);

        if (!$response->successful()) {
            throw ValidationException::withMessages([
                'access_token' => ['Could not verify this Facebook account. Please try again.'],
            ]);
        }

        $payload = $response->json();
        $email = $payload['email'] ?? null;
        $facebookId = $payload['id'] ?? null;

        if (!$facebookId) {
            throw ValidationException::withMessages([
                'access_token' => ['Facebook did not return the expected account details.'],
            ]);
        }

        // Facebook accounts can decline to share an email — fall back to a
        // synthetic, uniquely-addressable placeholder so registration
        // doesn't fail outright.
        $email = $email ?? "fb_{$facebookId}@facebook.sbrai.local";

        $user = User::where('facebook_id', $facebookId)->orWhere('email', $email)->first();

        if ($user) {
            if (!$user->facebook_id) {
                $user->update(['facebook_id' => $facebookId]);
            }
        } else {
            $user = User::create([
                'full_name'            => $payload['name'] ?? 'Sbrai User',
                'email'                => $email,
                'phone'                => null,
                'password'             => Hash::make(Str::random(32)),
                'role'                 => $request->role,
                'avatar_url'           => $payload['picture']['data']['url'] ?? null,
                'kyc_status'           => 'not_submitted',
                'is_verified'          => false,
                'facebook_id'          => $facebookId,
                'email_verified_at'    => now(),
                'account_confirmed_at' => now(),
            ]);

            if ($user->role === 'vendor') {
                $user->voucher_balance = 5000;
                $user->save();
                $user->transactions()->create([
                    'type'        => 'credit',
                    'amount'      => 5000,
                    'description' => 'Welcome bonus - Sign up voucher',
                    'balance'     => 5000,
                ]);
            }

            NotificationService::sendWelcome($user);
        }

        $user->tokens()->delete();
        $token = $user->createToken('sbrai-app')->plainTextToken;
        $user->update(['last_login_at' => now()]);
        $user->load('activeSubscription');

        return response()->json([
            'success' => true,
            'message' => 'Signed in successfully',
            'token'   => $token,
            'user'    => $this->formatUser($user),
        ]);
    }

    // ─── Logout ───────────────────────────────────────────────────────
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['success' => true, 'message' => 'Logged out']);
    }

    // ─── Get Current User ─────────────────────────────────────────────
    public function me(Request $request): JsonResponse
    {
        $user = $request->user()->load('activeSubscription');
        return response()->json([
            'success' => true,
            'user'    => $this->formatUser($user),
        ]);
    }

    // ─── Update Profile ───────────────────────────────────────────────
    public function updateProfile(Request $request): JsonResponse
    {
        $user = $request->user();

        $request->validate([
            'full_name'        => 'sometimes|string|max:255',
            'phone'            => 'sometimes|string|max:20',
            'business_name'    => 'sometimes|string|max:255|nullable',
            'business_address' => 'sometimes|string|max:500|nullable',
        ]);

        $user->update($request->only([
            'full_name', 'phone', 'business_name', 'business_address',
        ]));

        return response()->json([
            'success' => true,
            'user'    => $this->formatUser($user),
        ]);
    }

    // ─── Upload Avatar ────────────────────────────────────────────────
    public function uploadAvatar(Request $request): JsonResponse
    {
        $request->validate(['avatar' => 'required|image|max:5120']);

        $user = $request->user();

        // Delete old avatar
        if ($user->avatar_url) {
            Storage::disk('public')->delete(str_replace('/storage/', '', $user->avatar_url));
        }

        $path = $request->file('avatar')->store('avatars', 'public');
        $user->update(['avatar_url' => '/storage/' . $path]);

        return response()->json([
            'success'    => true,
            'avatar_url' => $user->avatar_url,
        ]);
    }

    // ─── Change Password ──────────────────────────────────────────────
    public function changePassword(Request $request): JsonResponse
    {
        $request->validate([
            'current_password' => 'required|string',
            'password'         => 'required|string|min:6|confirmed',
        ]);

        $user = $request->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json(['success' => false, 'message' => 'Current password is incorrect'], 422);
        }

        $user->update(['password' => Hash::make($request->password)]);

        return response()->json(['success' => true, 'message' => 'Password changed successfully']);
    }

    // ─── Update FCM Token ─────────────────────────────────────────────
    public function updateFcmToken(Request $request): JsonResponse
    {
        $request->validate(['fcm_token' => 'required|string']);
        $request->user()->update(['fcm_token' => $request->fcm_token]);
        return response()->json(['success' => true]);
    }

    // ─── Delete Account ───────────────────────────────────────────────
    public function deleteAccount(Request $request): JsonResponse
    {
        $user = $request->user();
        $user->tokens()->delete();
        $user->delete(); // Soft delete
        return response()->json(['success' => true, 'message' => 'Account deleted']);
    }

    // ─── Get / Update Settings ────────────────────────────────────────
    public function getSettings(Request $request): JsonResponse
    {
        $settings = $request->user()->settings ?? $this->defaultSettings();
        return response()->json(['success' => true, 'settings' => $settings]);
    }

    public function updateSettings(Request $request): JsonResponse
    {
        $request->user()->update(['settings' => $request->all()]);
        return response()->json(['success' => true, 'settings' => $request->all()]);
    }

    // ─── Forgot / Reset Password ──────────────────────────────────────
    public function forgotPassword(Request $request): JsonResponse
    {
        $request->validate(['email' => 'required|email|exists:users,email']);

        $user  = User::where('email', $request->email)->first();
        $token = Str::random(64);

        // Store/replace reset token (custom table, works for both buyer & vendor)
        DB::table('password_reset_tokens')->where('email', $user->email)->delete();
        DB::table('password_reset_tokens')->insert([
            'email'      => $user->email,
            'token'      => Hash::make($token),
            'created_at' => now(),
        ]);

        try {
            Mail::to($user->email)->send(new ResetPasswordMail($user, $token));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Reset password email failed: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Could not send reset email. Please try again.'], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'Password reset link sent to ' . $user->email,
        ]);
    }

    public function resetPassword(Request $request): JsonResponse
    {
        $request->validate([
            'token'    => 'required|string',
            'email'    => 'required|email',
            'password' => 'required|min:6|confirmed',
        ]);

        $record = DB::table('password_reset_tokens')->where('email', $request->email)->first();

        if (!$record || !Hash::check($request->token, $record->token)) {
            return response()->json(['success' => false, 'message' => 'Invalid or expired reset token'], 422);
        }

        // Token expires after 60 minutes
        if (now()->diffInMinutes($record->created_at) > 60) {
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();
            return response()->json(['success' => false, 'message' => 'Reset link has expired. Please request a new one.'], 422);
        }

        $user = User::where('email', $request->email)->first();
        $user->update(['password' => Hash::make($request->password)]);
        $user->tokens()->delete();

        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return response()->json(['success' => true, 'message' => 'Password reset successfully. You can now sign in.']);
    }

    // ─── Reset Password (HTML form, for email links opened in browser) ─
    public function showResetForm(Request $request)
    {
        return view('auth.reset-password-form', [
            'token' => $request->query('token', ''),
            'email' => $request->query('email', ''),
        ]);
    }

    public function handleResetForm(Request $request)
    {
        $validated = $request->validate([
            'token'                 => 'required|string',
            'email'                 => 'required|email',
            'password'              => 'required|min:6|confirmed',
            'password_confirmation' => 'required',
        ]);

        $record = DB::table('password_reset_tokens')->where('email', $validated['email'])->first();

        if (!$record || !Hash::check($validated['token'], $record->token)) {
            return back()->withErrors(['error' => 'Invalid or expired reset token.']);
        }

        if (now()->diffInMinutes($record->created_at) > 60) {
            DB::table('password_reset_tokens')->where('email', $validated['email'])->delete();
            return back()->withErrors(['error' => 'Reset link has expired. Please request a new one.']);
        }

        $user = User::where('email', $validated['email'])->first();
        $user->update(['password' => Hash::make($validated['password'])]);
        $user->tokens()->delete();

        DB::table('password_reset_tokens')->where('email', $validated['email'])->delete();

        return back()->with('status', 'Password reset successfully! You can now sign in from the Sbrai app.');
    }

    // ─── Account Confirmation (email link) ─────────────────────────────
    public function confirmAccount(string $token)
    {
        $user = User::where('confirmation_token', $token)->first();

        if (!$user) {
            return response()->view('emails.confirm-result', ['success' => false, 'message' => 'Invalid or expired confirmation link.']);
        }

        $user->update([
            'account_confirmed_at' => now(),
            'confirmation_token'   => null,
        ]);

        return response()->view('emails.confirm-result', ['success' => true, 'message' => 'Your account has been confirmed successfully!']);
    }

    public function resendConfirmation(Request $request): JsonResponse
    {
        $user = $request->user();

        if ($user->account_confirmed_at) {
            return response()->json(['success' => false, 'message' => 'Account already confirmed'], 422);
        }

        $user->update(['confirmation_token' => Str::random(64)]);

        try {
            Mail::to($user->email)->send(new ConfirmAccountMail($user));
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to send confirmation email'], 500);
        }

        return response()->json(['success' => true, 'message' => 'Confirmation email resent']);
    }

    // ─── Format User Response ─────────────────────────────────────────
    private function formatUser(User $user): array
    {
        return [
            'id'               => $user->id,
            'full_name'        => $user->full_name,
            'email'            => $user->email,
            'phone'            => $user->phone,
            'avatar_url'       => $user->avatar_url,
            'role'             => $user->role,
            'kyc_status'       => $user->kyc_status,
            'is_verified'      => $user->is_verified,
            'created_at'       => $user->created_at->toISOString(),
            'business_name'    => $user->business_name,
            'business_address' => $user->business_address,
            'cac_number'       => $user->cac_number,
            'rating'           => round($user->rating ?? 0, 1),
            'review_count'     => $user->review_count ?? 0,
            'active_listings'  => $user->listings()->where('status', 'active')->count(),
            'total_views'      => $user->listings()->sum('view_count') ?? 0,
            'total_chats'      => $user->chats()->count() ?? 0,
            'subscription'     => $user->activeSubscription ? [
                'id'             => $user->activeSubscription->id,
                'vendor_id'      => $user->activeSubscription->vendor_id,
                'status'         => $user->activeSubscription->status,
                'start_date'     => $user->activeSubscription->start_date->toISOString(),
                'end_date'       => $user->activeSubscription->end_date->toISOString(),
                'amount_paid'    => $user->activeSubscription->amount_paid,
                'payment_method' => $user->activeSubscription->payment_method,
                'transaction_id' => $user->activeSubscription->transaction_id,
            ] : null,
        ];
    }

    private function defaultSettings(): array
    {
        return [
            'notifications' => [
                'new_listings' => true,
                'price_drops'  => true,
                'messages'     => true,
                'promotions'   => false,
            ],
            'privacy' => [
                'show_online_status' => true,
                'show_phone'         => true,
                'allow_messages'     => true,
            ],
            'language' => 'en',
            'currency' => 'NGN',
        ];
    }
}
