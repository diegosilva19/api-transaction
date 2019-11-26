<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class LogUserAccountBalanceTableMigration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('log_user_account_balance', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_account');
            $table->integer('ammount');
            $table->integer('ammount_operation')->comment('diff between old and new amount');
            $table->integer('type_operation')->comment('add  or remove');
            $table->dateTime('update_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('log_user_account_balance');
    }
}
