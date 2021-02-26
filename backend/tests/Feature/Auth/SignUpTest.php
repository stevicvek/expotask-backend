<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use Illuminate\Http\Response;
use App\Domain\Auth\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SignUpTest extends TestCase
{
  use RefreshDatabase, WithFaker;

  /** @test */
  public function it_can_sign_up()
  {
    $response = $this->postJson(route('register'), $this->data());
    $response->assertStatus(Response::HTTP_CREATED)
      ->assertJsonStructure(['id', 'fullname', 'email']);
  }

  /** @test */
  public function it_fails_when_name_email_or_password_field_are_not_filled()
  {
    $response = $this->postJson(route('register'), $this->data([
      'firstname' => '',
      'lastname' => '',
      'email' => '',
      'password' => ''
    ]));

    $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
      ->assertJsonValidationErrors(['firstname', 'lastname', 'email', 'password'])
      ->assertJson([
        'errors' => [
          'firstname' => [__('validation.required', ['attribute' => 'firstname'])],
          'lastname' => [__('validation.required', ['attribute' => 'lastname'])],
          'email' => [__('validation.required', ['attribute' => 'email'])],
          'password' => [__('validation.required', ['attribute' => 'password'])]
        ]
      ]);
  }

  /** @test */
  public function it_fails_when_email_field_is_not_valid_format()
  {
    $response = $this->postJson(route('register'), $this->data(['email' => 'asd']));

    $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
      ->assertJsonValidationErrors(['email'])
      ->assertJson([
        'errors' => ['email' => [__('validation.email', ['attribute' => 'email'])]]
      ]);
  }

  /** @test */
  public function it_fails_when_password_field_is_not_long_enough()
  {
    $response = $this->postJson(route('register'), $this->data(['password' => '12345']));

    $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
      ->assertJsonValidationErrors(['password'])
      ->assertJson([
        'errors' => ['password' => [__('validation.min.string', ['attribute' => 'password', 'min' => 6])]]
      ]);
  }

  /** @test */
  public function it_fails_when_password_field_is_not_confirmed()
  {
    $response = $this->postJson(route('register'), $this->data(['password_confirmation' => '']));

    $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
      ->assertJsonValidationErrors(['password'])
      ->assertJson([
        'errors' => ['password' => [__('validation.confirmed', ['attribute' => 'password'])]]
      ]);
  }

  /**
   * Generate data for requests.
   *
   * @param array $mergeData
   * @return array
   */
  public function data(array $mergeData = []): array
  {
    $user = User::factory()->make();

    return array_merge([
      'firstname' => $user->firstname,
      'lastname' => $user->lastname,
      'email' => $user->email,
      'password' => 'password',
      'password_confirmation' => 'password',
    ], $mergeData);
  }
}
