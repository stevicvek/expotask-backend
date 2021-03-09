<?php

namespace App\Domain\Auth\Models;

use App\Domain\Role\Models\Role;
use App\Domain\Team\Models\Team;
use Laravel\Passport\HasApiTokens;
use App\Domain\Team\Models\Membership;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
	use HasApiTokens, HasFactory, Notifiable;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'firstname',
		'lastname',
		'email',
		'password',
	];

	/**
	 * The attributes that should be hidden for arrays.
	 *
	 * @var array
	 */
	protected $hidden = [
		'password',
		'remember_token',
	];

	/**
	 * The attributes that should be cast to native types.
	 *
	 * @var array
	 */
	protected $casts = [
		'email_verified_at' => 'datetime',
	];

	/**
	 * Get fullname attribute.
	 *  
	 * @return string
	 */
	public function getFullnameAttribute(): string
	{
		return "$this->firstname $this->lastname";
	}

	/**
	 * Return users teams.
	 * 
	 * @return BelongsToMany
	 */
	public function teams(): BelongsToMany
	{
		return $this
			->belongsToMany(Team::class, 'team_members', 'member_id', 'team_id')
			->using(Membership::class);
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

		return $this->whereHas('teams', function ($query) use ($team, $role) {
			return $query
				->where('member_id', auth()->user()->id)
				->where('team_id', $team)
				->where('role_id', $role);
		})->count();
	}
}
