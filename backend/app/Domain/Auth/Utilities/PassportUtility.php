<?php

namespace App\Domain\Auth\Utilities;

use Illuminate\Http\Request;

class PassportUtility
{
  /**
   * Set cookie name.
   * 
   * @var string
   */
  private string $cookieName = '__refresh_token__';

  /**
   * Generate access token.
   * 
   * @param array $creds  Credentials needed to authenticate.
   */
  public function generateAccessToken(array $creds = [])
  {
    $params = [
      'grant_type' => 'password',
      'username' => $creds['email'],
      'password' => $creds['password'],
    ];

    return $this->makeRequest($params);
  }

  /**
   * Generate refresh token.
   */
  public function refreshAccessToken()
  {
    $refreshToken = request()->cookie($this->cookieName);

    abort_unless($refreshToken, 403, 'Token expired.');

    $params = [
      'grant_type' => 'refresh_token',
      'refresh_token' => $refreshToken,
    ];

    return $this->makeRequest($params);
  }

  /**
   * Make post request to OAuth.
   * 
   * @param array $params   All paramaters needed for request.
   * @return object $response
   */
  protected function makeRequest(array $params = []): Object
  {
    $params = array_merge([
      'client_id' => config('services.passport.client_id'),
      'client_secret' => config('services.passport.client_secret'),
      'scope' => '*',
    ], $params);

    $request = Request::create(route('passport.token'), 'POST', $params);
    $response = json_decode(app()->handle($request)->getContent());

    $this->setCookie($response->refresh_token);

    return $response;
  }

  /**
   * Send HTTPOnly cookie with request.
   * 
   * @param string $refreshToken
   */
  protected function setCookie(string $refreshToken)
  {
    cookie()->queue($this->cookieName, $refreshToken, config('services.passport.refresh_token_ttl'), null, null, false, true);
  }
}
