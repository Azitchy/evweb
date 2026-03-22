@extends('layouts.app')
@section('title', 'Session Details')
@section('page-title', 'Session #' . $session->id)

@section('content')
<div class="row g-4">
    <div class="col-lg-6">
        <div class="stat-card">
            <h6 class="mb-3">Session Information</h6>
            <div class="d-flex justify-content-between py-2 border-bottom">
                <span class="text-muted">User</span>
                <a href="{{ route('web.users.show', $session->user) }}"><strong>{{ $session->user->name ?? 'N/A' }}</strong></a>
            </div>
            <div class="d-flex justify-content-between py-2 border-bottom">
                <span class="text-muted">Station</span>
                <a href="{{ route('web.stations.show', $session->chargingStation) }}"><strong>{{ $session->chargingStation->name ?? 'N/A' }}</strong></a>
            </div>
            <div class="d-flex justify-content-between py-2 border-bottom">
                <span class="text-muted">Start Percentage</span>
                <strong>{{ $session->start_percentage }}%</strong>
            </div>
            <div class="d-flex justify-content-between py-2 border-bottom">
                <span class="text-muted">End Percentage</span>
                <strong>{{ $session->end_percentage ?? 'In Progress' }}%</strong>
            </div>
            <div class="d-flex justify-content-between py-2 border-bottom">
                <span class="text-muted">Charged</span>
                <strong>{{ $session->charged_percentage ?? 0 }}%</strong>
            </div>
            <div class="d-flex justify-content-between py-2 border-bottom">
                <span class="text-muted">Price Per %</span>
                <strong>Rs. {{ number_format($session->price_per_percentage ?? 0, 2) }}</strong>
            </div>
            <div class="d-flex justify-content-between py-2 border-bottom">
                <span class="text-muted">Total Cost</span>
                <strong class="text-success">Rs. {{ number_format($session->cost ?? 0, 2) }}</strong>
            </div>
            <div class="d-flex justify-content-between py-2 border-bottom">
                <span class="text-muted">Status</span>
                @if($session->status === 'charging')
                    <span class="badge bg-warning text-dark badge-status">Charging</span>
                @elseif($session->status === 'completed')
                    <span class="badge bg-success badge-status">Completed</span>
                @else
                    <span class="badge bg-secondary badge-status">{{ ucfirst($session->status) }}</span>
                @endif
            </div>
            <div class="d-flex justify-content-between py-2 border-bottom">
                <span class="text-muted">Started At</span>
                <strong>{{ $session->started_at ? \Carbon\Carbon::parse($session->started_at)->format('M d, Y H:i:s') : '-' }}</strong>
            </div>
            <div class="d-flex justify-content-between py-2">
                <span class="text-muted">Ended At</span>
                <strong>{{ $session->ended_at ? \Carbon\Carbon::parse($session->ended_at)->format('M d, Y H:i:s') : '-' }}</strong>
            </div>
        </div>

        <div class="mt-3">
            <a href="{{ route('web.sessions.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left"></i> Back to Sessions</a>
        </div>
    </div>
</div>
@endsection
