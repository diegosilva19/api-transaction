<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UserConsumerTableMigration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_consumer', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_user');
            $table->string('username', 50);

            $table->unique(['id_user'], 'unique_user_consumer_id_user');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::table('user_consumer', function (Blueprint $table) {
           $table->dropUnique('unique_user_consumer_id_user');
        });

        Schema::dropIfExists('user_consumer');
    }
}
