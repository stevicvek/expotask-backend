<?php

use Tests\TestCase;
use App\Domain\Auth\Models\User;
use App\Domain\Team\Models\Team;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Domain\Team\Actions\Membership\AttachAndAcceptAction;
use App\Domain\Team\Exceptions\MembershipAlreadyExists;
use App\Domain\Team\Models\Membership;

class AttachAndAcceptTest extends TestCase
{
  use RefreshDatabase;

  private AttachAndAcceptAction $action;

  public function setUp(): void
  {
    parent::setUp();

    $this->action = new AttachAndAcceptAction;
  }

  /** @test */
  public function it_can_attach_and_accept_membership()
  {
    $user = User::factory()->create();
    $team = Team::factory()->create();

    $this->action->execute($team, $user);

    $this
      ->assertDatabaseCount('team_members', 1)
      ->assertDatabaseHas('team_members', [
        'team_id' => $team->id,
        'member_id' => $user->id,
        'accepted' => 1,
      ]);
  }

  /** @test */
  public function it_throws_exception_if_membership_already_exists()
  {
    $user = User::factory()->create();
    $team = Team::factory()->create();
    $team->members()->attach($user);

    $this->expectException(MembershipAlreadyExists::class);
    $this->action->execute($team, $user);
  }
}
