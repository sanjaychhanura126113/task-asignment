@extends('layouts.app')

@section('title', 'My Member Dashboard')

@section('content')
    <!-- Member Welcome Banner -->
    <div class="card bg-primary text-white border-0 shadow-sm p-4 mb-4" style="background: linear-gradient(135deg, #4f46e5 0%, #3730a3 100%);">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <span class="badge bg-white text-primary mb-2 fw-bold">Team Member Workspace</span>
                <h2 class="h3 fw-bold mb-1">Welcome back, {{ Auth::user()->name }}!</h2>
                <p class="mb-0 text-white-50 small">
                    Department: <strong>{{ Auth::user()->department ?? 'General Operations' }}</strong> &bull; 
                    Email: <strong>{{ Auth::user()->email }}</strong>
                </p>
            </div>
            <div>
                <a href="{{ route('member.tasks.index') }}" class="btn btn-light text-primary fw-bold shadow-sm">
                    <i class="bi bi-list-task me-1"></i> View All My Tasks
                </a>
            </div>
        </div>
    </div>

    <!-- Personal Task Metrics -->
    <div class="row g-3 mb-4">
        <!-- Total Assigned -->
        <div class="col-sm-6 col-md-3">
            <div class="card stat-card h-100">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small fw-bold text-uppercase">Assigned</span>
                    <div class="icon-box bg-primary-subtle text-primary">
                        <i class="bi bi-inbox-fill"></i>
                    </div>
                </div>
                <h3 class="fw-bold mb-1">{{ $stats['total_assigned'] }}</h3>
                <span class="small text-muted">Total tasks assigned to you</span>
            </div>
        </div>

        <!-- Pending -->
        <div class="col-sm-6 col-md-3">
            <div class="card stat-card h-100">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small fw-bold text-uppercase">Pending</span>
                    <div class="icon-box bg-warning-subtle text-warning">
                        <i class="bi bi-clock-history"></i>
                    </div>
                </div>
                <h3 class="fw-bold mb-1 text-warning">{{ $stats['pending'] }}</h3>
                <a href="{{ route('member.tasks.index', ['status' => 'pending']) }}" class="small text-warning fw-semibold text-decoration-none">
                    View pending &rarr;
                </a>
            </div>
        </div>

        <!-- In Progress -->
        <div class="col-sm-6 col-md-3">
            <div class="card stat-card h-100">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small fw-bold text-uppercase">In Progress</span>
                    <div class="icon-box bg-info-subtle text-info">
                        <i class="bi bi-gear-wide-connected"></i>
                    </div>
                </div>
                <h3 class="fw-bold mb-1 text-info">{{ $stats['in_progress'] }}</h3>
                <a href="{{ route('member.tasks.index', ['status' => 'in_progress']) }}" class="small text-info fw-semibold text-decoration-none">
                    View active &rarr;
                </a>
            </div>
        </div>

        <!-- Completed -->
        <div class="col-sm-6 col-md-3">
            <div class="card stat-card h-100">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small fw-bold text-uppercase">Completed</span>
                    <div class="icon-box bg-success-subtle text-success">
                        <i class="bi bi-check2-circle"></i>
                    </div>
                </div>
                <h3 class="fw-bold mb-1 text-success">{{ $stats['completed'] }}</h3>
                <a href="{{ route('member.tasks.index', ['status' => 'completed']) }}" class="small text-success fw-semibold text-decoration-none">
                    View completed &rarr;
                </a>
            </div>
        </div>
    </div>

    <!-- Active & Upcoming Tasks for Member -->
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="fw-bold mb-0">My Priority & Upcoming Tasks</h5>
                        <small class="text-muted">Tasks needing your attention or nearing their deadline</small>
                    </div>
                    <a href="{{ route('member.tasks.index') }}" class="btn btn-sm btn-outline-primary">
                        View All
                    </a>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Task Title</th>
                                <th>Priority</th>
                                <th>Status</th>
                                <th>Deadline</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($upcomingTasks as $task)
                                <tr>
                                    <td>
                                        <div class="fw-bold text-dark">
                                            <a href="{{ route('member.tasks.show', $task) }}" class="text-dark text-decoration-none">
                                                {{ Str::limit($task->title, 42) }}
                                            </a>
                                        </div>
                                        <small class="text-muted">Assigned by: {{ $task->creator?->name ?? 'Admin' }}</small>
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
                                                <i class="bi bi-exclamation-triangle-fill me-1"></i> Overdue
                                            @endif
                                            {{ $task->due_date ? $task->due_date->format('M d, Y') : 'No Date' }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('member.tasks.show', $task) }}" class="btn btn-sm btn-primary py-1 px-3">
                                            Update &rarr;
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted">
                                        <i class="bi bi-emoji-smile fs-1 text-success d-block mb-2"></i>
                                        <h6 class="fw-bold">All clear! No pending tasks right now.</h6>
                                        <p class="small mb-0">You have completed all currently assigned assignments.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Recently Completed Accomplishments -->
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white py-3 border-bottom">
                    <h5 class="fw-bold mb-0">Recent Accomplishments</h5>
                    <small class="text-muted">Completed task deliverables</small>
                </div>
                <div class="card-body p-3">
                    <div class="d-flex flex-column gap-3">
                        @forelse($recentCompleted as $task)
                            <div class="p-3 bg-light rounded-3">
                                <div class="d-flex justify-content-between align-items-start mb-1">
                                    <span class="fw-bold small text-dark">{{ Str::limit($task->title, 32) }}</span>
                                    <i class="bi bi-patch-check-fill text-success fs-5"></i>
                                </div>
                                <div class="text-muted small" style="font-size: 0.75rem;">
                                    Completed {{ $task->completed_at ? $task->completed_at->diffForHumans() : 'recently' }}
                                </div>
                            </div>
                        @empty
                            <p class="text-muted small text-center my-4">No completed tasks recorded yet.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
