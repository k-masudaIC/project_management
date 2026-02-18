<?php

namespace App\Policies;

use App\Models\TimeEntry;
use App\Models\User;

class TimeEntryPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->is_active;
    }

    public function view(User $user, TimeEntry $timeEntry): bool
    {
        return $user->id === $timeEntry->user_id || $user->role === 'admin' || $user->role === 'pm';
    }

    public function create(User $user): bool
    {
        return $user->is_active;
    }

    public function update(User $user, TimeEntry $timeEntry): bool
    {
        return $user->id === $timeEntry->user_id || $user->role === 'admin' || $user->role === 'pm';
    }

    public function delete(User $user, TimeEntry $timeEntry): bool
    {
        return $user->id === $timeEntry->user_id || $user->role === 'admin' || $user->role === 'pm';
    }
}
