<?php

use App\Models\Project;
use function Pest\Laravel\assertDatabaseHas;

test('a user can create a project', function () {
    $this->withoutExceptionHandling();

    $attributes = [
        'title' => fake()->title,
        'description' => fake()->paragraph,
    ];

    $this->post('/projects', $attributes)->assertRedirect('/projects');

    assertDatabaseHas('projects', $attributes);

    $this->get('/projects')->assertSee($attributes);
});

test('a project requires a title', function () {
    $attributes = Project::factory()->raw(['title' => '']);

    $response = $this->post('/projects', $attributes);

    $response->assertSessionHasErrors('title');
});

test('a project requires a description', function () {
    $attributes = Project::factory()->raw(['description' => '']);

    $response = $this->post('/projects', $attributes);

    $response->assertSessionHasErrors('description');
});
