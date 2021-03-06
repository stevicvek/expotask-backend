<?php

namespace App\Domain\Team\Controllers;

use Symfony\Component\HttpFoundation\JsonResponse;
use App\Domain\Team\Actions\CreateTeamAction;
use App\Domain\Team\Requests\StoreTeamRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

class CreateTeamController extends Controller
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

    return $this->sendSuccessResponse($team, 'Succesfully created team.', Response::HTTP_CREATED);
  }
}
