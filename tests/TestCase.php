<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    public function signIn(?User $user = null): User
    {
        $user = $user ?? User::factory()->create();

        $this->actingAs($user);

        return $user;
    }

    public function assertInvitationForbidden($user, $project): void
    {
            $this
                ->actingAs($user)
                ->post($project->path() . '/invitations', [
                    'email' => User::factory()->create()->email
                ])
                ->assertForbidden();
    }
}
