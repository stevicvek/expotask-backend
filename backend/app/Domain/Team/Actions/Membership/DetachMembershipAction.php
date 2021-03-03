<?php

namespace App\Domain\Team\Actions\Membership;

use App\Domain\Auth\Models\User;
use App\Domain\Team\Models\Team;
use App\Domain\Team\Exceptions\MembershipDoesntExists;

class DetachMembershipAction
{
  /**
   * Dettach member to team
   * 
   * @param Team $team
   * @param User $user
   * @throws MembershipDoesntExists
   * @return bool true if succeed
   */
  public function execute(Team $team, User $user): bool
  {
    if (!$team->members->contains($user)) {
      throw new MembershipDoesntExists;
    }

    $team->members()->detach($user);

    return true;
  }
}
