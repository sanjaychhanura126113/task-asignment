<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() && $this->user()->isAdmin();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'min:3', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'assigned_to' => ['required', 'exists:users,id'],
            'priority' => ['required', 'in:low,medium,high,urgent'],
            'status' => ['required', 'in:pending,in_progress,completed,cancelled'],
            'due_date' => ['required', 'date'],
            'initial_comment' => ['nullable', 'string', 'max:1000'],
        ];
    }

    /**
     * Custom validation error messages.
     */
    public function messages(): array
    {
        return [
            'title.required' => 'The task title is required.',
            'title.min' => 'The task title must contain at least 3 characters.',
            'assigned_to.required' => 'Please select a team member to assign this task.',
            'assigned_to.exists' => 'The selected team member was not found in the database.',
            'priority.required' => 'Please select a priority level.',
            'status.required' => 'Please select an initial task status.',
            'due_date.required' => 'Due date deadline is required.',
            'due_date.date' => 'Please provide a valid date format for the due date.',
        ];
    }
}
