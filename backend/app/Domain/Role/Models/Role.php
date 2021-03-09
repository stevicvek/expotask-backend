<?php

namespace App\Domain\Role\Models;

use Sushi\Sushi;
use App\Domain\Auth\Models\User;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
	use Sushi;

	public $timestamps = false;

	protected $rows = [
		['id' => 1, 'slug' => 'admin', 'label' => 'Administrator'],
		['id' => 2, 'slug' => 'manager', 'label' => 'Manager'],
		['id' => 3, 'slug' => 'member', 'label' => 'Member'],
	];
}
