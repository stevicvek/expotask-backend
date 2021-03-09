<?php

namespace App\Domain\Role\Models;

use Sushi\Sushi;
use App\Domain\Auth\Models\User;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
	use Sushi;

	protected $rows = [
		['id' => 1, 'slug' => 'admin', 'label' => 'Administrator'],
		['id' => 2, 'slug' => 'manager', 'label' => 'Manager'],
		['id' => 3, 'slug' => 'member', 'label' => 'Member'],
	];

	/**
	 * Useless
	 */
	public function members()
	{
		return $this->belongsToMany(User::class, 'team_members', 'role_id', 'member_id');
	}
}
