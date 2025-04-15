<?php

use App\Models\Project;
use App\Models\Task;
use App\Models\User;

test('guest cannot add task', function () {
    $project = Project::factory()->create();
    $taskBody = fake()->sentence;

    $response = $this
        ->post($project->path() . '/tasks', ['body' => $taskBody]);

    $response->assertRedirect(route('login'));
    $this->assertDatabaseMissing('tasks', ['body' => $taskBody]);
});

test('prevent user from adding tasks to project of others', function () {
    $user = User::factory()->create();
    $project = Project::factory()->create();
    $taskBody = fake()->sentence;

    $response = $this
        ->actingAs($user)
        ->post($project->path() . '/tasks', ['body' => $taskBody]);

    $response->assertForbidden();
    $this->assertDatabaseMissing('tasks', ['body' => $taskBody]);
});

test('user create task for their project', function () {
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
