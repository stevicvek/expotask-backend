<?php

namespace App\Domain\Team\Actions\Membership;

use App\Domain\Auth\Models\User;
use App\Domain\Team\Models\Team;
use App\Domain\Team\Models\Membership;
use App\Domain\Team\Exceptions\MembershipAlreadyExists;

class AttachAndAcceptAction
{
  /**
   * Attach and accept action
   * 
   * @param Team $team
   * @param User $user
   * @throws MembershipAlreadyExists
   * @return bool
   */
  public function execute(Team $team, User $user): bool
  {
    if ($team->members->contains($user)) {
      throw new MembershipAlreadyExists;
    }

    $team->members()->attach($user, ['accepted' => true]);

    return true;
  }
}
