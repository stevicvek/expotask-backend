<?php

use App\Domain\Auth\Models\User;
use App\Domain\Team\Models\Team;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TeamModelTest extends TestCase
{
  use RefreshDatabase;

  /** @test */
  public function it_returns_members()
  {
    $teams = Team::factory()->count(10)->create();
    $teams = $teams->filter(fn ($team) => $team->id === 2 || $team->id === 3);

    $user = User::factory()->create();

    foreach ($teams as $team) {
      $team->members()->attach($user);
    }

    $this
      ->assertDatabaseCount('teams', 10)
      ->assertDatabaseCount('team_members', 2)
      ->assertEquals($user->teams->count(), 2);
  }

  /** @test */
  public function it_removes_relation_row_when_team_is_deleted()
  {
    $user = User::factory()->create();
    $team = Team::factory()->create();

    $team->members()->attach($user);

    $this->assertDatabaseCount('team_members', 1);

    $team->delete();

    $this
      ->assertDatabaseCount('teams', 0)
      ->assertDatabaseCount('users', 1)
      ->assertDatabaseCount('team_members', 0);
  }
}
