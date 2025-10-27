<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // If the request contains 'done', we're just marking as complete
        if ($this->has('done')) {
            return [
                'done' => ['required', 'boolean'],
                'status' => ['nullable', 'string', 'in:open,overdue,'],
            ];
        }

        // Otherwise, it’s a normal edit
        return [
            'description' => ['required', 'string', 'max:255'],
            'due_date' => ['nullable', 'date'],
            'status' => ['nullable', 'string', 'in:open,overdue,'],
        ];
    }
}
