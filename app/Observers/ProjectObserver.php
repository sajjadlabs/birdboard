<?php

namespace App\Observers;

use App\Models\Activity;
use App\Models\Project;

class ProjectObserver
{

    public function created(Project $project): void
    {
        $this->recordActivity('created', $project);
    }


    public function updated(Project $project): void
    {
        $this->recordActivity('updated', $project);
    }

    public function recordActivity(string $type, Project $project)
    {
        Activity::create([
            'project_id' => $project->id,
            'description' => $type
        ]);
    }
}
