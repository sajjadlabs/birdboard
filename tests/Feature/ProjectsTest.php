<?php

use App\Models\Project;
use App\Models\User;
use function Pest\Laravel\assertDatabaseHas;

test('a guest cannot interact with projects', function () {
    $project = Project::factory()->create();
    $attributes = Project::factory()->raw();

    $this->get('/projects')->assertRedirect('/');
    $this->get($project->path())->assertRedirect('/');
    $this->post('/projects', $attributes)->assertRedirect('/');
});

test('a user can see their own project', function () {
    $this->withoutExceptionHandling();
    $user = User::factory()->create();
    $this->be($user);

    $project = Project::factory()
        ->recycle($user)
        ->create();

    $this->get($project->path())
        ->assertSee($project->title)
        ->assertSee($project->description);
});

test('a user cannot see projects of others', function () {
    $this->be(User::factory()->create());
    $project = Project::factory()->create();

    $this->get($project->path())->assertForbidden();
});

test('a user can create a project', function () {
    $this->withoutExceptionHandling();

    $this->actingAs(User::factory()->create());

    $attributes = [
        'title' => fake()->title,
        'description' => fake()->paragraph,
    ];

    $this->post('/projects', $attributes)->assertRedirect('/projects');

    assertDatabaseHas('projects', $attributes);

    $this->get('/projects')->assertSee($attributes['title']);
});

test('a project requires a title', function () {
    $this->actingAs(User::factory()->create());

    $attributes = Project::factory()->raw(['title' => '']);

    $response = $this->post('/projects', $attributes);

    $response->assertSessionHasErrors('title');
});

test('a project requires a description', function () {
    $this->actingAs(User::factory()->create());

    $attributes = Project::factory()->raw(['description' => '']);

    $response = $this->post('/projects', $attributes);

    $response->assertSessionHasErrors('description');
});
