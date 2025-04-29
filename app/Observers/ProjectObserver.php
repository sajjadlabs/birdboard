<?php

namespace App\Observers;

use App\Models\Activity;
use App\Models\Project;

class ProjectObserver
{

    public function created(Project $project): void
    {
        $project->recordActivity('created');
    }

    public function updating(Project $project) : void
    {
        $project->old = $project->getOriginal();
    }

    public function updated(Project $project): void
    {
        $project->recordActivity('updated');
    }

}
