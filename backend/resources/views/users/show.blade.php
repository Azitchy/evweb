@extends('layouts.app')
@section('title', 'User Details')
@section('page-title', 'User: ' . $user->name)

@section('content')
<div class="row g-4">
    <div class="col-lg-4">
        <div class="stat-card mb-3">
            <div class="d-flex align-items-center gap-3 mb-3">
                <div class="icon-box bg-primary bg-opacity-10 text-primary" style="width:56px;height:56px;font-size:1.5rem">
                    <i class="bi bi-person-fill"></i>
                </div>
                <div>
                    <h5 class="mb-0">{{ $user->name }}</h5>
                    <span class="badge {{ $user->role === 'admin' ? 'bg-primary' : 'bg-secondary' }}">{{ ucfirst($user->role) }}</span>
                    @if($user->is_active)
                        <span class="badge bg-success">Active</span>
                    @else
                        <span class="badge bg-danger">Inactive</span>
                    @endif
                </div>
            </div>
            <hr>
            <p class="mb-1"><i class="bi bi-envelope me-2"></i>{{ $user->email }}</p>
            <p class="mb-1"><i class="bi bi-phone me-2"></i>{{ $user->phone }}</p>
            <p class="mb-1"><i class="bi bi-calendar me-2"></i>Joined {{ $user->created_at->format('M d, Y') }}</p>
            <p class="mb-0"><i class="bi bi-wallet2 me-2"></i>Balance: <strong>Rs. {{ number_format($user->wallet->balance ?? 0, 2) }}</strong></p>
        </div>

        <div class="stat-card">
            <h6>Statistics</h6>
            <div class="d-flex justify-content-between py-2 border-bottom">
                <span class="text-muted">Total Sessions</span>
                <strong>{{ $stats['total_sessions'] }}</strong>
            </div>
            <div class="d-flex justify-content-between py-2 border-bottom">
                <span class="text-muted">Total Spent</span>
                <strong>Rs. {{ number_format($stats['total_spent'], 2) }}</strong>
            </div>
            <div class="d-flex justify-content-between py-2">
                <span class="text-muted">Total Charged</span>
                <strong>{{ $stats['total_charged'] }}%</strong>
            </div>
        </div>

        <div class="mt-3 d-flex gap-2">
            <a href="{{ route('web.users.edit', $user) }}" class="btn btn-warning btn-sm"><i class="bi bi-pencil"></i> Edit</a>
            <form method="POST" action="{{ route('web.users.toggle-status', $user) }}">
                @csrf @method('PATCH')
                <button class="btn {{ $user->is_active ? 'btn-danger' : 'btn-success' }} btn-sm">
                    {{ $user->is_active ? 'Deactivate' : 'Activate' }}
                </button>
            </form>
        </div>
    </div>

    <div class="col-lg-8">
        <!-- Recent Sessions -->
        <div class="table-card mb-4">
            <div class="card-header">Recent Charging Sessions</div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr><th>Station</th><th>Charged</th><th>Cost</th><th>Status</th><th>Date</th></tr>
                    </thead>
                    <tbody>
                        @forelse($user->chargingSessions->take(10) as $session)
                        <tr>
                            <td>{{ $session->chargingStation->name ?? 'N/A' }}</td>
                            <td>{{ $session->start_percentage }}% → {{ $session->end_percentage ?? '...' }}%</td>
                            <td>Rs. {{ number_format($session->cost ?? 0, 2) }}</td>
                            <td>
                                <span class="badge {{ $session->status === 'completed' ? 'bg-success' : ($session->status === 'charging' ? 'bg-warning text-dark' : 'bg-secondary') }} badge-status">
                                    {{ ucfirst($session->status) }}
                                </span>
                            </td>
                            <td>{{ $session->created_at->format('M d, H:i') }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-center text-muted py-3">No sessions</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Recent Transactions -->
        <div class="table-card">
            <div class="card-header">Recent Transactions</div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr><th>Type</th><th>Amount</th><th>Balance After</th><th>Description</th><th>Date</th></tr>
                    </thead>
                    <tbody>
                        @forelse($user->transactions->take(10) as $txn)
                        <tr>
                            <td><span class="badge {{ $txn->type === 'credit' ? 'bg-success' : 'bg-danger' }} badge-status">{{ ucfirst($txn->type) }}</span></td>
                            <td>Rs. {{ number_format($txn->amount, 2) }}</td>
                            <td>Rs. {{ number_format($txn->balance_after, 2) }}</td>
                            <td>{{ Str::limit($txn->description, 40) }}</td>
                            <td>{{ $txn->created_at->format('M d, H:i') }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-center text-muted py-3">No transactions</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
