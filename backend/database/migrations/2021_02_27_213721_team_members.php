<?php

use App\Domain\Role\Models\Role;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TeamMembers extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('team_members', function (Blueprint $table) {
			$table
				->foreignId('team_id')
				->constrained('teams')
				->onDelete('cascade');
			$table
				->foreignId('member_id')
				->constrained('users')
				->onDelete('cascade');
			$table
				->string('invitation_code')
				->unique();
			$table
				->boolean('accepted')
				->default(false);
			$table
				->foreignIdFor(Role::class, 'role_id')
				->onDelete('cascade')
				->default(3);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('team_members');
	}
}
