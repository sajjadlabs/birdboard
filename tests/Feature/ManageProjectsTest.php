<?php

use App\Models\Project;
use App\Models\User;
use function Pest\Laravel\assertDatabaseHas;

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
    $this->withoutExceptionHandling();
    $user = User::factory()->create();
    $attributes = [
        'title' => fake()->sentence(4),
        'description' => fake()->text(100),
        'notes' => fake()->paragraph(),
    ];

    $createResponse = $this
        ->actingAs($user)
        ->get('/projects/create');

    $storeResponse = $this
        ->actingAs($user)
        ->from('/projects/create')
        ->post('/projects', $attributes);

    $projectPath = Project::where($attributes)->first()->path();

    $getResponse = $this->get($projectPath);

    $createResponse->assertOk();
    $storeResponse->assertRedirect($projectPath);
    $getResponse
        ->assertSee($attributes['title'])
        ->assertSee($attributes['description'])
        ->assertSee($attributes['notes']);

    assertDatabaseHas('projects', $attributes);
});

test('user can update project', function () {
    $user = User::factory()->create();
    $project = Project::factory()
        ->recycle($user)
        ->create();

    $attributes = [
        'title' => fake()->sentence(4),
        'description' => fake()->text(100),
        'notes' => fake()->paragraph(),
    ];

    $patchRequest = $this
        ->actingAs($user)
        ->patch($project->path(), $attributes);

    $getRequest = $this
        ->actingAs($user)
        ->get($project->path());

    $patchRequest->assertRedirect($project->path());
    $this->assertDatabaseHas('projects', $attributes);
    $getRequest->assertSee($attributes);

});

test('user not allowed to update project of others', function () {
    $user = User::factory()->create();
    $project = Project::factory()->create();

    $attributes = [
        'title' => fake()->sentence(4),
        'description' => fake()->text(100),
        'notes' => fake()->paragraph(),
    ];

    $patchRequest = $this
        ->actingAs($user)
        ->patch($project->path(), $attributes);

    $patchRequest->assertForbidden();
    $this->assertDatabaseMissing('projects', $attributes);
});

test('user access their projects', function () {
    $user = User::factory()->create();

    $project = Project::factory()
        ->recycle($user)
        ->create();

    $response = $this
        ->actingAs($user)
        ->get('/projects');

    $response->assertSee($project->title);
});

test('user access their specific project', function () {
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

test('user cannot see project of others', function () {
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
