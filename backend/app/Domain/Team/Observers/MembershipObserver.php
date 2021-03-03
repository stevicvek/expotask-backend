<?php

namespace App\Domain\Team\Observers;

use App\Domain\Team\Models\Membership;
use Illuminate\Support\Str;

class MembershipObserver
{
	/**
	 * Handle invatation code generating.
	 *
	 * @param Membership $model
	 * @return void
	 */
	public function creating(Membership $model): void
	{
		$model->invitation_code = Str::random(32);
	}
}
