<?php

use App\Models\Project;
use App\Models\Task;
use App\Models\User;

test('user create tasks for project', function () {
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
   $project = Project::factory()->create();
   $task = Task::factory()->raw(['body' => '']);

   $response = $this
       ->actingAs($user)
       ->post($project->path() . '/tasks', $task);

   $response->assertSessionHasErrors('body');
});
