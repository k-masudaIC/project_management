<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReportFilterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Policyで制御
    }

    public function rules(): array
    {
        return [
            'from' => ['nullable', 'date'],
            'to' => ['nullable', 'date'],
            'user_id' => ['nullable', 'integer', 'exists:users,id'],
            'project_id' => ['nullable', 'integer', 'exists:projects,id'],
            'type' => ['nullable', 'string', 'in:monthly,project,member'],
        ];
    }
}
