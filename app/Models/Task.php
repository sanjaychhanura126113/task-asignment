<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'assigned_to',
        'created_by',
        'priority',
        'status',
        'due_date',
        'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'due_date' => 'date',
            'completed_at' => 'datetime',
        ];
    }

    /**
     * User who is assigned to this task.
     */
    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /**
     * User who created this task.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Comments and updates on this task.
     */
    public function comments()
    {
        return $this->hasMany(TaskComment::class)->latest();
    }

    /**
     * Check if task is overdue.
     */
    public function isOverdue(): bool
    {
        if ($this->status === 'completed' || $this->status === 'cancelled') {
            return false;
        }

        return $this->due_date && $this->due_date->isPast();
    }

    /**
     * Priority badge class helper.
     */
    public function getPriorityBadgeClass(): string
    {
        return match ($this->priority) {
            'urgent' => 'bg-danger text-white',
            'high' => 'bg-warning text-dark',
            'medium' => 'bg-primary text-white',
            'low' => 'bg-secondary text-white',
            default => 'bg-light text-dark',
        };
    }

    /**
     * Status badge class helper.
     */
    public function getStatusBadgeClass(): string
    {
        return match ($this->status) {
            'completed' => 'bg-success text-white',
            'in_progress' => 'bg-info text-white',
            'pending' => 'bg-warning text-dark',
            'cancelled' => 'bg-secondary text-white',
            default => 'bg-light text-dark',
        };
    }

    /**
     * Human-friendly status label.
     */
    public function getStatusLabel(): string
    {
        return match ($this->status) {
            'in_progress' => 'In Progress',
            'pending' => 'Pending',
            'completed' => 'Completed',
            'cancelled' => 'Cancelled',
            default => ucfirst($this->status),
        };
    }

    /**
     * Human-friendly priority label.
     */
    public function getPriorityLabel(): string
    {
        return ucfirst($this->priority);
    }
}
