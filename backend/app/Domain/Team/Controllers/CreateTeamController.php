<?php

namespace App\Domain\Team\Controllers;

use App\Domain\Team\Actions\CreateTeamAction;
use App\Domain\Team\Requests\StoreTeamRequest;
use Symfony\Component\HttpFoundation\JsonResponse;
use Illuminate\Http\Response;

class CreateTeamController
{
  /**
   * Create team.
   * 
   * @param StoreTeamRequest $request
   * @param CreateTeamAction $action
   * @return JsonResponse
   */
  public function __invoke(StoreTeamRequest $request, CreateTeamAction $action): JsonResponse
  {
    $data = $request->validated();

    $team = $action->execute($data);

    return response()
      ->json($team, Response::HTTP_CREATED);
  }
}
