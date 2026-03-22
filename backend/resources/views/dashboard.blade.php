@extends('layouts.app')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<!-- Stats Cards -->
<div class="row g-3 mb-4">
    <div class="col-md-4 col-lg-3">
        <div class="stat-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="text-muted small">Total Revenue</div>
                    <h4 class="mb-0 mt-1">Rs. {{ number_format($stats['total_revenue'], 2) }}</h4>
                </div>
                <div class="icon-box bg-success bg-opacity-10 text-success">
                    <i class="bi bi-currency-dollar"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4 col-lg-3">
        <div class="stat-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="text-muted small">Today's Revenue</div>
                    <h4 class="mb-0 mt-1">Rs. {{ number_format($stats['today_revenue'], 2) }}</h4>
                </div>
                <div class="icon-box bg-info bg-opacity-10 text-info">
                    <i class="bi bi-graph-up-arrow"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4 col-lg-3">
        <div class="stat-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="text-muted small">Monthly Revenue</div>
                    <h4 class="mb-0 mt-1">Rs. {{ number_format($stats['monthly_revenue'], 2) }}</h4>
                </div>
                <div class="icon-box bg-warning bg-opacity-10 text-warning">
                    <i class="bi bi-calendar-check"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4 col-lg-3">
        <div class="stat-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="text-muted small">Active Sessions</div>
                    <h4 class="mb-0 mt-1">{{ $stats['active_sessions'] }}</h4>
                </div>
                <div class="icon-box bg-danger bg-opacity-10 text-danger">
                    <i class="bi bi-lightning-charge-fill"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-4 col-lg-3">
        <div class="stat-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="text-muted small">Total Users</div>
                    <h4 class="mb-0 mt-1">{{ number_format($stats['total_users']) }}</h4>
                </div>
                <div class="icon-box bg-primary bg-opacity-10 text-primary">
                    <i class="bi bi-people-fill"></i>
                </div>
            </div>
            <small class="text-muted">{{ $stats['active_users'] }} active</small>
        </div>
    </div>
    <div class="col-md-4 col-lg-3">
        <div class="stat-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="text-muted small">Total Stations</div>
                    <h4 class="mb-0 mt-1">{{ number_format($stats['total_stations']) }}</h4>
                </div>
                <div class="icon-box bg-success bg-opacity-10 text-success">
                    <i class="bi bi-ev-station-fill"></i>
                </div>
            </div>
            <small class="text-muted">{{ $stats['active_stations'] }} active</small>
        </div>
    </div>
    <div class="col-md-4 col-lg-3">
        <div class="stat-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="text-muted small">Total Sessions</div>
                    <h4 class="mb-0 mt-1">{{ number_format($stats['total_sessions']) }}</h4>
                </div>
                <div class="icon-box bg-info bg-opacity-10 text-info">
                    <i class="bi bi-activity"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tables Row -->
<div class="row g-4">
    <div class="col-lg-7">
        <div class="table-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>Recent Charging Sessions</span>
                <a href="{{ route('web.sessions.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>User</th>
                            <th>Station</th>
                            <th>Charged</th>
                            <th>Cost</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentSessions as $session)
                        <tr>
                            <td>{{ $session->user->name ?? 'N/A' }}</td>
                            <td>{{ Str::limit($session->chargingStation->name ?? 'N/A', 20) }}</td>
                            <td>{{ $session->charged_percentage ?? 0 }}%</td>
                            <td>Rs. {{ number_format($session->cost ?? 0, 2) }}</td>
                            <td>
                                @if($session->status === 'charging')
                                    <span class="badge bg-warning text-dark badge-status">Charging</span>
                                @elseif($session->status === 'completed')
                                    <span class="badge bg-success badge-status">Completed</span>
                                @else
                                    <span class="badge bg-secondary badge-status">{{ ucfirst($session->status) }}</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-center text-muted py-3">No sessions yet</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-5">
        <div class="table-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>Recent Transactions</span>
                <a href="{{ route('web.transactions.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>User</th>
                            <th>Type</th>
                            <th>Amount</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentTransactions as $txn)
                        <tr>
                            <td>{{ $txn->user->name ?? 'N/A' }}</td>
                            <td>
                                @if($txn->type === 'credit')
                                    <span class="badge bg-success badge-status">Credit</span>
                                @else
                                    <span class="badge bg-danger badge-status">Debit</span>
                                @endif
                            </td>
                            <td>Rs. {{ number_format($txn->amount, 2) }}</td>
                            <td>{{ $txn->created_at->format('M d, H:i') }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center text-muted py-3">No transactions yet</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
