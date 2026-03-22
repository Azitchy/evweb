@extends('layouts.app')
@section('title', 'Charging Sessions')
@section('page-title', 'Charging Sessions')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <form class="d-flex gap-2" method="GET">
        <select name="status" class="form-select form-select-sm" style="width:140px">
            <option value="">All Status</option>
            <option value="charging" {{ request('status') === 'charging' ? 'selected' : '' }}>Charging</option>
            <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
            <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
        </select>
        <input type="date" name="date_from" class="form-control form-control-sm" value="{{ request('date_from') }}" style="width:150px">
        <input type="date" name="date_to" class="form-control form-control-sm" value="{{ request('date_to') }}" style="width:150px">
        <button class="btn btn-sm btn-primary"><i class="bi bi-search"></i></button>
        <a href="{{ route('web.sessions.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-x-lg"></i></a>
    </form>
</div>

<div class="table-card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>User</th>
                    <th>Station</th>
                    <th>Start %</th>
                    <th>End %</th>
                    <th>Charged</th>
                    <th>Cost</th>
                    <th>Price/Unit</th>
                    <th>Status</th>
                    <th>Started</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($sessions as $session)
                <tr>
                    <td>{{ $session->id }}</td>
                    <td>
                        @if($session->user)
                            <a href="{{ route('web.users.show', $session->user) }}">{{ $session->user->name }}</a>
                        @else
                            N/A
                        @endif
                    </td>
                    <td>
                        @if($session->chargingStation)
                            <a href="{{ route('web.stations.show', $session->chargingStation) }}">{{ Str::limit($session->chargingStation->name, 20) }}</a>
                        @else
                            N/A
                        @endif
                    </td>
                    <td>{{ $session->start_percentage }}%</td>
                    <td>{{ $session->end_percentage ?? '-' }}%</td>
                    <td>{{ $session->charged_percentage ?? 0 }}%</td>
                    <td>Rs. {{ number_format($session->cost ?? 0, 2) }}</td>
                    <td>Rs. {{ number_format($session->price_per_percentage ?? 0, 2) }}</td>
                    <td>
                        @if($session->status === 'charging')
                            <span class="badge bg-warning text-dark badge-status">Charging</span>
                        @elseif($session->status === 'completed')
                            <span class="badge bg-success badge-status">Completed</span>
                        @else
                            <span class="badge bg-secondary badge-status">{{ ucfirst($session->status) }}</span>
                        @endif
                    </td>
                    <td>{{ $session->started_at ? \Carbon\Carbon::parse($session->started_at)->format('M d, H:i') : $session->created_at->format('M d, H:i') }}</td>
                    <td>
                        <a href="{{ route('web.sessions.show', $session) }}" class="btn btn-sm btn-outline-info"><i class="bi bi-eye"></i></a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="11" class="text-center text-muted py-3">No sessions found</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">{{ $sessions->links() }}</div>
@endsection
