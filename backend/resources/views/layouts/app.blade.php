<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'EV Charging Admin') - EVWeb</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        :root {
            --sidebar-width: 260px;
            --primary-green: #00b894;
            --dark-bg: #1a1a2e;
            --sidebar-bg: #16213e;
        }
        body { background: #f0f2f5; font-family: 'Segoe UI', sans-serif; }
        .sidebar {
            width: var(--sidebar-width);
            min-height: 100vh;
            background: var(--sidebar-bg);
            position: fixed;
            top: 0; left: 0;
            z-index: 1000;
            transition: transform 0.3s;
        }
        .sidebar .brand {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid rgba(255,255,255,0.08);
        }
        .sidebar .brand h4 {
            color: var(--primary-green);
            margin: 0;
            font-weight: 700;
        }
        .sidebar .nav-link {
            color: rgba(255,255,255,0.65);
            padding: 0.7rem 1.5rem;
            border-radius: 0;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            transition: all 0.2s;
        }
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            color: #fff;
            background: rgba(0,184,148,0.15);
            border-left: 3px solid var(--primary-green);
        }
        .sidebar .nav-link i { font-size: 1.1rem; width: 20px; text-align: center; }
        .sidebar .nav-section {
            color: rgba(255,255,255,0.35);
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 1rem 1.5rem 0.5rem;
            font-weight: 600;
        }
        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
        }
        .top-navbar {
            background: #fff;
            padding: 0.75rem 1.5rem;
            border-bottom: 1px solid #e0e0e0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .content-area { padding: 1.5rem; }
        .stat-card {
            background: #fff;
            border-radius: 12px;
            padding: 1.5rem;
            border: none;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
            transition: transform 0.2s;
        }
        .stat-card:hover { transform: translateY(-2px); }
        .stat-card .icon-box {
            width: 48px; height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
        }
        .table-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
            overflow: hidden;
        }
        .table-card .card-header {
            background: #fff;
            border-bottom: 1px solid #eee;
            padding: 1rem 1.25rem;
            font-weight: 600;
        }
        .badge-status {
            padding: 0.35em 0.75em;
            border-radius: 20px;
            font-size: 0.78rem;
            font-weight: 500;
        }
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.show { transform: translateX(0); }
            .main-content { margin-left: 0; }
        }
    </style>
    @stack('styles')
</head>
<body>
    <!-- Sidebar -->
    <nav class="sidebar" id="sidebar">
        <div class="brand">
            <h4><i class="bi bi-ev-station"></i> EVWeb</h4>
            <small class="text-muted">Management Panel</small>
        </div>
        <div class="py-2">
            <div class="nav-section">Main</div>
            <a href="{{ route('web.dashboard') }}" class="nav-link {{ request()->routeIs('web.dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid-1x2-fill"></i> Dashboard
            </a>

            <div class="nav-section">Management</div>
            <a href="{{ route('web.users.index') }}" class="nav-link {{ request()->routeIs('web.users.*') ? 'active' : '' }}">
                <i class="bi bi-people-fill"></i> Users
            </a>
            <a href="{{ route('web.stations.index') }}" class="nav-link {{ request()->routeIs('web.stations.*') ? 'active' : '' }}">
                <i class="bi bi-ev-station-fill"></i> Charging Stations
            </a>
            <a href="{{ route('web.sessions.index') }}" class="nav-link {{ request()->routeIs('web.sessions.*') ? 'active' : '' }}">
                <i class="bi bi-lightning-charge-fill"></i> Charging Sessions
            </a>
            <a href="{{ route('web.iot.index') }}" class="nav-link {{ request()->routeIs('web.iot.*') ? 'active' : '' }}">
                <i class="bi bi-cpu-fill"></i> IoT Devices
            </a>

            <div class="nav-section">Finance</div>
            <a href="{{ route('web.transactions.index') }}" class="nav-link {{ request()->routeIs('web.transactions.*') ? 'active' : '' }}">
                <i class="bi bi-receipt"></i> Transactions
            </a>
            <a href="{{ route('web.payments.index') }}" class="nav-link {{ request()->routeIs('web.payments.*') ? 'active' : '' }}">
                <i class="bi bi-credit-card-fill"></i> Payments
            </a>
            <a href="{{ route('web.pricing.index') }}" class="nav-link {{ request()->routeIs('web.pricing.*') ? 'active' : '' }}">
                <i class="bi bi-tags-fill"></i> Pricing
            </a>

            <div class="nav-section">Subscriptions</div>
            <a href="{{ route('web.plans.index') }}" class="nav-link {{ request()->routeIs('web.plans.*') ? 'active' : '' }}">
                <i class="bi bi-card-checklist"></i> Plans
            </a>
            <a href="{{ route('web.subscriptions.index') }}" class="nav-link {{ request()->routeIs('web.subscriptions.*') ? 'active' : '' }}">
                <i class="bi bi-person-badge-fill"></i> User Subscriptions
            </a>

            <div class="nav-section">System</div>
            <a href="{{ route('web.notifications.index') }}" class="nav-link {{ request()->routeIs('web.notifications.*') ? 'active' : '' }}">
                <i class="bi bi-bell-fill"></i> Notifications
            </a>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="main-content">
        <div class="top-navbar">
            <div class="d-flex align-items-center gap-3">
                <button class="btn btn-sm btn-outline-secondary d-md-none" onclick="document.getElementById('sidebar').classList.toggle('show')">
                    <i class="bi bi-list"></i>
                </button>
                <h5 class="mb-0">@yield('page-title', 'Dashboard')</h5>
            </div>
            <div class="d-flex align-items-center gap-3">
                <span class="text-muted small">{{ Auth::user()->name ?? 'Admin' }}</span>
                <form method="POST" action="{{ route('web.logout') }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-danger">
                        <i class="bi bi-box-arrow-right"></i> Logout
                    </button>
                </form>
            </div>
        </div>

        <div class="content-area">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
