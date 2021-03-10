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
    $this->actingAs($user = User::factory()->create(), 'api');
    $team = Team::factory()->create();
    $team->members()->attach($user);

    $invCode = Membership::whereTeam($team->id)
      ->whereMember($user->id)
      ->notAccepted()
      ->first()
      ->invitation_code;

    $res = $this->getJson(route('membership.accept', [
      'team' => $team->id,
      'code' => $invCode
    ]));

    $res
      ->assertStatus(Response::HTTP_ACCEPTED)
      ->assertExactJson([
        'success' => true,
        'code' => Response::HTTP_ACCEPTED,
        'message' => 'Membership is accepted!',
        'data' => null,
      ]);
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

    $response = $this->getJson(route('membership.accept', [
      'team' => $team->id,
      'code' => $invCode
    ]));

    $this->assertInstanceOf(MembershipAlreadyAccepted::class, $response->exception);
    $response
      ->assertStatus(Response::HTTP_FORBIDDEN)
      ->assertJson([
        'success' => false,
        'code' => Response::HTTP_FORBIDDEN,
        'message' => 'Membership is already accepted!',
        'data' => null
      ]);
  }
}
