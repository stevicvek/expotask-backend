<?php

namespace App\Domain\Role\Controllers;

use App\Domain\Auth\Models\User;
use App\Domain\Team\Models\Team;
use App\Http\Controllers\Controller;
use App\Domain\Role\Requests\GiveRoleRequest;

class GiveRoleController extends Controller
{
  public function __invoke(Team $team, GiveRoleRequest $request)
  {
    $data = $request->validated();
    User::find($data['user'])->giveRole($team->id, $data['role']);
  }
}
