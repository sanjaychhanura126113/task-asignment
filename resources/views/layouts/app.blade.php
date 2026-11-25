<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Task Assignment Portal') - TaskFlow</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5.3 & Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        :root {
            --tf-primary: #4f46e5;
            --tf-primary-dark: #4338ca;
            --tf-primary-light: #e0e7ff;
            --tf-body-bg: #f8fafc;
            --tf-card-bg: #ffffff;
            --tf-border: #e2e8f0;
            --tf-text: #1e293b;
            --tf-text-muted: #64748b;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--tf-body-bg);
            color: var(--tf-text);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .navbar-brand {
            font-weight: 800;
            letter-spacing: -0.5px;
            color: var(--tf-primary) !important;
        }

        .navbar {
            background-color: #ffffff;
            border-bottom: 1px solid var(--tf-border);
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.05);
        }

        .nav-link {
            font-weight: 500;
            color: var(--tf-text-muted);
            padding: 0.5rem 1rem !important;
            border-radius: 8px;
            transition: all 0.2s ease;
        }

        .nav-link:hover {
            color: var(--tf-primary);
            background-color: #f1f5f9;
        }

        .nav-link.active {
            color: var(--tf-primary) !important;
            background-color: var(--tf-primary-light);
            font-weight: 600;
        }

        .card {
            border: 1px solid var(--tf-border);
            border-radius: 14px;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.04), 0 1px 2px -1px rgba(0, 0, 0, 0.04);
            background-color: var(--tf-card-bg);
            transition: transform 0.15s ease, box-shadow 0.15s ease;
        }

        .btn-primary {
            background-color: var(--tf-primary);
            border-color: var(--tf-primary);
            font-weight: 600;
            border-radius: 8px;
            padding: 0.55rem 1.25rem;
        }

        .btn-primary:hover, .btn-primary:focus {
            background-color: var(--tf-primary-dark);
            border-color: var(--tf-primary-dark);
        }

        .btn-outline-primary {
            color: var(--tf-primary);
            border-color: var(--tf-primary);
            font-weight: 600;
            border-radius: 8px;
        }

        .btn-outline-primary:hover {
            background-color: var(--tf-primary);
            border-color: var(--tf-primary);
            color: #fff;
        }

        .badge-role-admin {
            background-color: #fee2e2;
            color: #b91c1c;
            border: 1px solid #fca5a5;
            font-size: 0.75rem;
            padding: 0.35rem 0.65rem;
            border-radius: 20px;
            font-weight: 700;
        }

        .badge-role-member {
            background-color: #e0e7ff;
            color: #4338ca;
            border: 1px solid #c7d2fe;
            font-size: 0.75rem;
            padding: 0.35rem 0.65rem;
            border-radius: 20px;
            font-weight: 700;
        }

        .stat-card {
            padding: 1.25rem;
            border-radius: 12px;
            position: relative;
            overflow: hidden;
        }

        .stat-card .icon-box {
            width: 48px;
            height: 48px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
        }

        .avatar-circle {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background-color: #e0e7ff;
            color: #4f46e5;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.85rem;
        }

        .table > :not(caption) > * > * {
            padding: 0.9rem 1rem;
            vertical-align: middle;
        }

        .table thead th {
            background-color: #f8fafc;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #64748b;
            font-weight: 700;
            border-bottom: 2px solid var(--tf-border);
        }

        .form-control, .form-select {
            border-radius: 8px;
            border-color: #cbd5e1;
            padding: 0.6rem 0.9rem;
            font-size: 0.95rem;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--tf-primary);
            box-shadow: 0 0 0 0.25rem rgba(79, 70, 229, 0.15);
        }

        .invalid-feedback {
            font-weight: 600;
            font-size: 0.825rem;
        }

        .footer {
            margin-top: auto;
            border-top: 1px solid var(--tf-border);
            background: #ffffff;
            padding: 1.25rem 0;
            font-size: 0.85rem;
            color: var(--tf-text-muted);
        }
    </style>
    @stack('styles')
