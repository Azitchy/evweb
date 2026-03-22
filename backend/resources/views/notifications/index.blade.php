@extends('layouts.app')
@section('title', 'Notifications')
@section('page-title', 'Notifications')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <form class="d-flex gap-2" method="GET">
        <input type="text" name="type" class="form-control form-control-sm" placeholder="Filter by type..." value="{{ request('type') }}" style="width:180px">
        <button class="btn btn-sm btn-primary"><i class="bi bi-search"></i></button>
        <a href="{{ route('web.notifications.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-x-lg"></i></a>
    </form>
</div>

<div class="table-card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>User</th>
                    <th>Title</th>
                    <th>Body</th>
                    <th>Type</th>
                    <th>Read</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse($notifications as $notif)
                <tr class="{{ !$notif->is_read ? 'table-light' : '' }}">
                    <td>{{ $notif->id }}</td>
                    <td>
                        @if($notif->user)
                            <a href="{{ route('web.users.show', $notif->user) }}">{{ $notif->user->name }}</a>
                        @else
                            N/A
                        @endif
                    </td>
                    <td><strong>{{ $notif->title }}</strong></td>
                    <td>{{ Str::limit($notif->body, 60) }}</td>
                    <td><span class="badge bg-info badge-status">{{ $notif->type ?? 'general' }}</span></td>
                    <td>
                        @if($notif->is_read)
                            <span class="text-success"><i class="bi bi-check-circle-fill"></i></span>
                        @else
                            <span class="text-muted"><i class="bi bi-circle"></i></span>
                        @endif
                    </td>
                    <td>{{ $notif->created_at->format('M d, Y H:i') }}</td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center text-muted py-3">No notifications found</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">{{ $notifications->links() }}</div>
@endsection
