<?php

namespace App\Domain\Team\Actions;

use App\Domain\Team\Models\Team;

class CreateTeamAction
{
  /**
   * Execute action.
   * 
   * @param array $data
   * @return Team
   */
  public function execute(array $data = []): Team
  {
    $team = Team::create($data);
    $team->members()->attach(auth()->user(), ['accepted' => 1]);
    return $team;
  }
}
