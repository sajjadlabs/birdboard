<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ProjectTasksController extends Controller
{
    public function store(Request $request, Project $project)
    {
        Gate::authorize('update', $project);

        $attributes = $request->validate(['body' => 'required']);

        $project->addTask($attributes['body']);

        return redirect($project->path());
    }

    public function update(Request $request, Project $project, Task $task)
    {
        Gate::authorize('update', $task->project);

        $attributes = $request->validate(['body' => ['required']]);

        $task->update(['body' => $attributes['body']]);

        if ($request->has('completed')) {
            $task->complete();
        }

        return redirect($project->path());
    }
}
