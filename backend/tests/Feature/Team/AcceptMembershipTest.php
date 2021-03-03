<?php

namespace Tests\Feature\Team;

use Tests\TestCase;
use Illuminate\Http\Response;
use App\Domain\Auth\Models\User;
use App\Domain\Team\Models\Team;
use App\Domain\Team\Models\Membership;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Domain\Team\Exceptions\MembershipAlreadyAccepted;

class AcceptMembershipTest extends TestCase
{
  use RefreshDatabase;

  /** @test */
  public function it_can_accept_invitation()
  {
    $this->withoutExceptionHandling();

    $this->actingAs($user = User::factory()->create(), 'api');
    $team = Team::factory()->create();
    $team->members()->attach($user);

    $invCode = Membership::whereTeam($team->id)->notAccepted()->first()->invitation_code;

    $res = $this->getJson(route('team.accept', [
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

    $invCode = Membership::whereTeam($team->id)
      ->whereMember(auth()->user()->id)
      ->first()
      ->invitation_code;

    $res = $this->getJson(route('team.accept', [
      'team' => $team->id,
      'code' => $invCode
    ]));

    $this->assertInstanceOf(MembershipAlreadyAccepted::class, $res->exception);
  }
}
