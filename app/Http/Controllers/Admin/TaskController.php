<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Task;
use App\Models\TaskComment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    /**
     * Display a listing of all tasks with filtering, search, and pagination.
     */
    public function index(Request $request)
    {
        $query = Task::with(['assignee', 'creator']);

        // Search by keyword (title or description)
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by Status
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        // Filter by Priority
        if ($request->filled('priority')) {
            $query->where('priority', $request->input('priority'));
        }

        // Filter by Assigned User
        if ($request->filled('assigned_to')) {
            $query->where('assigned_to', $request->input('assigned_to'));
        }

        // Filter Overdue tasks
        if ($request->input('filter') === 'overdue') {
            $query->where('due_date', '<', Carbon::today())
                  ->whereNotIn('status', ['completed', 'cancelled']);
        }

        // Sorting
        $sort = $request->input('sort', 'latest');
        if ($sort === 'due_date_asc') {
            $query->orderBy('due_date', 'asc');
        } elseif ($sort === 'due_date_desc') {
            $query->orderBy('due_date', 'desc');
        } elseif ($sort === 'priority') {
            $query->orderByRaw("CASE 
                WHEN priority = 'urgent' THEN 1 
                WHEN priority = 'high' THEN 2 
                WHEN priority = 'medium' THEN 3 
                ELSE 4 END");
        } else {
            $query->latest();
        }

        $tasks = $query->paginate(10)->withQueryString();

        // Team members for filter dropdown
        $members = User::orderBy('name')->get();

        return view('admin.tasks.index', compact('tasks', 'members'));
    }

    /**
     * Show the form for creating a new task.
     */
    public function create()
    {
        $members = User::orderBy('name')->get();
        return view('admin.tasks.create', compact('members'));
    }

    /**
     * Store a newly created task in storage.
     */
    public function store(StoreTaskRequest $request)
    {
        $validated = $request->validated();

        $task = Task::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'assigned_to' => $validated['assigned_to'],
            'created_by' => Auth::id(),
            'priority' => $validated['priority'],
            'status' => $validated['status'],
            'due_date' => $validated['due_date'],
            'completed_at' => ($validated['status'] === 'completed') ? now() : null,
        ]);

        // Add initial comment if provided
        if (!empty($validated['initial_comment'])) {
            TaskComment::create([
                'task_id' => $task->id,
                'user_id' => Auth::id(),
                'comment' => $validated['initial_comment'],
                'status_change' => 'Task assigned to ' . $task->assignee?->name,
            ]);
        }

        return redirect()->route('admin.tasks.index')
            ->with('success', "Task \"{$task->title}\" was created and assigned successfully!");
    }

    /**
     * Display the specified task with its timeline and comments.
     */
    public function show(Task $task)
    {
        $task->load(['assignee', 'creator', 'comments.user']);
        return view('admin.tasks.show', compact('task'));
    }

    /**
     * Show the form for editing the specified task.
     */
    public function edit(Task $task)
    {
        $members = User::orderBy('name')->get();
        return view('admin.tasks.edit', compact('task', 'members'));
    }

    /**
     * Update the specified task in storage.
     */
    public function update(UpdateTaskRequest $request, Task $task)
    {
        $validated = $request->validated();

        $oldStatus = $task->status;
        $newStatus = $validated['status'];
        $statusChanged = ($oldStatus !== $newStatus);

        $task->update([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'assigned_to' => $validated['assigned_to'],
            'priority' => $validated['priority'],
            'status' => $validated['status'],
            'due_date' => $validated['due_date'],
            'completed_at' => ($newStatus === 'completed') ? ($task->completed_at ?? now()) : null,
        ]);

        // If status changed or note provided, log comment
        if ($statusChanged || !empty($validated['update_note'])) {
            $changeMsg = $statusChanged
                ? "Status updated from " . ucfirst(str_replace('_', ' ', $oldStatus)) . " to " . ucfirst(str_replace('_', ' ', $newStatus))
                : null;

            TaskComment::create([
                'task_id' => $task->id,
                'user_id' => Auth::id(),
                'comment' => $validated['update_note'] ?? 'Updated task details.',
                'status_change' => $changeMsg,
            ]);
        }

        return redirect()->route('admin.tasks.show', $task)
            ->with('success', "Task \"{$task->title}\" was updated successfully!");
    }

    /**
     * Remove the specified task from storage.
     */
    public function destroy(Task $task)
    {
        $title = $task->title;
        $task->delete();

        return redirect()->route('admin.tasks.index')
            ->with('success', "Task \"{$title}\" has been permanently deleted.");
    }

    /**
     * Add a comment or remark to the task.
     */
    public function addComment(Request $request, Task $task)
    {
        $validated = $request->validate([
            'comment' => ['required', 'string', 'min:2', 'max:1000'],
        ], [
            'comment.required' => 'Please write a message before posting.',
            'comment.min' => 'Comment must be at least 2 characters long.',
        ]);

        TaskComment::create([
            'task_id' => $task->id,
            'user_id' => Auth::id(),
            'comment' => $validated['comment'],
            'status_change' => null,
        ]);

        return redirect()->route('admin.tasks.show', $task)
            ->with('success', 'Your remark was posted successfully.');
    }
}
