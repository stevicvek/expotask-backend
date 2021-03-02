<?php

namespace App\Domain\Team\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Pivot;

class TeamMembers extends Pivot
{
  protected $table = 'team_members';
  public $timestamps = false;

  /**
   * Where team
   * 
   * @return Builder
   */
  public function scopeWhereTeam($query, $id): Builder
  {
    return $query->where('team_id', $id);
  }

  /**
   * Where Member
   * 
   * @return Builder
   */
  public function scopeWhereMember($query, $id): Builder
  {
    return $query->where('member_id', $id);
  }

  /**
   * Where Code
   * 
   * @return Builder
   */
  public function scopeWhereCode($query, $code): Builder
  {
    return $query->where('invitation_code', $code);
  }

  /**
   * Where membership is accepted
   */
  public function scopeAccepted($query): Builder
  {
    return $query->where('accepted', 1);
  }

  /**
   * Where membership is not accepted.
   */
  public function scopeNotAccepted($query): Builder
  {
    return $query->where('accepted', 0);
  }
}
