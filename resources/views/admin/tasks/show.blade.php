@extends('layouts.app')

@section('title', 'Task Details #' . $task->id)

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Breadcrumbs -->
            <nav aria-label="breadcrumb" class="mb-3">
                <ol class="breadcrumb small">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.tasks.index') }}" class="text-decoration-none">Tasks</a></li>
                    <li class="breadcrumb-item active" aria-current="page">#{{ $task->id }} - {{ Str::limit($task->title, 30) }}</li>
                </ol>
            </nav>

            <!-- Overdue Warning Callout -->
            @if($task->isOverdue())
                <div class="alert alert-danger shadow-sm border-0 d-flex align-items-center mb-4">
                    <i class="bi bi-exclamation-octagon-fill fs-3 me-3 text-danger"></i>
                    <div>
                        <strong class="d-block">Overdue Alert!</strong>
                        <span>This task was scheduled to be completed by {{ $task->due_date->format('M d, Y') }} and is currently past its deadline.</span>
                    </div>
                </div>
            @endif

            <!-- Main Task Card -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3 border-bottom d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2">
                    <div>
                        <span class="text-muted small fw-bold">TASK #{{ $task->id }}</span>
                        <h4 class="h5 fw-bold mb-0 text-dark">{{ $task->title }}</h4>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.tasks.edit', $task) }}" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-pencil me-1"></i> Edit Task
                        </a>
                        <form method="POST" action="{{ route('admin.tasks.destroy', $task) }}" onsubmit="return confirm('Are you sure you want to delete this task?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                <i class="bi bi-trash me-1"></i> Delete
                            </button>
                        </form>
                    </div>
                </div>

                <div class="card-body p-4">
                    <!-- Badges & Meta strip -->
                    <div class="row g-3 p-3 bg-light rounded-3 mb-4">
                        <div class="col-sm-6 col-md-3">
                            <span class="text-muted small d-block">Status</span>
                            <span class="badge {{ $task->getStatusBadgeClass() }} px-2 py-1 mt-1">
                                {{ $task->getStatusLabel() }}
                            </span>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <span class="text-muted small d-block">Priority</span>
                            <span class="badge {{ $task->getPriorityBadgeClass() }} px-2 py-1 mt-1">
                                {{ $task->getPriorityLabel() }}
                            </span>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <span class="text-muted small d-block">Due Date</span>
                            <span class="fw-bold mt-1 d-block {{ $task->isOverdue() ? 'text-danger' : 'text-dark' }}">
                                <i class="bi bi-calendar-event me-1"></i>
                                {{ $task->due_date ? $task->due_date->format('M d, Y') : 'None' }}
                            </span>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <span class="text-muted small d-block">Created On</span>
                            <span class="fw-semibold text-dark mt-1 d-block">
                                <i class="bi bi-clock me-1"></i>
                                {{ $task->created_at->format('M d, Y') }}
                            </span>
                        </div>
                    </div>

                    <!-- Description Section -->
                    <div class="mb-4">
                        <h6 class="fw-bold text-dark mb-2">Description & Scope</h6>
                        <div class="p-3 bg-white border rounded text-secondary" style="white-space: pre-line; min-height: 80px;">
                            {{ $task->description ?: 'No detailed instructions provided for this task.' }}
                        </div>
                    </div>

                    <!-- Assignee & Creator Info Row -->
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="p-3 border rounded h-100">
                                <span class="text-muted small fw-bold text-uppercase d-block mb-2">Assigned Team Member</span>
                                @if($task->assignee)
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="avatar-circle" style="width: 44px; height: 44px; font-size: 1rem;">
                                            {{ strtoupper(substr($task->assignee->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark">{{ $task->assignee->name }}</div>
                                            <div class="text-muted small">{{ $task->assignee->email }}</div>
                                            <span class="badge bg-light text-secondary border mt-1" style="font-size: 0.7rem;">
                                                Dept: {{ $task->assignee->department ?? 'General' }}
                                            </span>
                                        </div>
                                    </div>
                                @else
                                    <div class="text-muted fst-italic">No member assigned yet.</div>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="p-3 border rounded h-100">
                                <span class="text-muted small fw-bold text-uppercase d-block mb-2">Created By</span>
                                @if($task->creator)
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="avatar-circle bg-secondary-subtle text-secondary" style="width: 44px; height: 44px; font-size: 1rem;">
                                            {{ strtoupper(substr($task->creator->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark">{{ $task->creator->name }}</div>
                                            <div class="text-muted small">{{ $task->creator->email }}</div>
                                            <span class="badge bg-secondary-subtle text-secondary mt-1" style="font-size: 0.7rem;">
                                                Role: {{ ucfirst($task->creator->role) }}
                                            </span>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Activity History & Comments Section -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3 border-bottom">
                    <h5 class="fw-bold mb-0">Activity History & Discussion</h5>
                </div>
                <div class="card-body p-4">
                    <!-- Post New Remark / Comment Form -->
                    <form method="POST" action="{{ route('admin.tasks.comments.store', $task) }}" class="mb-4">
                        @csrf
                        <div class="mb-2">
                            <label for="comment" class="form-label fw-semibold small">Add Remark / Update Message</label>
                            <textarea 
                                name="comment" 
                                id="comment" 
                                rows="2" 
                                class="form-control @error('comment') is-invalid @enderror" 
                                placeholder="Post an update, instruction, or note regarding this task..." 
                                required
                            >{{ old('comment') }}</textarea>
                            @error('comment')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-sm btn-primary">
                                <i class="bi bi-chat-left-text me-1"></i> Post Remark
                            </button>
                        </div>
                    </form>

                    <hr class="my-4">

                    <!-- Comments Timeline -->
                    <div class="d-flex flex-column gap-3">
                        @forelse($task->comments as $comment)
                            <div class="d-flex gap-3 p-3 bg-light rounded-3">
                                <div class="avatar-circle flex-shrink-0">
                                    {{ strtoupper(substr($comment->user->name, 0, 1)) }}
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <div>
                                            <span class="fw-bold text-dark">{{ $comment->user->name }}</span>
                                            <span class="badge {{ $comment->user->isAdmin() ? 'bg-danger-subtle text-danger' : 'bg-primary-subtle text-primary' }} ms-1" style="font-size: 0.65rem;">
                                                {{ ucfirst($comment->user->role) }}
                                            </span>
                                        </div>
                                        <span class="text-muted small">{{ $comment->created_at->format('M d, Y h:i A') }}</span>
                                    </div>
                                    @if($comment->status_change)
                                        <div class="mb-1">
                                            <span class="badge bg-secondary text-white py-0 px-2" style="font-size: 0.7rem;">
                                                <i class="bi bi-arrow-repeat me-1"></i> {{ $comment->status_change }}
                                            </span>
                                        </div>
                                    @endif
                                    <p class="mb-0 text-secondary small">{{ $comment->comment }}</p>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-4 text-muted small">
                                <i class="bi bi-chat-square-dots fs-3 d-block mb-1"></i>
                                No remarks or status history recorded yet.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
