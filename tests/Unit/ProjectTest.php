<?php

use App\Models\Project;

it('has a path', function () {
   $project = Project::factory()->create();

   $this->assertEquals('/projects/' . $project->id, $project->path());
});