</head>
<body>

    <!-- Main Navigation Bar -->
    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2" href="{{ Auth::user() && Auth::user()->isAdmin() ? route('admin.dashboard') : route('member.dashboard') }}">
                <i class="bi bi-check2-square text-primary fs-3"></i>
                <span>TaskFlow <span class="text-muted fw-normal fs-6">Portal</span></span>
            </a>

            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarMain">
                @auth
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-3">
                        @if(Auth::user()->isAdmin())
                            <!-- Admin Navigation Links -->
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                                    <i class="bi bi-speedometer2 me-1"></i> Dashboard
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.tasks.*') ? 'active' : '' }}" href="{{ route('admin.tasks.index') }}">
                                    <i class="bi bi-list-task me-1"></i> Task Management
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.members.*') ? 'active' : '' }}" href="{{ route('admin.members.index') }}">
                                    <i class="bi bi-people me-1"></i> Team Members
                                </a>
                            </li>
                        @else
                            <!-- Member Navigation Links -->
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('member.dashboard') ? 'active' : '' }}" href="{{ route('member.dashboard') }}">
                                    <i class="bi bi-speedometer2 me-1"></i> My Dashboard
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('member.tasks.*') ? 'active' : '' }}" href="{{ route('member.tasks.index') }}">
                                    <i class="bi bi-check2-all me-1"></i> My Tasks
                                </a>
                            </li>
                        @endif
                    </ul>

                    <!-- User Information & Actions -->
                    <div class="d-flex align-items-center gap-3">
                        <!-- Role Badge -->
                        @if(Auth::user()->isAdmin())
                            <span class="badge-role-admin">
                                <i class="bi bi-shield-lock-fill me-1"></i> Admin Portal
                            </span>
                        @else
                            <span class="badge-role-member">
                                <i class="bi bi-person-badge-fill me-1"></i> Team Member
                            </span>
                        @endif

                        <!-- User Profile Dropdown -->
                        <div class="dropdown">
                            <button class="btn btn-light dropdown-toggle d-flex align-items-center gap-2 border" type="button" data-bs-toggle="dropdown">
                                <div class="avatar-circle">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </div>
                                <div class="text-start d-none d-sm-block">
                                    <div class="fw-bold small lh-1">{{ Auth::user()->name }}</div>
                                    <span class="text-muted" style="font-size: 0.725rem;">{{ Auth::user()->department ?? 'Staff' }}</span>
                                </div>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 mt-2 p-2" style="min-width: 220px;">
                                <li class="px-3 py-2 border-bottom mb-1">
                                    <div class="fw-bold">{{ Auth::user()->name }}</div>
                                    <div class="text-muted small text-truncate">{{ Auth::user()->email }}</div>
                                </li>
                                @if(Auth::user()->isAdmin())
                                    <li>
                                        <a class="dropdown-item rounded py-2 small" href="{{ route('admin.tasks.create') }}">
                                            <i class="bi bi-plus-circle me-2 text-primary"></i> Assign New Task
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item rounded py-2 small" href="{{ route('admin.members.create') }}">
                                            <i class="bi bi-person-plus me-2 text-primary"></i> Add Team Member
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                @endif
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item rounded py-2 small text-danger">
                                            <i class="bi bi-box-arrow-right me-2"></i> Log Out
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                @else
                    <!-- Guest Navigation Links -->
                    <div class="navbar-nav ms-auto d-flex align-items-center gap-2">
                        <a href="{{ route('login') }}" class="btn btn-outline-primary btn-sm px-3">Log In</a>
                        <a href="{{ route('register') }}" class="btn btn-primary btn-sm px-3">Register</a>
                    </div>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Global Alerts & Notifications Container -->
    <main class="py-4 flex-grow-1">
        <div class="container">
            <!-- Flash Success Message -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 d-flex align-items-center mb-4" role="alert">
                    <i class="bi bi-check-circle-fill fs-5 me-2 text-success"></i>
                    <div>{{ session('success') }}</div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Flash Error Message -->
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0 d-flex align-items-center mb-4" role="alert">
                    <i class="bi bi-exclamation-triangle-fill fs-5 me-2 text-danger"></i>
                    <div>{{ session('error') }}</div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Flash Info Message -->
            @if(session('info'))
                <div class="alert alert-info alert-dismissible fade show shadow-sm border-0 d-flex align-items-center mb-4" role="alert">
                    <i class="bi bi-info-circle-fill fs-5 me-2 text-info"></i>
                    <div>{{ session('info') }}</div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Global Validation Errors Alert -->
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0 mb-4" role="alert">
                    <div class="d-flex align-items-center mb-2">
                        <i class="bi bi-x-circle-fill fs-5 me-2 text-danger"></i>
                        <strong>Please fix the errors below to proceed:</strong>
                    </div>
                    <ul class="mb-0 ps-4 small">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Page Content -->
            @yield('content')
        </div>
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container d-flex flex-column flex-sm-row justify-content-between align-items-center gap-2">
            <div>
                <strong>TaskFlow</strong> &copy; {{ date('Y') }} - Task Assignment & Management Portal
            </div>
            <div class="small">
                <span>Built with Laravel & Blade Engine</span>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5.3 Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
