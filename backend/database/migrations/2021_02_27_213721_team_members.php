<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
