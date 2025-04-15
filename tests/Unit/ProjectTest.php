<?php

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use function PHPUnit\Framework\assertCount;
use function PHPUnit\Framework\assertTrue;

it('has a path', function () {
   $project = Project::factory()->create();

   $this->assertEquals('/projects/' . $project->id, $project->path());
});

it('belongs to a user', function () {
   $project = Project::factory()->create();

   $this->assertInstanceOf(User::class, $project->owner);
});

it('can have tasks', function () {
    $project = Project::factory()->create();

    $task = $project->addTask('Test task');

    assertCount(1, $project->tasks);
    assertTrue($project->tasks->contains($task));
});
