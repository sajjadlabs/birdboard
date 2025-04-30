<?php

use App\Models\Project;
use App\Models\Task;
use Facades\Tests\Arrangement\ProjectArrangement;

test('creating a project', function () {
    $project = Project::factory()->create();

    $this->assertCount(1, $project->activities);
    tap($project->activities->last(), function ($activity) {
    $this->assertEquals('created_project', $activity->description);
        $this->assertNull($activity->changes);
    });
});

test('updating a project', function () {
    $project = Project::factory()->create();
    $originalTitle = $project->title;

    $project->update(['title' => 'Change']);

    $this->assertCount(2, $project->activities);

    $expected = [
        'before' => ['title' => $originalTitle],
        'after' => ['title' => 'Change']
    ];

    tap($project->activities->last(), function ($activity) use ($expected) {
        $this->assertEquals('updated_project', $activity->description);
        $this->assertEquals($expected, $activity->changes);
    });
});

test('creating a task', function () {
    $project = ProjectArrangement::withTasks(1)->create();
    $taskBody = $project->tasks[0]->body;

    $this->assertCount(2, $project->activities);

    tap($project->activities->last(), function ($activity) {
        $this->assertEquals('created_task', $activity->description);
        $this->assertInstanceOf(Task::class, $activity->subject);
    });
});

test('completing a task', function () {
    $project = ProjectArrangement::withTasks(1)->create();

    $this
        ->actingAs($project->owner)
        ->patch($project->tasks[0]->path(), [
            'body' => 'foobar',
            'completed' => true
        ]);

    $project->refresh();

    $this->assertCount(3, $project->activities);

    tap($project->activities->last(), function ($activity) {
        $this->assertEquals('completed_task', $activity->description);
        $this->assertInstanceOf(Task::class, $activity->subject);
    });
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

    tap($project->activities->last(), function ($activity) {
        $this->assertEquals('uncompleted_task', $activity->description);
        $this->assertInstanceOf(Task::class, $activity->subject);
    });
});

test('deleting a task', function () {
    $project = ProjectArrangement::withTasks(1)->create();

    $project->tasks[0]->delete();

    $this->assertCount(3, $project->activities);
    $this->assertEquals('deleted_task', $project->activities->last()->description);
});
