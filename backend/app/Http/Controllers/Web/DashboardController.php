<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\ChargingSession;
use App\Models\ChargingStation;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users' => User::count(),
            'active_users' => User::where('is_active', true)->count(),
            'total_stations' => ChargingStation::count(),
            'active_stations' => ChargingStation::where('status', 'active')->count(),
            'total_sessions' => ChargingSession::count(),
            'active_sessions' => ChargingSession::where('status', 'charging')->count(),
            'total_revenue' => Transaction::where('type', 'debit')->sum('amount'),
            'today_revenue' => Transaction::where('type', 'debit')->whereDate('created_at', today())->sum('amount'),
            'monthly_revenue' => Transaction::where('type', 'debit')->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->sum('amount'),
        ];

        $recentSessions = ChargingSession::with(['user', 'chargingStation'])
            ->latest()
            ->take(10)
            ->get();

        $recentTransactions = Transaction::with('user')
            ->latest()
            ->take(10)
            ->get();

        $monthlyRevenue = Transaction::where('type', 'debit')
            ->where('created_at', '>=', now()->subMonths(6))
            ->select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('YEAR(created_at) as year'),
                DB::raw('SUM(amount) as total')
            )
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        return view('dashboard', compact('stats', 'recentSessions', 'recentTransactions', 'monthlyRevenue'));
    }
}
