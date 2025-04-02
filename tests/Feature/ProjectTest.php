<?php

use function Pest\Laravel\assertDatabaseHas;

test('a user can create a project', function () {
    // Arrange
    $this->withoutExceptionHandling();

    $attributes = [
        'title' => fake()->title,
        'description' => fake()->paragraph,
    ];

    // Action and Assert
    $this->post('/projects', $attributes)->assertRedirect('/projects');

    assertDatabaseHas('projects', $attributes);

    $this->get('/projects')->assertSee($attributes);
});
