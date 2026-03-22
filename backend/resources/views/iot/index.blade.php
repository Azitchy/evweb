@extends('layouts.app')
@section('title', 'IoT Devices')
@section('page-title', 'IoT Devices')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <form class="d-flex gap-2" method="GET">
        <input type="text" name="search" class="form-control form-control-sm" placeholder="Search device ID or name..." value="{{ request('search') }}" style="width:220px">
        <select name="status" class="form-select form-select-sm" style="width:140px">
            <option value="">All Status</option>
            <option value="online" {{ request('status') === 'online' ? 'selected' : '' }}>Online</option>
            <option value="offline" {{ request('status') === 'offline' ? 'selected' : '' }}>Offline</option>
            <option value="maintenance" {{ request('status') === 'maintenance' ? 'selected' : '' }}>Maintenance</option>
            <option value="error" {{ request('status') === 'error' ? 'selected' : '' }}>Error</option>
        </select>
        <button class="btn btn-sm btn-primary"><i class="bi bi-search"></i></button>
        <a href="{{ route('web.iot.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-x-lg"></i></a>
    </form>
    <a href="{{ route('web.iot.create') }}" class="btn btn-sm btn-success"><i class="bi bi-plus-lg"></i> Register Device</a>
</div>

<div class="table-card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Device ID</th>
                    <th>Name</th>
                    <th>Station</th>
                    <th>Status</th>
                    <th>Power</th>
                    <th>Voltage</th>
                    <th>Temp</th>
                    <th>Firmware</th>
                    <th>Last Heartbeat</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($devices as $device)
                <tr>
                    <td>{{ $device->id }}</td>
                    <td><code>{{ $device->device_id }}</code></td>
                    <td><a href="{{ route('web.iot.show', $device) }}">{{ $device->device_name }}</a></td>
                    <td>
                        @if($device->chargingStation)
                            <a href="{{ route('web.stations.show', $device->chargingStation) }}">{{ Str::limit($device->chargingStation->name, 20) }}</a>
                        @else
                            N/A
                        @endif
                    </td>
                    <td>
                        @if($device->status === 'online')
                            <span class="badge bg-success badge-status">Online</span>
                        @elseif($device->status === 'error')
                            <span class="badge bg-danger badge-status">Error</span>
                        @elseif($device->status === 'maintenance')
                            <span class="badge bg-warning text-dark badge-status">Maintenance</span>
                        @else
                            <span class="badge bg-secondary badge-status">Offline</span>
                        @endif
                    </td>
                    <td>{{ $device->current_power_kw ?? 0 }} kW</td>
                    <td>{{ $device->voltage ?? 0 }} V</td>
                    <td>{{ $device->temperature ?? 0 }}°C</td>
                    <td><small>{{ $device->firmware_version ?? '-' }}</small></td>
                    <td>
                        @if($device->last_heartbeat_at)
                            <small>{{ \Carbon\Carbon::parse($device->last_heartbeat_at)->diffForHumans() }}</small>
                        @else
                            <small class="text-muted">Never</small>
                        @endif
                    </td>
                    <td>
                        <div class="btn-group btn-group-sm">
                            <a href="{{ route('web.iot.show', $device) }}" class="btn btn-outline-info"><i class="bi bi-eye"></i></a>
                            <a href="{{ route('web.iot.edit', $device) }}" class="btn btn-outline-warning"><i class="bi bi-pencil"></i></a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="11" class="text-center text-muted py-3">No devices found</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">{{ $devices->links() }}</div>
@endsection
