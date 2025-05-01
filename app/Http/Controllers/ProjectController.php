<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectStoreRequest;
use App\Http\Requests\ProjectUpdateRequest;
use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class ProjectController extends Controller
{
    public function index(): View
    {
        $projects = auth()->user()->accessibleProjects();

        return view('projects.index', compact('projects'));
    }

    public function create()
    {
        return view('projects.create');
    }

    public function store(ProjectStoreRequest $form): RedirectResponse
    {
        return redirect($form->store()->path());
    }

    public function show(Project $project): View
    {
        Gate::authorize('update', $project);

        return view('projects.show', compact('project'));
    }

    public function edit(Project $project)
    {
        return view('projects.edit', compact('project'));
    }

    public function update(ProjectUpdateRequest $form)
    {
        return redirect($form->save()->path());
    }

    public function destroy(Project $project)
    {
        Gate::authorize('update', $project);

        $project->delete();

        return redirect(route('projects'));
    }
}
