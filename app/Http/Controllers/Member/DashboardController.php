<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\TaskComment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display the member's personal task dashboard.
     */
    public function index()
    {
        $user = Auth::user();
        $today = Carbon::today();

        $stats = [
            'total_assigned' => Task::where('assigned_to', $user->id)->count(),
            'pending' => Task::where('assigned_to', $user->id)->where('status', 'pending')->count(),
            'in_progress' => Task::where('assigned_to', $user->id)->where('status', 'in_progress')->count(),
            'completed' => Task::where('assigned_to', $user->id)->where('status', 'completed')->count(),
            'overdue' => Task::where('assigned_to', $user->id)
                ->where('due_date', '<', $today)
                ->whereNotIn('status', ['completed', 'cancelled'])
                ->count(),
        ];

        // Urgent or Due Soon tasks for this member
        $upcomingTasks = Task::where('assigned_to', $user->id)
            ->whereNotIn('status', ['completed', 'cancelled'])
            ->orderBy('due_date', 'asc')
            ->take(5)
            ->get();

        // Recently completed tasks by this member
        $recentCompleted = Task::where('assigned_to', $user->id)
            ->where('status', 'completed')
            ->latest('completed_at')
            ->take(4)
            ->get();

        return view('member.dashboard', compact('stats', 'upcomingTasks', 'recentCompleted'));
    }
}
