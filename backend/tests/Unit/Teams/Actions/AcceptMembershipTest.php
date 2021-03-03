<?php

use Tests\TestCase;
use App\Domain\Auth\Models\User;
use App\Domain\Team\Models\Team;
use App\Domain\Team\Models\Membership;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Domain\Team\Exceptions\MembershipAlreadyAccepted;
use App\Domain\Team\Actions\Membership\AcceptMembershipAction;

class AcceptMembershipTest extends TestCase
{
  use RefreshDatabase;

  private AcceptMembershipAction $action;

  public function setUp(): void
  {
    parent::setUp();

    $this->action = new AcceptMembershipAction;
  }

  /** @test */
  public function it_can_accept_membership_invitation()
  {
    $user = User::factory()->create();
    $team = Team::factory()->create();
    $team->members()->attach($user);

    $invCode = Membership::whereTeam($team->id)->notAccepted()->first()->invitation_code;

    $response = $this->action->execute($team, $invCode);

    $this->assertEquals($response, true);
    $this
      ->assertDatabaseCount('team_members', 1)
      ->assertDatabaseHas('team_members', [
        'team_id' => $team->id,
        'member_id' => $user->id,
        'invitation_code' => $invCode,
        'accepted' => 1,
      ]);
  }

  /** @test */
  public function it_throws_exception_when_membership_is_already_accepted()
  {
    $user = User::factory()->create();
    $team = Team::factory()->create();
    $team->members()->attach($user, ['accepted' => 1]);

    $invCode = Membership::whereTeam($team->id)->accepted()->first()->invitation_code;

    $this->expectException(MembershipAlreadyAccepted::class);
    $this->action->execute($team, $invCode);
  }
}
