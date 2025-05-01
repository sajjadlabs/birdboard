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

test('unauthorized user cannot delete a project', function () {
    $project = ProjectArrangement::create();
    $user = User::factory()->create();

    $guestResponse = $this->delete($project->path());

    $this->actingAs($user);

    $authResponse = $this->delete($project->path());

    $guestResponse->assertRedirect(route('login'));
    $authResponse->assertForbidden();
});

test('a user can delete a project', function () {
    $this->withoutExceptionHandling();
    $project = ProjectArrangement::create();

    $response = $this
        ->actingAs($project->owner)
        ->delete($project->path());

    $response->assertRedirect(route('projects'));

    $this->assertDatabaseMissing('projects', $project->only('id'));
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

test('a user can see all projects they have been invited to on their dashboard', function () {
    $project = tap(ProjectArrangement::create())->invite($this->signIn());

    $this->get('/projects')->assertSee($project->title);
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
