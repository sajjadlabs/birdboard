<?php

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Facades\Tests\Arrangement\ProjectArrangement;

it('has projects', function () {
   $user = User::factory()->create();

   $this->assertInstanceOf(Collection::class, $user->projects);
});

it('it has accessible projects', function () {
    $john = $this->signIn();
    $sally = User::factory()->create();
    $nick = User::factory()->create();

    ProjectArrangement::ownedBy($john)->create();
    $project = tap(ProjectArrangement::ownedBy($sally)->create())->invite($nick);

    $this->assertCount(1, $john->accessibleProjects());

    $project->invite($john);

    $this->assertCount(2, $john->accessibleProjects());
});
