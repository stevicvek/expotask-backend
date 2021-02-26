<?php

namespace App\Domain\Auth\Controllers;

use App\Domain\Auth\Requests\CreateUserRequest;
use App\Domain\Auth\Resources\UserResource;
use Illuminate\Http\JsonResponse;
use App\Domain\Auth\Models\User;
use Illuminate\Http\Response;

class SignUpController
{
  /**
   * Create user request.
   * 
   * @param CreateUserRequest $request
   * @return JsonResponse
   */
  public function __invoke(CreateUserRequest $request): JsonResponse
  {
    $data = $request->validated();

    $user = User::create($data);

    return response()
      ->json(new UserResource($user), Response::HTTP_CREATED);
  }
}
