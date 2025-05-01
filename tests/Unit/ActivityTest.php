<?php

use App\Models\User;
use Facades\Tests\Arrangement\ProjectArrangement;

test('it has a user', closure: function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $project = ProjectArrangement::ownedBy($user)->create();

    $this->actingAs($project->owner);

    $this->assertEquals($user->id, $project->activities[0]->user->id);
});
