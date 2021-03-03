<?php

namespace App\Domain\Team\Actions\Membership;

use App\Domain\Team\Models\Team;
use App\Domain\Team\Models\TeamMembers;
use App\Domain\Team\Exceptions\MembershipAlreadyAccepted;

class AcceptMembershipAction
{
  /**
   * Accept membership
   * 
   * @param Team $team
   * @param string $code invitation code
   * @return bool
   */
  public function execute(Team $team, string $code): bool
  {
    if (TeamMembers::whereTeam($team->id)->whereCode($code)->accepted()->count()) {
      throw new MembershipAlreadyAccepted;
    }

    $membership = TeamMembers::whereTeam($team->id)
      ->whereCode($code)
      ->update(['accepted' => 1]);

    return $membership;
  }
}
