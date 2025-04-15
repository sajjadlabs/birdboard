<?php

use App\Models\Project;
use App\Models\Task;

it('belongs to a project', function () {
    $project = Project::factory()->create();

    $task = Task::factory()
        ->recycle($project)
        ->create();

    expect($task->project->is($project))->toBeTrue();
});

it('has a path', function () {
    $project = Project::factory()->create();

    $task = Task::factory()
        ->recycle($project)
        ->create();

    $this->assertEquals($task->path(), "/projects/$project->id/tasks/$task->id");
});
