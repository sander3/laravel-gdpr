<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLastActivityAndAcceptedGdprToUsersTable extends Migration
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
            $table->boolean('isAnonymized')->default(false);
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
            $table->dropColumn('isAnonymized');
        });
    }
}
