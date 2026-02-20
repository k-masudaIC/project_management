<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDailyTimeEntryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'work_date' => ['required', 'date'],
            'tasks' => ['required', 'array'],
            'tasks.*.task_id' => ['required', 'exists:tasks,id'],
            'tasks.*.hours' => ['required', 'numeric', 'min:0.01', 'max:24'],
            'tasks.*.description' => ['nullable', 'string'],
        ];
    }
}
