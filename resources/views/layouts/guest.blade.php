<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Sign In') - TaskFlow Assignment Portal</title>

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
            --tf-body-bg: #f1f5f9;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--tf-body-bg);
            background-image: radial-gradient(#cbd5e1 1px, transparent 1px);
            background-size: 24px 24px;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
        }

        .auth-card {
            background: #ffffff;
            border-radius: 18px;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.08), 0 8px 10px -6px rgba(0, 0, 0, 0.04);
            border: 1px solid #e2e8f0;
            overflow: hidden;
            width: 100%;
            max-width: 520px;
        }

        .btn-primary {
            background-color: var(--tf-primary);
            border-color: var(--tf-primary);
            font-weight: 600;
            border-radius: 8px;
            padding: 0.65rem 1.25rem;
        }

        .btn-primary:hover {
            background-color: var(--tf-primary-dark);
            border-color: var(--tf-primary-dark);
        }

        .form-control, .form-select {
            border-radius: 8px;
            border-color: #cbd5e1;
            padding: 0.65rem 0.95rem;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--tf-primary);
            box-shadow: 0 0 0 0.25rem rgba(79, 70, 229, 0.15);
        }

        .demo-chip {
            border: 1px dashed #c7d2fe;
            background-color: #f8fafc;
            border-radius: 10px;
            padding: 0.5rem 0.75rem;
            cursor: pointer;
            transition: all 0.2s ease;
            text-decoration: none;
            color: #334155;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .demo-chip:hover {
            background-color: #e0e7ff;
            border-color: #818cf8;
            color: #1e1b4b;
        }
    </style>
</head>
<body>

    <div class="auth-card p-4 p-md-5">
        <div class="text-center mb-4">
            <div class="d-inline-flex align-items-center justify-content-center bg-primary-subtle text-primary rounded-circle mb-3" style="width: 60px; height: 60px;">
                <i class="bi bi-check2-square fs-1"></i>
            </div>
            <h3 class="fw-bold text-dark mb-1">TaskFlow Portal</h3>
            <p class="text-muted small">Task Assignment & Team Workflow Management</p>
        </div>

        <!-- Global Flash Alerts -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show small mb-3">
                <i class="bi bi-check-circle me-1"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show small mb-3">
                <i class="bi bi-exclamation-triangle me-1"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('info'))
            <div class="alert alert-info alert-dismissible fade show small mb-3">
                <i class="bi bi-info-circle me-1"></i> {{ session('info') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
