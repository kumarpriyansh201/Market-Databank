<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Market Databank')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body>
    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <div class="logo-icon"><i class="fas fa-chart-line"></i></div>
            <div>
                <h5>Market Databank</h5>
                <span>Data Platform</span>
            </div>
        </div>

        <nav class="sidebar-nav">
            <div class="nav-label">Main</div>
            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="fas fa-th-large"></i> Dashboard
            </a>

            @if(auth()->user()->isUser())
                <a href="{{ route('market-data.index') }}" class="nav-link {{ request()->routeIs('market-data.index') || request()->routeIs('market-data.show') ? 'active' : '' }}">
                    <i class="fas fa-compass"></i> Explore Data
                </a>
                <a href="{{ route('saved-datasets.index') }}" class="nav-link {{ request()->routeIs('saved-datasets.*') ? 'active' : '' }}">
                    <i class="fas fa-bookmark"></i> Saved Datasets
                </a>
                <a href="{{ route('reports.index') }}" class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                    <i class="fas fa-file-alt"></i> Reports
                </a>
            @endif

            @if(auth()->user()->isContributor())
                <a href="{{ route('market-data.mine') }}" class="nav-link {{ request()->routeIs('market-data.mine') ? 'active' : '' }}">
                    <i class="fas fa-table-list"></i> My Datasets
                </a>
                <a href="{{ route('market-data.create') }}" class="nav-link {{ request()->routeIs('market-data.create') ? 'active' : '' }}">
                    <i class="fas fa-upload"></i> Upload Dataset
                </a>
                <a href="{{ route('analytics') }}" class="nav-link {{ request()->routeIs('analytics*') ? 'active' : '' }}">
                    <i class="fas fa-chart-bar"></i> Analytics
                </a>
                <a href="{{ route('reports.index') }}" class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                    <i class="fas fa-file-alt"></i> Reports
                </a>
            @endif

            @if(auth()->user()->isAdmin())
                <a href="{{ route('market-data.index') }}" class="nav-link {{ request()->routeIs('market-data.*') ? 'active' : '' }}">
                    <i class="fas fa-database"></i> Market Data
                </a>
                <a href="{{ route('reports.index') }}" class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                    <i class="fas fa-file-alt"></i> Reports
                </a>
            @endif

            @if(auth()->user()->isAdmin())
                <div class="nav-label">Administration</div>
                <a href="{{ route('admin.users') }}" class="nav-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
                    <i class="fas fa-users-cog"></i> Users
                </a>
                <a href="{{ route('admin.approvals') }}" class="nav-link {{ request()->routeIs('admin.approvals*') ? 'active' : '' }}">
                    <i class="fas fa-check-double"></i> Approvals
                </a>
                <a href="{{ route('admin.categories') }}" class="nav-link {{ request()->routeIs('admin.categories*') ? 'active' : '' }}">
                    <i class="fas fa-tags"></i> Categories
                </a>
                <a href="{{ route('admin.products') }}" class="nav-link {{ request()->routeIs('admin.products*') ? 'active' : '' }}">
                    <i class="fas fa-boxes"></i> Products
                </a>
                <a href="{{ route('admin.locations') }}" class="nav-link {{ request()->routeIs('admin.locations*') ? 'active' : '' }}">
                    <i class="fas fa-map-marker-alt"></i> Locations
                </a>
            @endif

            <div class="nav-label">Account</div>
            <a href="{{ route('profile') }}" class="nav-link {{ request()->routeIs('profile') ? 'active' : '' }}">
                <i class="fas fa-user-circle"></i> Profile
            </a>
            <a href="{{ route('notifications') }}" class="nav-link {{ request()->routeIs('notifications') ? 'active' : '' }}">
                <i class="fas fa-bell"></i> Notifications
                @if(auth()->user()->unreadNotifications()->count() > 0)
                    <span class="badge bg-danger ms-auto" style="font-size:0.65rem">{{ auth()->user()->unreadNotifications()->count() }}</span>
                @endif
            </a>
            <a href="{{ url('/logout') }}" class="nav-link">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </nav>
    </aside>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Navbar -->
        <header class="top-navbar">
            <div class="d-flex align-items-center gap-3">
                <button class="sidebar-toggle" onclick="document.getElementById('sidebar').classList.toggle('show')">
                    <i class="fas fa-bars"></i>
                </button>
                <form class="search-box d-none d-md-block" method="GET" action="{{ route('market-data.index') }}">
                    <i class="fas fa-search"></i>
                    <input type="text" placeholder="Search market data..." id="globalSearch" name="search" value="{{ request('search') }}">
                    <button type="submit" aria-label="Search">
                        <i class="fas fa-arrow-right"></i>
                    </button>
                </form>
            </div>
            <div class="navbar-actions">
                <button id="darkModeToggle" class="notification-btn text-decoration-none" onclick="toggleDarkMode()" title="Toggle Dark Mode" style="background: none; border: none; cursor: pointer;">
                    <i class="fas fa-moon"></i>
                </button>
                <a href="{{ route('notifications') }}" class="notification-btn text-decoration-none">
                    <i class="fas fa-bell"></i>
                    @if(auth()->user()->unreadNotifications()->count() > 0)
                        <span class="notification-badge">{{ auth()->user()->unreadNotifications()->count() }}</span>
                    @endif
                </a>
                <div class="dropdown">
                    <div class="user-menu" data-bs-toggle="dropdown">
                        <div class="user-avatar">
                            @if(auth()->user()->avatar)
                                <img src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="{{ auth()->user()->name }}">
                            @else
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            @endif
                        </div>
                        <div class="d-none d-md-block">
                            <div style="font-size:0.85rem;font-weight:600;color:var(--gray-900)">{{ auth()->user()->name }}</div>
                            <div style="font-size:0.7rem;color:var(--gray-500)">{{ auth()->user()->roleLabel() }}</div>
                        </div>
                    </div>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="{{ route('profile') }}"><i class="fas fa-user me-2"></i>Profile</a></li>
                        <li><a class="dropdown-item" href="{{ route('notifications') }}"><i class="fas fa-bell me-2"></i>Notifications</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="{{ url('/logout') }}"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                    </ul>
                </div>
            </div>
        </header>

        <!-- Page Content -->
        <div class="page-content">
            @if(session('success'))
                <div class="alert alert-modern alert-success alert-dismissible fade show" role="alert" style="background:#d1fae5;color:#065f46;">
                    <i class="fas fa-check-circle"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-modern alert-danger alert-dismissible fade show" role="alert" style="background:#fee2e2;color:#991b1b;">
                    <i class="fas fa-exclamation-circle"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-modern alert-danger alert-dismissible fade show" role="alert" style="background:#fee2e2;color:#991b1b;">
                    <div>
                        <i class="fas fa-exclamation-triangle me-1"></i>
                        <ul class="mb-0 ps-3">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>

    <!-- Dark Mode Script -->
    <script>
        // Initialize dark mode on page load
        document.addEventListener('DOMContentLoaded', function() {
            const isDarkMode = localStorage.getItem('darkMode') === 'true';
            if (isDarkMode) {
                document.documentElement.setAttribute('data-theme', 'dark');
                updateDarkModeIcon();
            }
        });

        function toggleDarkMode() {
            const isDarkMode = document.documentElement.getAttribute('data-theme') === 'dark';
            
            if (isDarkMode) {
                document.documentElement.removeAttribute('data-theme');
                localStorage.setItem('darkMode', 'false');
            } else {
                document.documentElement.setAttribute('data-theme', 'dark');
                localStorage.setItem('darkMode', 'true');
            }
            
            updateDarkModeIcon();
        }

        function updateDarkModeIcon() {
            const isDarkMode = document.documentElement.getAttribute('data-theme') === 'dark';
            const btn = document.getElementById('darkModeToggle');
            if (btn) {
                btn.innerHTML = isDarkMode ? '<i class="fas fa-sun"></i>' : '<i class="fas fa-moon"></i>';
            }
        }
    </script>

    <!-- Dark Mode Styles -->
    <style>
        /* Dark Mode Variables */
        [data-theme="dark"] {
            --primary: #2dd4bf;
            --primary-light: #5eead4;
            --primary-dark: #0f766e;
            --gray-50: #0f172a;
            --gray-100: #1e293b;
            --gray-200: #334155;
            --gray-300: #475569;
            --gray-500: #94a3b8;
            --gray-700: #cbd5e1;
            --gray-900: #f1f5f9;
            --text-color: #f1f5f9;
            --bg-color: #0f172a;
        }

        /* Apply dark mode styles */
        [data-theme="dark"] {
            background: var(--bg-color);
            color: var(--text-color);
        }

        [data-theme="dark"] body {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #0f172a 100%);
            color: var(--text-color);
        }

        [data-theme="dark"] .sidebar {
            background: linear-gradient(180deg, #0a0e27 0%, #0f172a 45%, #042f2e 100%);
            border-right: 1px solid #334155;
        }

        [data-theme="dark"] .top-navbar {
            background: rgba(15, 23, 42, 0.8);
            border-bottom: 1px solid #334155;
        }

        [data-theme="dark"] .page-content {
            background: transparent;
        }

        [data-theme="dark"] .card-modern,
        [data-theme="dark"] .stat-card {
            background: rgba(30, 41, 59, 0.8);
            border: 1px solid #334155;
            color: var(--text-color);
        }

        [data-theme="dark"] .table-modern thead th {
            background: #1e293b;
            border-bottom: 1px solid #334155;
            color: #94a3b8;
        }

        [data-theme="dark"] .table-modern tbody td {
            border-bottom: 1px solid #334155;
            color: var(--text-color);
        }

        [data-theme="dark"] .table-modern tbody tr:hover td {
            background: rgba(45, 212, 191, 0.1);
        }

        [data-theme="dark"] .form-control,
        [data-theme="dark"] .form-select {
            background: #1e293b;
            border: 1px solid #334155;
            color: var(--text-color);
        }

        [data-theme="dark"] .form-control:focus,
        [data-theme="dark"] .form-select:focus {
            background: #1e293b;
            border-color: #2dd4bf;
            color: var(--text-color);
            box-shadow: 0 0 0 4px rgba(45, 212, 191, 0.2);
        }

        [data-theme="dark"] .modal-content {
            background: #1e293b;
            color: var(--text-color);
            border: 1px solid #334155;
        }

        [data-theme="dark"] .modal-header {
            border-bottom: 1px solid #334155;
            color: var(--text-color);
        }

        [data-theme="dark"] .modal-title {
            color: var(--text-color);
        }

        [data-theme="dark"] .modal-footer {
            border-top: 1px solid #334155;
        }

        /* Button fixes for dark mode */
        [data-theme="dark"] .btn {
            color: inherit;
        }

        [data-theme="dark"] .btn-outline-modern {
            border-color: #334155;
            color: var(--text-color);
            background: transparent;
        }

        [data-theme="dark"] .btn-outline-modern:hover {
            background: rgba(45, 212, 191, 0.1);
            border-color: #2dd4bf;
            color: #2dd4bf;
        }

        [data-theme="dark"] .btn-primary-modern {
            background: linear-gradient(135deg, #2dd4bf 0%, #14b8a6 100%);
            color: #0f172a;
        }

        [data-theme="dark"] .btn-primary-modern:hover {
            color: #0f172a;
        }

        [data-theme="dark"] .btn-secondary {
            background: #334155;
            border-color: #334155;
            color: var(--text-color);
        }

        [data-theme="dark"] .btn-secondary:hover {
            background: #475569;
            border-color: #475569;
            color: var(--text-color);
        }

        [data-theme="dark"] .btn-success {
            background: #10b981;
            border-color: #10b981;
            color: white;
        }

        [data-theme="dark"] .btn-danger {
            background: #ef4444;
            border-color: #ef4444;
            color: white;
        }

        [data-theme="dark"] .btn-sm {
            color: inherit;
        }

        [data-theme="dark"] .badge {
            color: #0f172a;
        }

        [data-theme="dark"] .badge-modern {
            color: #0f172a;
        }

        [data-theme="dark"] .alert-modern {
            background: rgba(45, 212, 191, 0.1);
            border: 1px solid #2dd4bf;
            color: var(--text-color);
        }

        [data-theme="dark"] .dropdown-menu {
            background: #1e293b;
            border: 1px solid #334155;
        }

        [data-theme="dark"] .dropdown-item {
            color: var(--text-color);
        }

        [data-theme="dark"] .dropdown-item:hover,
        [data-theme="dark"] .dropdown-item:focus {
            background: rgba(45, 212, 191, 0.1);
            color: #2dd4bf;
        }

        [data-theme="dark"] .search-box input {
            background: #1e293b;
            border: 1px solid #334155;
            color: var(--text-color);
        }

        [data-theme="dark"] .search-box input::placeholder {
            color: #64748b;
        }

        [data-theme="dark"] .search-box i {
            color: #94a3b8;
        }

        [data-theme="dark"] .page-header h1,
        [data-theme="dark"] .page-header p {
            color: var(--text-color);
        }

        [data-theme="dark"] .text-muted {
            color: #94a3b8 !important;
        }

        [data-theme="dark"] .text-danger {
            color: #ef4444 !important;
        }

        [data-theme="dark"] hr {
            border-color: #334155;
        }

        [data-theme="dark"] input::placeholder,
        [data-theme="dark"] textarea::placeholder {
            color: #64748b;
        }

        [data-theme="dark"] .notification-btn {
            color: var(--text-color);
        }

        [data-theme="dark"] .notification-btn:hover {
            color: #2dd4bf;
        }

        [data-theme="dark"] .user-menu {
            color: var(--text-color);
        }

        [data-theme="dark"] .nav-link {
            color: rgba(255, 255, 255, 0.7);
        }

        [data-theme="dark"] .nav-link:hover,
        [data-theme="dark"] .nav-link.active {
            color: white;
        }

        [data-theme="dark"] a {
            color: #2dd4bf;
        }

        [data-theme="dark"] a:hover {
            color: #5eead4;
        }

        [data-theme="dark"] strong,
        [data-theme="dark"] b {
            color: var(--text-color);
        }

        [data-theme="dark"] label {
            color: var(--text-color);
        }
    </style>

    @stack('scripts')
</body>
</html>
