<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AssignTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Policyで認可するためtrue
        return true;
    }

    public function rules(): array
    {
        return [
            'task_id' => ['required', 'exists:tasks,id'],
            'user_id' => ['required', 'exists:users,id'],
        ];
    }
}
