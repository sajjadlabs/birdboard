<?php

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use function Pest\Laravel\assertDatabaseHas;

test('guest cannot create task', function () {
    $project = Project::factory()->create();
    $taskBody = fake()->sentence;

    $response = $this
        ->post($project->path() . '/tasks', ['body' => $taskBody]);

    $response->assertRedirect(route('login'));
    $this->assertDatabaseMissing('tasks', ['body' => $taskBody]);
});

test('only project owner may create task', function () {
    $user = User::factory()->create();
    $project = Project::factory()->create();
    $taskBody = fake()->sentence;

    $response = $this
        ->actingAs($user)
        ->post($project->path() . '/tasks', ['body' => $taskBody]);

    $response->assertForbidden();
    $this->assertDatabaseMissing('tasks', ['body' => $taskBody]);
});

test('a task can be created', function () {
    $this->withoutExceptionHandling();
    $user = User::Factory()->create();

    $project = Project::factory()
        ->recycle($user)
        ->create();

    $storeResponse = $this
        ->actingAs($user)
        ->post($project->path() . '/tasks', [
            'body' => 'TestTask'
        ]);

    $showResponse = $this
        ->get($project->path());

    $storeResponse->assertRedirect($project->path());
    $showResponse->assertSee('TestTask');
});

test('only the project owner may update task', function () {
    $project = Project::factory()->create();
    $task = $project->addTask(fake()->sentence());
    $updateAttributes = Task::factory()->raw();

    $response = $this
        ->patch($task->path(), $updateAttributes);

    $response->assertRedirect(route('login'));
    $this->assertDatabaseMissing('tasks', $updateAttributes);
});

test('a task can be updated', function () {
    $this->withoutExceptionHandling();
    $user = User::factory()->create();
    $updatedTask = 'updated task';

    $project = Project::factory()
        ->recycle($user)
        ->create();

    $task = $project->addTask('task');

    $response = $this
        ->actingAs($user)
        ->patch($task->path(), ['body' => $updatedTask]);

    $response->assertRedirect($project->path());
    assertDatabaseHas('tasks', ['body' => $updatedTask]);
});

test('task requires a body', function () {
    $user = User::factory()->create();
    $project = Project::factory()
        ->recycle($user)
        ->create();
    $task = Task::factory()->raw(['body' => '']);

    $response = $this
        ->actingAs($user)
        ->post($project->path() . '/tasks', $task);

    $response->assertSessionHasErrors('body');
});
