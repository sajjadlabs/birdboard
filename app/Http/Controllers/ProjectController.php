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
        $projects = Project::all();

        return view('projects.index', compact('projects'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required'
        ]);

        $project = Project::create([
            'title' => $request->title,
            'description' => $request->description
        ]);

        return redirect('/projects');
    }
}
