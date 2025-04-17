<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;

class ProjectTasksController extends Controller
{
    public function store(Request $request, Project $project)
    {
        if (auth()->user()->isNot($project->owner)) {
            abort(403);
        }

        $attributes = $request->validate(['body' => 'required']);

        $project->addTask($attributes['body']);

        return redirect($project->path());
    }

    public function update(Request $request, Project $project, Task $task)
    {
        if (auth()->user()->isNot($task->project->owner)) {
            abort(403);
        }

        $attributes = $request->validate(['body' => 'required']);

        $task->update([
           'body' => $attributes['body'],
           'completed' => $request->has('completed')
        ]);

        return redirect($project->path());
    }
}
