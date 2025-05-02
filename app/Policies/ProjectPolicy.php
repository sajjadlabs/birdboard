<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;

class ProjectPolicy
{
    public function manage(User $user, Project $project): bool
    {
        return $user->is($project->owner);
    }
    public function update(User $user, Project $project): bool
    {
        return $user->is($project->owner) || $project->members->contains($user);
    }
}
