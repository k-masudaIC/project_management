<?php

namespace App\Policies;

use App\Models\TaskAssignment;
use App\Models\User;

class TaskAssignmentPolicy
{
    public function assign(User $user): bool
    {
        // 管理者またはPMのみアサイン可能
        return in_array($user->role, ['admin', 'pm']);
    }

    public function delete(User $user, TaskAssignment $assignment): bool
    {
        // 管理者またはPM、もしくは自分自身のアサインは削除可
        return in_array($user->role, ['admin', 'pm']) || $user->id === $assignment->user_id;
    }
}
