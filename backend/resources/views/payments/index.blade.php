@extends('layouts.app')
@section('title', 'Payments')
@section('page-title', 'Payment Transactions')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <form class="d-flex gap-2" method="GET">
        <select name="status" class="form-select form-select-sm" style="width:140px">
            <option value="">All Status</option>
            <option value="initiated" {{ request('status') === 'initiated' ? 'selected' : '' }}>Initiated</option>
            <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
            <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Failed</option>
            <option value="refunded" {{ request('status') === 'refunded' ? 'selected' : '' }}>Refunded</option>
        </select>
        <select name="gateway" class="form-select form-select-sm" style="width:120px">
            <option value="">All Gateways</option>
            <option value="esewa" {{ request('gateway') === 'esewa' ? 'selected' : '' }}>eSewa</option>
            <option value="khalti" {{ request('gateway') === 'khalti' ? 'selected' : '' }}>Khalti</option>
        </select>
        <input type="date" name="date_from" class="form-control form-control-sm" value="{{ request('date_from') }}" style="width:150px">
        <input type="date" name="date_to" class="form-control form-control-sm" value="{{ request('date_to') }}" style="width:150px">
        <button class="btn btn-sm btn-primary"><i class="bi bi-search"></i></button>
        <a href="{{ route('web.payments.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-x-lg"></i></a>
    </form>
</div>

<div class="table-card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>User</th>
                    <th>Gateway</th>
                    <th>Amount</th>
                    <th>Purpose</th>
                    <th>Status</th>
                    <th>Transaction ID</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse($payments as $payment)
                <tr>
                    <td>{{ $payment->id }}</td>
                    <td>
                        @if($payment->user)
                            <a href="{{ route('web.users.show', $payment->user) }}">{{ $payment->user->name }}</a>
                        @else
                            N/A
                        @endif
                    </td>
                    <td><span class="badge bg-info badge-status">{{ ucfirst($payment->gateway) }}</span></td>
                    <td>Rs. {{ number_format($payment->amount, 2) }}</td>
                    <td>{{ $payment->purpose ?? '-' }}</td>
                    <td>
                        @if($payment->status === 'completed')
                            <span class="badge bg-success badge-status">Completed</span>
                        @elseif($payment->status === 'initiated')
                            <span class="badge bg-warning text-dark badge-status">Initiated</span>
                        @elseif($payment->status === 'failed')
                            <span class="badge bg-danger badge-status">Failed</span>
                        @else
                            <span class="badge bg-secondary badge-status">{{ ucfirst($payment->status) }}</span>
                        @endif
                    </td>
                    <td><code>{{ Str::limit($payment->gateway_transaction_id ?? '-', 20) }}</code></td>
                    <td>{{ $payment->created_at->format('M d, Y H:i') }}</td>
                </tr>
                @empty
                <tr><td colspan="8" class="text-center text-muted py-3">No payments found</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">{{ $payments->links() }}</div>
@endsection
