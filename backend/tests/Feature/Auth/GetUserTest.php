<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use App\Domain\Auth\Models\User;
use App\Domain\Team\Models\Team;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GetUserTest extends TestCase
{
  use RefreshDatabase;

  /** @test */
  public function it_can_recieve_its_data()
  {
    $this->actingAs($user = User::factory()->create(), 'api');

    $team = Team::factory()->count(10)->create();
    $user->teams()->attach($team);

    $response = $this->getJson(route('auth.me'));

    $response
      ->assertOk()
      ->assertJsonStructure(['id', 'fullname', 'email', 'teams']);
  }
}
