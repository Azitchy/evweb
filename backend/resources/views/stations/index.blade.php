@extends('layouts.app')
@section('title', 'Charging Stations')
@section('page-title', 'Charging Stations')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <form class="d-flex gap-2" method="GET">
        <input type="text" name="search" class="form-control form-control-sm" placeholder="Search name or address..." value="{{ request('search') }}" style="width:220px">
        <select name="status" class="form-select form-select-sm" style="width:140px">
            <option value="">All Status</option>
            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
            <option value="maintenance" {{ request('status') === 'maintenance' ? 'selected' : '' }}>Maintenance</option>
        </select>
        <select name="charger_type" class="form-select form-select-sm" style="width:140px">
            <option value="">All Types</option>
            <option value="Type 1" {{ request('charger_type') === 'Type 1' ? 'selected' : '' }}>Type 1</option>
            <option value="Type 2" {{ request('charger_type') === 'Type 2' ? 'selected' : '' }}>Type 2</option>
            <option value="CCS" {{ request('charger_type') === 'CCS' ? 'selected' : '' }}>CCS</option>
            <option value="CHAdeMO" {{ request('charger_type') === 'CHAdeMO' ? 'selected' : '' }}>CHAdeMO</option>
        </select>
        <button class="btn btn-sm btn-primary"><i class="bi bi-search"></i></button>
        <a href="{{ route('web.stations.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-x-lg"></i></a>
    </form>
    <a href="{{ route('web.stations.create') }}" class="btn btn-sm btn-success"><i class="bi bi-plus-lg"></i> Add Station</a>
</div>

<div class="table-card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Address</th>
                    <th>Type</th>
                    <th>Power</th>
                    <th>Ports</th>
                    <th>IoT Devices</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($stations as $station)
                <tr>
                    <td>{{ $station->id }}</td>
                    <td><a href="{{ route('web.stations.show', $station) }}">{{ $station->name }}</a></td>
                    <td>{{ Str::limit($station->address, 30) }}</td>
                    <td><span class="badge bg-info badge-status">{{ $station->charger_type }}</span></td>
                    <td>{{ $station->power_kw }} kW</td>
                    <td>{{ $station->available_ports }}/{{ $station->total_ports }}</td>
                    <td>{{ $station->iot_devices_count }}</td>
                    <td>
                        @if($station->status === 'active')
                            <span class="badge bg-success badge-status">Active</span>
                        @elseif($station->status === 'maintenance')
                            <span class="badge bg-warning text-dark badge-status">Maintenance</span>
                        @else
                            <span class="badge bg-secondary badge-status">Inactive</span>
                        @endif
                    </td>
                    <td>
                        <div class="btn-group btn-group-sm">
                            <a href="{{ route('web.stations.show', $station) }}" class="btn btn-outline-info"><i class="bi bi-eye"></i></a>
                            <a href="{{ route('web.stations.edit', $station) }}" class="btn btn-outline-warning"><i class="bi bi-pencil"></i></a>
                            <form method="POST" action="{{ route('web.stations.destroy', $station) }}" class="d-inline" onsubmit="return confirm('Delete this station?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="9" class="text-center text-muted py-3">No stations found</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">{{ $stations->links() }}</div>
@endsection
