@extends('layouts.app')

@section('title', 'Assign New Task')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-9 col-xl-8">
            <!-- Breadcrumb Navigation -->
            <nav aria-label="breadcrumb" class="mb-3">
                <ol class="breadcrumb small">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.tasks.index') }}" class="text-decoration-none">Tasks</a></li>
                    <li class="breadcrumb-item active" aria-current="page">New Assignment</li>
                </ol>
            </nav>

            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
                    <div>
                        <h4 class="h5 fw-bold mb-0">Create & Assign New Task</h4>
                        <small class="text-muted">Fill out the form below with appropriate details and assignment parameters.</small>
                    </div>
                    <a href="{{ route('admin.tasks.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-arrow-left me-1"></i> Back to List
                    </a>
                </div>

                <div class="card-body p-4">
                    <form method="POST" action="{{ route('admin.tasks.store') }}" novalidate>
                        @csrf

                        <!-- Task Title -->
                        <div class="mb-3">
                            <label for="title" class="form-label fw-semibold">
                                Task Title <span class="text-danger">*</span>
                            </label>
                            <input 
                                type="text" 
                                name="title" 
                                id="title" 
                                class="form-control @error('title') is-invalid @enderror" 
                                value="{{ old('title') }}" 
                                placeholder="e.g. Design UI components for onboarding wizard" 
                                required
                            >
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @else
                                <div class="form-text small">A concise, descriptive summary of the work to be completed.</div>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="mb-3">
                            <label for="description" class="form-label fw-semibold">
                                Detailed Description & Instructions
                            </label>
                            <textarea 
                                name="description" 
                                id="description" 
                                rows="4" 
                                class="form-control @error('description') is-invalid @enderror" 
                                placeholder="Explain specifications, criteria for completion, deliverables, and helpful reference links..."
                            >{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Assignee & Priority in a row -->
                        <div class="row g-3 mb-3">
                            <!-- Assigned Team Member -->
                            <div class="col-md-6">
                                <label for="assigned_to" class="form-label fw-semibold">
                                    Assign To <span class="text-danger">*</span>
                                </label>
                                <select 
                                    name="assigned_to" 
                                    id="assigned_to" 
                                    class="form-select @error('assigned_to') is-invalid @enderror" 
                                    required
                                >
                                    <option value="">-- Choose Team Member --</option>
                                    @foreach($members as $member)
                                        <option value="{{ $member->id }}" {{ old('assigned_to') == $member->id ? 'selected' : '' }}>
                                            {{ $member->name }} ({{ $member->department ?? 'General' }} - {{ ucfirst($member->role) }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('assigned_to')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Priority Level -->
                            <div class="col-md-6">
                                <label for="priority" class="form-label fw-semibold">
                                    Priority Level <span class="text-danger">*</span>
                                </label>
                                <select 
                                    name="priority" 
                                    id="priority" 
                                    class="form-select @error('priority') is-invalid @enderror" 
                                    required
                                >
                                    <option value="low" {{ old('priority') === 'low' ? 'selected' : '' }}>🟢 Low (Flexible)</option>
                                    <option value="medium" {{ old('priority', 'medium') === 'medium' ? 'selected' : '' }}>🔵 Medium (Standard)</option>
                                    <option value="high" {{ old('priority') === 'high' ? 'selected' : '' }}>🟠 High (Important)</option>
                                    <option value="urgent" {{ old('priority') === 'urgent' ? 'selected' : '' }}>🔴 Urgent (Critical)</option>
                                </select>
                                @error('priority')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Status & Due Date in a row -->
                        <div class="row g-3 mb-3">
                            <!-- Initial Status -->
                            <div class="col-md-6">
                                <label for="status" class="form-label fw-semibold">
                                    Initial Status <span class="text-danger">*</span>
                                </label>
                                <select 
                                    name="status" 
                                    id="status" 
                                    class="form-select @error('status') is-invalid @enderror" 
                                    required
                                >
                                    <option value="pending" {{ old('status', 'pending') === 'pending' ? 'selected' : '' }}>Pending (Not Started)</option>
                                    <option value="in_progress" {{ old('status') === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                    <option value="completed" {{ old('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="cancelled" {{ old('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Due Date -->
                            <div class="col-md-6">
                                <label for="due_date" class="form-label fw-semibold">
                                    Due Date Deadline <span class="text-danger">*</span>
                                </label>
                                <input 
                                    type="date" 
                                    name="due_date" 
                                    id="due_date" 
                                    class="form-control @error('due_date') is-invalid @enderror" 
                                    value="{{ old('due_date', date('Y-m-d', strtotime('+3 days'))) }}" 
                                    required
                                >
                                @error('due_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Initial Comment / Note -->
                        <div class="mb-4">
                            <label for="initial_comment" class="form-label fw-semibold">
                                Initial Note / Kickoff Remark <span class="text-muted fw-normal small">(Optional)</span>
                            </label>
                            <input 
                                type="text" 
                                name="initial_comment" 
                                id="initial_comment" 
                                class="form-control @error('initial_comment') is-invalid @enderror" 
                                value="{{ old('initial_comment') }}" 
                                placeholder="e.g. Please reach out to Sarah for design wireframes before proceeding."
                            >
                            @error('initial_comment')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Buttons Row -->
                        <div class="d-flex justify-content-end gap-2 pt-2 border-top">
                            <a href="{{ route('admin.tasks.index') }}" class="btn btn-light border px-3">
                                Cancel
                            </a>
                            <button type="submit" class="btn btn-primary px-4 shadow-sm">
                                <i class="bi bi-check2-circle me-1"></i> Save & Assign Task
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
