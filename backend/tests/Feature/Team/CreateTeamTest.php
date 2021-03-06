<?php

namespace Tests\Feature\Team;

use Tests\TestCase;
use Illuminate\Http\Response;
use App\Domain\Auth\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateTeamTest extends TestCase
{
  use RefreshDatabase, WithFaker;

  /** @test */
  public function it_can_create_team()
  {
    $this->actingAs($user = User::factory()->create(), 'api');

    $response = $this->postJson(route('team.create'), [
      'name' => $this->faker->name,
      'color' => $this->faker->hexColor,
    ]);

    $response
      ->assertStatus(Response::HTTP_CREATED)
      ->assertJsonStructure(['success', 'code', 'message', 'data'])
      ->assertJson([
        'success' => true,
        'code' => Response::HTTP_CREATED,
        'message' => 'Succesfully created team.',
      ]);

    $this
      ->assertDatabaseCount('teams', 1)
      ->assertDatabaseCount('team_members', 1)
      ->assertDatabaseMissing('team_members', [
        [
          'team_id' => json_decode($response->getContent())->data->id,
          'member_id' => $user->id,
          'accepted' => 1
        ]
      ]);
  }

  /** @test */
  public function it_fails_when_name_or_color_fields_are_not_filled()
  {
    $this->actingAs(User::factory()->create(), 'api');

    $response = $this->postJson(route('team.create'), []);

    $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
      ->assertJsonValidationErrors(['name', 'color'])
      ->assertJson([
        'errors' => [
          'name' => [__('validation.required', ['attribute' => 'name'])],
          'color' => [__('validation.required', ['attribute' => 'color'])],
        ]
      ]);
  }

  /** @test */
  public function it_fails_when_color_field_is_incorrect_format()
  {
    $this->actingAs(User::factory()->create(), 'api');

    $response = $this->postJson(route('team.create'), [
      'name' => $this->faker->name,
      'color' => 'test',
    ]);

    $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
      ->assertJsonValidationErrors(['color'])
      ->assertJson([
        'errors' => [
          'color' => [__('validation.color', ['attribute' => 'color'])],
        ]
      ]);
  }

  /** @test */
  public function it_fails_when_name_field_is_not_long_enough()
  {
    $this->actingAs(User::factory()->create(), 'api');

    $response = $this->postJson(route('team.create'), [
      'name' => 'asd',
      'color' => $this->faker->hexColor,
    ]);

    $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
      ->assertJsonValidationErrors(['name'])
      ->assertJson([
        'errors' => ['name' => [__('validation.min.string', ['attribute' => 'name', 'min' => 6])]]
      ]);
  }
}
