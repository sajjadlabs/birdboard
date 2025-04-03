<?php

use App\Models\Project;
use App\Models\User;

it('has a path', function () {
   $project = Project::factory()->create();

   $this->assertEquals('/projects/' . $project->id, $project->path());
});

it('belongs to a user', function () {
   $project = Project::factory()->create();

   $this->assertInstanceOf(User::class, $project->owner);
});
