<?php

namespace App\Domain\Team\Controllers;

use App\Domain\Team\Exceptions\TeamAccessDenied;
use Illuminate\Http\Response;
use App\Domain\Team\Models\Team;
use App\Domain\Team\Resources\TeamResource;
use Illuminate\Http\JsonResponse;

class GetTeamBySlugController
{
  /**
   * Get team by slug
   * 
   * @param Team $team
   * @return JsonResponse
   */
  public function __invoke(Team $team): JsonResponse
  {
    if (!$team->members->contains(auth()->user())) {
      throw new TeamAccessDenied;
    }

    return response()
      ->json(new TeamResource($team), Response::HTTP_OK);
  }
}
