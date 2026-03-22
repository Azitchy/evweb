@extends('layouts.app')
@section('title', 'Station Details')
@section('page-title', 'Station: ' . $station->name)

@section('content')
<div class="row g-4">
    <div class="col-lg-4">
        <div class="stat-card mb-3">
            <div class="d-flex align-items-center gap-3 mb-3">
                <div class="icon-box bg-success bg-opacity-10 text-success" style="width:56px;height:56px;font-size:1.5rem">
                    <i class="bi bi-ev-station-fill"></i>
                </div>
                <div>
                    <h5 class="mb-0">{{ $station->name }}</h5>
                    @if($station->status === 'active')
                        <span class="badge bg-success">Active</span>
                    @elseif($station->status === 'maintenance')
                        <span class="badge bg-warning text-dark">Maintenance</span>
                    @else
                        <span class="badge bg-secondary">Inactive</span>
                    @endif
                </div>
            </div>
            <hr>
            <p class="mb-1"><i class="bi bi-geo-alt me-2"></i>{{ $station->address }}</p>
            <p class="mb-1"><i class="bi bi-pin-map me-2"></i>{{ $station->latitude }}, {{ $station->longitude }}</p>
            <p class="mb-1"><i class="bi bi-plug me-2"></i>Type: <strong>{{ $station->charger_type }}</strong></p>
            <p class="mb-1"><i class="bi bi-lightning me-2"></i>Power: <strong>{{ $station->power_kw }} kW</strong></p>
            <p class="mb-1"><i class="bi bi-usb-drive me-2"></i>Ports: <strong>{{ $station->available_ports }}/{{ $station->total_ports }}</strong></p>
            @if($station->description)
                <p class="mb-0 mt-2 text-muted">{{ $station->description }}</p>
            @endif
        </div>

        <div class="d-flex gap-2 mb-3">
            <a href="{{ route('web.stations.edit', $station) }}" class="btn btn-warning btn-sm"><i class="bi bi-pencil"></i> Edit</a>
            <a href="{{ route('web.stations.index') }}" class="btn btn-outline-secondary btn-sm">Back</a>
        </div>

        <!-- IoT Devices -->
        <div class="table-card">
            <div class="card-header">IoT Devices ({{ $station->iotDevices->count() }})</div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr><th>Device</th><th>Status</th><th>Power</th></tr>
                    </thead>
                    <tbody>
                        @forelse($station->iotDevices as $device)
                        <tr>
                            <td><a href="{{ route('web.iot.show', $device) }}">{{ $device->device_name }}</a></td>
                            <td>
                                <span class="badge {{ $device->status === 'online' ? 'bg-success' : ($device->status === 'error' ? 'bg-danger' : 'bg-secondary') }} badge-status">
                                    {{ ucfirst($device->status) }}
                                </span>
                            </td>
                            <td>{{ $device->current_power_kw ?? 0 }} kW</td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="text-center text-muted py-3">No devices</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="table-card">
            <div class="card-header">Recent Charging Sessions</div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr><th>User</th><th>Start %</th><th>End %</th><th>Charged</th><th>Cost</th><th>Status</th><th>Date</th></tr>
                    </thead>
                    <tbody>
                        @forelse($recentSessions as $session)
                        <tr>
                            <td>{{ $session->user->name ?? 'N/A' }}</td>
                            <td>{{ $session->start_percentage }}%</td>
                            <td>{{ $session->end_percentage ?? '-' }}%</td>
                            <td>{{ $session->charged_percentage ?? 0 }}%</td>
                            <td>Rs. {{ number_format($session->cost ?? 0, 2) }}</td>
                            <td>
                                <span class="badge {{ $session->status === 'completed' ? 'bg-success' : ($session->status === 'charging' ? 'bg-warning text-dark' : 'bg-secondary') }} badge-status">
                                    {{ ucfirst($session->status) }}
                                </span>
                            </td>
                            <td>{{ $session->created_at->format('M d, H:i') }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="7" class="text-center text-muted py-3">No sessions at this station</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
