<?php

use App\Models\Project;
use App\Models\User;
use Facades\Tests\Arrangement\ProjectArrangement;

test('guest cannot interact with project', function () {
    $project = Project::factory()->create();
    $attributes = Project::factory()->raw();

    $indexResponse = $this
        ->get('/projects');

    $showResponse = $this
        ->get($project->path());

    $createResponse = $this
        ->get('/projects/create');

    $storeResponse = $this
        ->post('/projects', $attributes);

    $indexResponse->assertRedirect(route('login'));
    $showResponse->assertRedirect(route('login'));
    $createResponse->assertRedirect(route('login'));
    $storeResponse->assertRedirect(route('login'));
});

    test('user can create project', function () {
    $user = User::factory()->create();
    $attributes = [
        'title' => fake()->sentence(4),
        'description' => fake()->text(100),
        'notes' => fake()->paragraph(),
    ];

    $storeResponse = $this
        ->actingAs($user)
        ->from('/projects/create')
        ->post('/projects', $attributes);

    $projectPath = Project::where($attributes)->first()->path();

    $getResponse = $this->get($projectPath);

    $storeResponse->assertRedirect($projectPath);

    $getResponse->assertSee($attributes);
});

test('user can update project', function () {
    $project = ProjectArrangement::create();

    $attributes = [
        'title' => fake()->sentence(4),
        'description' => fake()->text(100),
        'notes' => fake()->paragraph(),
    ];

    $patchRequest = $this
        ->actingAs($project->owner)
        ->patch($project->path(), $attributes);

    $project = Project::where($attributes)->first();

    $getRequest = $this
        ->get($project->path());

    $patchRequest->assertRedirect($project->path());
    $getRequest->assertSee($attributes);
    $this->assertDatabaseHas('projects', $attributes);
});

test('user not allowed to update project of others', function () {
    $user = User::factory()->create();
    $project = Project::factory()->create();

    $patchRequest = $this
        ->actingAs($user)
        ->patch($project->path());

    $patchRequest->assertForbidden();
});

test('user access their projects', function () {
    $project = ProjectArrangement::create();

    $response = $this
        ->actingAs($project->owner)
        ->get('/projects');

    $response->assertSee($project->title);
});

test('user access their specific project', function () {
    $project = ProjectArrangement::create();

    $response = $this
        ->actingAs($project->owner)
        ->get($project->path());

    $response
        ->assertSee($project->title)
        ->assertSee($project->description);
});

test('user cannot see project of others', function () {
    $user = User::factory()->create();
    $project = Project::factory()->create();

    $response = $this
        ->actingAs($user)
        ->get($project->path());

    $response->assertForbidden();
});

test('a project requires a title', function () {
    $user = User::factory()->create();
    $attributes = Project::factory()->raw(['title' => '']);

    $response = $this
        ->actingAs($user)
        ->post('/projects', $attributes);

    $response->assertSessionHasErrors('title');
});

test('a project requires a description', function () {
    $user = User::factory()->create();
    $attributes = Project::factory()->raw(['description' => '']);

    $response = $this
        ->actingAs($user)
        ->post('/projects', $attributes);

    $response->assertSessionHasErrors('description');
});
