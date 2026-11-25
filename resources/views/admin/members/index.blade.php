@extends('layouts.app')

@section('title', 'Team Members')

@section('content')
    <!-- Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h2 class="h4 fw-bold mb-1">Team Members Directory</h2>
            <p class="text-muted small mb-0">Manage system users, assign roles, departments, and track individual workloads.</p>
        </div>
        <div>
            <a href="{{ route('admin.members.create') }}" class="btn btn-primary shadow-sm">
                <i class="bi bi-person-plus me-1"></i> Add New Member
            </a>
        </div>
    </div>

    <!-- Members Table Card -->
    <div class="card shadow-sm border-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>Member Name</th>
                        <th>Email Address</th>
                        <th>Department</th>
                        <th>Role</th>
                        <th>Workload (Tasks)</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($members as $member)
                        <tr>
                            <!-- Name + Avatar -->
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="avatar-circle">
                                        {{ strtoupper(substr($member->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark">{{ $member->name }}</div>
                                        <span class="text-muted" style="font-size: 0.72rem;">{{ $member->phone ?: 'No phone added' }}</span>
                                    </div>
                                </div>
                            </td>

                            <!-- Email -->
                            <td class="small text-muted">{{ $member->email }}</td>

                            <!-- Department -->
                            <td>
                                <span class="badge bg-light text-dark border">
                                    {{ $member->department ?: 'General' }}
                                </span>
                            </td>

                            <!-- Role Badge -->
                            <td>
                                @if($member->isAdmin())
                                    <span class="badge-role-admin">
                                        <i class="bi bi-shield-lock-fill me-1"></i> Admin
                                    </span>
                                @else
                                    <span class="badge-role-member">
                                        <i class="bi bi-person-badge-fill me-1"></i> Member
                                    </span>
                                @endif
                            </td>

                            <!-- Tasks Workload -->
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge bg-warning-subtle text-warning border border-warning-subtle">
                                        {{ $member->pending_tasks }} Pending
                                    </span>
                                    <span class="badge bg-success-subtle text-success border border-success-subtle">
                                        {{ $member->completed_tasks }} Done
                                    </span>
                                    <span class="text-muted small">({{ $member->total_tasks }} Total)</span>
                                </div>
                            </td>

                            <!-- Actions -->
                            <td class="text-end">
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.members.edit', $member) }}" class="btn btn-light border text-primary" title="Edit Member">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    @if($member->id !== Auth::id())
                                        <form method="POST" action="{{ route('admin.members.destroy', $member) }}" class="d-inline" onsubmit="return confirm('Remove team member {{ $member->name }}? Any assigned tasks will be unassigned.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-light border text-danger" title="Remove Member">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">
                                No team members found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($members->hasPages())
            <div class="card-footer bg-white py-3">
                {{ $members->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
@endsection
