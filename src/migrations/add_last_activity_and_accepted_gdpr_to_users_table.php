<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class add_last_activity_and_accepted_gdpr_to_users_table extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('users', function (Blueprint $table) {
			$table->datetime('last_activity')->nullable()->default(null);
			$table->boolean('accepted_gdpr')->nullable()->default(null);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('users', function ($table) {
			$table->dropColumn('last_activity');
			$table->dropColumn('accepted_gdpr');
		});
	}
}
