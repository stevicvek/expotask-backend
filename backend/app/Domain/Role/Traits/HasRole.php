<?php

namespace App\Domain\Role\Traits;

use App\Domain\Role\Models\Role;

trait HasRole
{
  /**
   * Give Role
   * @param int $team
   * @param string $slug role slug
   * @return bool
   */
  public function giveRole(int $team, string $slug): bool
  {
    $role = Role::whereSlug($slug)->firstOrFail()->id;

    return $this
      ->teams()
      ->newPivotStatementForId($team)
      ->update([
        'role_id' => $role
      ]);
  }

  /**
   * Has role
   * 
   * @param int $team
   * @param string $slug role slug
   * @return bool
   */
  public function hasRole(int $team, string $slug): bool
  {
    $role = Role::whereSlug($slug)->firstOrFail()->id;

    return $this
      ->teams()
      ->wherePivot('member_id', $this->id)
      ->wherePivot('team_id', $team)
      ->wherePivot('role_id', $role)
      ->count();
  }
}
