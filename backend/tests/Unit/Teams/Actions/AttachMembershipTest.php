<?php

use Tests\TestCase;
use App\Domain\Auth\Models\User;
use App\Domain\Team\Models\Team;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Domain\Team\Exceptions\MembershipAlreadyExists;
use App\Domain\Team\Actions\Membership\AttachMembershipAction;

class AttachMembershipTest extends TestCase
{
  use RefreshDatabase;

  private AttachMembershipAction $action;

  public function setUp(): void
  {
    parent::setUp();

    $this->action = new AttachMembershipAction;
  }

  /** @test */
  public function it_attaches_to_team()
  {
    $user = User::factory()->create();
    $team = Team::factory()->create();
    $this->action->execute($team, $user);

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
    $this->action->execute($team, $user);
  }
}
