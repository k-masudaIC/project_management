<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Policyで制御
    }

    public function rules(): array
    {
        $projectId = $this->route('project')?->id;

        return [
            'client_id' => ['required', 'exists:clients,id'],
            'name' => ['required', 'string', 'max:255'],
            'code' => ['nullable', 'string', 'max:50', \Illuminate\Validation\Rule::unique('projects', 'code')->ignore($projectId)],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'in:proposal,in_progress,on_hold,completed,cancelled'],
            'budget' => ['nullable', 'numeric', 'min:0'],
            'estimated_hours' => ['nullable', 'numeric', 'min:0'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
        ];
    }
}
