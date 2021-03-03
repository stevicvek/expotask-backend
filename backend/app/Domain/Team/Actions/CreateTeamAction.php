<?php

namespace App\Domain\Team\Actions;

use App\Domain\Team\Actions\Membership\AttachAndAcceptAction;
use App\Domain\Team\Models\Team;

class CreateTeamAction
{
  protected AttachAndAcceptAction $action;

  /**
   * @param AttachAndAcceptAction $action
   */
  public function __construct(AttachAndAcceptAction $action)
  {
    $this->action = $action;
  }

  /**
   * Execute action.
   * 
   * @param array $data
   * @return Team
   */
  public function execute(array $data = []): Team
  {
    $team = Team::create($data);

    $this->action->execute($team, auth()->user());

    return $team;
  }
}
