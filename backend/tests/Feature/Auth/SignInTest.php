<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use App\Domain\Auth\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;

class SignInTest extends TestCase
{
  use RefreshDatabase;

  public function setUp(): void
  {
    parent::setUp();

    $this->artisan('passport:install');

    $secret = DB::table('oauth_clients')
      ->where('id', 2)
      ->first()
      ->secret;

    Config::set('services.passport.client_id', 2);
    Config::set('services.passport.client_secret', $secret);
  }

  /** @test */
  public function it_can_sign_in()
  {
    $user = User::factory()->create();

    $response = $this->postJson(route('login'), [
      'email' => $user->email,
      'password' => 'password'
    ]);

    $response->assertStatus(Response::HTTP_OK)
      ->assertJsonStructure(['token_type', 'expires_in', 'access_token', 'refresh_token']);
  }

  /** @test */
  public function it_fails_when_email_or_password_field_are_not_filled()
  {
    $response = $this->postJson(route('login'), []);

    $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
      ->assertJsonValidationErrors(['email', 'password'])
      ->assertJson([
        'errors' => [
          'email' => [__('validation.required', ['attribute' => 'email'])],
          'password' => [__('validation.required', ['attribute' => 'password'])]
        ]
      ]);
  }

  /** @test */
  public function it_fails_when_email_field_is_not_valid_format()
  {
    $response = $this->postJson(route('login'), ['email' => 'asd']);

    $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
      ->assertJsonValidationErrors(['email'])
      ->assertJson([
        'errors' => [
          'email' => [__('validation.email', ['attribute' => 'email'])],
        ]
      ]);
  }

  /** @test */
  public function it_fails_when_password_field_is_not_long_enough()
  {
    $response = $this->postJson(route('login'), ['email' => 'test@test.com', 'password' => 'a']);

    $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
      ->assertJsonValidationErrors(['password'])
      ->assertJson([
        'errors' => ['password' => [__('validation.min.string', ['attribute' => 'password', 'min' => 6])]]
      ]);
  }
}
