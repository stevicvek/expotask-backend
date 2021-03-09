<?php

use Tests\TestCase;
use App\Domain\Auth\Models\User;
use App\Domain\Role\Models\Role;
use App\Domain\Team\Models\Team;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserModelTest extends TestCase
{
  use RefreshDatabase;

  /** @test */
  public function it_returns_fullname()
  {
    $user = User::factory()->create();

    $this->assertNotEmpty($user->fullname);
    $this->assertEquals($user->fullname, "$user->firstname $user->lastname");
  }

  /** @test */
  public function it_returns_teams()
  {
    $user = User::factory()->create();
    $teams = Team::factory()->count(10)->create();

    foreach ($teams as $team) {
      $user->teams()->attach($team);
    }

    $this
      ->assertDatabaseCount('team_members', 10)
      ->assertEquals($user->teams->count(), 10);
  }

  /** @test */
  public function it_removes_relation_row_when_user_is_deleted()
  {
    $user = User::factory()->create();
    $teams = Team::factory()->count(10)->create();

    foreach ($teams as $team) {
      $user->teams()->attach($team);
    }

    $this
      ->assertDatabaseCount('team_members', 10)
      ->assertEquals($user->teams->count(), 10);

    $user->delete();

    $this->assertDatabaseCount('team_members', 0);
  }

  /** @test */
  public function it_returns_if_user_has_role()
  {
    $this->actingAs($user = User::factory()->create());
    $team = Team::factory()->create();
    $user->teams()->attach($team, ['role_id' => 1]);

    $this->assertDatabaseHas('team_members', [
      'member_id' => $user->id,
      'team_id' => $team->id,
      'role_id' => 1,
    ]);

    $slug = Role::where('id', 1)->first()->slug;

    $this->assertTrue($user->hasRole($team->id, $slug));
  }
}
