<?php

use App\Models\Project;
use Facades\Tests\Arrangement\ProjectArrangement;

test('creating a project', function () {
    $project = Project::factory()->create();

    $this->assertCount(1, $project->activities);
    $this->assertEquals('created', $project->activities->last()->description);
});

test('updating a project', function () {
    $project = Project::factory()->create();

    $project->update(['title' => fake()->sentence]);

    $this->assertCount(2, $project->activities);
    $this->assertEquals('updated', $project->activities->last()->description);
});

test('creating a task', function () {
    $project = ProjectArrangement::withTasks(1)->create();

    $this->assertCount(2, $project->activities);
    $this->assertEquals('created_task', $project->activities->last()->description);
});

test('completing a task', function () {
    $project = ProjectArrangement::withTasks(1)->create();

    $this
        ->actingAs($project->owner)
        ->patch($project->tasks[0]->path(), [
            'body'      => 'foobar',
            'completed' => true
        ]);

    $project->refresh();

    $this->assertCount(3, $project->activities);
    $this->assertEquals('completed_task', $project->activities->last()->description);
});

test('incompleting a task', function () {
    $project = ProjectArrangement::withTasks(1)->create();

    $this
        ->actingAs($project->owner)
        ->patch($project->tasks[0]->path(), [
            'body' => 'foobar',
            'completed' => true
        ]);

    $this->patch($project->tasks[0]->path(), [
            'body' => 'foobar',
            'completed' => false
        ]);

    $this->assertCount(4, $project->activities);
    $this->assertEquals('uncompleted_task', $project->activities->last()->description);
});

test('deleting a task', function () {
   $project = ProjectArrangement::withTasks(1)->create();

   $project->tasks[0]->delete();

   $this->assertCount(3, $project->activities);
   $this->assertEquals('deleted_task', $project->activities->last()->description);
});
