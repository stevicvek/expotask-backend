<?php

namespace App\Domain\Auth\Controllers;

use App\Domain\Auth\Actions\SignInAction;
use App\Domain\Auth\Requests\LoginUserRequest;
use Illuminate\Http\Response;

class SignInController
{
  public function __invoke(LoginUserRequest $request, SignInAction $action)
  {
    $data = $action->execute($request->validated());

    return response()
      ->json($data, Response::HTTP_OK);
  }
}
