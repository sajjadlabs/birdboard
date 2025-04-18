<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
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

        $attributes['notes'] = $request['notes'];

        $project = auth()->user()->projects()->create($attributes);

        return redirect($project->path());
    }

    public function show(Project $project): View
    {
        Gate::authorize('update', $project);

        return view('projects.show', [
            'project' => $project
        ]);
    }

    public function update(Request $request, Project $project)
    {
        Gate::authorize('update', $project);

        $project->update($request->only(['title', 'description', 'notes']));

        return redirect($project->path());
    }
}
