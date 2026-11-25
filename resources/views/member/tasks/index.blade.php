@extends('layouts.app')

@section('title', 'My Assigned Tasks')

@section('content')
    <!-- Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h2 class="h4 fw-bold mb-1">My Tasks Directory</h2>
            <p class="text-muted small mb-0">Review task specifications, track progress, and update status deliverables.</p>
        </div>
    </div>

    <!-- Filters & Search Toolbar -->
    <div class="card mb-4 shadow-sm border-0">
        <div class="card-body p-3">
            <form method="GET" action="{{ route('member.tasks.index') }}" class="row g-2 align-items-center">
                <!-- Search input -->
                <div class="col-md-4">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-light"><i class="bi bi-search text-muted"></i></span>
                        <input 
                            type="text" 
                            name="search" 
                            class="form-control" 
                            placeholder="Search your tasks..." 
                            value="{{ request('search') }}"
                        >
                    </div>
                </div>

                <!-- Status Filter -->
                <div class="col-sm-6 col-md-3">
                    <select name="status" class="form-select form-select-sm">
                        <option value="">-- All Statuses --</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                    </select>
                </div>

                <!-- Priority Filter -->
                <div class="col-sm-6 col-md-3">
                    <select name="priority" class="form-select form-select-sm">
                        <option value="">-- All Priorities --</option>
                        <option value="urgent" {{ request('priority') === 'urgent' ? 'selected' : '' }}>Urgent</option>
                        <option value="high" {{ request('priority') === 'high' ? 'selected' : '' }}>High</option>
                        <option value="medium" {{ request('priority') === 'medium' ? 'selected' : '' }}>Medium</option>
                        <option value="low" {{ request('priority') === 'low' ? 'selected' : '' }}>Low</option>
                    </select>
                </div>

                <!-- Submit & Clear -->
                <div class="col-md-2 d-flex gap-1">
                    <button type="submit" class="btn btn-sm btn-primary w-100">
                        <i class="bi bi-funnel me-1"></i> Filter
                    </button>
                    @if(request()->hasAny(['search', 'status', 'priority', 'filter']))
                        <a href="{{ route('member.tasks.index') }}" class="btn btn-sm btn-light border" title="Reset">
                            <i class="bi bi-x-lg text-danger"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Tasks Listing Table -->
    <div class="card shadow-sm border-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th style="width: 60px;">#</th>
                        <th>Task Title & Summary</th>
                        <th>Priority</th>
                        <th>Status</th>
                        <th>Due Date</th>
                        <th class="text-end" style="width: 140px;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tasks as $task)
                        <tr>
                            <td class="text-muted fw-bold">#{{ $task->id }}</td>
                            <td>
                                <div class="fw-bold text-dark">
                                    <a href="{{ route('member.tasks.show', $task) }}" class="text-dark text-decoration-none">
                                        {{ $task->title }}
                                    </a>
                                </div>
                                @if($task->description)
                                    <small class="text-muted text-truncate d-block" style="max-width: 450px;">
                                        {{ $task->description }}
                                    </small>
                                @endif
                            </td>
                            <td>
                                <span class="badge {{ $task->getPriorityBadgeClass() }} px-2 py-1">
                                    {{ $task->getPriorityLabel() }}
                                </span>
                            </td>
                            <td>
                                <span class="badge {{ $task->getStatusBadgeClass() }} px-2 py-1">
                                    {{ $task->getStatusLabel() }}
                                </span>
                            </td>
                            <td>
                                <div class="small {{ $task->isOverdue() ? 'text-danger fw-bold' : 'text-muted' }}">
                                    @if($task->isOverdue())
                                        <i class="bi bi-exclamation-triangle-fill me-1"></i> Overdue
                                    @endif
                                    <div>{{ $task->due_date ? $task->due_date->format('d M, Y') : 'No Date' }}</div>
                                </div>
                            </td>
                            <td class="text-end">
                                <a href="{{ route('member.tasks.show', $task) }}" class="btn btn-sm btn-outline-primary px-3">
                                    <i class="bi bi-box-arrow-up-right me-1"></i> Details
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                <h6 class="fw-bold">No tasks assigned matching your filter.</h6>
                                <p class="small mb-0">Check back later or contact your project administrator.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($tasks->hasPages() || $tasks->total() > 0)
            <div class="card-footer bg-white py-3 d-flex flex-column flex-sm-row justify-content-between align-items-center gap-2">
                <span class="small text-muted">
                    Showing <strong>{{ $tasks->firstItem() ?? 0 }}</strong> to <strong>{{ $tasks->lastItem() ?? 0 }}</strong> of <strong>{{ $tasks->total() }}</strong> tasks
                </span>
                <div>
                    {{ $tasks->links('pagination::bootstrap-5') }}
                </div>
            </div>
        @endif
    </div>
@endsection
