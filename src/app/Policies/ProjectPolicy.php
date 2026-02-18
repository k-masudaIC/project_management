<?php
namespace App\Policies;

use App\Models\Project;
use App\Models\User;

class ProjectPolicy
{
    public function viewAny(User $user)
    {
        return true;
    }
    public function view(User $user, Project $project)
    {
        return true;
    }
    public function create(User $user)
    {
        return in_array($user->role, ['admin', 'pm']);
    }
    public function update(User $user, Project $project)
    {
        return $user->role === 'admin' || $user->id === $project->created_by;
    }
    public function delete(User $user, Project $project)
    {
        return $user->role === 'admin' || $user->id === $project->created_by;
    }
}
