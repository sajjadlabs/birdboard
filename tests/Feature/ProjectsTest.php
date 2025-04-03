<?php

use App\Models\Project;
use App\Models\User;
use function Pest\Laravel\assertDatabaseHas;

test('only authenticated users can create projects', function () {
    $attributes = Project::factory()->raw();

    $response = $this->post('/projects', $attributes);

    $response->assertRedirect('/');
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

test('a user view a specific project', function () {
    $this->withoutExceptionHandling();

    $project = Project::factory()->create();

    $this->get($project->path())
        ->assertSee($project->title)
        ->assertSee($project->description);
});
