<?php

namespace App\Domain\Team\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Domain\Team\Models\Team;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Domain\Team\Actions\Membership\AcceptMembershipAction;

class AcceptMembershipController extends Controller
{
  /**
   * @param AcceptMembershipAction $action
   * @param Request $request
   * @return JsonResponse
   */
  public function __invoke(AcceptMembershipAction $action, Request $request): JsonResponse
  {
    $action->execute(
      Team::find($request->team),
      $request->code
    );

    return $this->sendSuccessResponse(null, 'Membership is accepted!', Response::HTTP_ACCEPTED);
  }
}
