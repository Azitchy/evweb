<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ChargingSession;
use App\Models\PricingSetting;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    // ── User Management ──

    public function users(Request $request): JsonResponse
    {
        $query = User::with('wallet')->where('role', 'user');

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        return response()->json($query->paginate(15));
    }

    public function toggleUserStatus(int $userId): JsonResponse
    {
        $user = User::where('role', 'user')->findOrFail($userId);
        $user->is_active = ! $user->is_active;
        $user->save();

        return response()->json([
            'message' => $user->is_active ? 'User activated.' : 'User deactivated.',
            'user' => $user,
        ]);
    }

    // ── Pricing Control ──

    public function getCurrentPricing(): JsonResponse
    {
        $pricing = PricingSetting::where('is_active', true)->latest()->first();

        return response()->json([
            'pricing' => $pricing,
        ]);
    }

    public function updatePricing(Request $request): JsonResponse
    {
        $request->validate([
            'price_per_percentage' => ['required', 'numeric', 'min:0.01', 'max:10000'],
        ]);

        PricingSetting::where('is_active', true)->update(['is_active' => false]);

        $pricing = PricingSetting::create([
            'price_per_percentage' => $request->price_per_percentage,
            'is_active' => true,
        ]);

        return response()->json([
            'message' => 'Pricing updated successfully.',
            'pricing' => $pricing,
        ]);
    }

    // ── Transaction Management ──

    public function transactions(Request $request): JsonResponse
    {
        $query = Transaction::with('user');

        if ($request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->has('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        return response()->json($query->orderByDesc('created_at')->paginate(20));
    }

    // ── Charging Monitoring ──

    public function activeSessions(): JsonResponse
    {
        $sessions = ChargingSession::with('user')
            ->where('status', 'charging')
            ->orderByDesc('started_at')
            ->get();

        return response()->json([
            'active_sessions' => $sessions,
        ]);
    }

    public function chargingLogs(Request $request): JsonResponse
    {
        $query = ChargingSession::with('user');

        if ($request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        return response()->json($query->orderByDesc('created_at')->paginate(20));
    }

    // ── Reports & Analytics ──

    public function dashboard(): JsonResponse
    {
        $totalRevenue = Transaction::where('type', 'debit')->sum('amount');
        $totalUsers = User::where('role', 'user')->count();
        $activeUsers = User::where('role', 'user')->where('is_active', true)->count();
        $totalSessions = ChargingSession::where('status', 'completed')->count();
        $activeSessions = ChargingSession::where('status', 'charging')->count();

        $todayRevenue = Transaction::where('type', 'debit')
            ->whereDate('created_at', today())
            ->sum('amount');

        $monthlyRevenue = Transaction::where('type', 'debit')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('amount');

        return response()->json([
            'total_revenue' => round($totalRevenue, 2),
            'today_revenue' => round($todayRevenue, 2),
            'monthly_revenue' => round($monthlyRevenue, 2),
            'total_users' => $totalUsers,
            'active_users' => $activeUsers,
            'total_sessions' => $totalSessions,
            'active_sessions' => $activeSessions,
        ]);
    }

    public function revenueReport(Request $request): JsonResponse
    {
        $request->validate([
            'period' => ['sometimes', 'in:daily,monthly'],
        ]);

        $period = $request->input('period', 'daily');

        if ($period === 'monthly') {
            $report = Transaction::where('type', 'debit')
                ->selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, SUM(amount) as total')
                ->groupByRaw('YEAR(created_at), MONTH(created_at)')
                ->orderByRaw('YEAR(created_at) DESC, MONTH(created_at) DESC')
                ->limit(12)
                ->get();
        } else {
            $report = Transaction::where('type', 'debit')
                ->selectRaw('DATE(created_at) as date, SUM(amount) as total')
                ->groupByRaw('DATE(created_at)')
                ->orderByRaw('DATE(created_at) DESC')
                ->limit(30)
                ->get();
        }

        return response()->json([
            'period' => $period,
            'report' => $report,
        ]);
    }

    public function userActivityReport(): JsonResponse
    {
        $users = User::where('role', 'user')
            ->withCount(['chargingSessions as total_sessions' => function ($q) {
                $q->where('status', 'completed');
            }])
            ->withSum(['transactions as total_spent' => function ($q) {
                $q->where('type', 'debit');
            }], 'amount')
            ->orderByDesc('total_spent')
            ->paginate(20);

        return response()->json($users);
    }
}
