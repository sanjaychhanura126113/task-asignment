@extends('layouts.app')

@section('title', 'Task Details #' . $task->id)

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Breadcrumbs -->
            <nav aria-label="breadcrumb" class="mb-3">
                <ol class="breadcrumb small">
                    <li class="breadcrumb-item"><a href="{{ route('member.dashboard') }}" class="text-decoration-none">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('member.tasks.index') }}" class="text-decoration-none">My Tasks</a></li>
                    <li class="breadcrumb-item active" aria-current="page">#{{ $task->id }} - {{ Str::limit($task->title, 25) }}</li>
                </ol>
            </nav>

            <!-- Overdue Warning -->
            @if($task->isOverdue())
                <div class="alert alert-danger shadow-sm border-0 d-flex align-items-center mb-4">
                    <i class="bi bi-exclamation-octagon-fill fs-3 me-3 text-danger"></i>
                    <div>
                        <strong class="d-block">Overdue Notice</strong>
                        <span>This task passed its deadline on {{ $task->due_date->format('M d, Y') }}. Please prioritize or update your progress below.</span>
                    </div>
                </div>
            @endif

            <!-- Main Task Card -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-muted small fw-bold">TASK #{{ $task->id }}</span>
                        <h4 class="h5 fw-bold mb-0 text-dark">{{ $task->title }}</h4>
                    </div>
                    <a href="{{ route('member.tasks.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-arrow-left me-1"></i> Back to Tasks
                    </a>
                </div>

                <div class="card-body p-4">
                    <!-- Badges Row -->
                    <div class="row g-3 p-3 bg-light rounded-3 mb-4">
                        <div class="col-sm-6 col-md-3">
                            <span class="text-muted small d-block">Current Status</span>
                            <span class="badge {{ $task->getStatusBadgeClass() }} px-2 py-1 mt-1">
                                {{ $task->getStatusLabel() }}
                            </span>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <span class="text-muted small d-block">Priority Level</span>
                            <span class="badge {{ $task->getPriorityBadgeClass() }} px-2 py-1 mt-1">
                                {{ $task->getPriorityLabel() }}
                            </span>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <span class="text-muted small d-block">Deadline</span>
                            <span class="fw-bold mt-1 d-block {{ $task->isOverdue() ? 'text-danger' : 'text-dark' }}">
                                <i class="bi bi-calendar-event me-1"></i>
                                {{ $task->due_date ? $task->due_date->format('M d, Y') : 'No Deadline' }}
                            </span>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <span class="text-muted small d-block">Assigned By</span>
                            <span class="fw-semibold text-dark mt-1 d-block">
                                <i class="bi bi-person me-1"></i>
                                {{ $task->creator?->name ?? 'Admin' }}
                            </span>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="mb-4">
                        <h6 class="fw-bold text-dark mb-2">Detailed Task Instructions</h6>
                        <div class="p-3 bg-white border rounded text-secondary" style="white-space: pre-line; min-height: 80px;">
                            {{ $task->description ?: 'No detailed instructions provided.' }}
                        </div>
                    </div>

                    <!-- Status Update Form Card -->
                    <div class="card bg-light border-primary-subtle p-3 mb-2">
                        <h6 class="fw-bold text-primary mb-2">
                            <i class="bi bi-arrow-repeat me-1"></i> Update My Task Progress
                        </h6>
                        <form method="POST" action="{{ route('member.tasks.status.update', $task) }}" novalidate>
                            @csrf
                            <div class="row g-2 mb-2">
                                <div class="col-md-4">
                                    <label for="status" class="form-label small fw-semibold">New Status <span class="text-danger">*</span></label>
                                    <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                                        <option value="pending" {{ old('status', $task->status) === 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="in_progress" {{ old('status', $task->status) === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                        <option value="completed" {{ old('status', $task->status) === 'completed' ? 'selected' : '' }}>Completed</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-8">
                                    <label for="note" class="form-label small fw-semibold">Progress / Completion Note <span class="text-muted fw-normal">(Optional)</span></label>
                                    <input 
                                        type="text" 
                                        name="note" 
                                        id="note" 
                                        class="form-control @error('note') is-invalid @enderror" 
                                        placeholder="Brief note on what was completed or currently being worked on..."
                                        value="{{ old('note') }}"
                                    >
                                    @error('note')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary btn-sm px-3 shadow-sm">
                                    <i class="bi bi-check2-circle me-1"></i> Save Status Update
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Activity & Discussion Feed -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3 border-bottom">
                    <h5 class="fw-bold mb-0">Activity History & Messages</h5>
                </div>
                <div class="card-body p-4">
                    <!-- Post Comment Form -->
                    <form method="POST" action="{{ route('member.tasks.comments.store', $task) }}" class="mb-4">
                        @csrf
                        <div class="mb-2">
                            <label for="comment" class="form-label fw-semibold small">Ask a question or post an update</label>
                            <textarea 
                                name="comment" 
                                id="comment" 
                                rows="2" 
                                class="form-control @error('comment') is-invalid @enderror" 
                                placeholder="Type your message to the team or admin..." 
                                required
                            >{{ old('comment') }}</textarea>
                            @error('comment')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-send me-1"></i> Send Message
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
