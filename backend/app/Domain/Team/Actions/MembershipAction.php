<?php

// TODO: attachAndApprove function

namespace App\Domain\Team\Actions;

use App\Domain\Auth\Models\User;
use App\Domain\Team\Models\Team;
use App\Domain\Team\Exceptions\MembershipDoesntExists;
use App\Domain\Team\Exceptions\MembershipAlreadyExists;

class MembershipAction
{
  /**
   * Attach member to team
   *
   * @param Team $team
   * @param User $user
   * @throws MembershipAlreadyExists 
   * @return bool true if succeed
   */
  public function attach(Team $team, User $user): bool
  {
    if ($team->members->contains($user)) {
      throw new MembershipAlreadyExists;
    }

    $team->members()->attach($user, [
      'accepted' => 0
    ]);

    return true;
  }

  /**
   * Dettach member to team
   * 
   * @param Team $team
   * @param User $user
   * @throws MembershipDoesntExists
   * @return bool true if succeed
   */
  public function detach(Team $team, User $user): bool
  {
    if (!$team->members->contains($user)) {
      throw new MembershipDoesntExists;
    }

    $team->members()->detach($user);

    return true;
  }
}
