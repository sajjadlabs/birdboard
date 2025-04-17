<?php

use App\Models\Project;
use App\Models\User;
use function Pest\Laravel\assertDatabaseHas;

test('guest cannot interact with projects', function () {
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

test('users can create projects', function () {
    $user = User::factory()->create();
    $attributes = Project::factory()->raw(['owner_id' => $user->id]);

    $createResponse = $this
        ->actingAs($user)
        ->get('/projects/create');

    $storeResponse = $this
        ->actingAs($user)
        ->from('/projects/create')
        ->post('/projects', $attributes);

    $getResponse = $this->get('/projects');

    $projectPath = Project::where($attributes)->first()->path();

    $createResponse->assertOk();
    $storeResponse->assertRedirect($projectPath);
    $getResponse->assertSee($attributes['title']);
    assertDatabaseHas('projects', $attributes);
});

test('users access their projects', function() {
    $user = User::factory()->create();

    $project = Project::factory()
        ->recycle($user)
        ->create();

    $response = $this
        ->actingAs($user)
        ->get('/projects');

    $response->assertSee($project->title);
});

test('users access their specific project', function () {
    $this->withoutExceptionHandling();
    $user = User::factory()->create();

    $project = Project::factory()
        ->recycle($user)
        ->create();

    $this
        ->actingAs($user)
        ->get($project->path())
        ->assertSee($project->title)
        ->assertSee($project->description);
});

test('users cannot see projects of others', function () {
    $project = Project::factory()->create();
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->get($project->path());

    $response->assertForbidden();
});

test('a project requires a title', function () {
    $attributes = Project::factory()->raw(['title' => '']);
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->post('/projects', $attributes);

    $response->assertSessionHasErrors('title');
});

test('a project requires a description', function () {
    $attributes = Project::factory()->raw(['description' => '']);
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->post('/projects', $attributes);

    $response->assertSessionHasErrors('description');
});
