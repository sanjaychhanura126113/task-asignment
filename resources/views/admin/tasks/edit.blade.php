@extends('layouts.app')

@section('title', 'Edit Task #' . $task->id)

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-9 col-xl-8">
            <!-- Breadcrumbs -->
            <nav aria-label="breadcrumb" class="mb-3">
                <ol class="breadcrumb small">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.tasks.index') }}" class="text-decoration-none">Tasks</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.tasks.show', $task) }}" class="text-decoration-none">#{{ $task->id }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit</li>
                </ol>
            </nav>

            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
                    <div>
                        <h4 class="h5 fw-bold mb-0">Edit Task Assignment #{{ $task->id }}</h4>
                        <small class="text-muted">Modify parameters, reassign member, or change status level.</small>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.tasks.show', $task) }}" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-eye me-1"></i> View Task
                        </a>
                    </div>
                </div>

                <div class="card-body p-4">
                    <form method="POST" action="{{ route('admin.tasks.update', $task) }}" novalidate>
                        @csrf
                        @method('PUT')

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
                                value="{{ old('title', $task->title) }}" 
                                required
                            >
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="mb-3">
                            <label for="description" class="form-label fw-semibold">
                                Description & Instructions
                            </label>
                            <textarea 
                                name="description" 
                                id="description" 
                                rows="4" 
                                class="form-control @error('description') is-invalid @enderror"
                            >{{ old('description', $task->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Assignee & Priority in a row -->
                        <div class="row g-3 mb-3">
                            <!-- Assigned Team Member -->
                            <div class="col-md-6">
                                <label for="assigned_to" class="form-label fw-semibold">
                                    Assigned Member <span class="text-danger">*</span>
                                </label>
                                <select 
                                    name="assigned_to" 
                                    id="assigned_to" 
                                    class="form-select @error('assigned_to') is-invalid @enderror" 
                                    required
                                >
                                    <option value="">-- Choose Member --</option>
                                    @foreach($members as $member)
                                        <option value="{{ $member->id }}" {{ old('assigned_to', $task->assigned_to) == $member->id ? 'selected' : '' }}>
                                            {{ $member->name }} ({{ $member->department ?? 'General' }})
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
                                    <option value="low" {{ old('priority', $task->priority) === 'low' ? 'selected' : '' }}>🟢 Low (Flexible)</option>
                                    <option value="medium" {{ old('priority', $task->priority) === 'medium' ? 'selected' : '' }}>🔵 Medium (Standard)</option>
                                    <option value="high" {{ old('priority', $task->priority) === 'high' ? 'selected' : '' }}>🟠 High (Important)</option>
                                    <option value="urgent" {{ old('priority', $task->priority) === 'urgent' ? 'selected' : '' }}>🔴 Urgent (Critical)</option>
                                </select>
                                @error('priority')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Status & Due Date in a row -->
                        <div class="row g-3 mb-3">
                            <!-- Status -->
                            <div class="col-md-6">
                                <label for="status" class="form-label fw-semibold">
                                    Status <span class="text-danger">*</span>
                                </label>
                                <select 
                                    name="status" 
                                    id="status" 
                                    class="form-select @error('status') is-invalid @enderror" 
                                    required
                                >
                                    <option value="pending" {{ old('status', $task->status) === 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="in_progress" {{ old('status', $task->status) === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                    <option value="completed" {{ old('status', $task->status) === 'completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="cancelled" {{ old('status', $task->status) === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
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
                                    value="{{ old('due_date', $task->due_date ? $task->due_date->format('Y-m-d') : '') }}" 
                                    required
                                >
                                @error('due_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Update Note -->
                        <div class="mb-4">
                            <label for="update_note" class="form-label fw-semibold">
                                Change Log Note <span class="text-muted fw-normal small">(Optional - logged in activity history)</span>
                            </label>
                            <input 
                                type="text" 
                                name="update_note" 
                                id="update_note" 
                                class="form-control @error('update_note') is-invalid @enderror" 
                                value="{{ old('update_note') }}" 
                                placeholder="Explain why details or deadlines were changed..."
                            >
                            @error('update_note')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Buttons Row -->
                        <div class="d-flex justify-content-end gap-2 pt-2 border-top">
                            <a href="{{ route('admin.tasks.show', $task) }}" class="btn btn-light border px-3">
                                Cancel
                            </a>
                            <button type="submit" class="btn btn-primary px-4 shadow-sm">
                                <i class="bi bi-check-lg me-1"></i> Update Task Details
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
