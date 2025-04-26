<?php

use App\Models\Project;
use App\Models\Task;
use Facades\Tests\Arrangement\ProjectArrangement;

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

it('can be completed', function () {
    $task = Task::factory()->create();

    $this->assertFalse($task->completed);

    $task->complete();

    $this->assertTrue($task->completed);
});

it('can be marked as incomplete', function () {
    $task = Task::factory()->create(['completed' => true]);

    $this->assertTrue($task->completed);

    $task->incomplete();

    $this->assertFalse($task->completed);
});
