<?php

namespace App\Observers;

use App\Models\Task;

class TaskObserver
{

    public function created(Task $task): void
    {
        $task->project->recordActivity('created_task');
    }

    public function deleted(Task $task): void
    {
        $task->project->recordActivity('deleted_task');
    }

}
