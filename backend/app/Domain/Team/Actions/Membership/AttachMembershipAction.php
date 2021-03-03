<?php

namespace App\Domain\Team\Actions\Membership;

use App\Domain\Auth\Models\User;
use App\Domain\Team\Models\Team;
use Illuminate\Support\Facades\Mail;
use App\Domain\Team\Models\Membership;
use App\Domain\Team\Mail\InvitationMail;
use App\Domain\Team\Exceptions\MembershipAlreadyExists;

class AttachMembershipAction
{
  /**
   * Attach member to team
   *
   * @param Team $team
   * @param User $user
   * @throws MembershipAlreadyExists 
   * @return bool true if succeed
   */
  public function execute(Team $team, User $user): bool
  {
    if ($team->members->contains($user)) {
      throw new MembershipAlreadyExists;
    }

    $team->members()->attach($user, ['accepted' => 0]);

    $invitationCode = Membership::whereTeam($team->id)
      ->notAccepted()
      ->first()
      ->invitation_code;

    Mail::to($user->email)
      ->send(new InvitationMail($user, $team, $invitationCode));

    return true;
  }
}
