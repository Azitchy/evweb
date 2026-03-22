@extends('layouts.app')
@section('title', 'Users')
@section('page-title', 'Users Management')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <form class="d-flex gap-2" method="GET">
        <input type="text" name="search" class="form-control form-control-sm" placeholder="Search name, email, phone..." value="{{ request('search') }}" style="width:220px">
        <select name="role" class="form-select form-select-sm" style="width:120px">
            <option value="">All Roles</option>
            <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
            <option value="user" {{ request('role') === 'user' ? 'selected' : '' }}>User</option>
        </select>
        <select name="status" class="form-select form-select-sm" style="width:120px">
            <option value="">All Status</option>
            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
        </select>
        <button class="btn btn-sm btn-primary"><i class="bi bi-search"></i></button>
        <a href="{{ route('web.users.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-x-lg"></i></a>
    </form>
    <a href="{{ route('web.users.create') }}" class="btn btn-sm btn-success"><i class="bi bi-plus-lg"></i> Add User</a>
</div>

<div class="table-card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Role</th>
                    <th>Wallet</th>
                    <th>Status</th>
                    <th>Joined</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td><a href="{{ route('web.users.show', $user) }}">{{ $user->name }}</a></td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->phone }}</td>
                    <td><span class="badge {{ $user->role === 'admin' ? 'bg-primary' : 'bg-secondary' }} badge-status">{{ ucfirst($user->role) }}</span></td>
                    <td>Rs. {{ number_format($user->wallet->balance ?? 0, 2) }}</td>
                    <td>
                        @if($user->is_active)
                            <span class="badge bg-success badge-status">Active</span>
                        @else
                            <span class="badge bg-danger badge-status">Inactive</span>
                        @endif
                    </td>
                    <td>{{ $user->created_at->format('M d, Y') }}</td>
                    <td>
                        <div class="btn-group btn-group-sm">
                            <a href="{{ route('web.users.show', $user) }}" class="btn btn-outline-info" title="View"><i class="bi bi-eye"></i></a>
                            <a href="{{ route('web.users.edit', $user) }}" class="btn btn-outline-warning" title="Edit"><i class="bi bi-pencil"></i></a>
                            <form method="POST" action="{{ route('web.users.toggle-status', $user) }}" class="d-inline">
                                @csrf @method('PATCH')
                                <button type="submit" class="btn {{ $user->is_active ? 'btn-outline-danger' : 'btn-outline-success' }}" title="{{ $user->is_active ? 'Deactivate' : 'Activate' }}">
                                    <i class="bi {{ $user->is_active ? 'bi-person-x' : 'bi-person-check' }}"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="9" class="text-center text-muted py-3">No users found</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">{{ $users->links() }}</div>
@endsection
