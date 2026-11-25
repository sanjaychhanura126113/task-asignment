<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateMemberRequest extends FormRequest
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
        $memberId = $this->route('member')?->id ?? $this->route('member');

        return [
            'name' => ['required', 'string', 'min:3', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($memberId)],
            'password' => ['nullable', 'string', 'min:6'],
            'role' => ['required', 'in:member,admin'],
            'department' => ['nullable', 'string', 'max:100'],
            'phone' => ['nullable', 'string', 'max:20'],
        ];
    }

    /**
     * Custom validation error messages.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Member name is required.',
            'name.min' => 'Member name must contain at least 3 characters.',
            'email.required' => 'Email address is required.',
            'email.unique' => 'This email address is already in use.',
        ];
    }
}
