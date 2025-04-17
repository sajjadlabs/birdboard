<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProjectController extends Controller
{
    public function index(): View
    {
        $projects = auth()->user()->projects;

        return view('projects.index', compact('projects'));
    }

    public function create()
    {
        return view('projects.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $attributes = $request->validate([
            'title' => 'required',
            'description' => 'required',
        ]);

        $project = auth()->user()->projects()->create($attributes);

        return redirect($project->path());
    }

    public function show(Project $project): View
    {
        if (auth()->user()->id != $project->owner->id) {
            abort(403);
        }

        return view('projects.show', [
            'project' => $project
        ]);
    }
}
