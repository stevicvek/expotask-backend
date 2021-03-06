<?php

use Tests\TestCase;
use App\Domain\Auth\Models\User;
use App\Domain\Team\Models\Team;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Domain\Team\Exceptions\MembershipDoesntExists;
use App\Domain\Team\Actions\Membership\DetachMembershipAction;

class DetachMembershipTest extends TestCase
{
  use RefreshDatabase;

  private DetachMembershipAction $action;

  public function setUp(): void
  {
    parent::setUp();

    $this->action = new DetachMembershipAction;
  }

  /** @test */
  public function it_detaches_user_from_team()
  {
    $user = User::factory()->create();
    $team = Team::factory()->create();
    $team->members()->attach($user);
    $this->assertDatabaseCount('team_members', 1);

    $this->action->execute($team, $user);
    $this->assertDatabaseCount('team_members', 0);
  }

  /** @test */
  public function it_throws_error_if_user_is_not_member_of_team_and_wants_to_be_deatached()
  {
    $user = User::factory()->create();
    $team = Team::factory()->create();

    $this->expectException(MembershipDoesntExists::class);
    $this->action->execute($team, $user);
  }
}
