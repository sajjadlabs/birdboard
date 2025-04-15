<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

class ProjectTasksController extends Controller
{
    public function store(Project $project)
    {
        $attributes = request()->validate(['body' => 'required']);

        $project->addTask($attributes['body']);

        return redirect($project->path());
    }
}
