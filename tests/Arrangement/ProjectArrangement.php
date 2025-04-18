<?php

namespace Tests\Arrangement;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class ProjectArrangement
{
    public User $user;
    public int $taskCount = 0;

    public function create(): Model
    {
        $project = Project::factory()
            ->recycle($this->user ?? User::factory())
            ->create();

        Task::factory($this->taskCount)
            ->recycle($project)
            ->create();

        return $project;
    }

    public function ownedBy(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function withTasks($count): self
    {
        $this->taskCount = $count;

        return $this;
    }
}
