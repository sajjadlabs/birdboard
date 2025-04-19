<?php

use App\Models\Project;

test('creating a project generates activity', function () {
   $project = Project::factory()->create();

   $this->assertCount(1, $project->activities);
   $this->assertEquals('created', $project->activities[0]->description);
});

test('updating a project generates activity', function () {
    $project = Project::factory()->create();

    $project->update(['title' => fake()->sentence]);

    $this->assertCount(2, $project->activities);
    $this->assertEquals('updated', $project->activities->last()->description);
});
