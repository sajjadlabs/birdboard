<?php

namespace App\Http\Controllers;

use App\Models\Project;
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
}
