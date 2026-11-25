<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\TaskComment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display the Admin overview dashboard.
     */
    public function index()
    {
        $today = Carbon::today();

        $stats = [
            'total_tasks' => Task::count(),
            'pending_tasks' => Task::where('status', 'pending')->count(),
            'in_progress_tasks' => Task::where('status', 'in_progress')->count(),
            'completed_tasks' => Task::where('status', 'completed')->count(),
            'cancelled_tasks' => Task::where('status', 'cancelled')->count(),
            'overdue_tasks' => Task::where('due_date', '<', $today)
                ->whereNotIn('status', ['completed', 'cancelled'])
                ->count(),
            'total_members' => User::where('role', 'member')->count(),
            'total_admins' => User::where('role', 'admin')->count(),
        ];

        // Priority breakdown
        $priorityStats = [
            'urgent' => Task::where('priority', 'urgent')->count(),
            'high' => Task::where('priority', 'high')->count(),
            'medium' => Task::where('priority', 'medium')->count(),
            'low' => Task::where('priority', 'low')->count(),
        ];

        // Recent tasks
        $recentTasks = Task::with('assignee', 'creator')
            ->latest()
            ->take(6)
            ->get();

        // Team members workload
        $teamMembers = User::where('role', 'member')
            ->withCount([
                'assignedTasks as total_assigned',
                'assignedTasks as pending_count' => function ($q) {
                    $q->whereIn('status', ['pending', 'in_progress']);
                },
                'assignedTasks as completed_count' => function ($q) {
                    $q->where('status', 'completed');
                },
            ])
            ->take(5)
            ->get();

        // Recent task activities/comments
        $recentActivities = TaskComment::with('user', 'task')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'priorityStats', 'recentTasks', 'teamMembers', 'recentActivities'));
    }
}
