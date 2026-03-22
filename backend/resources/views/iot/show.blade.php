@extends('layouts.app')
@section('title', 'Device Details')
@section('page-title', 'Device: ' . $device->device_name)

@section('content')
<div class="row g-4">
    <div class="col-lg-4">
        <div class="stat-card mb-3">
            <div class="d-flex align-items-center gap-3 mb-3">
                <div class="icon-box bg-info bg-opacity-10 text-info" style="width:56px;height:56px;font-size:1.5rem">
                    <i class="bi bi-cpu-fill"></i>
                </div>
                <div>
                    <h5 class="mb-0">{{ $device->device_name }}</h5>
                    @if($device->status === 'online')
                        <span class="badge bg-success">Online</span>
                    @elseif($device->status === 'error')
                        <span class="badge bg-danger">Error</span>
                    @elseif($device->status === 'maintenance')
                        <span class="badge bg-warning text-dark">Maintenance</span>
                    @else
                        <span class="badge bg-secondary">Offline</span>
                    @endif
                </div>
            </div>
            <hr>
            <p class="mb-1"><i class="bi bi-hash me-2"></i>Device ID: <code>{{ $device->device_id }}</code></p>
            <p class="mb-1"><i class="bi bi-ev-station me-2"></i>Station: <a href="{{ route('web.stations.show', $device->chargingStation) }}">{{ $device->chargingStation->name ?? 'N/A' }}</a></p>
            <p class="mb-1"><i class="bi bi-gear me-2"></i>Firmware: {{ $device->firmware_version ?? 'N/A' }}</p>
            <p class="mb-0"><i class="bi bi-clock me-2"></i>Last Heartbeat: {{ $device->last_heartbeat_at ? \Carbon\Carbon::parse($device->last_heartbeat_at)->diffForHumans() : 'Never' }}</p>
        </div>

        <div class="stat-card mb-3">
            <h6>Live Readings</h6>
            <div class="row g-3 mt-1">
                <div class="col-6">
                    <div class="p-2 bg-light rounded text-center">
                        <div class="text-muted small">Power</div>
                        <strong>{{ $device->current_power_kw ?? 0 }} kW</strong>
                    </div>
                </div>
                <div class="col-6">
                    <div class="p-2 bg-light rounded text-center">
                        <div class="text-muted small">Voltage</div>
                        <strong>{{ $device->voltage ?? 0 }} V</strong>
                    </div>
                </div>
                <div class="col-6">
                    <div class="p-2 bg-light rounded text-center">
                        <div class="text-muted small">Current</div>
                        <strong>{{ $device->current_amps ?? 0 }} A</strong>
                    </div>
                </div>
                <div class="col-6">
                    <div class="p-2 bg-light rounded text-center">
                        <div class="text-muted small">Temperature</div>
                        <strong>{{ $device->temperature ?? 0 }}°C</strong>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('web.iot.edit', $device) }}" class="btn btn-warning btn-sm"><i class="bi bi-pencil"></i> Edit</a>
            <a href="{{ route('web.iot.index') }}" class="btn btn-outline-secondary btn-sm">Back</a>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="table-card">
            <div class="card-header">Recent Telemetry (Last 50)</div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Power</th>
                            <th>Voltage</th>
                            <th>Current</th>
                            <th>Temp</th>
                            <th>Energy</th>
                            <th>Battery %</th>
                            <th>Timestamp</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($device->telemetry as $t)
                        <tr>
                            <td>{{ $t->power_kw }} kW</td>
                            <td>{{ $t->voltage }} V</td>
                            <td>{{ $t->current_amps }} A</td>
                            <td>{{ $t->temperature }}°C</td>
                            <td>{{ $t->energy_kwh }} kWh</td>
                            <td>{{ $t->battery_percentage }}%</td>
                            <td>{{ $t->created_at->format('M d, H:i:s') }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="7" class="text-center text-muted py-3">No telemetry data</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
