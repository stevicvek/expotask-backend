<?php

namespace Tests\Feature\Role;

use Tests\TestCase;
use App\Domain\Auth\Models\User;
use App\Domain\Role\Exceptions\PermissionDenied;
use App\Domain\Team\Models\Team;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GiveRoleTest extends TestCase
{
  use RefreshDatabase;

  /** @test */
  public function it_can_give_role()
  {
    $this->actingAs($user = User::factory()->create(), 'api');
    $secUser = User::factory()->create();
    $team = Team::factory()->create();
    $user->teams()->attach($team, ['role_id' => 1]);
    $secUser->teams()->attach($team, ['role_id' => 3]);

    $response = $this->postJson(route('role.give', ['team' => $team->id]), [
      'user' => $secUser->id,
      'role' => 'manager'
    ]);

    $this->assertTrue($secUser->hasRole($team->id, 'manager'));
  }

  /** @test */
  public function it_can_not_give_role_if_logged_in_user_is_not_admin()
  {
    $this->actingAs($user = User::factory()->create(), 'api');
    $secUser = User::factory()->create();
    $team = Team::factory()->create();
    $user->teams()->attach($team, ['role_id' => 2]); // Logged in user is manager
    $secUser->teams()->attach($team, ['role_id' => 3]);

    $response = $this->postJson(route('role.give', ['team' => $team->id]), [
      'user' => $secUser->id,
      'role' => 'manager'
    ]);

    $this->assertInstanceOf(PermissionDenied::class, $response->exception);
  }
}
