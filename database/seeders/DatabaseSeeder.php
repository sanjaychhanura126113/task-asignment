<?php

namespace Database\Seeders;

use App\Models\Task;
use App\Models\TaskComment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Truncate or clean existing records for clean seeding
        TaskComment::query()->delete();
        Task::query()->delete();
        User::query()->delete();

        // 1. Create Admin User
        $admin = User::create([
            'name' => 'Admin Manager',
            'email' => 'admin@taskflow.local',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'department' => 'Operations & Management',
            'phone' => '+91 98765 43210',
        ]);

        // 2. Create Team Members (Employees/Users)
        $sarah = User::create([
            'name' => 'Sarah Khan',
            'email' => 'sarah@taskflow.local',
            'password' => Hash::make('password123'),
            'role' => 'member',
            'department' => 'UI/UX & Frontend',
            'phone' => '+91 98111 22334',
        ]);

        $rahul = User::create([
            'name' => 'Rahul Sharma',
            'email' => 'rahul@taskflow.local',
            'password' => Hash::make('password123'),
            'role' => 'member',
            'department' => 'Backend Engineering',
            'phone' => '+91 98222 33445',
        ]);

        $priya = User::create([
            'name' => 'Priya Patel',
            'email' => 'priya@taskflow.local',
            'password' => Hash::make('password123'),
            'role' => 'member',
            'department' => 'Quality Assurance',
            'phone' => '+91 98333 44556',
        ]);

        $aman = User::create([
            'name' => 'Aman Verma',
            'email' => 'aman@taskflow.local',
            'password' => Hash::make('password123'),
            'role' => 'member',
            'department' => 'DevOps & Cloud',
            'phone' => '+91 98444 55667',
        ]);

        // 3. Seed Realistic Tasks
        $task1 = Task::create([
            'title' => 'Design responsive user dashboard layout in Blade',
            'description' => 'Build high-fidelity dashboard views in Blade templates with responsive metrics cards, badges, and quick links for both Admin and Member roles.',
            'assigned_to' => $sarah->id,
            'created_by' => $admin->id,
            'priority' => 'high',
            'status' => 'in_progress',
            'due_date' => Carbon::now()->addDays(3),
        ]);

        TaskComment::create([
            'task_id' => $task1->id,
            'user_id' => $admin->id,
            'comment' => 'Please follow standard Bootstrap 5 styling with clear visual hierarchy.',
            'status_change' => null,
        ]);

        TaskComment::create([
            'task_id' => $task1->id,
            'user_id' => $sarah->id,
            'comment' => 'Started layout wireframing. Using mobile-friendly collapsible cards.',
            'status_change' => 'Changed status to In Progress',
        ]);

        $task2 = Task::create([
            'title' => 'Implement Task CRUD Controllers and Form Validation',
            'description' => 'Create controllers for store, update, delete, with rigorous validation rules for required fields, character limits, and valid date formats.',
            'assigned_to' => $rahul->id,
            'created_by' => $admin->id,
            'priority' => 'urgent',
            'status' => 'completed',
            'due_date' => Carbon::now()->subDays(1),
            'completed_at' => Carbon::now()->subHours(6),
        ]);

        TaskComment::create([
            'task_id' => $task2->id,
            'user_id' => $rahul->id,
            'comment' => 'Implemented server-side validation rules and flash session feedback messages.',
            'status_change' => 'Changed status to Completed',
        ]);

        $task3 = Task::create([
            'title' => 'Verify input validation and edge cases',
            'description' => 'Perform QA on Task creation and editing forms: test empty submissions, invalid email formats, past due dates, and long strings.',
            'assigned_to' => $priya->id,
            'created_by' => $admin->id,
            'priority' => 'medium',
            'status' => 'pending',
            'due_date' => Carbon::now()->addDays(5),
        ]);

        $task4 = Task::create([
            'title' => 'Configure SQLite database backup and optimization',
            'description' => 'Set up automated database backup commands and verify query indexes on foreign keys (assigned_to, created_by).',
            'assigned_to' => $aman->id,
            'created_by' => $admin->id,
            'priority' => 'low',
            'status' => 'pending',
            'due_date' => Carbon::now()->addDays(7),
        ]);

        $task5 = Task::create([
            'title' => 'Fix overdue alert notification banner',
            'description' => 'Ensure any task with a past due date that is not marked completed shows a distinctive danger badge and warning callout on the dashboard.',
            'assigned_to' => $sarah->id,
            'created_by' => $admin->id,
            'priority' => 'urgent',
            'status' => 'pending',
            'due_date' => Carbon::now()->subDays(2), // Overdue intentionally
        ]);

        $task6 = Task::create([
            'title' => 'Add search and multi-criteria filters on task listings',
            'description' => 'Allow filtering tasks by assigned member, priority level, status, and free-text search on title and description.',
            'assigned_to' => $rahul->id,
            'created_by' => $admin->id,
            'priority' => 'high',
            'status' => 'in_progress',
            'due_date' => Carbon::now()->addDays(2),
        ]);

        $task7 = Task::create([
            'title' => 'Run security checks on role-based middleware',
            'description' => 'Confirm that regular members cannot access admin routes (creating members, deleting tasks, or modifying admin-restricted settings).',
            'assigned_to' => $priya->id,
            'created_by' => $admin->id,
            'priority' => 'urgent',
            'status' => 'in_progress',
            'due_date' => Carbon::now()->addDays(1),
        ]);

        $task8 = Task::create([
            'title' => 'Prepare user documentation and setup guide',
            'description' => 'Write clear instructions in README for project setup, running artisan serve, default credentials, and route breakdown.',
            'assigned_to' => $aman->id,
            'created_by' => $admin->id,
            'priority' => 'medium',
            'status' => 'completed',
            'due_date' => Carbon::now()->subDays(3),
            'completed_at' => Carbon::now()->subDays(1),
        ]);
    }
}
