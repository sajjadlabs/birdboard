<?php

use App\Models\Project;
use App\Models\User;

test('a project can invite a user', function () {
    $project = Project::factory()->create();
    $user = User::factory()->create();

    $project->invite($user);

    $this
        ->actingAs($user)
        ->post(route('tasks.store', $project), $task = ['body' => 'foobar']);

    $this->assertDatabaseHas('tasks', $task);
});
