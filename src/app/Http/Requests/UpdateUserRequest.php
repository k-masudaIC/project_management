<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $userId = $this->route('user')->id ?? null;
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $userId,
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:admin,pm,member,contractor',
            'rate_type' => 'required|in:hourly,daily',
            'hourly_rate' => 'nullable|numeric|min:0|required_if:rate_type,hourly',
            'daily_rate' => 'nullable|numeric|min:0|required_if:rate_type,daily',
            'avatar' => 'nullable|string',
            'is_active' => 'boolean',
            'client_ids' => 'nullable|array',
            'client_ids.*' => 'exists:clients,id',
        ];
    }
}
