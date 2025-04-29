<?php

namespace App\Observers;

use App\Models\Task;

class TaskObserver
{

    public function created(Task $task): void
    {
        $task->recordActivity('created_task');
    }

    public function deleted(Task $task): void
    {
        $task->recordActivity('deleted_task');
    }

}
