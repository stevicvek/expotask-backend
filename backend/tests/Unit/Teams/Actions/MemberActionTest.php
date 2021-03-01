<?php

use Tests\TestCase;
use App\Domain\Auth\Models\User;
use App\Domain\Team\Models\Team;
use App\Domain\Team\Actions\MembershipAction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Domain\Team\Exceptions\MembershipAlreadyExists;
use App\Domain\Team\Exceptions\MembershipDoesntExists;

class MemberActionTest extends TestCase
{
  use RefreshDatabase;

  private MembershipAction $action;

  public function setUp(): void
  {
    parent::setUp();

    $this->action = new MembershipAction;
  }

  /** @test */
  public function it_attaches_user_to_team()
  {
    $user = User::factory()->create();
    $team = Team::factory()->create();
    $this->action->attach($team, $user);

    $this
      ->assertDatabaseCount('team_members', 1)
      ->assertDatabaseHas('team_members', [
        'team_id' => $team->id,
        'member_id' => $user->id,
        'accepted' => 0,
      ]);
  }

  /** @test */
  public function it_throws_error_if_user_is_already_in_team()
  {
    $user = User::factory()->create();
    $team = Team::factory()->create();
    $team->members()->attach($user);

    $this->expectException(MembershipAlreadyExists::class);
    $this->action->attach($team, $user);
  }

  /** @test */
  public function it_detaches_user_from_team()
  {
    $user = User::factory()->create();
    $team = Team::factory()->create();
    $team->members()->attach($user);
    $this->assertDatabaseCount('team_members', 1);

    $this->action->detach($team, $user);
    $this->assertDatabaseCount('team_members', 0);
  }

  /** @test */
  public function it_throws_error_if_user_is_not_member_of_team_and_wants_to_be_deatached()
  {
    $user = User::factory()->create();
    $team = Team::factory()->create();

    $this->expectException(MembershipDoesntExists::class);
    $this->action->detach($team, $user);
  }
}
