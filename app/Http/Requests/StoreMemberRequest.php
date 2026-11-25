<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMemberRequest extends FormRequest
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
            'name' => ['required', 'string', 'min:3', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6'],
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
            'email.unique' => 'This email is already registered to another user.',
            'password.required' => 'A password is required for the user account.',
            'password.min' => 'Password must be at least 6 characters.',
        ];
    }
}
