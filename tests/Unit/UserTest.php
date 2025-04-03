<?php

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

it('has projects', function () {
   $user = User::factory()->create();

   $this->assertInstanceOf(Collection::class, $user->projects);
});
