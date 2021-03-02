<?php

namespace App\Domain\Team\Observers;

use App\Domain\Team\Models\TeamMembers;
use Illuminate\Support\Str;

class TeamMembersObserver
{
	/**
	 * Handle invatation code generating.
	 *
	 * @param TeamMembers $model
	 * @return void
	 */
	public function creating(TeamMembers $model): void
	{
		$model->invitation_code = Str::random(32);
	}
}
