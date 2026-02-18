<?php
namespace App\Policies;

use App\Models\Task;
use App\Models\User;

class TaskPolicy
{
    public function viewAny($user = null)
    {
        return true;
    }
    public function view($user = null, Task $task = null)
    {
        return true;
    }
    // 開発中は全ユーザーに許可
    public function create(User $user)
    {
        return true;
    }
    public function update(User $user, Task $task)
    {
        return true;
    }
    public function delete(User $user, Task $task)
    {
        return true;
    }
}
