<?php

namespace App\Domain\Team\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Domain\Team\Models\Team;
use Illuminate\Http\JsonResponse;
use App\Domain\Team\Actions\MembershipAction;

class ApproveMembershipController
{
  /**
   * @param MembershipAction $action
   * @param Request $request
   * @return JsonResponse|Response
   */
  public function __invoke(MembershipAction $action, Request $request)
  {
    $action->accept(
      Team::find($request->team),
      $request->code
    );

    return response()
      ->json('Accepted!', Response::HTTP_ACCEPTED);
  }
}
