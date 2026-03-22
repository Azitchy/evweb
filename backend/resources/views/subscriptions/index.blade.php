@extends('layouts.app')
@section('title', 'User Subscriptions')
@section('page-title', 'User Subscriptions')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <form class="d-flex gap-2" method="GET">
        <select name="status" class="form-select form-select-sm" style="width:140px">
            <option value="">All Status</option>
            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
            <option value="expired" {{ request('status') === 'expired' ? 'selected' : '' }}>Expired</option>
            <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
        </select>
        <button class="btn btn-sm btn-primary"><i class="bi bi-search"></i></button>
        <a href="{{ route('web.subscriptions.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-x-lg"></i></a>
    </form>
</div>

<div class="table-card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>User</th>
                    <th>Plan</th>
                    <th>Amount Paid</th>
                    <th>Starts At</th>
                    <th>Expires At</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($subscriptions as $sub)
                <tr>
                    <td>{{ $sub->id }}</td>
                    <td>
                        @if($sub->user)
                            <a href="{{ route('web.users.show', $sub->user) }}">{{ $sub->user->name }}</a>
                        @else
                            N/A
                        @endif
                    </td>
                    <td>{{ $sub->plan->name ?? 'N/A' }}</td>
                    <td>Rs. {{ number_format($sub->amount_paid, 2) }}</td>
                    <td>{{ $sub->starts_at ? \Carbon\Carbon::parse($sub->starts_at)->format('M d, Y') : '-' }}</td>
                    <td>{{ $sub->expires_at ? \Carbon\Carbon::parse($sub->expires_at)->format('M d, Y') : '-' }}</td>
                    <td>
                        @if($sub->status === 'active')
                            <span class="badge bg-success badge-status">Active</span>
                        @elseif($sub->status === 'expired')
                            <span class="badge bg-secondary badge-status">Expired</span>
                        @else
                            <span class="badge bg-danger badge-status">{{ ucfirst($sub->status) }}</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center text-muted py-3">No subscriptions found</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">{{ $subscriptions->links() }}</div>
@endsection
