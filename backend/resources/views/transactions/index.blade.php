@extends('layouts.app')
@section('title', 'Transactions')
@section('page-title', 'Transactions')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <form class="d-flex gap-2" method="GET">
        <select name="type" class="form-select form-select-sm" style="width:120px">
            <option value="">All Types</option>
            <option value="credit" {{ request('type') === 'credit' ? 'selected' : '' }}>Credit</option>
            <option value="debit" {{ request('type') === 'debit' ? 'selected' : '' }}>Debit</option>
        </select>
        <input type="date" name="date_from" class="form-control form-control-sm" value="{{ request('date_from') }}" style="width:150px">
        <input type="date" name="date_to" class="form-control form-control-sm" value="{{ request('date_to') }}" style="width:150px">
        <button class="btn btn-sm btn-primary"><i class="bi bi-search"></i></button>
        <a href="{{ route('web.transactions.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-x-lg"></i></a>
    </form>
</div>

<div class="table-card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>User</th>
                    <th>Type</th>
                    <th>Amount</th>
                    <th>Balance After</th>
                    <th>Description</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transactions as $txn)
                <tr>
                    <td>{{ $txn->id }}</td>
                    <td>
                        @if($txn->user)
                            <a href="{{ route('web.users.show', $txn->user) }}">{{ $txn->user->name }}</a>
                        @else
                            N/A
                        @endif
                    </td>
                    <td>
                        @if($txn->type === 'credit')
                            <span class="badge bg-success badge-status">Credit</span>
                        @else
                            <span class="badge bg-danger badge-status">Debit</span>
                        @endif
                    </td>
                    <td>Rs. {{ number_format($txn->amount, 2) }}</td>
                    <td>Rs. {{ number_format($txn->balance_after, 2) }}</td>
                    <td>{{ Str::limit($txn->description, 50) }}</td>
                    <td>{{ $txn->created_at->format('M d, Y H:i') }}</td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center text-muted py-3">No transactions found</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">{{ $transactions->links() }}</div>
@endsection
