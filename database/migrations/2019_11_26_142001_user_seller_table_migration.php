<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UserSellerTableMigration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_seller', function (Blueprint $table) {
            $table->increments('id');
            $table->string('cnpj', 14);
            $table->string('fantasy_name', 60);
            $table->string('social_name', 60);
            $table->string('username', 50);
            $table->integer('id_user');

            $table->unique(['id_user']);


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::table('user_seller', function (Blueprint $table) {
            $table->dropUnique(['id_user']);
        });

        Schema::dropIfExists('user_seller');
    }
}
