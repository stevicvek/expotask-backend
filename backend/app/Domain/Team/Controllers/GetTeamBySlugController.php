<?php

namespace App\Domain\Team\Controllers;

use App\Domain\Team\Exceptions\TeamAccessDenied;
use App\Domain\Team\Resources\TeamResource;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use App\Domain\Team\Models\Team;

class GetTeamBySlugController extends Controller
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

    return $this->sendSuccessResponse(new TeamResource($team), 'Successfully loaded team.');
  }
}
