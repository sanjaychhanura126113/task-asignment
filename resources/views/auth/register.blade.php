@extends('layouts.guest')

@section('title', 'Register New Account')

@section('content')
    <form method="POST" action="{{ route('register.post') }}" novalidate>
        @csrf

        <!-- Full Name -->
        <div class="mb-3">
            <label for="name" class="form-label fw-semibold small">Full Name <span class="text-danger">*</span></label>
            <div class="input-group">
                <span class="input-group-text bg-light text-muted"><i class="bi bi-person"></i></span>
                <input 
                    type="text" 
                    name="name" 
                    id="name" 
                    class="form-control @error('name') is-invalid @enderror" 
                    value="{{ old('name') }}" 
                    placeholder="e.g. John Doe" 
                    required 
                    autofocus
                >
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <!-- Email Address -->
        <div class="mb-3">
            <label for="email" class="form-label fw-semibold small">Email Address <span class="text-danger">*</span></label>
            <div class="input-group">
                <span class="input-group-text bg-light text-muted"><i class="bi bi-envelope"></i></span>
                <input 
                    type="email" 
                    name="email" 
                    id="email" 
                    class="form-control @error('email') is-invalid @enderror" 
                    value="{{ old('email') }}" 
                    placeholder="e.g. john@example.com" 
                    required
                >
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <!-- Role & Department in Row -->
        <div class="row g-2 mb-3">
            <div class="col-md-6">
                <label for="role" class="form-label fw-semibold small">Select Role <span class="text-danger">*</span></label>
                <select name="role" id="role" class="form-select @error('role') is-invalid @enderror" required>
                    <option value="member" {{ old('role') === 'member' ? 'selected' : '' }}>Team Member</option>
                    <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Administrator</option>
                </select>
                @error('role')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6">
                <label for="department" class="form-label fw-semibold small">Department</label>
                <input 
                    type="text" 
                    name="department" 
                    id="department" 
                    class="form-control @error('department') is-invalid @enderror" 
                    value="{{ old('department') }}" 
                    placeholder="e.g. Design / Engineering"
                >
                @error('department')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <!-- Phone Number -->
        <div class="mb-3">
            <label for="phone" class="form-label fw-semibold small">Phone (Optional)</label>
            <div class="input-group">
                <span class="input-group-text bg-light text-muted"><i class="bi bi-telephone"></i></span>
                <input 
                    type="text" 
                    name="phone" 
                    id="phone" 
                    class="form-control @error('phone') is-invalid @enderror" 
                    value="{{ old('phone') }}" 
                    placeholder="e.g. +91 98765 43210"
                >
                @error('phone')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <!-- Password -->
        <div class="mb-3">
            <label for="password" class="form-label fw-semibold small">Password <span class="text-danger">*</span></label>
            <div class="input-group">
                <span class="input-group-text bg-light text-muted"><i class="bi bi-lock"></i></span>
                <input 
                    type="password" 
                    name="password" 
                    id="password" 
                    class="form-control @error('password') is-invalid @enderror" 
                    placeholder="Minimum 6 characters" 
                    required
                >
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <!-- Confirm Password -->
        <div class="mb-4">
            <label for="password_confirmation" class="form-label fw-semibold small">Confirm Password <span class="text-danger">*</span></label>
            <div class="input-group">
                <span class="input-group-text bg-light text-muted"><i class="bi bi-lock-fill"></i></span>
                <input 
                    type="password" 
                    name="password_confirmation" 
                    id="password_confirmation" 
                    class="form-control" 
                    placeholder="Re-enter password" 
                    required
                >
            </div>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn btn-primary w-100 mb-3 shadow-sm">
            <i class="bi bi-person-check me-1"></i> Register Account
        </button>
    </form>

    <div class="text-center small">
        Already registered? <a href="{{ route('login') }}" class="text-primary fw-semibold text-decoration-none">Sign In Here</a>
    </div>
@endsection
