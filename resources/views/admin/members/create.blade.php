@extends('layouts.app')

@section('title', 'Add Team Member')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-7 col-xl-6">
            <!-- Breadcrumbs -->
            <nav aria-label="breadcrumb" class="mb-3">
                <ol class="breadcrumb small">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.members.index') }}" class="text-decoration-none">Team Members</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Add Member</li>
                </ol>
            </nav>

            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
                    <div>
                        <h4 class="h5 fw-bold mb-0">Add New Team Member</h4>
                        <small class="text-muted">Register a staff member who can be assigned project tasks.</small>
                    </div>
                    <a href="{{ route('admin.members.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-arrow-left me-1"></i> Back
                    </a>
                </div>

                <div class="card-body p-4">
                    <form method="POST" action="{{ route('admin.members.store') }}" novalidate>
                        @csrf

                        <!-- Name -->
                        <div class="mb-3">
                            <label for="name" class="form-label fw-semibold">Full Name <span class="text-danger">*</span></label>
                            <input 
                                type="text" 
                                name="name" 
                                id="name" 
                                class="form-control @error('name') is-invalid @enderror" 
                                value="{{ old('name') }}" 
                                placeholder="e.g. Ananya Roy" 
                                required
                            >
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="mb-3">
                            <label for="email" class="form-label fw-semibold">Email Address <span class="text-danger">*</span></label>
                            <input 
                                type="email" 
                                name="email" 
                                id="email" 
                                class="form-control @error('email') is-invalid @enderror" 
                                value="{{ old('email') }}" 
                                placeholder="e.g. ananya@taskflow.local" 
                                required
                            >
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div class="mb-3">
                            <label for="password" class="form-label fw-semibold">Initial Password <span class="text-danger">*</span></label>
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

                        <!-- Role & Department -->
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label for="role" class="form-label fw-semibold">System Role <span class="text-danger">*</span></label>
                                <select name="role" id="role" class="form-select @error('role') is-invalid @enderror" required>
                                    <option value="member" {{ old('role') === 'member' ? 'selected' : '' }}>Team Member</option>
                                    <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Administrator</option>
                                </select>
                                @error('role')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="department" class="form-label fw-semibold">Department</label>
                                <input 
                                    type="text" 
                                    name="department" 
                                    id="department" 
                                    class="form-control @error('department') is-invalid @enderror" 
                                    value="{{ old('department') }}" 
                                    placeholder="e.g. Engineering / Design"
                                >
                                @error('department')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Phone -->
                        <div class="mb-4">
                            <label for="phone" class="form-label fw-semibold">Contact Phone</label>
                            <input 
                                type="text" 
                                name="phone" 
                                id="phone" 
                                class="form-control @error('phone') is-invalid @enderror" 
                                value="{{ old('phone') }}" 
                                placeholder="e.g. +91 98765 12345"
                            >
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Actions -->
                        <div class="d-flex justify-content-end gap-2 pt-2 border-top">
                            <a href="{{ route('admin.members.index') }}" class="btn btn-light border px-3">Cancel</a>
                            <button type="submit" class="btn btn-primary px-4 shadow-sm">
                                <i class="bi bi-person-check me-1"></i> Save Team Member
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
