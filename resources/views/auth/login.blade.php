@extends('layouts.guest')

@section('title', 'Sign In')

@section('content')
    <form method="POST" action="{{ route('login.post') }}" novalidate>
        @csrf

        <!-- Email Field -->
        <div class="mb-3">
            <label for="email" class="form-label fw-semibold small">Email Address</label>
            <div class="input-group">
                <span class="input-group-text bg-light text-muted"><i class="bi bi-envelope"></i></span>
                <input 
                    type="email" 
                    name="email" 
                    id="email" 
                    class="form-control @error('email') is-invalid @enderror" 
                    value="{{ old('email', 'admin@taskflow.local') }}" 
                    placeholder="e.g. admin@taskflow.local" 
                    required 
                    autofocus
                >
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <!-- Password Field -->
        <div class="mb-3">
            <label for="password" class="form-label fw-semibold small">Password</label>
            <div class="input-group">
                <span class="input-group-text bg-light text-muted"><i class="bi bi-lock"></i></span>
                <input 
                    type="password" 
                    name="password" 
                    id="password" 
                    class="form-control @error('password') is-invalid @enderror" 
                    placeholder="Enter password" 
                    value="password123" 
                    required
                >
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <!-- Remember Me Checkbox -->
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                <label class="form-check-label small text-muted" for="remember">
                    Remember my session
                </label>
            </div>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn btn-primary w-100 mb-3 shadow-sm">
            <i class="bi bi-box-arrow-in-right me-1"></i> Sign In to Portal
        </button>
    </form>

    <div class="text-center mb-4 small">
        Don't have an account? <a href="{{ route('register') }}" class="text-primary fw-semibold text-decoration-none">Create Account</a>
    </div>

    <!-- Quick Demo Accounts (1-Click Login Shortcuts) -->
    <div class="border-top pt-3">
        <div class="d-flex align-items-center justify-content-between mb-2">
            <span class="text-uppercase fw-bold text-muted" style="font-size: 0.7rem; letter-spacing: 0.05em;">
                ⚡ 1-Click Demo Accounts (Instant Test)
            </span>
        </div>

        <div class="d-flex flex-column gap-2">
            @foreach($demoUsers as $user)
                <a href="{{ route('quick.login', $user->id) }}" class="demo-chip">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi {{ $user->isAdmin() ? 'bi-shield-lock-fill text-danger' : 'bi-person-badge-fill text-primary' }}"></i>
                        <div>
                            <span class="fw-semibold small">{{ $user->name }}</span>
                            <span class="badge {{ $user->isAdmin() ? 'bg-danger-subtle text-danger' : 'bg-primary-subtle text-primary' }} ms-1" style="font-size: 0.65rem;">
                                {{ ucfirst($user->role) }}
                            </span>
                            <div class="text-muted" style="font-size: 0.72rem;">{{ $user->email }}</div>
                        </div>
                    </div>
                    <span class="btn btn-sm btn-outline-secondary py-0 px-2" style="font-size: 0.75rem;">Login &rarr;</span>
                </a>
            @endforeach
        </div>
    </div>
@endsection
