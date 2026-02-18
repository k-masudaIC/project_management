<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Policyで制御
    }

    public function rules(): array
    {
        $taskId = $this->route('task')?->id;
        return [
            'project_id' => ['required', 'exists:projects,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'in:not_started,in_progress,in_review,completed,on_hold'],
            'priority' => ['required', 'in:low,medium,high'],
            'estimated_hours' => ['nullable', 'numeric', 'min:0'],
            'start_date' => ['nullable', 'date'],
            'due_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'sort_order' => ['nullable', 'integer'],
        ];
    }
}
