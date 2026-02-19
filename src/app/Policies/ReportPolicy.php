<?php

namespace App\Policies;

use App\Models\User;

class ReportPolicy
{
    public function view(User $user): bool
    {
        // 管理者・PMのみ閲覧可
        return in_array($user->role, ['admin', 'pm']);
    }

    public function export(User $user): bool
    {
        // 管理者のみエクスポート可
        return $user->role === 'admin';
    }
}
