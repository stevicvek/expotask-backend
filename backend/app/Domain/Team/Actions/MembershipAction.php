<?php

// TODO: attachAndApprove function

namespace App\Domain\Team\Actions;

use App\Domain\Auth\Models\User;
use App\Domain\Team\Exceptions\MembershipAlreadyAccepted;
use App\Domain\Team\Models\Team;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Domain\Team\Models\TeamMembers;
use App\Domain\Team\Mail\InvitationMail;
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

    $team->members()->attach($user, ['accepted' => 0]);

    $invitationCode = TeamMembers::whereTeam($team->id)
      ->notApproved()
      ->first()
      ->invitation_code;

    Mail::to($user->email)
      ->send(new InvitationMail($user, $team, $invitationCode));

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

  /**
   * Accept membership
   * 
   * @param Team $team
   * @param string $code
   * @return bool
   */
  public function accept(Team $team, string $code): bool
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
