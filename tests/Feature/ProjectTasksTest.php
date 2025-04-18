<?php

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Facades\Tests\Arrangement\ProjectArrangement;
use function Pest\Laravel\assertDatabaseHas;

test('guest cannot create task', function () {
    $project = Project::factory()->create();
    $attributes = ['body' => fake()->sentence];

    $response = $this
        ->post($project->path() . '/tasks', $attributes);

    $response->assertRedirect(route('login'));
    $this->assertDatabaseMissing('tasks', $attributes);
});

test('only project owner may create task', function () {
    $this->signIn();
    $project = Project::factory()->create();

    $response = $this
        ->post($project->path() . '/tasks');

    $response->assertForbidden();
});

test('a task can be created', function () {
    $project = Project::factory()->create();
    $attributes = ['body' => fake()->sentence];

    $storeResponse = $this
        ->actingAs($project->owner)
        ->post($project->path() . '/tasks', $attributes);

    $showResponse = $this
        ->get($project->path());

    $storeResponse->assertRedirect($project->path());
    $showResponse->assertSee($attributes['body']);
    $this->assertDatabaseHas('tasks', $attributes);
});

test('only the project owner may update task', function () {
    $this->signIn();
    $project = ProjectArrangement::withTasks(1)->create();

    $response = $this
        ->patch($project->tasks[0]->path());

    $response->assertForbidden();
});

test('a task can be updated', function () {
    $project = ProjectArrangement::withTasks(1)->create();
    $attributes = ['body' => fake()->sentence];

    $response = $this
        ->actingAs($project->owner)
        ->patch($project->tasks[0]->path(), $attributes);

    $response->assertRedirect($project->path());
    assertDatabaseHas('tasks', $attributes);
});

test('task requires a body', function () {
    $project = ProjectArrangement::create();
    $task = Task::factory()->raw(['body' => '']);

    $response = $this
        ->actingAs($project->owner)
        ->post($project->path() . '/tasks', $task);

    $response->assertSessionHasErrors('body');
});
