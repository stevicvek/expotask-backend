<?php

namespace App\Domain\Auth\Controllers;

use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use App\Domain\Auth\Actions\RefreshTokenAction;

class RefreshTokenController
{
  /**
   * Refresh token.
   * 
   * @param RefreshTokenAction $action
   */
  public function __invoke(RefreshTokenAction $action): JsonResponse
  {
    $response = $action->execute();

    return response()
      ->json($response, Response::HTTP_OK);
  }
}
