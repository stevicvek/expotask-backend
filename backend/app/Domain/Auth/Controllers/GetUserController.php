<?php

namespace App\Domain\Auth\Controllers;

use App\Domain\Auth\Resources\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class GetUserController
{
  /**
   * Get logged in user data
   * 
   * @return JsonResponse
   */
  public function __invoke(): JsonResponse
  {
    $data = auth()->user()->with('teams')->first();

    return response()
      ->json(new UserResource($data), Response::HTTP_OK);
  }
}
