<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use Illuminate\Http\Response;
use App\Domain\Auth\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RefreshTokenTest extends TestCase
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
  public function it_can_refresh_token()
  {
    $user = User::factory()->create();

    $response = $this->postJson(route('login'), [
      'email' => $user->email,
      'password' => 'password'
    ]);

    $cookies = ['__refresh_token__' => json_decode($response->getContent())->refresh_token];

    $response = $this->call('GET', route('refresh'), [], $cookies);

    $response->assertStatus(Response::HTTP_OK)
      ->assertJsonStructure(['token_type', 'expires_in', 'access_token', 'refresh_token']);
  }

  /** @test */
  public function it_throws_error_when_cookie_is_not_provided()
  {
    $response = $this->call('GET', route('refresh'), []);

    $response->assertStatus(Response::HTTP_FORBIDDEN);
  }
}
