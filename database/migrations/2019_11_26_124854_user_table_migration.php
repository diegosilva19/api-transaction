<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UserTableMigration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('cpf');
            $table->string('email', 100);
            $table->string('full_name', 100);

            $table->string('username', 50)->comment('test user dev')->nullable();

            $table->string('password', 80);
            $table->string('phone_number',9);

            $table->index(['full_name'], 'user_full_name_search_index');
            $table->unique('email', 'unique_email');
            $table->unique('cpf', 'unique_cpf');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user', function (Blueprint $table) {
            $table->dropIndex('user_full_name_search_index');
            $table->dropUnique('unique_email');
            $table->dropUnique('unique_cpf');
        });

        Schema::dropIfExists('user');
    }
}
