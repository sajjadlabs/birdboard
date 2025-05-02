<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectInvitationRequest;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ProjectInvitationController extends Controller
{
    public function store(Request $request, Project $project, ProjectInvitationRequest $form)
    {
        $user = User::whereEmail($request['email'])->first();

        $project->invite($user);

        return redirect(route('projects'));
    }
}
