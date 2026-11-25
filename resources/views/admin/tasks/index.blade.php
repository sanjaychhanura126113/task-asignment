@extends('layouts.app')

@section('title', 'Task Listings & Management')

@section('content')
    <!-- Page Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h2 class="h4 fw-bold mb-1">Task Assignment Directory</h2>
            <p class="text-muted small mb-0">Browse, filter, assign, and manage all organizational tasks.</p>
        </div>
        <div>
            <a href="{{ route('admin.tasks.create') }}" class="btn btn-primary shadow-sm">
                <i class="bi bi-plus-lg me-1"></i> Assign New Task
            </a>
        </div>
    </div>

    <!-- Filters & Search Toolbar Card -->
    <div class="card mb-4">
        <div class="card-body p-3">
            <form method="GET" action="{{ route('admin.tasks.index') }}" class="row g-2 align-items-center">
                <!-- Search Keyword -->
                <div class="col-md-3">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-light"><i class="bi bi-search text-muted"></i></span>
                        <input 
                            type="text" 
                            name="search" 
                            class="form-control" 
                            placeholder="Search title, description..." 
                            value="{{ request('search') }}"
                        >
                    </div>
                </div>

                <!-- Status Filter -->
                <div class="col-sm-6 col-md-2">
                    <select name="status" class="form-select form-select-sm">
                        <option value="">-- All Statuses --</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>

                <!-- Priority Filter -->
                <div class="col-sm-6 col-md-2">
                    <select name="priority" class="form-select form-select-sm">
                        <option value="">-- All Priorities --</option>
                        <option value="urgent" {{ request('priority') === 'urgent' ? 'selected' : '' }}>Urgent</option>
                        <option value="high" {{ request('priority') === 'high' ? 'selected' : '' }}>High</option>
                        <option value="medium" {{ request('priority') === 'medium' ? 'selected' : '' }}>Medium</option>
                        <option value="low" {{ request('priority') === 'low' ? 'selected' : '' }}>Low</option>
                    </select>
                </div>

                <!-- Assignee Filter -->
                <div class="col-sm-6 col-md-2">
                    <select name="assigned_to" class="form-select form-select-sm">
                        <option value="">-- All Members --</option>
                        @foreach($members as $m)
                            <option value="{{ $m->id }}" {{ request('assigned_to') == $m->id ? 'selected' : '' }}>
                                {{ $m->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Sort Order -->
                <div class="col-sm-6 col-md-2">
                    <select name="sort" class="form-select form-select-sm">
                        <option value="latest" {{ request('sort') === 'latest' ? 'selected' : '' }}>Newest First</option>
                        <option value="due_date_asc" {{ request('sort') === 'due_date_asc' ? 'selected' : '' }}>Due Date (Earliest)</option>
                        <option value="priority" {{ request('sort') === 'priority' ? 'selected' : '' }}>Priority Order</option>
                    </select>
                </div>

                <!-- Submit & Clear Buttons -->
                <div class="col-md-1 d-flex gap-1">
                    <button type="submit" class="btn btn-sm btn-primary w-100" title="Apply Filters">
                        <i class="bi bi-funnel"></i>
                    </button>
                    @if(request()->hasAny(['search', 'status', 'priority', 'assigned_to', 'sort', 'filter']))
                        <a href="{{ route('admin.tasks.index') }}" class="btn btn-sm btn-light border" title="Reset Filters">
                            <i class="bi bi-x-lg text-danger"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Record Listings Table Card -->
    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th style="width: 60px;">ID</th>
                        <th>Task Title & Overview</th>
                        <th>Assigned To</th>
                        <th>Priority</th>
                        <th>Status</th>
                        <th>Due Date</th>
                        <th class="text-end" style="width: 150px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tasks as $task)
                        <tr>
                            <!-- Task ID -->
                            <td class="text-muted fw-bold">#{{ $task->id }}</td>

                            <!-- Title & Description preview -->
                            <td>
                                <div class="fw-bold text-dark">
                                    <a href="{{ route('admin.tasks.show', $task) }}" class="text-dark text-decoration-none">
                                        {{ $task->title }}
                                    </a>
                                </div>
                                @if($task->description)
                                    <small class="text-muted text-truncate d-block" style="max-width: 380px;">
                                        {{ $task->description }}
                                    </small>
                                @endif
                            </td>

                            <!-- Assigned Member -->
                            <td>
                                @if($task->assignee)
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="avatar-circle">
                                            {{ strtoupper(substr($task->assignee->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="fw-semibold small text-dark">{{ $task->assignee->name }}</div>
                                            <span class="text-muted" style="font-size: 0.72rem;">{{ $task->assignee->department ?? 'General' }}</span>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-muted small fst-italic">Unassigned</span>
                                @endif
                            </td>

                            <!-- Priority Badge -->
                            <td>
                                <span class="badge {{ $task->getPriorityBadgeClass() }} px-2 py-1">
                                    {{ $task->getPriorityLabel() }}
                                </span>
                            </td>

                            <!-- Status Badge -->
                            <td>
                                <span class="badge {{ $task->getStatusBadgeClass() }} px-2 py-1">
                                    {{ $task->getStatusLabel() }}
                                </span>
                            </td>

                            <!-- Due Date -->
                            <td>
                                <div class="small {{ $task->isOverdue() ? 'text-danger fw-bold' : 'text-muted' }}">
                                    @if($task->isOverdue())
                                        <i class="bi bi-exclamation-triangle-fill me-1"></i> Overdue
                                    @endif
                                    <div>{{ $task->due_date ? $task->due_date->format('d M, Y') : 'No Date' }}</div>
                                </div>
                            </td>

                            <!-- CRUD Actions -->
                            <td class="text-end">
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.tasks.show', $task) }}" class="btn btn-light border" title="View Task Details">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.tasks.edit', $task) }}" class="btn btn-light border text-primary" title="Edit Task">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <button 
                                        type="button" 
                                        class="btn btn-light border text-danger" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#deleteModal" 
                                        data-task-id="{{ $task->id }}" 
                                        data-task-title="{{ $task->title }}"
                                        title="Delete Task"
                                    >
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="bi bi-clipboard-x fs-1 text-muted d-block mb-2"></i>
                                <h6 class="fw-bold">No tasks matched your criteria.</h6>
                                <p class="small mb-3">Try adjusting your filters or search keywords.</p>
                                <a href="{{ route('admin.tasks.create') }}" class="btn btn-sm btn-primary">
                                    <i class="bi bi-plus-lg me-1"></i> Create New Task
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination & Summary Footer -->
        @if($tasks->hasPages() || $tasks->total() > 0)
            <div class="card-footer bg-white py-3 d-flex flex-column flex-sm-row justify-content-between align-items-center gap-2">
                <span class="small text-muted">
                    Showing <strong>{{ $tasks->firstItem() ?? 0 }}</strong> to <strong>{{ $tasks->lastItem() ?? 0 }}</strong> of <strong>{{ $tasks->total() }}</strong> total tasks
                </span>
                <div>
                    {{ $tasks->links('pagination::bootstrap-5') }}
                </div>
            </div>
        @endif
    </div>

    <!-- Reusable Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold text-danger" id="deleteModalLabel">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i> Confirm Delete Action
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-2">Are you sure you want to permanently delete this task?</p>
                    <div class="p-2 bg-light rounded text-dark fw-bold small" id="deleteTaskTitle"></div>
                    <p class="small text-muted mt-2 mb-0">This action cannot be undone and will delete all associated comments.</p>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-sm btn-light border" data-bs-dismiss="modal">Cancel</button>
                    <form id="deleteTaskForm" method="POST" action="">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger px-3">
                            <i class="bi bi-trash me-1"></i> Yes, Delete Task
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const deleteModal = document.getElementById('deleteModal');
        if (deleteModal) {
            deleteModal.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget;
                const taskId = button.getAttribute('data-task-id');
                const taskTitle = button.getAttribute('data-task-title');

                const titleHolder = document.getElementById('deleteTaskTitle');
                titleHolder.textContent = taskTitle;

                const form = document.getElementById('deleteTaskForm');
                form.action = "{{ url('admin/tasks') }}/" + taskId;
            });
        }
    });
</script>
@endpush
