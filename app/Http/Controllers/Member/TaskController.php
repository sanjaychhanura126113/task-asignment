<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\TaskComment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    /**
     * Display a listing of tasks assigned to the current member.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Task::with('creator')->where('assigned_to', $user->id);

        // Search
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Status Filter
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        // Priority Filter
        if ($request->filled('priority')) {
            $query->where('priority', $request->input('priority'));
        }

        // Overdue Filter
        if ($request->input('filter') === 'overdue') {
            $query->where('due_date', '<', Carbon::today())
                  ->whereNotIn('status', ['completed', 'cancelled']);
        }

        $tasks = $query->orderBy('due_date', 'asc')->paginate(10)->withQueryString();

        return view('member.tasks.index', compact('tasks'));
    }

    /**
     * Show task details for the assigned member.
     */
    public function show(Task $task)
    {
        $user = Auth::user();

        // Check if user is authorized to view this task
        if ($task->assigned_to !== $user->id && !$user->isAdmin()) {
            abort(403, 'Unauthorized. You can only view tasks assigned to you.');
        }

        $task->load(['creator', 'comments.user']);

        return view('member.tasks.show', compact('task'));
    }

    /**
     * Update task status with an optional progress remark.
     */
    public function updateStatus(Request $request, Task $task)
    {
        $user = Auth::user();

        if ($task->assigned_to !== $user->id && !$user->isAdmin()) {
            abort(403, 'Unauthorized.');
        }

        $validated = $request->validate([
            'status' => ['required', 'in:pending,in_progress,completed'],
            'note' => ['nullable', 'string', 'max:1000'],
        ], [
            'status.required' => 'Please select a status.',
        ]);

        $oldStatus = $task->status;
        $newStatus = $validated['status'];

        $task->status = $newStatus;
        if ($newStatus === 'completed') {
            $task->completed_at = now();
        } else {
            $task->completed_at = null;
        }
        $task->save();

        $changeMsg = "Changed status from " . ucfirst(str_replace('_', ' ', $oldStatus)) . " to " . ucfirst(str_replace('_', ' ', $newStatus));

        TaskComment::create([
            'task_id' => $task->id,
            'user_id' => $user->id,
            'comment' => $validated['note'] ?? $changeMsg,
            'status_change' => $changeMsg,
        ]);

        return redirect()->route('member.tasks.show', $task)
            ->with('success', "Task status updated to " . ucfirst(str_replace('_', ' ', $newStatus)) . "!");
    }

    /**
     * Add a comment/update on the task.
     */
    public function addComment(Request $request, Task $task)
    {
        $user = Auth::user();

        if ($task->assigned_to !== $user->id && !$user->isAdmin()) {
            abort(403, 'Unauthorized.');
        }

        $validated = $request->validate([
            'comment' => ['required', 'string', 'min:2', 'max:1000'],
        ], [
            'comment.required' => 'Please type a comment or update.',
            'comment.min' => 'Comment must have at least 2 characters.',
        ]);

        TaskComment::create([
            'task_id' => $task->id,
            'user_id' => $user->id,
            'comment' => $validated['comment'],
            'status_change' => null,
        ]);

        return redirect()->route('member.tasks.show', $task)
            ->with('success', 'Your update was posted successfully.');
    }
}
