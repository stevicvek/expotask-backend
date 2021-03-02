<?php

namespace Tests\Feature\Team;

use Tests\TestCase;
use Illuminate\Http\Response;
use App\Domain\Auth\Models\User;
use App\Domain\Team\Exceptions\MembershipAlreadyAccepted;
use App\Domain\Team\Models\Team;
use App\Domain\Team\Models\TeamMembers;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ApproveMembershipTest extends TestCase
{
  use RefreshDatabase;

  /** @test */
  public function it_can_accept_invitation()
  {
    $this->actingAs($user = User::factory()->create(), 'api');
    $team = Team::factory()->create();
    $team->members()->attach($user);

    $invCode = TeamMembers::whereTeam($team->id)->notAccepted()->first()->invitation_code;

    $res = $this->getJson(route('team.approve', [
      'team' => $team->id,
      'code' => $invCode
    ]));

    $res
      ->assertStatus(Response::HTTP_ACCEPTED)
      ->assertExactJson(['Accepted!']);
  }

  /** @test */
  public function it_throws_exception_when_user_already_accepted_invitation()
  {
    $this->actingAs($user = User::factory()->create(), 'api');
    $team = Team::factory()->create();
    $team->members()->attach($user, ['accepted' => 1]);

    $invCode = TeamMembers::whereTeam($team->id)
      ->whereMember(auth()->user()->id)
      ->first()
      ->invitation_code;

    $res = $this->getJson(route('team.approve', [
      'team' => $team->id,
      'code' => $invCode
    ]));

    $this->assertInstanceOf(MembershipAlreadyAccepted::class, $res->exception);
  }
}
