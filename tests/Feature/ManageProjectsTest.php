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
    $this->signIn();
    $attributes = [
        'title' => fake()->sentence(4),
        'description' => fake()->text(100),
        'notes' => fake()->paragraph(),
    ];

    $storeResponse = $this
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

    $editRequest = $this
        ->actingAs($project->owner)
        ->get($project->path() . '/edit');

    $updateRequest = $this
        ->actingAs($project->owner)
        ->patch($project->path(), $attributes);

    $project = Project::where($attributes)->first();

    $showRequest = $this
        ->get($project->path());

    $editRequest->assertOk();
    $updateRequest->assertRedirect($project->path());
    $showRequest->assertSee($attributes);
    $this->assertDatabaseHas('projects', $attributes);
});

test('user update general notes', function () {
    $project = Project::factory()->create();
    $attributes = ['notes' => fake()->sentence];

    $updateResponse = $this
        ->actingAs($project->owner)
        ->patch($project->path(), $attributes);

    $updateResponse->assertRedirect($project->path());
    $this->assertDatabaseHas('projects', $attributes);
});

test('user not allowed to update project of others', function () {
    $this->signIn();
    $project = Project::factory()->create();

    $patchRequest = $this
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
    $this->signIn();
    $project = Project::factory()->create();

    $response = $this
        ->get($project->path());

    $response->assertForbidden();
});

test('a project requires a title', function () {
    $this->signIn();
    $attributes = Project::factory()->raw(['title' => '']);

    $response = $this
        ->post('/projects', $attributes);

    $response->assertSessionHasErrors('title');
});

test('a project requires a description', function () {
    $this->signIn();
    $attributes = Project::factory()->raw(['description' => '']);

    $response = $this
        ->post('/projects', $attributes);

    $response->assertSessionHasErrors('description');
});
