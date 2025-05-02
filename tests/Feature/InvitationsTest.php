<?php

use App\Models\Project;
use App\Models\User;
use Facades\Tests\Arrangement\ProjectArrangement;

test('non-owners cannot invite a user', function () {
    $this
        ->actingAs($this->signIn())
        ->post(ProjectArrangement::create()->path() . '/invitations', [
            'email' => User::factory()->create()->email
        ])
        ->assertForbidden();
});

test('a project owner can invite a user', function () {
    $project = ProjectArrangement::create();
    $invitedUser = User::factory()->create();

    $response = $this
        ->actingAs($project->owner)
        ->post($project->path() . '/invitations', [
            'email' => $invitedUser->email
        ]);

    $response->assertRedirect(route('projects'));
    $this->assertTrue($project->members->contains($invitedUser));
});

test('email address must be associated with a Birdboard account', function () {
    $project = ProjectArrangement::create();

    $response = $this->actingAs($project->owner)->post($project->path() . '/invitations', [
        'email' => 'nonuser@email.com'
    ]);

    $response->assertSessionHasErrors(['email' => 'The user must have a birdboard account']);
});

test('invited users may change project details', function () {
    $project = Project::factory()->create();
    $user = User::factory()->create();

    $project->invite($user);

    $this
        ->actingAs($user)
        ->post(route('tasks.store', $project), $task = ['body' => 'foobar']);

    $this->assertDatabaseHas('tasks', $task);
});
