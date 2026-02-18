<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Client;

class ClientPolicy
{
    public function viewAny(User $user): bool
    {
        return (bool)($user->is_active ?? false);
    }

    public function view(User $user, Client $client): bool
    {
        return (bool)($user->is_active ?? false);
    }

    public function create(User $user): bool
    {
        return in_array($user->role, ['admin', 'pm']);
    }

    public function update(User $user, Client $client): bool
    {
        return in_array($user->role, ['admin', 'pm']);
    }

    public function delete(User $user, Client $client): bool
    {
        return $user->role === 'admin';
    }
}
