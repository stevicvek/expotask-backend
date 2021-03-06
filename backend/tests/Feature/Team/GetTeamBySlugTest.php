<?php

use Tests\TestCase;
use Illuminate\Http\Response;
use App\Domain\Auth\Models\User;
use App\Domain\Team\Models\Team;
use App\Domain\Team\Exceptions\TeamAccessDenied;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GetTeamBySlugTest extends TestCase
{
  use RefreshDatabase;

  /** @test */
  public function it_can_get_team_by_slug()
  {
    $this->actingAs($user = User::factory()->create(), 'api');
    $team = Team::factory()->create();
    $team->members()->attach($team);

    $response = $this->getJson(route('team.getBySlug', ['team' => $team->slug]));

    $response
      ->assertOk()
      ->assertJson([
        'success' => true,
        'code' => Response::HTTP_OK,
        'message' => 'Team loaded.'
      ]);
  }

  /** @test */
  public function it_throws_exception_when_user_doesnt_have_permission_to_access_team()
  {
    $this->actingAs($user = User::factory()->create(), 'api');
    $team = Team::factory()->create();

    $response = $this->getJson(route('team.getBySlug', ['team' => $team->slug]));

    $this->assertInstanceOf(TeamAccessDenied::class, $response->exception);
    $response->assertStatus(Response::HTTP_NOT_ACCEPTABLE);
  }
}
