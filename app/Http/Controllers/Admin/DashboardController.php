<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Listing;
use App\Models\Subscription;
use App\Models\Transaction;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $stats = [
            'total_users'       => User::where('role', '!=', 'admin')->count(),
            'total_vendors'     => User::where('role', 'vendor')->count(),
            'total_buyers'      => User::where('role', 'buyer')->count(),
            'verified_vendors'  => User::where('role', 'vendor')->where('is_verified', true)->count(),
            'total_listings'    => Listing::count(),
            'pending_kyc'       => User::where('kyc_status', 'pending')->count(),
            'active_subs'       => Subscription::where('status', 'active')->where('end_date', '>', now())->count(),
            'total_revenue_ngn' => Transaction::where('currency', 'NGN')->where('status', 'completed')->sum('amount'),
            'total_revenue_esp' => Transaction::where('currency', 'ESPEES')->where('status', 'completed')->sum('amount'),
            'total_categories'  => Category::count(),
        ];

        // Revenue trend — last 30 days, grouped by day
        $revenueTrend = Transaction::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(CASE WHEN currency = "NGN" THEN amount ELSE 0 END) as ngn'),
                DB::raw('SUM(CASE WHEN currency = "ESPEES" THEN amount ELSE 0 END) as espees')
            )
            ->where('status', 'completed')
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Fill in missing days with zero so the chart line is continuous
        $trendMap = [];
        for ($i = 29; $i >= 0; $i--) {
            $d = now()->subDays($i)->format('Y-m-d');
            $trendMap[$d] = ['date' => $d, 'ngn' => 0, 'espees' => 0];
        }
        foreach ($revenueTrend as $row) {
            $trendMap[$row->date] = ['date' => $row->date, 'ngn' => (float) $row->ngn, 'espees' => (float) $row->espees];
        }

        // Determine database driver to prevent SQLite crash during testing
        $isSqlite = DB::getDriverName() === 'sqlite';

        // 1. User growth — last 12 weeks
        $yearWeekSql = $isSqlite ? "strftime('%Y%W', created_at)" : "YEARWEEK(created_at, 1)";

        $userGrowth = User::select(
                DB::raw("$yearWeekSql as yw"),
                DB::raw('MIN(DATE(created_at)) as week_start'),
                DB::raw('COUNT(*) as count')
            )
            ->where('created_at', '>=', now()->subWeeks(12))
            ->where('role', '!=', 'admin')
            ->groupBy('yw')
            ->orderBy('yw')
            ->get();

        // 2. New subscriptions by month — last 6 months
        $dateFormatSql = $isSqlite ? "strftime('%Y-%m', created_at)" : "DATE_FORMAT(created_at, '%Y-%m')";

        $subsByMonth = Subscription::select(
                DB::raw("$dateFormatSql as ym"),
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(CASE WHEN payment_method = "stripe" THEN amount_paid ELSE 0 END) as stripe_amt'),
                DB::raw('SUM(CASE WHEN payment_method = "espees" THEN amount_paid ELSE 0 END) as espees_amt')
            )
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('ym')
            ->orderBy('ym')
            ->get();

        // Payment method split
        $paymentSplit = [
            'stripe' => Subscription::where('payment_method', 'stripe')->count(),
            'espees' => Subscription::where('payment_method', 'espees')->count(),
        ];

        // Category distribution
        $categoryDistribution = Listing::select('category', DB::raw('COUNT(*) as count'))
            ->groupBy('category')
            ->orderByDesc('count')
            ->limit(8)
            ->get();

        // Recent activity feed
        $recentUsers = User::where('role', '!=', 'admin')
            ->latest()
            ->limit(6)
            ->get(['id', 'full_name', 'email', 'role', 'kyc_status', 'created_at']);

        $recentListings = Listing::with('vendor:id,full_name,business_name')
            ->latest()
            ->limit(6)
            ->get(['id', 'title', 'price', 'vendor_id', 'status', 'created_at']);

        $recentTransactions = Transaction::with('user:id,full_name,email')
            ->latest()
            ->limit(8)
            ->get();

        return view('admin.dashboard', compact(
            'stats', 'trendMap', 'userGrowth', 'subsByMonth',
            'paymentSplit', 'categoryDistribution',
            'recentUsers', 'recentListings', 'recentTransactions'
        ));
    }

    /**
     * Display a paginated listing of all uploaded items across the platform.
     */
    public function listings(Request $request)
    {
        $query = Listing::with('vendor:id,full_name,business_name');

        // Handle simple search query filtering
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where('title', 'LIKE', "%{$s}%")
                  ->orWhere('description', 'LIKE', "%{$s}%");
        }

        // Handle status filter overrides (e.g. active, pending, review)
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $listings = $query->latest()->paginate(20);

        return view('admin.listings.index', compact('listings'));
    }

    /**
     * Compile revenue reports aggregate data metrics for business insights.
     */
    public function revenueReports()
    {
        $isSqlite = DB::getDriverName() === 'sqlite';
        $dateFormatSql = $isSqlite ? "strftime('%Y-%m', created_at)" : "DATE_FORMAT(created_at, '%Y-%m')";

        // Split transaction reporting totals by completion statuses
        $financials = [
            'total_ngn'        => Transaction::where('currency', 'NGN')->where('status', 'completed')->sum('amount'),
            'total_espees'     => Transaction::where('currency', 'ESPEES')->where('status', 'completed')->sum('amount'),
            'pending_ngn'      => Transaction::where('currency', 'NGN')->where('status', 'pending')->sum('amount'),
            'pending_espees'   => Transaction::where('currency', 'ESPEES')->where('status', 'pending')->sum('amount'),
            'sub_earnings_ngn' => Subscription::where('status', 'active')->sum('amount_paid'),
        ];

        // Gather comprehensive historical performance statements grouped monthly
        $monthlyRevenue = Transaction::select(
                DB::raw("$dateFormatSql as month"),
                DB::raw('SUM(CASE WHEN currency = "NGN" THEN amount ELSE 0 END) as total_ngn'),
                DB::raw('SUM(CASE WHEN currency = "ESPEES" THEN amount ELSE 0 END) as total_espees'),
                DB::raw('COUNT(*) as total_transactions')
            )
            ->where('status', 'completed')
            ->groupBy('month')
            ->orderByDesc('month')
            ->limit(12)
            ->get();

        return view('admin.reports.revenue', compact('financials', 'monthlyRevenue'));
    }

    public function kycRequests()
    {
        $pending = User::where('kyc_status', 'pending')
            ->orWhere('kyc_status', 'not_submitted')
            ->latest()
            ->paginate(15);

        return view('admin.kyc-requests', compact('pending'));
    }

    public function approveKyc(Request $request, string $id)
    {
        $user = User::findOrFail($id);
        $user->update(['kyc_status' => 'verified']);

        if ($user->cac_number) {
            $user->update(['is_verified' => true]);
        }

        \App\Services\NotificationService::sendPush(
            $user, 'KYC Approved ✅', 'Your identity verification has been approved.'
        );

        return back()->with('success', "KYC approved for {$user->full_name}");
    }

    public function rejectKyc(Request $request, string $id)
    {
        $request->validate(['reason' => 'required|string|max:500']);
        $user = User::findOrFail($id);
        $user->update([
            'kyc_status'           => 'rejected',
            'kyc_rejection_reason' => $request->reason,
        ]);

        return back()->with('success', "KYC rejected for {$user->full_name}");
    }

    public function users(Request $request)
    {
        $query = User::where('role', '!=', 'admin')->orWhere('role', 'admin');

        if ($request->filled('role')) {
            $query = User::where('role', $request->role);
        }
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('full_name', 'LIKE', "%$s%")->orWhere('email', 'LIKE', "%$s%");
            });
        }

        $users = $query->latest()->paginate(20);
        return view('admin.users', compact('users'));
    }

    public function addUser(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'email'     => 'required|email|unique:users,email',
            'phone'     => 'required|string',
            'password'  => 'required|min:6',
            'role'      => 'required|in:buyer,vendor,admin',
        ]);

        User::create([
            'full_name' => $request->full_name,
            'email'     => $request->email,
            'phone'     => $request->phone,
            'password'  => \Illuminate\Support\Facades\Hash::make($request->password),
            'role'      => $request->role,
        ]);

        return back()->with('success', 'User created successfully');
    }

    public function deleteUser(string $id)
    {
        $user = User::findOrFail($id);
        if ($user->role === 'admin') {
            return back()->withErrors(['error' => 'Cannot delete admin accounts']);
        }
        $user->delete();
        return back()->with('success', 'User deleted successfully');
    }
}
