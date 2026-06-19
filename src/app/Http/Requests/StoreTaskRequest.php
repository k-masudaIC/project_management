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

    public function messages(): array
    {
        return [
            'project_id.required' => ':attributeは必須です。',
            'project_id.exists' => '選択した:attributeが存在しません。',
            'title.required' => ':attributeは必須です。',
            'title.max' => ':attributeは255文字以内で入力してください。',
            'status.required' => ':attributeは必須です。',
            'status.in' => ':attributeの値が不正です。',
            'priority.required' => ':attributeは必須です。',
            'priority.in' => ':attributeの値が不正です。',
            'estimated_hours.numeric' => ':attributeは数値で入力してください。',
            'estimated_hours.min' => ':attributeは0以上で入力してください。',
            'start_date.date' => ':attributeは日付形式で入力してください。',
            'due_date.date' => ':attributeは日付形式で入力してください。',
            'due_date.after_or_equal' => ':attributeは開始日以降の日付を指定してください。',
            'sort_order.integer' => ':attributeは整数で入力してください。',
        ];
    }

    public function attributes(): array
    {
        return [
            'project_id' => '案件',
            'title' => 'タスク名',
            'status' => 'ステータス',
            'priority' => '優先度',
            'estimated_hours' => '見積工数',
            'start_date' => '開始日',
            'due_date' => '期限',
            'sort_order' => '表示順',
        ];
    }
}
