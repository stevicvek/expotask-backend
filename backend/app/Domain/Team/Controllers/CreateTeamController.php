<?php

namespace App\Domain\Team\Controllers;

use Illuminate\Http\Response;
use App\Domain\Team\Models\Team;
use App\Domain\Team\Requests\StoreTeamRequest;

class CreateTeamController
{
  public function __invoke(StoreTeamRequest $request)
  {
    $data = $request->validated();

    $team = Team::create($data);

    $team->members()->attach(auth()->user(), ['accepted' => 1]);

    return response()
      ->json($team, Response::HTTP_CREATED);
  }
}
