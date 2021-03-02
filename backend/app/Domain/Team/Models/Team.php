<?php

namespace App\Domain\Team\Models;

use Spatie\Sluggable\HasSlug;
use App\Domain\Auth\Models\User;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Team extends Model
{
	use HasSlug, HasFactory;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'name',
		'color',
	];

	/**
	 * Return team members.
	 * 
	 * @return BelongsToMany
	 */
	public function members(): BelongsToMany
	{
		return $this
			->belongsToMany(User::class, 'team_members', 'team_id', 'member_id')
			->using(TeamMembers::class);
	}

	/**
	 * Generate slug.
	 * 
	 * @return SlugOptions
	 */
	public function getSlugOptions(): SlugOptions
	{
		return SlugOptions::create()
			->generateSlugsFrom('name')
			->saveSlugsTo('slug');
	}
}
