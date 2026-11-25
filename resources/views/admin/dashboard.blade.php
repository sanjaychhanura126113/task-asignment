@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
    <!-- Dashboard Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h2 class="h4 fw-bold mb-1">Administrative Dashboard</h2>
            <p class="text-muted small mb-0">Overview of project tasks, team workload, and workflow deadlines.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.tasks.create') }}" class="btn btn-primary shadow-sm">
                <i class="bi bi-plus-lg me-1"></i> Assign New Task
            </a>
            <a href="{{ route('admin.members.create') }}" class="btn btn-outline-secondary shadow-sm">
                <i class="bi bi-person-plus me-1"></i> Add Member
            </a>
        </div>
    </div>

    <!-- Quick Stats Metric Cards -->
    <div class="row g-3 mb-4">
        <!-- Total Tasks -->
        <div class="col-sm-6 col-xl-2">
            <div class="card stat-card h-100">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small fw-bold text-uppercase">Total</span>
                    <div class="icon-box bg-primary-subtle text-primary">
                        <i class="bi bi-kanban"></i>
                    </div>
                </div>
                <h3 class="fw-bold mb-1">{{ $stats['total_tasks'] }}</h3>
                <a href="{{ route('admin.tasks.index') }}" class="text-decoration-none small text-primary fw-semibold">
                    View all &rarr;
                </a>
            </div>
        </div>

        <!-- Pending Tasks -->
        <div class="col-sm-6 col-xl-2">
            <div class="card stat-card h-100">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small fw-bold text-uppercase">Pending</span>
                    <div class="icon-box bg-warning-subtle text-warning">
                        <i class="bi bi-clock-history"></i>
                    </div>
                </div>
                <h3 class="fw-bold mb-1">{{ $stats['pending_tasks'] }}</h3>
                <a href="{{ route('admin.tasks.index', ['status' => 'pending']) }}" class="text-decoration-none small text-warning fw-semibold">
                    Filter pending &rarr;
                </a>
            </div>
        </div>

        <!-- In Progress Tasks -->
        <div class="col-sm-6 col-xl-2">
            <div class="card stat-card h-100">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small fw-bold text-uppercase">In Progress</span>
                    <div class="icon-box bg-info-subtle text-info">
                        <i class="bi bi-gear-wide-connected"></i>
                    </div>
                </div>
                <h3 class="fw-bold mb-1">{{ $stats['in_progress_tasks'] }}</h3>
                <a href="{{ route('admin.tasks.index', ['status' => 'in_progress']) }}" class="text-decoration-none small text-info fw-semibold">
                    Filter active &rarr;
                </a>
            </div>
        </div>

        <!-- Completed Tasks -->
        <div class="col-sm-6 col-xl-2">
            <div class="card stat-card h-100">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small fw-bold text-uppercase">Completed</span>
                    <div class="icon-box bg-success-subtle text-success">
                        <i class="bi bi-check2-circle"></i>
                    </div>
                </div>
                <h3 class="fw-bold mb-1">{{ $stats['completed_tasks'] }}</h3>
                <a href="{{ route('admin.tasks.index', ['status' => 'completed']) }}" class="text-decoration-none small text-success fw-semibold">
                    Filter completed &rarr;
                </a>
            </div>
        </div>

        <!-- Overdue Tasks -->
        <div class="col-sm-6 col-xl-2">
            <div class="card stat-card h-100 {{ $stats['overdue_tasks'] > 0 ? 'border-danger' : '' }}">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-danger small fw-bold text-uppercase">Overdue</span>
                    <div class="icon-box bg-danger-subtle text-danger">
                        <i class="bi bi-exclamation-octagon"></i>
                    </div>
                </div>
                <h3 class="fw-bold mb-1 text-danger">{{ $stats['overdue_tasks'] }}</h3>
                <a href="{{ route('admin.tasks.index', ['filter' => 'overdue']) }}" class="text-decoration-none small text-danger fw-semibold">
                    Needs attention &rarr;
                </a>
            </div>
        </div>

        <!-- Team Members -->
        <div class="col-sm-6 col-xl-2">
            <div class="card stat-card h-100">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small fw-bold text-uppercase">Members</span>
                    <div class="icon-box bg-secondary-subtle text-dark">
                        <i class="bi bi-people"></i>
                    </div>
                </div>
                <h3 class="fw-bold mb-1">{{ $stats['total_members'] }}</h3>
                <a href="{{ route('admin.members.index') }}" class="text-decoration-none small text-secondary fw-semibold">
                    Manage team &rarr;
                </a>
            </div>
        </div>
    </div>

    <!-- Main Dashboard Row: Recent Tasks + Team Workload & Activity -->
    <div class="row g-4">
        <!-- Left: Recent Tasks Table -->
        <div class="col-lg-8">
            <div class="card h-100">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom">
                    <div>
                        <h5 class="fw-bold mb-0">Recent Assigned Tasks</h5>
                        <small class="text-muted">Latest tasks created in the system</small>
                    </div>
                    <a href="{{ route('admin.tasks.index') }}" class="btn btn-sm btn-outline-primary">
                        View All Tasks <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Task Title</th>
                                <th>Assigned Member</th>
                                <th>Priority</th>
                                <th>Status</th>
                                <th>Due Date</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentTasks as $task)
                                <tr>
                                    <td>
                                        <div class="fw-bold text-dark">
                                            <a href="{{ route('admin.tasks.show', $task) }}" class="text-dark text-decoration-none">
                                                {{ Str::limit($task->title, 38) }}
                                            </a>
                                        </div>
                                        <small class="text-muted">ID: #{{ $task->id }}</small>
                                    </td>
                                    <td>
                                        @if($task->assignee)
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="avatar-circle" style="width: 28px; height: 28px; font-size: 0.75rem;">
                                                    {{ strtoupper(substr($task->assignee->name, 0, 1)) }}
                                                </div>
                                                <span class="small fw-semibold">{{ $task->assignee->name }}</span>
                                            </div>
                                        @else
                                            <span class="text-muted small fst-italic">Unassigned</span>
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
                                        <span class="small {{ $task->isOverdue() ? 'text-danger fw-bold' : 'text-muted' }}">
                                            @if($task->isOverdue())
                                                <i class="bi bi-exclamation-circle-fill me-1"></i>
                                            @endif
                                            {{ $task->due_date ? $task->due_date->format('M d, Y') : 'No Date' }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('admin.tasks.show', $task) }}" class="btn btn-sm btn-light border py-1 px-2" title="View Details">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.tasks.edit', $task) }}" class="btn btn-sm btn-light border py-1 px-2 text-primary" title="Edit Task">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">
                                        <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                                        No tasks assigned yet. <a href="{{ route('admin.tasks.create') }}">Create the first task</a>.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Right: Team Member Workload & Recent Activity -->
        <div class="col-lg-4 d-flex flex-column gap-4">
            <!-- Team Workload Card -->
            <div class="card">
                <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0">Team Workload</h5>
                    <a href="{{ route('admin.members.index') }}" class="small text-decoration-none">Manage &rarr;</a>
                </div>
                <div class="card-body p-3">
                    <div class="d-flex flex-column gap-3">
                        @forelse($teamMembers as $member)
                            <div>
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="avatar-circle" style="width: 26px; height: 26px; font-size: 0.7rem;">
                                            {{ strtoupper(substr($member->name, 0, 1)) }}
                                        </div>
                                        <span class="small fw-bold">{{ $member->name }}</span>
                                    </div>
                                    <span class="small text-muted">
                                        {{ $member->pending_count }} active / {{ $member->total_assigned }} total
                                    </span>
                                </div>
                                @php
                                    $percent = $member->total_assigned > 0 ? round(($member->completed_count / $member->total_assigned) * 100) : 0;
                                @endphp
                                <div class="progress" style="height: 6px;">
                                    <div class="progress-bar bg-success" role="progressbar" style="width: {{ $percent }}%" title="{{ $percent }}% completed"></div>
                                </div>
                            </div>
                        @empty
                            <p class="text-muted small text-center my-2">No team members registered.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Recent Task Updates / Comments -->
            <div class="card flex-grow-1">
                <div class="card-header bg-white py-3 border-bottom">
                    <h5 class="fw-bold mb-0">Activity Feed</h5>
                </div>
                <div class="card-body p-3">
                    <div class="d-flex flex-column gap-3">
                        @forelse($recentActivities as $activity)
                            <div class="d-flex gap-2">
                                <div class="avatar-circle mt-1 flex-shrink-0" style="width: 28px; height: 28px; font-size: 0.7rem;">
                                    {{ strtoupper(substr($activity->user->name, 0, 1)) }}
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="small fw-bold text-dark">{{ $activity->user->name }}</span>
                                        <span class="text-muted" style="font-size: 0.7rem;">{{ $activity->created_at->diffForHumans() }}</span>
                                    </div>
                                    @if($activity->status_change)
                                        <span class="badge bg-secondary-subtle text-secondary small py-0 px-1 mb-1" style="font-size: 0.65rem;">
                                            {{ $activity->status_change }}
                                        </span>
                                    @endif
                                    <p class="small text-muted mb-0 lh-sm">{{ Str::limit($activity->comment, 80) }}</p>
                                    @if($activity->task)
                                        <a href="{{ route('admin.tasks.show', $activity->task) }}" class="small text-primary text-decoration-none" style="font-size: 0.725rem;">
                                            Task: {{ Str::limit($activity->task->title, 25) }}
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <p class="text-muted small text-center my-3">No activity recorded yet.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
